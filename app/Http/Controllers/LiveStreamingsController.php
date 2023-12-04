<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\User;
use App\Models\Reports;
use App\Models\LiveLikes;
use App\Models\LiveComments;
use Illuminate\Http\Request;
use App\Models\LiveStreamings;
use App\Models\LiveOnlineUsers;
use App\Events\LiveBroadcasting;
use Illuminate\Support\Facades\Validator;

class LiveStreamingsController extends Controller
{
  use Traits\Functions;

  public function __construct(Request $request)
  {
    $this->request = $request;
    $this->middleware('auth');
  }

  // Create live Stream
  public function create()
  {
    // Currency Position
    if (config('settings.currency_position') == 'right') {
      $currencyPosition =  2;
    } else {
      $currencyPosition =  null;
    }

    $messages = [
      'price.min' => __('general.amount_minimum' . $currencyPosition, ['symbol' => config('settings.currency_symbol'), 'code' => config('settings.currency_code')]),
      'price.max' => __('general.amount_maximum' . $currencyPosition, ['symbol' => config('settings.currency_symbol'), 'code' => config('settings.currency_code')]),
      'price.required_if' => __('validation.required')
    ];

    $validator = Validator::make($this->request->all(), [
      'name' => 'required|max:50',
      'price' => 'required_if:availability,all_pay,free_paid_subscribers|integer|min:' . config('settings.live_streaming_minimum_price') . '|max:' . config('settings.live_streaming_max_price'),
    ], $messages);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    }

    // Create Live Stream
    $live          = new LiveStreamings();
    $live->user_id = auth()->id();
    $live->name    = $this->request->name;
    $live->channel = 'live_' . str_random(5) . '_' . auth()->id();
    $live->price   = $this->request->price ?? 0;
    $live->availability = $this->request->availability;
    $live->save();

    // Notify to subscribers
    event(new LiveBroadcasting(auth()->user(), $live->id));

    return response()->json([
      'success' => true,
      'url' => url('live', auth()->user()->username)
    ]);
  } // End method create

  // End Live Stream
  public function finish($id)
  {
    if (!$this->request->expectsJson()) {
      abort(404);
    }

    $live = LiveStreamings::whereId($id)
      ->whereUserId(auth()->id())
      ->firstOrFail();
    $live->status = '1';
    $live->save();

    return response()->json([
      'success' => true
    ]);
  } // End method finish

  // Show Live Stream
  public function show()
  {
    // Live Streaming OFF
    if (config('settings.live_streaming_status') == 'off') {
      return redirect('/');
    }

    // Find Creator
    $creator = User::whereUsername($this->request->username)
      ->whereVerifiedId('yes')
      ->firstOrFail();

    // Hidden Live Blocked Countries
    if (
      in_array(Helper::userCountry(), $creator->blockedCountries())
      && auth()->check()
      && auth()->user()->permission != 'all'
      && auth()->id() != $creator->id
      || auth()->guest()
      && in_array(Helper::userCountry(), $creator->blockedCountries())
    ) {
      abort(404);
    }

    // Search last Live Streaming
    $live = LiveStreamings::whereType('normal')
      ->whereUserId($creator->id)
      ->where('updated_at', '>', now()->subMinutes(5))
      ->whereStatus('0')
      ->orderBy('id', 'desc')
      ->first();

    // Check subscription
    $checkSubscription = auth()->user()->checkSubscription($creator);

    // Free for paying subscribers
    if (
      $live
      && $checkSubscription
      && $checkSubscription->free == 'no'
      && $creator->id != auth()->id()
      && $live->availability == 'free_paid_subscribers'
    ) {
      LiveOnlineUsers::firstOrCreate([
        'user_id' => auth()->id(),
        'live_streamings_id' => $live->id
      ]);

      // Inser Comment Joined User
      LiveComments::firstOrCreate([
        'user_id' => auth()->id(),
        'live_streamings_id' => $live->id
      ]);
    }

    // Free for everyone
    if (
      $live
      && $creator->id != auth()->id()
      && $live->availability == 'everyone_free'
      && !auth()->user()->isSuperAdmin()
    ) {
      LiveOnlineUsers::firstOrCreate([
        'user_id' => auth()->id(),
        'live_streamings_id' => $live->id
      ]);

      // Inser Comment Joined User
      LiveComments::firstOrCreate([
        'user_id' => auth()->id(),
        'live_streamings_id' => $live->id
      ]);
    }

    // Check User Online (Already paid)
    if ($live) {
      $userPaidAccess = LiveOnlineUsers::whereUserId(auth()->id())
        ->whereLiveStreamingsId($live->id)
        ->first();

      $likes = $live->likes->count();
      $likeActive = $live->likes()->whereUserId(auth()->id())->first();

      if ($userPaidAccess) {
        $userPaidAccess->updated_at = now();
        $userPaidAccess->update();
      }
    }

    // Payment Access
    if ($live && $creator->id == auth()->id() || auth()->user()->isSuperAdmin()) {
      $paymentRequiredToAccess = false;
    } elseif ($live && $userPaidAccess || auth()->user()->isSuperAdmin()) {
      $paymentRequiredToAccess = false;
    } else {
      $paymentRequiredToAccess = true;
    }

    if ($live && config('settings.limit_live_streaming_paid') != 0 && $live->availability != 'everyone_free') {
      $limitLiveStreaming = config('settings.limit_live_streaming_paid') - $live->timeElapsed;
    } elseif ($live && config('settings.limit_live_streaming_free') != 0 && $live->availability == 'everyone_free') {
      $limitLiveStreaming = config('settings.limit_live_streaming_free') - $live->timeElapsed;
    } else {
      $limitLiveStreaming = false;
    }

    return view('users.live', [
      'creator' => $creator,
      'live' => $live,
      'checkSubscription' => $checkSubscription,
      'comments' => $live->comments ?? null,
      'likes' => $likes ?? null,
      'likeActive' => $likeActive ?? null,
      'paymentRequiredToAccess' => $paymentRequiredToAccess,
      'limitLiveStreaming' => $limitLiveStreaming > 0 ? $limitLiveStreaming : 0,
      'amountTips' => $live ? $live->comments()->sum('tip_amount') : 0
    ]);
  } // End method show

  public function getDataLive()
  {
    if (!auth()->check()) {
      return response()->json([
        'session_null' => true
      ]);
    }

    // Find Live Streaming
    $live = LiveStreamings::with(['comments'])
      ->withCount(['likes', 'onlineUsers'])
      ->whereId($this->request->live_id)
      ->whereUserId($this->request->creator)
      ->where('updated_at', '>', now()->subMinutes(5))
      ->whereStatus('0')
      ->first();

      $limitLiveStreaming = $live ? ($live->minutes - $live->timeElapsedLivePrivate) : 0;

    if ($live && $live->type == 'normal') {
      // Limit Live Streaming (time)
      if ($live && config('settings.limit_live_streaming_paid') != 0 && $live->availability != 'everyone_free') {
        $limitLiveStreaming = config('settings.limit_live_streaming_paid') - $live->timeElapsed;
      } elseif ($live && config('settings.limit_live_streaming_free') != 0 && $live->availability == 'everyone_free') {
        $limitLiveStreaming = config('settings.limit_live_streaming_free') - $live->timeElapsed;
      } else {
        $limitLiveStreaming = false;
      }
    }

    $status = $live ? 'online' : 'offline';

    if ($status == 'offline' || $limitLiveStreaming && $limitLiveStreaming <= 0) {
      if ($live && $limitLiveStreaming && $limitLiveStreaming <= 0) {
        $live->status = '1';
        $live->save();
      }

      return response()->json([
        'success' => true,
        'total' => null,
        'comments' => [],
        'onlineUsers' => 0,
        'status' => 'offline'
      ]);
    }

    // Online users
    $onlineUsers = $live->online_users_count;

    // Comments
    $comments = $live->comments()
      ->where('id', '>', $this->request->get('last_id'))
      ->get();

    $totalComments = $comments->count();
    $allComments = array();

    if ($totalComments != 0) {

      foreach ($comments as $comment) {
        $allComments[] = view('includes.comments-live', [
          'comments' => $comments
        ])->render();
      } //<--- foreach
    } //<--- IF != 0

    // Likes
    $likes = $live->likes_count;

    // Sum all tips
    $tipsAmount = $live->comments->sum('tip_amount');

    return response()->json([
      'success' => true,
      'comments' => $allComments,
      'likes' => Helper::formatNumber($likes),
      'onlineUsers' => Helper::formatNumber($onlineUsers),
      'status' => $status,
      'total' => $totalComments,
      'time' => $limitLiveStreaming > 0 ? $limitLiveStreaming : 0,
      'amountTips' => $tipsAmount != 0 ? Helper::formatPrice($tipsAmount) : null
    ]);
  } // End method getDataLive

  public function paymentAccess()
  {
    // Verify that the user has not paid
    if (LiveOnlineUsers::whereUserId(auth()->id())
      ->whereLiveStreamingsId($this->request->id)
      ->first()
    ) {
      return response()->json([
        "success" => false,
        "errors" => ['error' => __('general.already_payment_live_access')]
      ]);
    }

    // Find live exists
    $live = LiveStreamings::whereId($this->request->id)
      ->where('updated_at', '>', now()->subMinutes(5))
      ->whereStatus('0')
      ->firstOrFail();

    $messages = [
      'payment_gateway_live.required' => __('general.choose_payment_gateway')
    ];

    //<---- Validation
    $validator = Validator::make($this->request->all(), [
      'payment_gateway_live' => 'required'
    ], $messages);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    }

    if (auth()->user()->wallet < $live->price) {
      return response()->json([
        "success" => false,
        "errors" => ['error' => __('general.not_enough_funds')]
      ]);
    }

    // Admin and user earnings calculation
    $earnings = $this->earningsAdminUser($live->user()->custom_fee, $live->price, null, null);

    //== Insert Transaction
    $this->transaction(
      'live_' . str_random(25),
      auth()->id(),
      0,
      $live->user()->id,
      $live->price,
      $earnings['user'],
      $earnings['admin'],
      'Wallet',
      'live',
      $earnings['percentageApplied'],
      auth()->user()->taxesPayable()
    );

    // Subtract user funds
    auth()->user()->decrement('wallet', Helper::amountGross($live->price));

    // Add Earnings to User
    $live->user()->increment('balance', $earnings['user']);

    // Insert user to Online User
    $sql = new LiveOnlineUsers();
    $sql->user_id = auth()->id();
    $sql->live_streamings_id = $live->id;
    $sql->save();

    // Inser Comment Joined User
    $sql            = new LiveComments();
    $sql->user_id   = auth()->id();
    $sql->live_streamings_id = $live->id;
    $sql->save();

    return response()->json([
      "success" => true,
    ]);
  } // End method paymentAccess

  // Comments
  public function comments()
  {
    // Find Live Streaming
    $live = LiveStreamings::whereId($this->request->live_id)
      ->where('updated_at', '>', now()->subMinutes(5))
      ->whereStatus('0')
      ->firstOrFail();

    $messages = [
      'comment.required' => __('general.please_write_something'),
    ];

    //<---- Validation
    $validator = Validator::make($this->request->all(), [
      'comment' =>  'required|max:100|min:1',
    ], $messages);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    }

    $sql            = new LiveComments();
    $sql->user_id   = auth()->id();
    $sql->live_streamings_id = $live->id;
    $sql->comment   = trim(Helper::checkTextDb($this->request->comment));
    $sql->joined    = 0;
    $sql->save();

    return response()->json([
      'success' => true,
    ]);
  } //<--- End Method

  public function like()
  {
    // Find Live Streaming
    $live = LiveStreamings::whereId($this->request->id)
      ->where('updated_at', '>', now()->subMinutes(5))
      ->whereStatus('0')
      ->firstOrFail();

    $like = LiveLikes::firstOrNew([
      'user_id' => auth()->id(),
      'live_streamings_id' => $this->request->id
    ]);

    if ($like->exists) {
      $like->delete();
    } else {
      $like->save();
    }

    $likes = $live->likes->count();

    return response()->json([
      'success' => true,
      'likes' => $likes
    ]);
  }

  public function report()
  {
    $data = Reports::firstOrNew([
      'user_id' => auth()->id(),
      'report_id' => $this->request->id,
      'type' => 'live'
    ]);

    $validator = Validator::make($this->request->all(), [
      'reason' => 'required|in:spoofing,copyright,privacy_issue,violent_sexual,spam,fraud',
      'message' => 'required|max:200',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    }

    if ($data->exists) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => __('general.already_sent_report')],
      ]);
    } else {
      $data->reason = $this->request->reason;
      $data->message = $this->request->message;
      $data->save();

      return response()->json([
        'success' => true,
        'text' => __('general.reported_success'),
      ]);
    }
  }
}
