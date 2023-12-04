<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\User;
use App\Models\Messages;
use App\Models\LiveComments;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\LiveStreamings;
use App\Models\LiveOnlineUsers;
use App\Enums\LiveStreamingPrivateStatus;
use App\Models\LiveStreamingPrivateRequest;

class LiveStreamingPrivateController extends Controller
{
    use Traits\Functions;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function request(User $user)
    {
        if (!$this->request->minutes) {
            abort(500);
        }

        if (
            config('settings.live_streaming_private') == 'off'
            || $user->verified_id == 'no'
            || $user->allow_live_streaming_private == 'off'
            || auth()->user()->isRestricted($user->id)
        ) {
            return response()->json([
                'success' => false,
                'errors' => ['error' => __('general.request_cannot_processed')]
            ]);
        }

        $finalPriceItem = ($user->price_live_streaming_private * $this->request->minutes);
        $priceWithTaxes = Helper::amountGrossLivePrivateRequest($user->price_live_streaming_private, $this->request->minutes);

        // Check that the user has sufficient balance
        if (auth()->user()->wallet < $priceWithTaxes) {
            return response()->json([
                "success" => false,
                "errors" => ['error' => __('general.not_enough_funds')]
            ]);
        }

        // Admin and user earnings calculation
        $earnings = $this->earningsAdminUser($user->custom_fee, $finalPriceItem, null, null);

        //== Insert Transaction
        $transaction = $this->transaction(
            'private_live_' . str_random(25),
            auth()->id(),
            false,
            $user->id,
            $finalPriceItem,
            $earnings['user'],
            $earnings['admin'],
            'Wallet',
            'live_streaming_private',
            $earnings['percentageApplied'],
            auth()->user()->taxesPayable()
        );

        // Create Request Live Private
        LiveStreamingPrivateRequest::create([
            'transaction_id' => $transaction->id,
            'user_id' => auth()->id(),
            'creator_id' => $user->id,
            'minutes' => $this->request->minutes,
            'status' => LiveStreamingPrivateStatus::PENDING->value,
        ]);

        // Subtract user funds
        auth()->user()->decrement('wallet', $priceWithTaxes);

        // Add Earnings to User
        $user->increment('balance', $earnings['user']);

        // Send Notification to Creator
        Notifications::send($user->id, auth()->id(), 23, 0);

        return response()->json([
            'success' => true,
            'url' => route('live.requests_sended')
        ]);
    }

    public function show($token)
    {
        // Live Streaming Private OFF
        if (config('settings.live_streaming_private') == 'off') {
            return redirect('/');
        }

        // Search last Live Streaming
        $live = LiveStreamings::whereToken($token)
            ->whereType('private')
            ->whereStatus('0')
            ->firstOrFail();

        if (auth()->id() != $live->user_id && auth()->id() != $live->buyer_id) {
            abort(404);
        }

        // Find Creator
        $creator = User::whereId($live->user_id)
            ->whereVerifiedId('yes')
            ->firstOrFail();

        // Check subscription
        $checkSubscription = true; //set true for non-subscribers

        if ($checkSubscription && $creator->id != auth()->id() && !auth()->user()->isSuperAdmin()) {
            $onlineUser = LiveOnlineUsers::firstOrCreate([
                'user_id' => auth()->id(),
                'live_streamings_id' => $live->id
            ]);

            // Inser Comment Joined User
            LiveComments::firstOrCreate([
                'user_id' => auth()->id(),
                'live_streamings_id' => $live->id
            ]);

            if ($onlineUser->exists && is_null($live->joined_at)) {
                $live->update([
                    'joined_at' => now()
                ]);
            }
        }

        $likes = $live->likes->count();
        $likeActive = $live->likes()->whereUserId(auth()->id())->first();

        if ($creator->id == auth()->id() || $live->buyer_id == auth()->id() || auth()->user()->isSuperAdmin()) {
            $paymentRequiredToAccess = false;
        }

        $limitLiveStreaming = $live->minutes - $live->timeElapsedLivePrivate;

        return view('users.live', [
            'creator' => $creator,
            'live' => $live,
            'checkSubscription' => $checkSubscription,
            'comments' => $live->comments ?? null,
            'likes' => $likes ?? null,
            'likeActive' => $likeActive ?? null,
            'paymentRequiredToAccess' => $paymentRequiredToAccess,
            'limitLiveStreaming' => $limitLiveStreaming > 0 ? $limitLiveStreaming : 0,
            'amountTips' => $live->comments()->sum('tip_amount') ?: 0
        ]);
    }

    public function livePrivateRequestAccept(LiveStreamingPrivateRequest $live)
    {
        if ($live->status->value) {
            return response()->json([
                'success' => false,
                'errors' => ['error' => __('general.request_not_available')]
            ]);
        }

        if (!Helper::isOnline($live->user_id)) {
            return response()->json([
                'success' => false,
                'errors' => ['error' => __('general.user_online_accept_request')]
            ]);
        }

        // Create Live Stream
        $livePrivate = LiveStreamings::create([
            'type' => 'private',
            'user_id' => auth()->id(),
            'buyer_id' => $live->user_id,
            'name' => 'Live Private',
            'channel' => 'live_private_' . str_random(5) . '_' . auth()->id(),
            'minutes' => $live->minutes,
            'availability' => 'private',
            'token' => str_random(40)
        ]);

        // Send DM to user
        $this->notifyUserByMessage(
            $live->user,
            auth()->id(),
            route('live.private', ['token' => $livePrivate->token])
        );

        // Update Live to Accepted
        $live->update([
            'status' => LiveStreamingPrivateStatus::ACCEPTED->value
        ]);

        // Update Transaction to Approved
        $live->transaction->update([
            'approved' => '1'
        ]);

        return response()->json([
            'success' => true,
            'url' => route('live.private', ['token' => $livePrivate->token])
        ]);
    }

    protected function notifyUserByMessage(User $receiver, $sender, $url): void
    {
        app()->setLocale($receiver->language);

        Messages::create([
            'conversations_id' => 0,
            'from_user_id' => $sender,
            'to_user_id' => $receiver->id,
            'message' => __('general.join_private_live_stream_link', ['link' => $url]),
            'updated_at' => now()
        ]);
    }

    public function livePrivateReject(LiveStreamingPrivateRequest $live)
    {
        if ($live->status->value) {
            return response()->json([
                'success' => false,
                'errors' => ['error' => __('general.request_not_available')]
            ]);
        }

        $this->refundLiveStreamRequest($live, LiveStreamingPrivateStatus::REJECTED->value);

        return response()->json([
            'success' => true
        ]);
    }
}
