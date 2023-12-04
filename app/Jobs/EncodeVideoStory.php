<?php

namespace App\Jobs;

use FFMpeg;
use FFMpeg\Format\Video\X264;
use App\Helper;
use App\Models\MediaStories;
use App\Models\User;
use App\Models\Stories;
use App\Models\AdminSettings;
use App\Models\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\File;

class EncodeVideoStory implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $video;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(MediaStories $video)
  {
    $this->video = $video;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    // Admin Settings
    $settings = AdminSettings::select('watermark_on_videos')->first();

    $story = Stories::find($this->video->stories_id);

    // Paths
    $disk = 'default';
    $path = 'temp/';
    $videoPathDisk = $path . $this->video->name;
    $videoPathDiskMp4 = $this->video->id . str_random(20) . uniqid() . now()->timestamp . '-converted.mp4';
    $urlWatermark = ucfirst(Helper::urlToDomain(url('/'))) . '/' . $story->user->username;
    $font = public_path('webfonts/arial.TTF');

    // Create Thumbnail Video
    try {
      $videoPoster = str_random(20) . uniqid() . now()->timestamp . '-poster.jpg';

      $ffmpeg = FFMpeg::fromDisk($disk)
        ->open($videoPathDisk)
        ->getFrameFromSeconds(1)
        ->export()
        ->toDisk($disk);

      $ffmpeg->save($path . $videoPoster);

      // Clean
      FFMpeg::cleanupTemporaryFiles();
    } catch (\Exception $e) {
      $videoPoster = null;
    }

    // Create a video format...
    $format = new X264();
    $format->setAudioCodec('aac');
    $format->setVideoCodec('libx264');
    $format->setKiloBitrate(0);

    try {
      // open the uploaded video from the right disk...
      if ($settings->watermark_on_videos == 'on') {
        $ffmpeg = FFMpeg::fromDisk($disk)
          ->open($videoPathDisk)
          ->addFilter(['-strict', -2])
          ->addFilter(function ($filters) use ($urlWatermark, $font) {
            $filters->custom("drawtext=text=$urlWatermark:fontfile=$font:x=W-tw-15:y=H-th-15:fontsize=30:fontcolor=white");
          })
          ->export()
          ->toDisk($disk)
          ->inFormat($format);

        $ffmpeg->save($path . $videoPathDiskMp4);
      } else {
        $ffmpeg = FFMpeg::fromDisk($disk)
          ->open($videoPathDisk)
          ->addFilter(['-strict', -2])
          ->export()
          ->toDisk($disk)
          ->inFormat($format);

        $ffmpeg->save($path . $videoPathDiskMp4);
      }

      // Clean
      FFMpeg::cleanupTemporaryFiles();

      // Delete old video
      Storage::disk('default')->delete($videoPathDisk);

      // Get Duration Video
      $durationInSeconds = $ffmpeg->getFormat()->get('duration');
      $durationVideo = explode('.', $durationInSeconds);
      $durationVideo = (int)$durationVideo[0];

      // Update name video on Media table
      MediaStories::whereId($this->video->id)->update([
        'name' => $videoPathDiskMp4,
        'video_poster' => $videoPoster ?? null,
        'video_length' => $durationVideo ?: null,
        'status' => true
      ]);

      // Update date the story and status
      Stories::whereId($this->video->stories_id)->update([
        'created_at' => now(),
        'status' => 'active'
      ]);

      // Move Video File to Storage
      $this->moveFileStorage($videoPathDiskMp4);

      // Move Video Poster to Storage
      if ($videoPoster) {
        $this->moveFileStorage($videoPoster);
      }

      // Notify to user - destination, author, type, target
      Notifications::send($story->user->id, $story->user->id, 17, 0);

    } catch (\Exception $e) {
      Stories::whereId($this->video->stories_id)->delete();

      MediaStories::whereId($this->video->id)->delete();

      // Notify to user (ERROR) - destination, author, type, target
      Notifications::send($story->user->id, $story->user->id, 22, 0);

      // Delete file
      $this->deleteFile($videoPathDisk);

      if ($videoPoster) {
        $this->deleteFile($videoPoster);
      }
    }
  } // End Handle

  /**
   * Move file to Storage
   *
   * @return void
   */
  protected function moveFileStorage($file)
  {
    $disk = config('filesystems.default');
    $path = config('path.stories');
    $localFile = public_path('temp/' . $file);

    // Move the file...
    Storage::disk($disk)->putFileAs($path, new File($localFile), $file);

    // Delete temp file
    unlink($localFile);
  }

  /**
   * Delete file from temp folder (Eror)
   *
   * @return void
   */
  protected function deleteFile($file)
  {
    $localFile = public_path($file);

    unlink($localFile);
  }
}
