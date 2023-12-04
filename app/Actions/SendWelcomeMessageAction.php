<?php

namespace App\Actions;

use App\Helper;
use App\Models\User;
use App\Models\Messages;
use App\Models\MediaMessages;
use App\Models\Subscriptions;
use App\Models\MediaWelcomeMessage;
use App\Models\SubscriptionDeleted;
use Illuminate\Support\Facades\Storage;

final class SendWelcomeMessageAction
{
    public function execute(User $creator, $subscriberId)
    {
        if (
            $creator->send_welcome_message
            && SubscriptionDeleted::whereCreatorId($creator->id)->whereUserId($subscriberId)->doesntExist()
            && Subscriptions::whereCreatorId($creator->id)->whereUserId($subscriberId)->count() == 1
        ) {
            try {
                $message = new Messages();
                $message->conversations_id = 0;
                $message->from_user_id = $creator->id;
                $message->to_user_id = $subscriberId;
                $message->message = trim(Helper::checkTextDb($creator->welcome_message_new_subs));
                $message->updated_at = now();
                $message->price = $creator->price_welcome_message ?: 0.00;
                $message->save();

                // Select Media Media Welcome Message of Creator
                $media = MediaWelcomeMessage::whereCreatorId($creator->id)->whereStatus('active')->first();

                if ($media) {
                    MediaMessages::create([
                        'messages_id' => $message->id,
                        'type' => $media->type,
                        'file' => $media->file,
                        'token' => $media->token,
                        'width' => $media->width,
                        'height' => $media->height,
                        'video_poster' => $media->video_poster,
                        'duration_video' => $media->duration_video,
                        'quality_video' => $media->quality_video,
                        'encoded' => $media->encoded,
                        'job_id' => $media->job_id,
                        'status' => 'active',
                        'created_at' => now()
                    ]);

                    Storage::copy(config('path.welcome_messages') . $media->file, config('path.messages') . $media->file);

                    if ($media->video_poster) {
                        Storage::copy(config('path.welcome_messages') . $media->video_poster, config('path.messages') . $media->video_poster);
                    }
                }
            } catch (\Exception $e) {
                info('Error SendWelcomeMessageAction - ' . $e->getMessage());
            }
        }
    }
}
