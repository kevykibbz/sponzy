<?php

namespace App\Http\Controllers\Traits;

use App\Actions\SendWelcomeMessageAction;
use App\Enums\LiveStreamingPrivateStatus;
use DB;
use App\Helper;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Plans;
use App\Models\Deposits;
use App\Models\Messages;
use App\Models\TaxRates;
use App\Models\Countries;
use App\Models\Referrals;
use App\Models\PayPerViews;
use App\Models\Transactions;
use App\Models\AdminSettings;
use App\Models\LiveStreamingPrivateRequest;
use App\Models\LoginSessions;
use App\Models\Notifications;
use App\Models\Subscriptions;
use App\Models\TwoFactorCodes;
use App\Models\PaymentGateways;
use App\Notifications\TipReceived;
use App\Models\ReferralTransactions;
use App\Models\Withdrawals;
use App\Notifications\SendTwoFactorCode;
use App\Notifications\PayPerViewReceived;
use Phattarachai\LaravelMobileDetect\Agent;

trait Functions
{
	// Users on Card Explore
	public function userExplore($type = false)
	{
		if ($type) {
			return User::selectFieldsUserExplorer()->where('status', 'active')
				->where('id', '<>', auth()->id() ?? 0)
				->whereVerifiedId('yes')
				->where('id', '<>', config('settings.hide_admin_profile') == 'on' ? 1 : 0)
				->whereFreeSubscription('yes')
				->whereHideProfile('no')
				->where('blocked_countries', 'NOT LIKE', '%' . Helper::userCountry() . '%')
				->with([
					'media' => fn ($q) =>
					$q->select('type')
				])
				->orderByRaw('rand( ' . time() . ' * ' . time() . ')')
				->take(3)
				->get();
		}

		return User::selectFieldsUserExplorer()->where('status', 'active')
			->where('id', '<>', auth()->id() ?? 0)
			->whereVerifiedId('yes')
			->where('id', '<>', config('settings.hide_admin_profile') == 'on' ? 1 : 0)
			->whereHas('plans', function ($query) {
				$query->where('status', '1');
			})
			->whereFreeSubscription('no')
			->whereHideProfile('no')
			->where('blocked_countries', 'NOT LIKE', '%' . Helper::userCountry() . '%')
			->orWhere('status', 'active')
			->where('id', '<>', auth()->id() ?? 0)
			->whereVerifiedId('yes')
			->where('id', '<>', config('settings.hide_admin_profile') == 'on' ? 1 : 0)
			->whereFreeSubscription('yes')
			->whereHideProfile('no')
			->where('blocked_countries', 'NOT LIKE', '%' . Helper::userCountry() . '%')
			->with([
				'media' => fn ($q) =>
				$q->select('type')
			])
			->orderByRaw('rand( ' . time() . ' * ' . time() . ')')
			->take(3)
			->get();
	}

	// CCBill Form
	public function ccbillForm($price, $userAuth, $type, $creator = null, $isMessage = null)
	{
		// Get Payment Gateway
		$payment = PaymentGateways::whereName('CCBill')->firstOrFail();

		if ($creator) {
			$user  = User::whereVerifiedId('yes')->whereId($creator)->firstOrFail();
		}

		$currencyCodes = [
			'AUD' => 036,
			'CAD' => 124,
			'JPY' => 392,
			'GBP' => 826,
			'USD' => 840,
			'EUR' => 978
		];

		if ($type == 'wallet') {

			$taxes = config('settings.tax_on_wallet') ? ($this->request->amount * auth()->user()->isTaxable()->sum('percentage') / 100) : 0;

			if (config('settings.currency_code') == 'JPY') {
				$formPrice = round($price + ($price * $payment->fee / 100) + $payment->fee_cents + $taxes, 2, '.', '');
			} else {
				$formPrice = number_format($price + ($price * $payment->fee / 100) + $payment->fee_cents + $taxes, 2, '.', '');
			}
		} else {
			$charge = Helper::amountGross($price);
			$formPrice = number_format($charge, 2);
		}

		$formInitialPeriod = 2;
		$currencyCode = array_key_exists(config('settings.currency_code'), $currencyCodes) ? $currencyCodes[config('settings.currency_code')] : 840;

		// Hash
		$hash = md5($formPrice . $formInitialPeriod . $currencyCode . $payment->ccbill_salt);

		$input['clientAccnum']  = $payment->ccbill_accnum;
		$input['clientSubacc']  = $payment->ccbill_subacc;
		$input['currencyCode']  = $currencyCode;
		$input['formDigest']    = $hash;
		$input['initialPrice']  = $formPrice;
		$input['initialPeriod'] = $formInitialPeriod;
		$input['type']          = $type;
		$input['isMessage']     = $isMessage;
		$input['creator']       = $user->id ?? null;
		$input['user']          = $userAuth;
		$input['amountFixed']   = $price;
		$input['taxes']         = auth()->user()->taxesPayable();

		// Base url
		$baseURL = 'https://api.ccbill.com/wap-frontflex/flexforms/' . $payment->ccbill_flexid;

		// Build redirect url
		$inputs = http_build_query($input);
		$redirectUrl = $baseURL . '?' . $inputs;

		return response()->json([
			'success' => true,
			'url' => $redirectUrl,
		]);
	} // End Method

	// Admin and user earnings calculation
	public function earningsAdminUser($userCustomFee, $amount, $paymentFee, $paymentFeeCents)
	{
		$settings = AdminSettings::first();

		$feeCommission = $userCustomFee == 0 ? $settings->fee_commission : $userCustomFee;

		if (isset($paymentFee)) {
			$processorFees = $amount - ($amount * $paymentFee / 100) - $paymentFeeCents;

			// Earnings Net User
			$earningNetUser = $processorFees - ($processorFees * $feeCommission / 100);
			// Earnings Net Admin
			$earningNetAdmin = $processorFees - $earningNetUser;
		} else {
			// Earnings Net User
			$earningNetUser = $amount - ($amount * $feeCommission / 100);

			// Earnings Net Admin
			$earningNetAdmin = ($amount - $earningNetUser);
		}

		if (isset($paymentFee)) {
			$paymentFees =  $paymentFeeCents == 0.00 ? $paymentFee . '% + ' : $paymentFee . '%' . ' + ' . $paymentFeeCents . ' + ';
		} else {
			$paymentFees = null;
		}

		// Percentage applied
		$percentageApplied = $paymentFees . $feeCommission . '%';


		if ($settings->currency_code == 'JPY') {
			$userEarning = floor($earningNetUser);
			$adminEarning = floor($earningNetAdmin);
		} else {
			$userEarning = number_format($earningNetUser, 2, '.', '');
			$adminEarning = number_format($earningNetAdmin, 2, '.', '');
		}

		return [
			'user' => $userEarning,
			'admin' => $adminEarning,
			'percentageApplied' => $percentageApplied
		];
	} // End Method

	// Insert Transaction
	public function transaction(
		$txnId,
		$userId,
		$subscriptionsId,
		$subscribed,
		$amount,
		$userEarning,
		$adminEarning,
		$paymentGateway,
		$type,
		$percentageApplied,
		$taxes,
		$approved = '1'
	) {
		// Referred
		$referred = $approved == '1' ? $this->referred($userId, $adminEarning, $type) : null;

		// Stripe Connect
		if (
			$paymentGateway == 'Stripe' && $type == 'subscription'
			|| $paymentGateway == 'Stripe' && $type == 'tip'
			|| $paymentGateway == 'Stripe' && $type == 'ppv'
		) {
			$stripeConnect = $approved == '1' ? $this->stripeConnect($subscribed, $type, $userEarning) : null;
		}

		// Insert Transaction
		$txn = new Transactions();
		$txn->txn_id  = $txnId;
		$txn->user_id = $userId;
		$txn->subscriptions_id = $subscriptionsId;
		$txn->subscribed = $subscribed;
		$txn->amount   = $amount;
		$txn->earning_net_user  =  $userEarning;
		$txn->earning_net_admin = $referred ? $referred['adminEarning'] : $adminEarning;
		$txn->payment_gateway = $paymentGateway;
		$txn->type = $type;
		$txn->percentage_applied = $percentageApplied;
		$txn->approved = $approved;
		$txn->referred_commission = $referred ? true : false;
		$txn->taxes = $taxes;
		$txn->direct_payment = $stripeConnect ?? false;
		$txn->save();

		// Update Transaction ID on ReferralTransactions
		if ($referred) {
			ReferralTransactions::whereId($referred['txnId'])->update([
				'transactions_id' => $txn->id
			]);
		}

		return $txn;
	} // End Method Insert Transaction

	// Insert PayPerViews
	public function payPerViews($user_id, $updates_id, $messages_id)
	{
		$sql = new PayPerViews();
		$sql->user_id = $user_id;
		$sql->updates_id = $updates_id;
		$sql->messages_id = $messages_id;
		$sql->save();
	} // End Method

	// Send notification via Email to creator that you have received a tip
	protected function notifyEmailNewTip($user, $tipper, $amount)
	{
		$data = [
			'tipper' => $tipper,
			'amount' => $amount
		];

		try {
			$user->notify(new TipReceived($data));
		} catch (\Exception $e) {
			\Log::info($e->getMessage());
		}
	} // End Method

	// Send notification via Email to creator that you have received a PPV
	protected function notifyEmailNewPPV($user, $buyer, $media, $type)
	{
		$data = [
			'buyer' => $buyer,
			'content' => $media,
			'type' => $type
		];

		try {
			$user->notify(new PayPerViewReceived($data));
		} catch (\Exception $e) {
			\Log::info($e->getMessage());
		}
	} // End Method

	// Insert Deposit (Add funds user wallet)
	public function deposit($userId, $txnId, $amount, $paymentGateway, $taxes, $screenshotTransfer = '')
	{
		$payment = PaymentGateways::whereName($paymentGateway)->firstOrFail();
		$paymentFee = $payment->fee;
		$paymentFeeCents = $payment->fee_cents;

		// Percentage applied
		$percentageApplied =  $paymentFeeCents == 0.00 ?
			(($paymentFee != 0.0) ? $paymentFee . '%' : null)
			: (($paymentFee != 0.0) ? $paymentFee . '% + ' : null) . $paymentFeeCents;

		// Percentage applied amount
		$transactionFeeAmount = number_format($amount + ($amount * $paymentFee / 100) + $paymentFeeCents, 2, '.', '');
		$transactionFee = ($transactionFeeAmount - $amount);

		$sql = new Deposits();
		$sql->user_id = $userId;
		$sql->txn_id = $txnId;
		$sql->amount = $amount;
		$sql->payment_gateway = $paymentGateway;
		$sql->status = $paymentGateway == 'Bank' ? 'pending' : 'active';
		$sql->screenshot_transfer = $screenshotTransfer;
		$sql->percentage_applied = $percentageApplied;
		$sql->transaction_fee = $transactionFee;
		$sql->taxes = $taxes;
		$sql->save();

		return $sql;
	} // End Method

	public function generateTwofaCode($user)
	{
		$code = rand(1000, 9999);

		// Delete old session user id
		session()->forget('user:id');

		// Create session user
		session()->put('user:id', $user->id);

		TwoFactorCodes::updateOrCreate([
			'user_id' => $user->id,
			'code' => $code
		]);

		try {
			$data = ['code' => $code];

			$user->notify(new SendTwoFactorCode($data));
		} catch (Exception $e) {
			\Log::info("Error Two Factor Code: " . $e->getMessage());
		}
	} // End method

	public function createTaxStripe($id, $name, $country, $stateCode, $percentage)
	{
		$payment = PaymentGateways::whereName('Stripe')
			->whereEnabled('1')
			->where('key_secret', '<>', '')
			->first();

		if ($payment) {
			try {
				$stripe = new \Stripe\StripeClient($payment->key_secret);

				if ($stateCode) {
					$tax = $stripe->taxRates->create([
						'display_name' => $name,
						'description' => $name . ' - ' . $country->country_name,
						'country' => $country->country_code,
						'jurisdiction' => $country->country_code,
						'state' => $stateCode,
						'percentage' => $percentage,
						'inclusive' => false,
					]);
				} else {
					$tax = $stripe->taxRates->create([
						'display_name' => $name,
						'description' => $name . ' - ' . $country->country_name,
						'country' => $country->country_code,
						'jurisdiction' => $country->country_code,
						'percentage' => $percentage,
						'inclusive' => false,
					]);
				}

				// Insert ID to tax_rates table
				TaxRates::whereId($id)->update([
					'stripe_id' => $tax->id
				]);
			} catch (\Exception $e) {
				\Log::debug($e->getMessage());
			}
		}
	} // End method

	public function updateTaxStripe($stripe_id, $name, $status)
	{
		$payment = PaymentGateways::whereName('Stripe')
			->whereEnabled('1')
			->where('key_secret', '<>', '')
			->first();

		if ($payment) {
			try {
				$stripe = new \Stripe\StripeClient($payment->key_secret);

				$stripe->taxRates->update(
					$stripe_id,
					[
						'active' => $status ? 'true' : 'false',
						'display_name' => $name
					]
				);
			} catch (\Exception $e) {
				\Log::debug($e->getMessage());
			}
		}
	} // End method

	protected function referred($userId, $adminEarning, $type)
	{
		// Check Referred
		if (config('settings.referral_system') == 'on') {
			// Check for referred
			$referred = Referrals::whereUserId($userId)->first();

			if ($referred) {
				// Check if the user who referred exists
				$referredBy = User::find($referred->referred_by);

				if ($referredBy) {
					// Check numbers of transactions
					$transactions = ReferralTransactions::whereUserId($userId)->count();

					if (
						config('settings.referral_transaction_limit') == 'unlimited'
						|| $transactions < config('settings.referral_transaction_limit')
					) {

						$adminEarningFinal = $adminEarning - ($adminEarning * config('settings.percentage_referred') / 100);

						$earningNetUser = ($adminEarning - $adminEarningFinal);
						$adminEarning   = ($adminEarning - $earningNetUser);

						if (config('settings.currency_code') == 'JPY') {
							$earningNetUser = floor($earningNetUser);
							$adminEarning   = floor($adminEarning);
						} else {
							$earningNetUser = round($earningNetUser, 2, PHP_ROUND_HALF_DOWN);
							$adminEarning   = round($adminEarning, 2, PHP_ROUND_HALF_DOWN);
						}

						if ($earningNetUser != 0) {
							// Insert User Earning
							$newTransaction = new ReferralTransactions();
							$newTransaction->referrals_id = $referred->id;
							$newTransaction->user_id = $referred->user_id;
							$newTransaction->referred_by = $referred->referred_by;
							$newTransaction->earnings = $earningNetUser;
							$newTransaction->type = $type;
							$newTransaction->save();

							// Add Earnings to User
							$referred->referredBy()->increment('balance', $earningNetUser);

							// Notify to user - destination, author, type, target
							Notifications::send($referred->referred_by, $referred->referred_by, 11, $referred->referred_by);

							return [
								'txnId' => $newTransaction->id,
								'adminEarning' => $adminEarning
							];
						}
					}
				} //=== $referredBy
			} // $referred
		} // referral_system On

		return false;
	} // End Method referred

	protected function stripeConnect($user, $type, $earnings)
	{
		$settings = AdminSettings::first();

		// Get Payment Gateway
		$payment = PaymentGateways::whereName('Stripe')->first();

		// Get User
		$user = User::find($user);

		// Stripe Connect
		if ($user->stripe_connect_id && $user->completed_stripe_onboarding) {
			try {
				// Stripe Client
				$stripe = new \Stripe\StripeClient($payment->key_secret);

				$earningsUser = $settings->currency_code == 'JPY' ? $earnings : ($earnings * 100);

				switch ($type) {
					case 'tip':
						$description = __('general.tip');
						break;

					case 'ppv':
						$description = __('general.ppv');
						break;

					case 'subscription':
						$description = __('general.subscription');
						break;
				}

				$stripe->transfers->create([
					'amount' => $earningsUser,
					'currency' => $settings->currency_code,
					'destination' => $user->stripe_connect_id,
					'description' => $description
				]);

				// Subtract amount from balance
				$user->decrement('balance', $earnings);

				return true;
			} catch (\Exception $e) {
				return false;

				\Log::info('Error Stripe Connect Transfer --- ' . $e->getMessage());
			}
		}
		return false;
	} // End Method stripeConnect

	protected function deductReferredBalanceByRefund($transaction)
	{
		$referralTransaction = ReferralTransactions::whereTransactionsId($transaction->id)->first();
		if ($transaction->referred_commission && $referralTransaction) {
			User::find($referralTransaction->referred_by)->decrement('balance', $referralTransaction->earnings);
			$referralTransaction->delete();
		}
	}

	protected function autoFollowAdmin($user)
	{
		// Find user
		$admin = User::wherePermissions('full_access')
			->whereFreeSubscription('yes')
			->whereVerifiedId('yes')
			->first();

		if (!$admin) {
			return false;
		}

		// Verify plan no is empty
		if (!$admin->plan) {
			$admin->plan = 'user_' . $admin->id;
			$admin->save();
		}

		// Check if not plans
		if ($admin->plans()->count() == 0) {
			Plans::updateOrCreate(
				[
					'user_id' => $admin->id,
					'name' => 'user_' . $admin->id
				],
				[
					'interval' => 'monthly',
					'status' => '1'
				]
			);
		}

		// Verify subscription exists
		$subscription = Subscriptions::whereUserId($user)
			->whereStripePrice($admin->plan)
			->whereFree('yes')
			->first();

		if ($subscription) {
			return false;
		}

		// Insert DB
		$sql          = new Subscriptions();
		$sql->user_id = $user;
		$sql->stripe_price = $admin->plan;
		$sql->free = 'yes';
		$sql->save();

		if ($admin->notify_new_subscriber == 'yes') {
			// Send Notification to User --- destination, author, type, target
			Notifications::send($admin->id, $user, '1', $admin->id);
		}
	}

	public function filterByGenderAge($sql)
	{

		$sql->when(request('gender'), function ($q) {

			if (request('gender') == 'all') {
				$q->where('users.id', '<>', '');
			} else {
				$q->where('gender', request('gender'));
			}
		});

		$sql->when((int) request('min_age') >= 18, function ($q) {
			$minAge = Carbon::now()->subYear(request('min_age'));
			$q->where(DB::raw('STR_TO_DATE(birthdate, "%m/%d/%Y")'), '<', Carbon::parse($minAge->format('m/d/Y')));
		});

		$sql->when((int) request('max_age'), function ($q) {
			$maxAge = Carbon::now()->subYear(request('max_age'));
			$q->where(DB::raw('STR_TO_DATE(birthdate, "%m/%d/%Y")'), '>=', Carbon::parse($maxAge->format('m/d/Y')));
		});
	}

	public function loginSession($id)
	{
		$agent = new Agent();

		// Device
		$device  = $agent->device();

		// Device type
		$deviceType  = $agent->isPhone() ? 'phone' : 'desktop';

		// Browser
		$browser = $agent->browser();
		$browser = $browser . ' ' . $agent->version($browser);

		// Platform
		$platform = $agent->platform();
		$platform = $platform . ' ' . $agent->version($platform);

		try {
			$country = Countries::whereCountryCode(Helper::userCountry())->first();

			LoginSessions::updateOrCreate([
				'user_id' => $id,
				'device' => $device,
				'device_type' => $deviceType,
				'browser' => $browser,
			], [
				'user_id' => $id,
				'ip' => request()->ip(),
				'device' => $device,
				'device_type' => $deviceType,
				'browser' => $browser,
				'platform' => $platform,
				'country' => $country->country_name ?? null,
				'updated_at' => now()
			]);
		} catch (\Exception $e) {
			\Log::debug($e->getMessage());
		}
	}

	public function generateDates(Carbon $startDate, Carbon $endDate, $format = 'Y-m-d')
	{
		$dates = collect();
		$startDate = $startDate->copy();

		for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
			$dates->put($date->format($format), 0);
		}

		return $dates;
	}

	/**
	 * Insert Message Tip
	 */
	public function isMessageTip($userId, $fromUserId, $amount): void
	{
		$message = new Messages();
		$message->conversations_id = 0;
		$message->from_user_id    = $fromUserId;
		$message->to_user_id      = $userId;
		$message->message         = '';
		$message->updated_at      = now();
		$message->tip             = 'yes';
		$message->tip_amount      = $amount;
		$message->save();
	}

	/**
	 * Validate Phone Number
	 */
	public function validatePhone($phone): mixed
	{
		return filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
	}

	/**
	 * Refund Live Stream Request
	 */
	public function refundLiveStreamRequest(LiveStreamingPrivateRequest $live, int $status): void
	{
		$taxes = TaxRates::whereIn('id', collect(explode('_', $live->transaction->taxes)))->get();
		$totalTaxes = ($live->transaction->amount * $taxes->sum('percentage') / 100);
		$amountRefund = number_format($live->transaction->amount + $totalTaxes, 2, '.', '');

		// Add funds to wallet buyer
		$live->user->increment('wallet', $amountRefund);

		// Get amount referral (if exist)
		$this->deductReferredBalanceByRefund($live->transaction);

		// Remove creator funds
		if ($live->creator->balance <> 0.00) {
			$live->creator->decrement('balance', $live->transaction->earning_net_user);
		} else {
			// If the creator has withdrawn their entire balance remove from withdrawal
			$withdrawalPending = Withdrawals::whereUserId($live->creator->id)->whereStatus('pending')->first();

			if ($withdrawalPending) {
				$withdrawalPending->decrement('amount', $amountRefund);
			}
		}

		// Update Live to Rejected/Expired
		$live->update([
			'status' => $status
		]);

		// Update Transaction Cancel
		$live->transaction->update([
			'approved' => '2'
		]);
	}

	public function sendWelcomeMessageAction($creator, $userId)
	{
		(new SendWelcomeMessageAction())->execute($creator, $userId);
	}
}
