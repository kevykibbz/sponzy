<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\TaxRates;
use App\Models\Purchases;
use App\Models\Withdrawals;
use Illuminate\Bus\Queueable;
use App\Models\ReferralTransactions;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SalesRefund implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $purchases = Purchases::where('expired_at', '<', now())->get();

        foreach ($purchases as $purchase) {
            $amount = $purchase->transactions()->amount;

            $taxes = TaxRates::whereIn('id', collect(explode('_', $purchase->transactions()->taxes)))->get();
            $totalTaxes = ($amount * $taxes->sum('percentage') / 100);

            // Total paid by buyer
            $amountRefund = number_format($amount + $purchase->transactions()->transaction_fee + $totalTaxes, 2, '.', '');

            // Get amount referral (if exist)
            $referralTransaction = ReferralTransactions::whereTransactionsId($purchase->transactions()->id)->first();

            if ($purchase->transactions()->referred_commission && $referralTransaction) {
                User::find($referralTransaction->referred_by)->decrement('balance', $referralTransaction->earnings);

                // Delete $referralTransaction
                $referralTransaction->delete();
            }

            // Add funds to wallet buyer
            $purchase->user()->increment('wallet', $amountRefund);

            // User Balnce Current
            $userBalance = $purchase->products()->user()->balance;

            // If the creator has withdrawn their entire balance remove from withdrawal
            $withdrawalPending = Withdrawals::whereUserId($purchase->products()->user()->id)->whereStatus('pending')->first();

            // Remove creator funds
            if ($userBalance <> 0.00) {
                $purchase->products()->user()->decrement('balance', $purchase->transactions()->earning_net_user);
            } elseif ($withdrawalPending) {
                $withdrawalPending->decrement('amount', $amountRefund);
            } elseif ($userBalance == 0.00 && !$withdrawalPending) {
                $purchase->products()->user()->decrement('balance', $purchase->transactions()->earning_net_user);
            }

            // Delete transaction
            $purchase->transactions()->delete();

            // Delete purchase
            $purchase->delete();
        }
    }
}
