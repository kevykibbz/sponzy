<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CoconutVideoService
{
    public static function handle(Model $model, string $type)
    {
        switch ($type) {
            case 'post':
                $urlMedia = $model->video;
                $urlStorage = url('webhook/storage', $model->id);
                $urlWebhook = route('webhook.coco');
                $params = [
                    'postId' => $model->updates_id,
                    'mediaId' => $model->id
                ];

                self::job($model, $urlMedia, $params, $urlStorage, $urlWebhook);
                break;

            case 'message':
                $urlMedia = $model->file;
                $urlStorage = url('webhook/storage/message', $model->id);
                $urlWebhook = route('webhook.message.coco');
                $params = [
                    'messagesId' => $model->messages_id,
                    'mediaId' => $model->id
                ];

                self::job($model, $urlMedia, $params, $urlStorage, $urlWebhook);
                break;

            case 'welcomeMessage':
                $urlMedia = $model->file;
                $urlStorage = url('webhook/storage/welcome/message', $model->id);
                $urlWebhook = route('webhook.welcome.message.coco');
                $params = [
                    'creatorId' => $model->creator_id,
                    'mediaId' => $model->id
                ];

                self::job($model, $urlMedia, $params, $urlStorage, $urlWebhook);
                break;

            case 'story':
                $urlMedia = $model->name;
                $urlStorage = url('webhook/storage/story', $model->id);
                $urlWebhook = route('webhook.story.coco');
                $params = [
                    'storiesId' => $model->stories_id,
                    'mediaId' => $model->id
                ];

                self::job($model, $urlMedia, $params, $urlStorage, $urlWebhook);
                break;
        }
    }

    public static function job($model, $urlMedia, $params, $urlStorage, $urlWebhook)
    {
        $coconut = new \Coconut\Client(config('settings.coconut_key'));
        $url = url('public/temp', $urlMedia);
        $videoName = strtolower(auth()->user()->username . '-' . auth()->id() . time());

        $coconut->notification = [
            'type' => 'http',
            'url' => $urlWebhook,
            'metadata' => true,
            'params' => $params
        ];

        $coconut->storage = [
            'url' => $urlStorage
        ];

        try {
            $job = $coconut->job->create([
                'settings' => [
                    'ultrafast' => true
                ],
                'input' => ['url' => $url],
                'outputs' => [
                    'jpg' => [
                        'path' => "/thumbnail-{$videoName}.jpg",
                        'offsets' => [1]
                    ],

                    'mp4:::quality=5' => [
                        'path' => "/{$videoName}.mp4",
                        'watermark' => config('settings.watermark_on_videos') == 'on' ? [
                            'url' => url('public/img', config('settings.watermak_video')),
                            'position' => config('settings.watermark_position')
                        ] : false,
                    ]

                ]
            ]);

            $model->whereId($model->id)->update([
                'job_id' => $job->id
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function uploadVideo(Model $media, $field, $filePath, $storage, $request)
    {
        // Video Upload
        if ($request->hasFile('encoded_video') && $request->video_id) {
            $file = $request->file('encoded_video');
            $extension = $request->file('encoded_video')->getClientOriginalExtension();
            $fileName = 'converted-' . str_random(20) . uniqid() . now()->timestamp;
            $fileUpload = $fileName . '.' . $extension;

            // Video folder temp
            $videoPathDisk = 'temp/' . $filePath;

            $media->update([
                $field => $fileUpload,
            ]);

            $file->storePubliclyAs($storage, $fileUpload, config('filesystems.default'));

            // Delete old video
            Storage::disk('default')->delete($videoPathDisk);
        }
    }

    public static function uploadVideoPoster(Model $media, $storage, $request)
    {
        if ($request->hasFile('encoded_video') && !$request->video_id) {
            $file = $request->file('encoded_video');
            $extension = $request->file('encoded_video')->getClientOriginalExtension();
            $fileName = 'poster-' . str_random(20) . uniqid() . now()->timestamp;
            $fileUpload = $fileName . '.' . $extension;

            $media->update([
                'video_poster' => $fileUpload,
            ]);

            $file->storePubliclyAs($storage, $fileUpload, config('filesystems.default'));
        }
    }
}
