<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\User;
use App\Models\Updates;
use App\Models\Deposits;
use App\Models\Messages;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Notifications;
use App\Models\Subscriptions;
use App\Models\PaymentGateways;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client as HttpClient;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
  use Traits\Functions;

  public function __construct(AdminSettings $settings, Request $request)
  {
    $this->settings = $settings::first();
    $this->request = $request;
  }

  /**
   * Show/Send form PayPal
   *
   * @return response
   */
  public function show()
  {

    if (!$this->request->expectsJson()) {
      abort(404);
    }

    // Find the User
    $user = User::whereVerifiedId('yes')
      ->whereId($this->request->id)
      ->where('id', '<>', auth()->id())
      ->firstOrFail();

    // Check if Plan exists
    $plan = $user->plans()
      ->whereInterval($this->request->interval)
      ->whereStatus('1')
      ->firstOrFail();

    // Get Payment Gateway
    $payment = PaymentGateways::whereName($this->request->payment_gateway)->whereEnabled(1)->firstOrFail();

    $urlSuccess = route('subscription.success', ['user' => $user->username, 'delay' => 'paypal']);
    $urlCancel = url('buy/subscription/cancel', $user->username);

    switch ($plan->interval) {
      case 'weekly':
        $interval = 'DAY';
        $interval_count = 7;
        break;

      case 'monthly':
        $interval = 'MONTH';
        $interval_count = 1;
        break;

      case 'quarterly':
        $interval = 'MONTH';
        $interval_count = 3;
        break;

      case 'biannually':
        $interval = 'MONTH';
        $interval_count = 6;
        break;

      case 'yearly':
        $interval = 'YEAR';
        $interval_count = 1;
        break;
    }

    // Init PayPal
    $provider = new PayPalClient();
    $token = $provider->getAccessToken();
    $provider->setAccessToken($token);

    $product_id = 'product_' . $plan->name;

    try {
      // Get Product Details
      $product = $provider->showProductDetails($product_id);

      $getProductId = $product['id'];
    } catch (\Exception $e) {

      // Create Product
      $requestId = 'create-product-' . time();

      $product = $provider->createProduct([
        'id' => $product_id,
        'name' => '@' . $user->username . ' - ' . $plan->name,
        'description' => 'Product of @' . $user->username,
        'type' => 'DIGITAL',
        'category' => 'DIGITAL_MEDIA_BOOKS_MOVIES_MUSIC',
      ], $requestId);
    }

    try {
      // Create Plan
      $planPayPal = 'plan_' . $plan->name;

      $requestIdPlan = 'create-plan-' . time();

      $paypalPlan = $provider->createPlan([
        'product_id' => $product['id'],
        'name' => $planPayPal,
        'status' => 'ACTIVE',
        'billing_cycles' => [
          [
            'frequency' => [
              'interval_unit' => $interval,
              'interval_count' => $interval_count,
            ],
            'tenure_type' => 'REGULAR',
            'sequence' => 1,
            'total_cycles' => 0,
            'pricing_scheme' => [
              'fixed_price' => [
                'value' => Helper::amountGross($plan->price),
                'currency_code' => $this->settings->currency_code,
              ],
            ]
          ]
        ],
        'payment_preferences' => [
          'auto_bill_outstanding' => true,
          'payment_failure_threshold' => 0,
        ],
      ], $requestIdPlan);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => $e->getMessage()]
      ]);
    }

    try {
      // Create Subscription
      $subscription = $provider->createSubscription([
        'plan_id' => $paypalPlan['id'],
        'application_context' => [
          'brand_name' => $this->settings->title,
          'locale' => 'en-US',
          'shipping_preference' => 'SET_PROVIDED_ADDRESS',
          'user_action' => 'SUBSCRIBE_NOW',
          'payment_method' => [
            'payer_selected' => 'PAYPAL',
            'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED',
          ],
          'return_url' => $urlSuccess,
          'cancel_url' => $urlCancel
        ],
        'custom_id' => http_build_query([
          'id' => $this->request->id,
          'amount' => $plan->price,
          'subscriber' => auth()->id(),
          'plan' => $plan->name,
          'taxes' => auth()->user()->taxesPayable(),
        ])
      ]);

      return response()->json([
        'success' => true,
        'url' => $subscription['links'][0]['href']
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => $e->getMessage()]
      ]);
    }
  } // End methd show

  public function cancelSubscription($id)
  {
    $subscription = auth()->user()->userSubscriptions()->whereId($id)->firstOrFail();

    // Init PayPal
    $provider = new PayPalClient();
    $token = $provider->getAccessToken();
    $provider->setAccessToken($token);

    try {
      $provider->cancelSubscription($subscription->subscription_id, 'Not satisfied with the service');

      $subscription->cancelled = 'yes';
      $subscription->save();
    } catch (\Exception $e) {
    }

    // Wait for the Webhook capture
    sleep(3);

    return back()->withSubscriptionCancel(__('general.subscription_cancel'));
  } //<----- End Method cancelSubscription

  public function webhook()
  {
    // Get Payment Data
    $payment = PaymentGateways::whereName('PayPal')->first();

    // Init PayPal
    $provider = new PayPalClient();
    $token = $provider->getAccessToken();
    $provider->setAccessToken($token);

    $httpClient = new HttpClient();

    $baseUrl = 'https://' . ($payment->sandbox == 'true' ? 'api-m.sandbox' : 'api-m') . '.paypal.com/';

    // PayPal Webhook ID
    $webhookId = $payment->webhook_secret;

    // Get the payload's content
    $payload = $this->request->all();

    // Get payload's content verify Webhook
    $payloadWebhook = json_decode($this->request->getContent());

    $getPayload = get_object_vars($payloadWebhook);
    info('PayPal Event Webhook -> ' . $payload['event_type']);

    // Verify the webhook signature
    try {
      $verifyWebHookSignatureRequest = $httpClient->request(
        'POST',
        $baseUrl . 'v1/notifications/verify-webhook-signature',
        [
          'headers' => [
            'Authorization' => 'Bearer ' . $token['access_token'],
            'Content-Type' => 'application/json'
          ],
          'body' => json_encode([
            'auth_algo' => $this->request->header('PAYPAL-AUTH-ALGO'),
            'cert_url' => $this->request->header('PAYPAL-CERT-URL'),
            'transmission_id' => $this->request->header('PAYPAL-TRANSMISSION-ID'),
            'transmission_sig' => $this->request->header('PAYPAL-TRANSMISSION-SIG'),
            'transmission_time' => $this->request->header('PAYPAL-TRANSMISSION-TIME'),
            'webhook_id' => $webhookId,
            'webhook_event' => $payloadWebhook
          ])
        ]
      );

      $verifyWebHookSignature = json_decode($verifyWebHookSignatureRequest->getBody()->getContents());
    } catch (\Exception $e) {
      Log::debug($e);

      return response()->json([
        'status' => 400
      ], 400);
    }

    // Check if the webhook's signature status is successful
    if ($verifyWebHookSignature->verification_status != 'SUCCESS') {
      info('PayPal signature validation failed!');

      return response()->json([
        'status' => 400
      ], 400);
    }

    // Parse the custom data parameters
    parse_str($payload['resource']['custom_id'] ?? ($payload['resource']['custom'] ?? null), $data);

    if ($data) {
      if ($payload['event_type'] == 'PAYMENT.SALE.COMPLETED') {
        if (array_key_exists('billing_agreement_id', $payload['resource']) && !empty($payload['resource']['billing_agreement_id'])) {
          // Get user data
          $user = User::find($data['id']);

          // Check if Plan exists
          $plan = $user->plans()
            ->whereName($data['plan'])
            ->first();

          // Subscription ID
          $subscriptionId = $payload['resource']['billing_agreement_id'];

          // Get Subscription
          $subscription = Subscriptions::where('subscription_id', $subscriptionId)->first();

          // Update date if subscription exists
          if ($subscription && $subscription->cancelled == 'no') {
            $subscription->ends_at = $user->planInterval($plan->interval);
            $subscription->save();

            // Send Notification to User
            Notifications::firstOrCreate([
              'destination' => $data['id'],
              'author' => $data['subscriber'],
              'type' => 12,
              'created_at' => today()->format('Y-m-d'),
              'target' => $data['subscriber']
            ]);
            info('PayPal: Subscription updated! ID: ' . $subscriptionId);
          } else {
            info('PayPal: Subscription not exists ID: ' . $subscriptionId);
          }

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
            $subscription->save();

            // Send Notification to User --- destination, author, type, target
            Notifications::send($data['id'], $data['subscriber'], '1', $data['id']);

            $this->sendWelcomeMessageAction($user, $data['subscriber']);

            info('PayPal: Subscription created! ID: ' . $subscriptionId);
          }

          // Admin and user earnings calculation
          $earnings = $this->earningsAdminUser($user->custom_fee, $data['amount'], $payment->fee, $payment->fee_cents);

          $txnId = $payload['resource']['id'];

          $verifiedTxnId = Transactions::where('txn_id', $txnId)->first();

          if (!isset($verifiedTxnId)) {
            // Insert Transaction
            $this->transaction(
              $txnId,
              $data['subscriber'],
              $subscription->id,
              $data['id'],
              $data['amount'],
              $earnings['user'],
              $earnings['admin'],
              'PayPal',
              'subscription',
              $earnings['percentageApplied'],
              $data['taxes'] ?? null
            );

            // Add Earnings to User
            $user->increment('balance', $earnings['user']);

            info('PayPal: Transaction successfully inserted and earnings added to creator');
          } // End verifiedTxnId
        } else {
          info('PayPal billing_agreement_id NULL');
        }
      } else {
        info('PAYMENT.SALE.COMPLETED Not Completed');
      } // Payment Sale Completed
    } else {
      info('PayPal $data custom id NULL');
    } // $data custom id

    if (
      $payload['event_type'] == 'BILLING.SUBSCRIPTION.CANCELLED'
      || $payload['event_type'] == 'BILLING.SUBSCRIPTION.EXPIRED'
      || $payload['event_type'] == 'BILLING.SUBSCRIPTION.SUSPENDED'
    ) {
      $subscription = Subscriptions::where('subscription_id', $payload['resource']['id'])->first();

      if ($subscription) {
        $subscription->cancelled = 'yes';
        $subscription->save();
      }
    }

    if ($payload['event_type'] == 'PAYMENT.SALE.REFUNDED') {
      // Get Custom ID
      if ($data) {
        if (array_key_exists('sale_id', $payload['resource']) && !empty($payload['resource']['sale_id'])) {
          $transaction = Transactions::whereTxnId($payload['resource']['sale_id'])->wherePaymentGateway('PayPal')->first();

          if ($transaction) {
            if ($transaction->approved) {
              $this->deductReferredBalanceByRefund($transaction);
            }

            $transaction->approved = 2;
            $transaction->save();

            // If Subscription
            if ($transaction->subscriptions_id) {
              $transaction->subscription()->delete();
            }

            // Deduct balance to creator
            try {
              $transaction->subscribed()->decrement('balance', $transaction->earning_net_user);
            } catch (\Exception $e) {
            }
          }
        }
      }
    }
  } // End method webhook

  public function verifyTransaction()
  {
    // Get Payment Data
    $payment = PaymentGateways::whereName('PayPal')->first();

    // Init PayPal
    $provider = new PayPalClient();
    $token = $provider->getAccessToken();
    $provider->setAccessToken($token);

    try {
      // Get PaymentOrder using our transaction ID
      $order = $provider->capturePaymentOrder($this->request->token);
      $txnId = $order['purchase_units'][0]['payments']['captures'][0]['id'];

      // Parse the custom data parameters
      parse_str($order['purchase_units'][0]['payments']['captures'][0]['custom_id'] ?? null, $data);

      if ($order['status'] && $order['status'] === "COMPLETED") {
        if ($data) {
          switch ($data['type']) {

              //============ Start Deposit ==============
            case 'deposit':

              // Check outh POST variable and insert in DB
              $verifiedTxnId = Deposits::where('txn_id', $txnId)->first();

              if (!isset($verifiedTxnId)) {
                // Insert Deposit
                $this->deposit(
                  $data['id'],
                  $txnId,
                  $data['amount'],
                  'PayPal',
                  $data['taxes'] ?? null
                );

                // Add Funds to User
                User::find($data['id'])->increment('wallet', $data['amount']);
              } // <--- Verified Txn ID

              return redirect('my/wallet');

              break;

              //============ Start PPV ==============
            case 'ppv':

              // Check if it is a Message or Post
              $media = $data['m'] ? Messages::find($data['id']) : Updates::find($data['id']);

              // Admin and user earnings calculation
              $earnings = $this->earningsAdminUser($media->user()->custom_fee, $data['amount'], $payment->fee, $payment->fee_cents);

              // Check outh POST variable and insert in DB
              $verifiedTxnId = Transactions::whereTxnId($txnId)->first();

              if (!isset($verifiedTxnId)) {
                // Insert Transaction
                $this->transaction(
                  $txnId,
                  $data['sender'],
                  false,
                  $media->user()->id,
                  $data['amount'],
                  $earnings['user'],
                  $earnings['admin'],
                  'PayPal',
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

              //============ Start Tips ==============
            case 'tip':

              $user   = User::find($data['id']);
              $sender = User::find($data['sender']);

              // Admin and user earnings calculation
              $earnings = $this->earningsAdminUser($user->custom_fee, $data['amount'], $payment->fee, $payment->fee_cents);

              // Check outh POST variable and insert in DB
              $verifiedTxnId = Transactions::where('txn_id', $txnId)->first();

              if (!isset($verifiedTxnId)) {
                // Insert Transaction
                $this->transaction(
                  $txnId,
                  $data['sender'],
                  false,
                  $data['id'],
                  $data['amount'],
                  $earnings['user'],
                  $earnings['admin'],
                  'PayPal',
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

                  return redirect(url('paypal/msg/tip/redirect', $data['id']));
                } else {
                  return redirect(url('paypal/tip/success', $user->username));
                }
              } // <--- Verified Txn ID
              break;
          } // Switch case
        } // data

        return redirect('/');
      }
    } catch (\Exception $e) {
      return redirect('/');
    }
  } // End method verifyTransaction

}
