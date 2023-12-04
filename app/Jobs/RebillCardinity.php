<?php

namespace App\Jobs;

use App\Models\Plans;
use Cardinity\Client;
use App\Models\TaxRates;
use Cardinity\Exception;
use App\Models\Notifications;
use App\Models\Subscriptions;
use Cardinity\Method\Payment;
use Illuminate\Bus\Queueable;
use App\Models\PaymentGateways;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\Traits\Functions;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RebillCardinity implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Functions;

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    // Get Payment Gateway
    $payment = PaymentGateways::whereName('Cardinity')->firstOrFail();

    $subscriptions = Subscriptions::with(['creator:id,username,free_subscription,custom_fee,balance', 'subscriber:id,username,wallet'])
      ->where('ends_at', '<=', now())
      ->whereNotNull('payment_id')
      ->whereCancelled('no')
      ->get();

    if ($subscriptions) {
      foreach ($subscriptions as $subscription) {
        // Get price of Plan
        $plan = Plans::whereName($subscription->stripe_price)->first();

        // Get Taxes
        $taxes = TaxRates::whereIn('id', collect(explode('_', $subscription->taxes)))->get();
        $totalTaxes = ($plan->price * $taxes->sum('percentage') / 100);
        $planPrice = ($plan->price + $totalTaxes);

        if ($subscription->creator->free_subscription == 'no') {
          $client = Client::create([
            'consumerKey' => $payment->key,
            'consumerSecret' => $payment->key_secret,
          ]);

          // Payment ID Subscription
          $paymentId = $subscription->payment_id;

          $method = new Payment\Create([
            'amount' => $planPrice,
            'currency' => config('settings.currency_code'),
            'settle' => false,
            'description' => __('general.subscription_for') . ' @' . $subscription->creator->username,
            'order_id' => str_random(5),
            'country' => $subscription->creator->country()->country_code,
            'payment_method' => Payment\Create::RECURRING,
            'payment_instrument' => [
              'payment_id' => $paymentId
            ],
          ]);

          try {
            /** @type Cardinity\Method\Payment\Payment */
            $payment = $client->call($method);

            $status = $payment->getStatus();

            $newPaymentId = $payment->getId();

            if ($status == 'approved') {
              // Admin and user earnings calculation
              $earnings = $this->earningsAdminUser($subscription->creator->custom_fee, $plan->price, null, null);

              // Insert Transaction
              $this->transaction(
                $newPaymentId,
                $subscription->subscriber->id,
                $subscription->id,
                $subscription->creator->id,
                $plan->price,
                $earnings['user'],
                $earnings['admin'],
                'Cardinity',
                'subscription',
                $earnings['percentageApplied'],
                $subscription->taxes
              );

              // Add Earnings to Creator
              $subscription->creator->increment('balance', $earnings['user']);

              // Send Notification to User --- destination, author, type, target
              Notifications::send($subscription->creator->id, $subscription->subscriber->id, 12, $subscription->subscriber->id);

              $subscription->update([
                'ends_at' => $subscription->creator->planInterval($plan->interval)
              ]);
            }
          } catch (Exception\Declined $exception) {
            \Log::info('Cardinity Renewal Failed - ' . $exception->getErrorsAsString());

            $subscription->update([
              'cancelled' => 'yes'
            ]);
          } catch (Exception\ValidationFailed $exception) {
            \Log::info('Cardinity Renewal Failed - ' . $exception->getErrorsAsString());

            $subscription->update([
              'cancelled' => 'yes'
            ]);
          } catch (\Exception $exception) {
            \Log::info('Cardinity Renewal Failed - ' . $exception->getMessage());

            $subscription->update([
              'cancelled' => 'yes'
            ]);
          }
        }
      }
    }
  }
}
