<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\User;
use App\Models\Plans;
use Illuminate\Http\Request;
use App\Models\Subscriptions;
use App\Models\PaymentGateways;
use App\Models\Notifications;
use App\Models\SubscriptionDeleted;
use Illuminate\Support\Facades\Validator;

class SubscriptionsController extends Controller
{
  use Traits\Functions;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  /**
   * Buy subscription
   *
   * @return Response
   */
  public function buy()
  {
    // Find the User
    $user = User::whereVerifiedId('yes')
      ->whereId($this->request->id)
      ->where('id', '<>', auth()->id())
      ->firstOrFail();

    // Check if Plan exists
    $plan = $user->plans()
      ->whereInterval($this->request->interval)
      ->firstOrFail();

    if (!$plan->status) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => __('general.subscription_not_available')],
      ]);
    }

    // Check if subscription exists
    $checkSubscription = auth()->user()->mySubscriptions()
      ->whereStripePrice($plan->name)
      ->where('ends_at', '>=', now())->first();

    if ($checkSubscription) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => __('general.subscription_exists')],
      ]);
    }

    //<---- Validation
    $validator = Validator::make($this->request->all(), [
      'payment_gateway' => 'required',
      'agree_terms' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    }

    // Wallet
    if ($this->request->payment_gateway == 'wallet') {
      return $this->sendWallet();
    }

    // Get name of Payment Gateway
    $payment = PaymentGateways::whereName($this->request->payment_gateway)->whereEnabled(1)->firstOrFail();

    // Send data to the payment processor
    return redirect()->route(str_slug($payment->name), $this->request->except(['_token']));
  }

  /**
   * Free subscription
   *
   */
  public function subscriptionFree()
  {
    // Find user
    $creator = User::whereId($this->request->id)
      ->whereFreeSubscription('yes')
      ->whereVerifiedId('yes')
      ->firstOrFail();

    // Verify plan no is empty
    if (!$creator->plan) {
      $creator->plan = 'user_' . $creator->id;
      $creator->save();
    }

    // Check if not plans
    if ($creator->plans()->count() == 0) {

      Plans::updateOrCreate(
        [
          'user_id' => $creator->id,
          'name' => 'user_' . $creator->id
        ],
        [
          'interval' => 'monthly',
          'status' => '1'
        ]
      );
    }

    // Verify subscription exists
    $subscription = Subscriptions::whereUserId(auth()->id())
      ->whereStripePrice($creator->plan)
      ->whereFree('yes')
      ->first();

    if ($subscription) {
      return response()->json([
        'success' => false,
        'error' => __('general.subscription_exists'),
      ]);
    }

    // Insert DB
    $sql = new Subscriptions();
    $sql->user_id = auth()->id();
    $sql->creator_id  = $creator->id;
    $sql->stripe_price = $creator->plan;
    $sql->free = 'yes';
    $sql->save();

    // Send Email to User and Notification
    Subscriptions::sendEmailAndNotify(auth()->user()->name, $creator->id);

    $this->sendWelcomeMessageAction($creator, auth()->id());

    return response()->json([
      'success' => true,
    ]);
  }

  public function cancelFreeSubscription($id)
  {
    $checkSubscription = auth()->user()->userSubscriptions()->whereId($id)->firstOrFail();
    $creator = User::wherePlan($checkSubscription->stripe_price)->first();

    $this->subscriptionDeleted($creator->id, auth()->id());

    $checkSubscription->delete();

    session()->put('subscription_cancel', __('general.subscription_cancel'));

    return redirect($creator->username);
  }

  public function cancelWalletSubscription($id)
  {
    $subscription = auth()->user()->userSubscriptions()->whereId($id)->firstOrFail();
    $creator = Plans::whereName($subscription->stripe_price)->first();

    // Delete Subscription
    $subscription->cancelled = 'yes';
    $subscription->save();

    session()->put('subscription_cancel', __('general.subscription_cancel'));

    return redirect($creator->user()->username);
  }

  /**
   *  Subscription via Wallet
   *
   * @return Response
   */
  protected function sendWallet()
  {
    // Find user
    $creator = User::whereId($this->request->id)
      ->whereVerifiedId('yes')
      ->firstOrFail();

    // Check if Plan exists
    $plan = $creator->plans()
      ->whereInterval($this->request->interval)
      ->firstOrFail();

    $amount = $plan->price;

    // Verify plan no is empty
    if (!$creator->plan) {
      $creator->plan = 'user_' . $creator->id;
      $creator->save();
    }

    if (auth()->user()->wallet < Helper::amountGross($amount)) {
      return response()->json([
        "success" => false,
        "errors" => ['error' => __('general.not_enough_funds')]
      ]);
    }

    // Insert DB
    $subscription              = new Subscriptions();
    $subscription->user_id     = auth()->id();
    $subscription->creator_id  = $creator->id;
    $subscription->stripe_price = $plan->name;
    $subscription->ends_at     = $creator->planInterval($plan->interval);
    $subscription->rebill_wallet = 'on';
    $subscription->interval = $plan->interval;
    $subscription->taxes = auth()->user()->taxesPayable();
    $subscription->save();

    // Admin and user earnings calculation
    $earnings = $this->earningsAdminUser($creator->custom_fee, $amount, null, null);

    // Insert Transaction
    $this->transaction(
      'subw_' . str_random(25),
      auth()->id(),
      $subscription->id,
      $creator->id,
      $amount,
      $earnings['user'],
      $earnings['admin'],
      'Wallet',
      'subscription',
      $earnings['percentageApplied'],
      auth()->user()->taxesPayable()
    );

    // Subtract user funds
    auth()->user()->decrement('wallet', Helper::amountGross($amount));

    // Add Earnings to User
    $creator->increment('balance', $earnings['user']);

    // Send Email to User and Notification
    Subscriptions::sendEmailAndNotify(auth()->user()->name, $creator->id);

    $this->sendWelcomeMessageAction($creator, auth()->id());

    return response()->json([
      'success' => true,
      'url' => url('buy/subscription/success', $creator->username)
    ]);
  }

  protected function subscriptionDeleted($creatorId, $userId)
  {
    SubscriptionDeleted::firstOrCreate([
      'creator_id' => $creatorId,
      'user_id' => $userId
    ]);

    Notifications::whereDestination($creatorId)
      ->whereAuthor($userId)
      ->whereType(1)
      ->delete();
  }
}
