<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Notifications;
use App\Models\Plans;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Http\Controllers\WebhookController;
use Laravel\Cashier\Subscription;
use App\Models\PaymentGateways;
use App\Models\Transactions;
use App\Models\Deposits;
use Stripe\PaymentIntent as StripePaymentIntent;
use App\Models\User;

class StripeWebHookController extends WebhookController
{
  use Traits\Functions;

    /**
     *
     * customer.subscription.deleted
     *
     * @param array $payload
     * @return Response|\Symfony\Component\HttpFoundation\Response
     */
    public function handleCustomerSubscriptionDeleted(array $payload) {
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);
        if ($user) {
            $user->subscriptions->filter(function ($subscription) use ($payload) {
                return $subscription->stripe_id === $payload['data']['object']['id'];
            })->each(function ($subscription) {
                $subscription->markAsCanceled();
            });
        }
        return new Response('Webhook Handled', 200);
    }

    /**
     *
     * WEBHOOK Insert the information of each payment in the Payments table when successfully generating an invoice in Stripe
     *
     * @param array $payload
     * @return Response|\Symfony\Component\HttpFoundation\Response
     */
    public function handleInvoicePaymentSucceeded($payload)
    {
        try {
            $settings = AdminSettings::first();
            $data     = $payload['data'];
            $object   = $data['object'];
            $customer = $object['customer'];
            $amount   = $settings->currency_code == 'JPY' ? $object['subtotal'] : ($object['subtotal'] / 100);
            $user     = $this->getUserByStripeId($customer);
            $interval = $object['lines']['data'][0]['metadata']['interval'] ?? 'monthly';
            $taxes    = $object['lines']['data'][0]['metadata']['taxes'] ?? null;

            if ($user) {
                $subscription = Subscription::whereStripeId($object['subscription'])->first();
                if ($subscription) {

                    // User Plan
                    $user = Plans::whereName($subscription->stripe_price)->first();

                    $subscription->stripe_status = "active";
                    $subscription->creator_id = $user->user()->id;
                    $subscription->interval = $interval;
                    $subscription->save();

                    
                    // Get Payment Gateway
                    $payment = PaymentGateways::whereName('Stripe')->firstOrFail();
                    // Admin and user earnings calculation
                    $earnings = $this->earningsAdminUser($user->user()->custom_fee, $amount, $payment->fee, $payment->fee_cents);

                    // Insert Transaction
                    $this->transaction(
                          $object['id'],
                          $subscription->user_id,
                          $subscription->id,
                          $user->user()->id,
                          $amount,
                          $earnings['user'],
                          $earnings['admin'],
                          'Stripe',
                          'subscription',
                          $earnings['percentageApplied'],
                          $taxes ?? null
                        );

                    // Add Earnings to User
                    $user->user()->increment('balance', $earnings['user']);

                    // Send Notification to user
                    if ($object['billing_reason'] == 'subscription_cycle') {
                      // Notify to user - destination, author, type, target
                  		Notifications::send($user->user()->id, $subscription->user_id, 12, $subscription->user_id);
                    }
                }
                return new Response('Webhook Handled: {handleInvoicePaymentSucceeded}', 200);
            }
            return new Response('Webhook Handled but user not found: {handleInvoicePaymentSucceeded}', 200);
        } catch (\Exception $exception) {
            Log::debug($exception->getMessage());
            return new Response('Webhook Unhandled: {handleInvoicePaymentSucceeded}', $exception->getCode());
        }
    }

    /**
     *
     * checkout.session.completed
     *
     * @param array $payload
     * @return Response|\Symfony\Component\HttpFoundation\Response
     */
    public function handleCheckoutSessionCompleted($payload)
    {
        try {
            $settings = AdminSettings::first();
            $data     = $payload['data'];
            $object   = $data['object'];
            $user     = $object['metadata']['user'] ?? null;
            $amount   = $object['metadata']['amount'] ?? null;
            $taxes    = $object['metadata']['taxes'] ?? null;
            $type     = $object['metadata']['type'] ?? null;

            if (! isset($type)) {
              return new Response('Webhook Handled with error: type transaction not defined', 500);
            }

            // Add funds (Deposit)
            if (isset($type) && $type == 'deposit') {
              if ($object['payment_status'] == 'paid' && isset($user)) {
                $amount_total = $settings->currency_code == 'JPY' ? $object['amount_total'] : $object['amount_total'] / 100;
                if (isset($amount) && $amount_total >= $amount) {
                  // Check transaction
      						$verifiedTxnId = Deposits::where('txn_id', $object['payment_intent'])->first();
                  if (! $verifiedTxnId) {
                    // Insert Deposit
                    $this->deposit($user, $object['payment_intent'], $amount, 'Stripe', $taxes);

                    // Add Funds to User
                    User::find($user)->increment('wallet', $amount);
                  }
                }
              }
            }

            return new Response('Webhook Handled: {handleInvoicePaymentSucceeded}', 200);

        } catch (\Exception $exception) {
            Log::debug($exception->getMessage());
            return new Response('Webhook Unhandled: {handleInvoicePaymentSucceeded}', $exception->getCode());
        }
    }

    /**
     *
     * charge.refunded
     *
     * @param array $payload
     * @return Response|\Symfony\Component\HttpFoundation\Response
     */
    public function handleChargeRefunded($payload)
    {
        try {
          $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
          $stripe->subscriptions->cancel($payload['data']['object']['subscription'], []);

          return new Response('Webhook Handled: {handleChargeRefunded}', 200);

        } catch (\Exception $exception) {
            Log::debug("Exception Webhook {handleChargeRefunded}: " . $exception->getMessage() . ", Line: " . $exception->getLine() . ', File: ' . $exception->getFile());
            return new Response('Webhook Handled with error: {handleChargeRefunded}', 400);
        }
    }

    /**
     * WEBHOOK Manage the SCA by notifying the user by email
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleInvoicePaymentActionRequired(array $payload)
    {
        $subscription = Subscription::whereStripeId($payload['data']['object']['subscription'])->first();
        if ($subscription) {
            $subscription->stripe_status = "incomplete";
            $subscription->last_payment = $payload['data']['object']['payment_intent'];
            $subscription->save();
        }

        if (is_null($notification = config('cashier.payment_notification'))) {
            return $this->successMethod();
        }

        if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            if (in_array(Notifiable::class, class_uses_recursive($user))) {
              $payment = new \Laravel\Cashier\Payment(Cashier::stripe()->paymentIntents->retrieve(
                  $payload['data']['object']['payment_intent']
              ));

                $user->notify(new $notification($payment));
            }
        }
        return $this->successMethod();
    }
}
