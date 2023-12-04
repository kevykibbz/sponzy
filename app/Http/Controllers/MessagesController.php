<?php

namespace App\Http\Controllers;

use Cache;
use App\Helper;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Messages;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MediaMessages;
use App\Models\Notifications;
use App\Events\MassMessagesEvent;
use App\Jobs\EncodeVideoMessages;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Traits\PushNotificationTrait;
use App\Services\CoconutVideoService;

class MessagesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  // Subscribed to your Content
  protected function subscribedToYourContent($user)
  {
    return auth()->user()
      ->mySubscriptions()
      ->where('subscriptions.user_id', $user->id)
      ->where('stripe_id', '=', '')
      ->where('ends_at', '>=', now())
      ->whereIn('stripe_price', auth()->user()->plans()->pluck('name'))

      ->orWhere('stripe_status', 'active')
      ->where('subscriptions.user_id', auth()->id())
      ->where('stripe_id', '<>', '')
      ->whereIn('stripe_price', $user->plans()->pluck('name'))

      ->orWhere('stripe_status', 'canceled')
      ->where('subscriptions.user_id', auth()->id())
      ->where('ends_at', '>=', now())
      ->where('stripe_id', '<>', '')
      ->whereIn('stripe_price', $user->plans()->pluck('name'))

      ->orWhere('stripe_id', '=', '')
      ->where('stripe_price', $user->plan)
      ->where('free', '=', 'yes')
      ->where('subscriptions.user_id', auth()->id())
      ->first();
  }

  // Subscribed to my Content
  protected function subscribedToMyContent($user)
  {
    return auth()->user()
      ->userSubscriptions()
      ->whereIn('stripe_price', $user->plans()->pluck('name'))
      ->where('stripe_id', '=', '')
      ->where('ends_at', '>=', now())

      ->orWhere('stripe_status', 'active')
      ->where('stripe_id', '<>', '')
      ->where('user_id', $user->id)
      ->whereIn('stripe_price', auth()->user()->plans()->pluck('name'))

      ->orWhere('stripe_status', 'canceled')
      ->where('stripe_id', '<>', '')
      ->where('user_id', $user->id)
      ->where('ends_at', '>=', now())
      ->whereIn('stripe_price', auth()->user()->plans()->pluck('name'))

      ->orWhere('stripe_id', '=', '')
      ->where('stripe_price', auth()->user()->plan)
      ->where('free', '=', 'yes')
      ->whereUserId($user->id)
      ->first();
  }

  /**
   * Display all messages inbox
   *
   * @return Response
   */
  public function inbox()
  {
    $messages = Messages::conversations();

    if (request()->ajax()) {
      return view('includes.messages-inbox', ['messagesInbox' => $messages])->render();
    }

    return view('users.messages', ['messagesInbox' => $messages]);
  } //<--- End Method inbox

  /**
   * Section chat
   *
   * @param int  $id
   * @return Response
   */
  public function messages($id)
  {
    $user = User::whereId($id)->where('id', '<>', auth()->id())->firstOrFail();

    $messages = Messages::where('to_user_id', auth()->id())
      ->where('from_user_id', $id)
      ->whereMode('active')
      ->orWhere('from_user_id', auth()->id())
      ->where('to_user_id', $id)
      ->whereMode('active')
      ->orderBy('messages.updated_at', 'DESC')
      ->take(1);

    $messagesInbox = Messages::conversations();

    // UPDATE MESSAGE 'READED'
    $messageReaded = new Messages();
    $messageReaded->timestamps = false;

    $messageReaded->newModelQuery()
      ->where('from_user_id', $id)
      ->where('to_user_id', auth()->id())
      ->where('status', 'new')
      ->update(['status' => 'readed']);

    // Check if subscription exists
    $subscribedToYourContent = $this->subscribedToYourContent($user);

    $subscribedToMyContent = $this->subscribedToMyContent($user);

    return view('users.messages-show', [
      'messages' => $messages,
      'messagesInbox' => $messagesInbox,
      'user' => $user,
      'subscribedToYourContent' => $subscribedToYourContent,
      'subscribedToMyContent' => $subscribedToMyContent
    ]);
  } //<--- End Method messages

  public function loadAjaxChat($id)
  {
    if (!request()->ajax()) {
      abort(401);
    }

    $user = User::whereId($id)->where('id', '<>', auth()->id())->firstOrFail();

    $messages = Messages::getMessageChat($id);

    $data = [];

    if ($messages->count()) {
      $data['reverse'] = collect($messages->values())->reverse();
    } else {
      $data['reverse'] = $messages;
    }

    $messages = $data['reverse'];

    return view('includes.messages-chat', [
      'messages' => $messages,
      'user' => $user,
      'hasMorePages' => $hasMorePages ?? null,
    ])->render();
  }

  // Insert Single chat
  public function ajaxChat(Request $request)
  {
    if (!auth()->check()) {
      return response()->json(['session_null' => true]);
    }

    $_sql = $request->get('first_msg') == 'true' ? '=' : '>';

    $fields = 'id,avatar,name,username';

    $message = Messages::where('to_user_id', auth()->id())
      ->where('from_user_id', $request->get('user_id'))
      ->where('id', $_sql, $request->get('last_id'))
      ->whereMode('active')
      ->orWhere('from_user_id', auth()->id())
      ->where('to_user_id', $request->get('user_id'))
      ->where('id', $_sql, $request->get('last_id'))
      ->whereMode('active')
      ->orderBy('messages.id', 'ASC')
      ->with(['sender:' . $fields, 'receiver:' . $fields, 'media'])
      ->get();

    $count = $message->count();
    $_array = [];

    if ($count != 0) {
      foreach ($message as $msg) {
        // UPDATE HOW READ MESSAGE
        if ($msg->to_user_id == auth()->id()) {
          $readed = Messages::where('id', $msg->id)
            ->where('to_user_id', auth()->id())
            ->where('status', 'new')
            ->update(['status' => 'readed'], ['updated_at' => false]);
        }

        $_array[] = view('includes.messages-chat', [
          'messages' => $message,
          'single' => true
        ])->render();
      } //<--- foreach
    } //<--- IF != 0

    $user = User::findOrFail($request->get('user_id'));

    if ($user->active_status_online == 'yes') {
      // Check User Online
      if (Cache::has('is-online-' . $request->get('user_id'))) {
        $userOnlineStatus = true;
      } else {
        $userOnlineStatus = false;
      }
    } else {
      $userOnlineStatus = null;
    }

    return response()->json([
      'total'    => $count,
      'messages' => $_array,
      'success' => true,
      'to' => $request->get('user_id'),
      'userOnline' => $userOnlineStatus,
      'last_seen' => date('c', strtotime($user->last_seen ?? $user->date))
    ], 200);
  } //<--- End Method ajaxChat

  /**
   * Load More Messages
   *
   * @param  \Illuminate\Http\Request  $request
   * @return Response
   */
  public function loadmore(Request $request)
  {
    $id   = $request->input('id');
    $skip = $request->input('skip');
    $user = User::whereId($id)->where('id', '<>', auth()->id())->firstOrFail();

    $messages = Messages::getMessageChat($id, $skip);

    $data = [];

    if ($messages->count()) {
      $data['reverse'] = collect($messages->values())->reverse();
    } else {
      $data['reverse'] = $messages;
    }

    $messages = $data['reverse'];

    return view('includes.messages-chat', [
      'messages' => $messages,
      'user' => $user,
      'hasMorePages' => $hasMorePages ?? null,
    ])->render();
  } //<--- End Method

  public function send(Request $request)
  {
    if (!auth()->check()) {
      return response()->json(['session_null' => true]);
    }

    // PATHS
    $path = config('path.messages');

    // Find user in Database
    $user = User::findOrFail($request->get('id_user'));

    // Currency Position
    if (config('settings.currency_position') == 'right') {
      $currencyPosition =  2;
    } else {
      $currencyPosition =  null;
    }

    $messages = [
      "required"    => __('validation.required'),
      "message.max"  => __('validation.max.string'),
      'price.min' => __('general.amount_minimum' . $currencyPosition, ['symbol' => config('settings.currency_symbol'), 'code' => config('settings.currency_code')]),
      'price.max' => __('general.amount_maximum' . $currencyPosition, ['symbol' => config('settings.currency_symbol'), 'code' => config('settings.currency_code')]),
    ];

    // Setup the validator
    $rules = [
      'message' => 'required|min:1|max:' . config('settings.comment_length') . '',
      'zip'    => 'mimes:zip|max:' . config('settings.file_size_allowed') . '',
      'price'  => 'numeric|min:' . config('settings.min_ppv_amount') . '|max:' . config('settings.max_ppv_amount'),
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    // Validate the input and return correct response
    if ($validator->fails()) {
      return response()->json(array(
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ));
    }

    $time = Carbon::now();

    $message = new Messages();
    $message->conversations_id = 0;
    $message->from_user_id    = auth()->id();
    $message->to_user_id      = $user->id;
    $message->message         = trim(Helper::checkTextDb($request->get('message')));
    $message->updated_at      = $time;
    $message->price           = $request->price;
    $message->mode            = 'pending';
    $message->save();

    // Insert Files
    $fileuploader = $request->input('fileuploader-list-media');
    $fileuploader = json_decode($fileuploader, TRUE);

    if ($fileuploader) {
      foreach ($fileuploader as $key => $media) {
        MediaMessages::whereFile($media['file'])->update([
          'messages_id' => $message->id,
          'status' => 'active'
        ]);
      }
    }

    //=== Upload File Zip
    if ($request->hasFile('zip')) {
      $fileZip         = $request->file('zip');
      $extension       = $fileZip->getClientOriginalExtension();
      $size            = Helper::formatBytes($fileZip->getSize(), 1);
      $originalName    = Helper::fileNameOriginal($fileZip->getClientOriginalName());
      $file            = strtolower(auth()->id() . time() . Str::random(20) . '.' . $extension);

      $fileZip->storePubliclyAs($path, $file);
      $token = Str::random(150) . uniqid() . now()->timestamp;

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
    } //=== End Upload File Zip

    // Get all videos of the message
    $videos = MediaMessages::whereMessagesId($message->id)->whereType('video')->get();

    if ($videos->count() && config('settings.video_encoding') == 'on') {
      try {
        foreach ($videos as $video) {
          if (config('settings.encoding_method') == 'ffmpeg') {
            $this->dispatch(new EncodeVideoMessages($video));
          } else {
            CoconutVideoService::handle($video, 'message');
          }
        }

        return response()->json([
          'success' => true,
          'encode' => true
        ]);
      } catch (\Exception $e) {
        \Log::info($e->getMessage());

        return response()->json([
          'success' => false,
          'errors' => ['error' => $e->getMessage()],
        ]);
      }
    } // End Videos->count

    // Get the minutes that the receiver of the message was active
    $diffInMinutes = now()->diffInMinutes($user->last_seen);
    $getPushNotificationDevices = $user->oneSignalDevices->pluck('player_id')->all();

    if (config('settings.push_notification_status') && $getPushNotificationDevices && $diffInMinutes > 10 && $diffInMinutes < 1000) {

      app()->setLocale($user->language);

      $messagePush = __('general.new_msg_from') . ' @' . auth()->user()->username;
      $linkDestination = url('messages', auth()->id());

      // Send push notification
      PushNotificationTrait::sendPushNotification($messagePush, $linkDestination, $getPushNotificationDevices);
    }

    // Change status Pending to Encode
    Messages::whereId($message->id)->update([
      'mode' => 'active'
    ]);

    return response()->json([
      'success' => true,
      'fromChat' => true,
      'last_id' => $message->id,
    ], 200);
  } //<<--- End Method send()

  public function delete(Request $request)
  {
    $message_id = $request->get('message_id');
    $path   = config('path.messages');

    $data = Messages::where('from_user_id', auth()->id())
      ->where('id', $message_id)
      ->orWhere('to_user_id', auth()->id())
      ->where('id', $message_id)->first();

    // Delete Notifications
    Notifications::where('target', $message_id)
      ->where('type', '10')
      ->delete();

    if (isset($data)) {

      foreach ($data->media as $media) {

        $messageWithSameFile = MediaMessages::whereFile($media->file)
          ->where('id', '<>', $media->id)
          ->count();

        if ($messageWithSameFile == 0) {
          Storage::delete($path . $media->file);
          Storage::delete($path . $media->video_poster);
        }

        $media->delete();
      }

      $countMessages = Messages::where('from_user_id', $data->from_user_id)
        ->where('to_user_id', $data->to_user_id)
        ->where('id', '<>', '$message_id')
        ->orWhere('from_user_id', $data->to_user_id)
        ->where('to_user_id', $data->from_user_id)
        ->where('id', '<>', '$message_id')
        ->count();

      $data->delete();

      return response()->json([
        'success' => true,
        'total' => $countMessages
      ]);
    } else {
      return response()->json([
        'success' => false,
        'error' => __('general.error')
      ]);
    }
  } //<--- End Method delete

  public function searchCreator(Request $request)
  {
    $query = $request->get('user');
    $data = "";

    if ($query != '' && strlen($query) >= 2) {
      $sql = User::where('status', 'active')
        ->where('username', 'LIKE', '%' . $query . '%')
        ->where('id', '<>', auth()->id())
        ->where('id', '<>', config('settings.hide_admin_profile') == 'on' ? 1 : 0)
        ->where('blocked_countries', 'NOT LIKE', '%' . Helper::userCountry() . '%')
        ->when(auth()->user()->verified_id <> 'yes', function ($query) {
          $query->where('verified_id', 'yes');
        })
        ->orWhere('status', 'active')
        ->where('name', 'LIKE', '%' . $query . '%')
        ->where('id', '<>', auth()->id())
        ->where('id', '<>', config('settings.hide_admin_profile') == 'on' ? 1 : 0)
        ->where('blocked_countries', 'NOT LIKE', '%' . Helper::userCountry() . '%')
        ->whereHideName('no')
        ->when(auth()->user()->verified_id <> 'yes', function ($query) {
          $query->where('verified_id', 'yes');
        })
        ->orderBy('verified_id', 'asc')
        ->take(5)
        ->get();

      if ($sql) {
        foreach ($sql as $user) {

          if ($user->active_status_online == 'yes') {
            if (Cache::has('is-online-' . $user->id)) {
              $userOnlineStatus = 'user-online';
            } else {
              $userOnlineStatus = 'user-offline';
            }
          } else {
            $userOnlineStatus = null;
          }

          $name = $user->hide_name == 'yes' ? $user->username : $user->name;
          $verified = $user->verified_id == 'yes' ? '<small class="verified"><i class="bi bi-patch-check-fill"></i></small>' : null;

          $data .= '<div class="card mb-2">
             <div class="list-group list-group-sm list-group-flush">
               <a href="' . url('messages/' . $user->id, $user->username) . '" class="list-group-item list-group-item-action text-decoration-none p-2">
                 <div class="media">
                  <div class="media-left mr-3 position-relative ' . $userOnlineStatus . '">
                      <img class="media-object rounded-circle" src="' . Helper::getFile(config('path.avatar') . $user->avatar) . '" width="45" height="45">
                  </div>
                  <div class="media-body overflow-hidden">
                    <div class="d-flex justify-content-between align-items-center">
                     <h6 class="media-heading mb-0 text-truncate">
                          ' . $name . ' ' . $verified . '
                      </h6>
                    </div>
                    <p class="text-truncate m-0 w-100 text-left">
                    <small>@' . $user->username . '</small>
                    </p>
                  </div>
              </div>
                </a>
             </div>
           </div>';
        }
        return $data;
      }
    }
  } // End Method

  public function deleteChat($id)
  {
    $path = config('path.messages');

    $messages = Messages::where('to_user_id', auth()->id())
      ->where('from_user_id', $id)
      ->orWhere('from_user_id', auth()->id())
      ->where('to_user_id', $id)
      ->get();

    if ($messages->count() != 0) {
      foreach ($messages as $msg) {

        foreach ($msg->media as $media) {

          $messageWithSameFile = MediaMessages::whereFile($media->file)
            ->where('id', '<>', $media->id)
            ->count();

          if ($messageWithSameFile == 0) {
            Storage::delete($path . $media->file);
          }

          $media->delete();
        }

        $msg->delete();
      }

      // Delete Notifications
      Notifications::where('destination', auth()->id())
        ->where('type', '10')
        ->delete();

      return redirect('messages');
    } else {
      return redirect('messages');
    }
  } //<--- End Method delete

  // Download File
  public function downloadFileZip($id)
  {
    $msg = Messages::findOrFail($id);

    if ($msg->to_user_id != auth()->id() && $msg->from_user_id != auth()->id()) {
      abort(404);
    }

    $media = MediaMessages::whereMessagesId($msg->id)->where('type', 'zip')->firstOrFail();

    $pathFile = config('path.messages') . $media->file;
    $headers = [
      'Content-Type:' => 'application/x-zip-compressed',
      'Cache-Control' => 'no-cache, no-store, must-revalidate',
      'Pragma' => 'no-cache',
      'Expires' => '0'
    ];

    return Storage::download($pathFile, $media->file_name . '.zip', $headers);
  } // End Method

  public function sendMessageMassive(Request $request)
  {
    if (!auth()->check()) {
      return response()->json(['session_null' => true]);
    }

    // PATHS
    $path = config('path.messages');

    // Currency Position
    if (config('settings.currency_position') == 'right') {
      $currencyPosition =  2;
    } else {
      $currencyPosition =  null;
    }

    $messages = [
      "required"    => __('validation.required'),
      "message.max"  => __('validation.max.string'),
      'price.min' => __('general.amount_minimum' . $currencyPosition, ['symbol' => config('settings.currency_symbol'), 'code' => config('settings.currency_code')]),
      'price.max' => __('general.amount_maximum' . $currencyPosition, ['symbol' => config('settings.currency_symbol'), 'code' => config('settings.currency_code')]),
    ];

    // Setup the validator
    $rules = [
      'message' => 'required|min:1|max:' . config('settings.comment_length') . '',
      'zip'    => 'mimes:zip|max:' . config('settings.file_size_allowed') . '',
      'price'  => 'numeric|min:' . config('settings.min_ppv_amount') . '|max:' . config('settings.max_ppv_amount'),
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    // Validate the input and return correct response
    if ($validator->fails()) {
      return response()->json(array(
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ));
    }

    //=== Upload File Zip
    if ($request->hasFile('zip')) {

      $fileZip         = $request->file('zip');
      $extension       = $fileZip->getClientOriginalExtension();
      $size            = Helper::formatBytes($fileZip->getSize(), 1);
      $originalName    = Helper::fileNameOriginal($fileZip->getClientOriginalName());
      $file            = strtolower(auth()->id() . time() . Str::random(20) . '.' . $extension);

      $fileZip->storePubliclyAs($path, $file);
      $token = Str::random(150) . uniqid() . now()->timestamp;
    } //=== End Upload File Zip

    // Event send all messages
    $authUser = auth()->user();
    $fileuploader = $request->input('fileuploader-list-media');
    $fileuploader = json_decode($fileuploader, TRUE);
    $messageData = $request->get('message');
    $priceMessage = $request->price;
    $hasFileZip = $request->hasFile('zip') ? true : false;

    event(new MassMessagesEvent(
      $authUser,
      $fileuploader,
      $messageData,
      $priceMessage,
      $hasFileZip,
      $file ?? null,
      $originalName ?? null,
      $size ?? null,
      $token ?? null
    ));

    return response()->json([
      'success' => true,
      'fromChat' => false,
    ], 200);
  } //<<--- End Method send()

}
