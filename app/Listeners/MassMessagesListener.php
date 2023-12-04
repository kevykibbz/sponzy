<?php

namespace App\Listeners;

use App\Helper;
use App\Models\Messages;
use App\Models\MediaMessages;
use App\Events\MassMessagesEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MassMessagesListener implements ShouldQueue
{

  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   *
   * @param  MassMessagesEvent  $event
   * @return void
   */
  public function handle(MassMessagesEvent $event)
  {
    // Get data
    $user = $event->user;
    $fileuploader = $event->fileuploader;
    $messageData = $event->messageData;
    $price = $event->priceMessage;
    $hasFileZip = $event->hasFileZip;
    $file = $event->file;
    $originalName = $event->originalName;
    $size = $event->size;
    $token = $event->token;

    // Get Subscriptions Active
    $subscriptionsActive = $user->mySubscriptions()
      ->where('stripe_id', '=', '')
      ->where('ends_at', '>=', now())
      ->orWhere('stripe_status', 'active')
      ->where('stripe_id', '<>', '')
      ->whereIn('stripe_price', $user->plans()->pluck('name'))
      ->orWhere('stripe_id', '=', '')
      ->whereIn('stripe_price', $user->plans()->pluck('name'))
      ->where('free', '=', 'yes')
      ->get();

    // Send an email notification to all subscribers when there is a new post
    foreach ($subscriptionsActive as $subscriber) {
      $message = new Messages();
      $message->conversations_id = 0;
      $message->from_user_id    = $user->id;
      $message->to_user_id      = $subscriber->user()->id;
      $message->message         = trim(Helper::checkTextDb($messageData));
      $message->updated_at      = now();
      $message->price           = $price;
      $message->save();

      if ($fileuploader) {
        foreach ($fileuploader as $key => $media) {
          $files = MediaMessages::whereFile($media['file'])
            ->where('messages_id', '<>', $message->id)
            ->groupBy('file')
            ->get();

          foreach ($files as $key) {
            $mediaMessages = new MediaMessages();
            $mediaMessages->messages_id = $message->id;
            $mediaMessages->type = $key->type;
            $mediaMessages->file = $key->file;
            $mediaMessages->video_poster = $key->video_poster;
            $mediaMessages->width = $key->width;
            $mediaMessages->height = $key->height;
            $mediaMessages->file_name = $key->file_name;
            $mediaMessages->file_size = $key->file_size;
            $mediaMessages->token = $key->token;
            $mediaMessages->status = 'active';
            $mediaMessages->created_at = now();
            $mediaMessages->save();
          }

          // Delete Old files
          MediaMessages::whereFile($media['file'])->whereMessagesId(0)->delete();
        }
      } // Fileuploader

      if ($hasFileZip) {
        // We insert the file into the database
        MediaMessages::create([
          'messages_id' => $message->id,
          'type' => 'zip',
          'file' => $file,
          'file_name' => $originalName,
          'file_size' => $size,
          'token' => $token,
          'status' => 'active',
          'created_at' => now()
        ]);
      }

    }

  }
}
