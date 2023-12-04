<?php

namespace App\Jobs;

use FFMpeg;
use App\Helper;
use Illuminate\Http\File;
use App\Models\AdminSettings;
use App\Models\Notifications;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use App\Models\MediaWelcomeMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EncodeVideoWelcomeMessage implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $video;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(MediaWelcomeMessage $video)
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

    $user = MediaWelcomeMessage::with(['creator:id,username'])->whereId($this->video->id)->first();

    // Paths
    $disk = 'default';
    $path = 'temp/';
    $videoPathDisk = $path . $this->video->file;
    $videoPathDiskMp4 = $this->video->id . str_random(20) . uniqid() . now()->timestamp . '-converted.mp4';
    $urlWatermark = ucfirst(Helper::urlToDomain(url('/'))) . '/' . $user->creator->username;
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

      // Get Dimensions video
      $dimensions = $ffmpeg->getVideoStream()->getDimensions();

      // Update name video on Media table
      MediaWelcomeMessage::whereId($this->video->id)->update([
        'file' => $videoPathDiskMp4,
        'encoded' => 'yes',
        'status' => 'active',
        'video_poster' => $videoPoster ?? null,
        'duration_video' => $durationVideo ? Helper::getDurationInMinutes($durationVideo) : null,
        'quality_video' => $dimensions->getWidth() ? Helper::getResolutionVideo($dimensions->getWidth()) : null
      ]);

      // Move Video File to Storage
      $this->moveFileStorage($videoPathDiskMp4);

      // Move Video Poster to Storage
      if ($videoPoster) {
        $this->moveFileStorage($videoPoster);
      }

      // Notify to user - destination, author, type, target
      Notifications::send($user->creator->id, $user->creator->id, 24, $this->video->id);

    } catch (\Exception $e) {
      // Notify to user (ERROR) - destination, author, type, target
      Notifications::send($user->creator->id, $user->creator->id, 25, $this->video->id);

      // Delete file
      $this->deleteFile($videoPathDisk);

      if ($videoPoster) {
        $this->deleteFile($videoPoster);
      }

      // Delete Media
      MediaWelcomeMessage::whereId($this->video->id)->delete();
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
    $path = config('path.welcome_messages');
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
