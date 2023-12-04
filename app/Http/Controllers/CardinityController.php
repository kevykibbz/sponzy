<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\User;
use App\Models\Plans;
use Cardinity\Client;
use App\Models\Updates;
use App\Models\Deposits;
use App\Models\Messages;
use Cardinity\Exception;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\Subscriptions;
use Cardinity\Method\Payment;
use App\Models\PaymentGateways;

class CardinityController extends Controller
{
  use Traits\Functions;

  public function show(Request $request)
  {
    if (!$request->expectsJson()) {
      abort(404);
    }

    // Find the User
    $user = User::whereVerifiedId('yes')
      ->whereId($request->id)
      ->where('id', '<>', auth()->id())
      ->firstOrFail();

    // Check if Plan exists
    $plan = $user->plans()
      ->whereInterval($request->interval)
      ->whereStatus('1')
      ->firstOrFail();

    // Get Payment Gateway
    $payment = PaymentGateways::whereName($request->payment_gateway)->whereEnabled(1)->firstOrFail();

    $data = base64_encode(http_build_query([
      'id' => $request->id,
      'amount' => $plan->price,
      'subscriber' => auth()->id(),
      'plan' => $plan->name,
      'taxes' => auth()->user()->taxesPayable(),
      'type' => 'subscription'
    ]));

    $amount       = Helper::amountGross($plan->price);
    $cancel_url   = route('cardinity.cancel', ['url' => 'home']);
    $country      = auth()->user()->getCountry();
    $language     = strtoupper(config('app.locale'));
    $currency     = config('settings.currency_code');
    $description  = __('general.subscription_for_creator');
    $order_id     = 'OR-' .random_int(100, 9999);
    $return_url   = route('webhook.cardinity', ['data' => $data]);

    $project_id = $payment->project_id;
    $project_secret = $payment->project_secret;

    $attributes = [
      "amount" => $amount,
      "currency" => $currency,
      "country" => $country,
      "language" => $language,
      "order_id" => $order_id,
      "description" => $description,
      "project_id" => $project_id,
      "cancel_url" => $cancel_url,
      "return_url" => $return_url,
    ];

    ksort($attributes);

    $message = '';
    foreach ($attributes as $key => $value) {
      $message .= $key . $value;
    }

    $signature = hash_hmac('sha256', $message, $project_secret);

    return response()->json([
      'success' => true,
      'insertBody' => '<form name="checkout" action="https://checkout.cardinity.com" method="POST" style="display:none">
                <input type="hidden" name="amount" value="' . $amount . '">
                <input type="hidden" name="cancel_url"   value="' . $cancel_url . '">
                <input type="hidden" name="country" value="' . $country . '">                
                <input type="hidden" name="language" value="' . $language . '">
                <input type="hidden" name="currency" value="' . $currency . '">
                <input type="hidden" name="description" value="' . $description . '">
                <input type="hidden" name="order_id" value="' . $order_id . '">
                <input type="hidden" name="project_id" value="' . $project_id . '">
                <input type="hidden" name="return_url" value="' . $return_url . '">
                <input type="hidden" name="signature" value="' . $signature . '">
                <input type="submit">
                </form> <script type="text/javascript">document.checkout.submit();</script>',
    ]);
  }

  public function cancelPayment(Request $request)
  {
    switch ($request->url) {
      case 'home':
        $url = '/';
        break;

      case 'wallet':
        $url = 'my/wallet';
        break;

      case 'messages':
        $url = 'messages';
        break;

      default:
        $url = '/';
        break;
    }
    return redirect($url);
  }

  public function webhook(Request $request)
  {
    $payment = PaymentGateways::whereName('Cardinity')->whereEnabled(1)->firstOrFail();

    $message = '';
    ksort($_POST);
    $projectSecret = $payment->project_secret;
    $params = $_POST;

    $dataDecode = base64_decode($request->data);
    parse_str($dataDecode, $data);

    foreach ($_POST as $key => $value) {
      if ($key == 'signature') continue;
      $message .= $key . $value;
    }

    $signature = hash_hmac('sha256', $message, $projectSecret);

    if ($signature == $_POST['signature']) {
      if ($params['status'] == 'approved') {

        $paymentId = $params['id'];

        switch ($data['type']) {

            //============ Subscription ==============
          case 'subscription':
            // Get user data
            $user = User::find($data['id']);

            // Check if Plan exists
            $plan = $user->plans()
              ->whereName($data['plan'])
              ->first();

            // Subscription ID
            $subscriptionId = $paymentId;

            // Get Subscription
            $subscription = Subscriptions::where('subscription_id', $subscriptionId)->first();

            // If the subscription does not exist
            if (!$subscription) {
              // Insert DB
              $subscription          = new Subscriptions();
              $subscription->user_id = $data['subscriber'];
              $subscription->creator_id = $user->id;
              $subscription->stripe_price = $data['plan'];
              $subscription->subscription_id = $subscriptionId;
              $subscription->ends_at = $user->planInterval($plan->interval);
              $subscription->interval = $plan->interval;
              $subscription->payment_id = $paymentId;
              $subscription->save();

              // Send Notification to User --- destination, author, type, target
              Notifications::send($data['id'], $data['subscriber'], '1', $data['id']);

              $this->sendWelcomeMessageAction($user, $data['subscriber']);

              // Admin and user earnings calculation
              $earnings = $this->earningsAdminUser($user->custom_fee, $data['amount'], $payment->fee, $payment->fee_cents);

              $verifiedTxnId = Transactions::where('txn_id', $paymentId)->first();

              if (!isset($verifiedTxnId)) {
                // Insert Transaction
                $this->transaction(
                  $paymentId,
                  $data['subscriber'],
                  $subscription->id,
                  $data['id'],
                  $data['amount'],
                  $earnings['user'],
                  $earnings['admin'],
                  'Cardinity',
                  'subscription',
                  $earnings['percentageApplied'],
                  $data['taxes'] ?? null
                );

                // Add Earnings to User
                $user->increment('balance', $earnings['user']);
              } // End verifiedTxnId

              return redirect($user->username);
            }

            break;

            //============ Deposits ==============
          case 'deposit':
            // Verify Transaction ID and insert in DB
            $verifiedTxnId = Deposits::where('txn_id', $paymentId)->first();

            if (!isset($verifiedTxnId)) {
              // Insert Deposit
              $this->deposit(
                $data['user'],
                $paymentId,
                $data['amount'],
                'Cardinity',
                $data['taxes']
              );
              // Add Funds to User
              User::find($data['user'])->increment('wallet', $data['amount']);
            }

            return redirect('my/wallet');

            break;

            //============ PAY PER VIEW ==============
          case 'ppv':
            // Check if it is a Message or Post
            $media = $data['m'] ? Messages::find($data['id']) : Updates::find($data['id']);

            // Admin and user earnings calculation
            $earnings = $this->earningsAdminUser($media->user()->custom_fee, $data['amount'], $payment->fee, $payment->fee_cents);

            // Check outh POST variable and insert in DB
            $verifiedTxnId = Transactions::whereTxnId($paymentId)->first();

            if (!isset($verifiedTxnId)) {
              // Insert Transaction
              $this->transaction(
                $paymentId,
                $data['sender'],
                false,
                $media->user()->id,
                $data['amount'],
                $earnings['user'],
                $earnings['admin'],
                'Cardinity',
                'ppv',
                $earnings['percentageApplied'],
                $data['taxes']
              );

              // Add Earnings to User
              $media->user()->increment('balance', $earnings['user']);

              // User Sender
              $buyer = User::find($data['sender']);

              //============== Check if is sent by message
              if ($data['m']) {
                // $user_id, $updates_id, $messages_id
                $this->payPerViews($data['sender'], false, $data['id']);

                // Send Email Creator
                if ($media->user()->email_new_ppv == 'yes') {
                  $this->notifyEmailNewPPV($media->user(), $buyer->username, $media->message, 'message');
                }

                // Send Notification - destination, author, type, target
                Notifications::send($media->user()->id, $data['sender'], '6', $data['id']);

                return redirect(url('messages', $media->user()->id));
              } else {
                // $user_id, $updates_id, $messages_id
                $this->payPerViews($data['sender'], $data['id'], false);

                // Send Email Creator
                if ($media->user()->email_new_ppv == 'yes') {
                  $this->notifyEmailNewPPV($media->user(), $buyer->username, $media->description, 'post');
                }

                // Send Notification - destination, author, type, target
                Notifications::send($media->user()->id, $data['sender'], '7', $data['id']);

                return redirect(url($media->user()->username, 'post') . '/' . $data['id']);
              }
            } // <--- Verified Txn ID
            break;

            //======= TIP ==========
          case 'tip';

            $user   = User::find($data['id']);
            $sender = User::find($data['sender']);

            // Admin and user earnings calculation
            $earnings = $this->earningsAdminUser($user->custom_fee, $data['amount'], $payment->fee, $payment->fee_cents);

            // Check outh POST variable and insert in DB
            $verifiedTxnId = Transactions::where('txn_id', $paymentId)->first();

            if (!isset($verifiedTxnId)) {
              // Insert Transaction
              $this->transaction(
                $paymentId,
                $data['sender'],
                false,
                $data['id'],
                $data['amount'],
                $earnings['user'],
                $earnings['admin'],
                'Cardinity',
                'tip',
                $earnings['percentageApplied'],
                $data['taxes']
              );

              // Add Earnings to User
              $user->increment('balance', $earnings['user']);

              // Send Email Creator
              if ($user->email_new_tip == 'yes') {
                $this->notifyEmailNewTip($user, $sender->username, $data['amount']);
              }

              // Send Notification to User --- destination, author, type, target
              Notifications::send($data['id'], $data['sender'], '5', $data['id']);

              //============== Check if the tip is sent by message
              if ($data['m']) {
                $this->isMessageTip($data['id'], $data['sender'], $data['amount']);

                return redirect(url('messages', $data['id']));
              } else {
                return redirect($user->username);
              }
            } // <--- Verified Txn ID

            break;
        }
      } else {
        session()->put('error_payment', 'An error has occurred with the payment.');
        return redirect('/');
      }
    } else {
      session()->put('error_payment', 'An error has occurred with the payment.');
      return redirect('/');
    }
  }

  public function cancelSubscription($id)
  {
    $subscription = auth()->user()->userSubscriptions()->whereId($id)->firstOrFail();
    $creator = Plans::whereName($subscription->stripe_price)->first();

    // Delete Subscription
    $subscription->cancelled = 'yes';
    $subscription->save();

    session()->put('subscription_cancel', __('general.subscription_cancel'));

    return redirect($creator->user()->username);
  }
}
