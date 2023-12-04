@extends('admin.layout')

@section('css')
<link href="{{ asset('public/js/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/js/select2/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.payment_settings') }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			@if (session('success_message'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check2 me-1"></i>	{{ session('success_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
                </div>
              @endif

              @include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form method="POST" action="{{ url('panel/admin/payments') }}" enctype="multipart/form-data">
             @csrf

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.currency_code') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $settings->currency_code }}" name="currency_code" type="text" class="form-control">
		          </div>
		        </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.currency_symbol') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->currency_symbol }}" name="currency_symbol" type="text" class="form-control">
                <small class="d-block">{{ __('admin.notice_currency') }}</small>
              </div>
            </div>

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.fee_commission') }}</label>
		          <div class="col-sm-10">
		            <select name="fee_commission" class="form-select">
                  @for ($i=1; $i <= 95; ++$i)
                    <option @if ($settings->fee_commission == $i) selected="selected" @endif value="{{$i}}">{{$i}}%</option>
                    @endfor
		           </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('general.percentage_referred') }}</label>
		          <div class="col-sm-10">
		            <select name="percentage_referred" class="form-select">
                  @for ($i=1; $i <= 30; ++$i)
                    <option @if ($settings->percentage_referred == $i) selected="selected" @endif value="{{$i}}">{{$i}}%</option>
                    @endfor
		           </select>
		          </div>
		        </div>

						<div class="row mb-3">
							<label class="col-sm-2 col-form-labe text-lg-end">{{ trans('general.referral_transaction_limit') }}</label>
							<div class="col-sm-10">
								<select name="referral_transaction_limit" class="form-select">
									<option @if ($settings->referral_transaction_limit == 'unlimited') selected="selected" @endif value="unlimited">
										{{ trans('admin.unlimited') }}
									</option>

									@for ($i=1; $i <= 100; ++$i)
										<option @if ($settings->referral_transaction_limit == $i) selected="selected" @endif value="{{$i}}">{{$i}}</option>
										@endfor
							 </select>
							</div>
						</div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.min_subscription_amount') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->min_subscription_amount }}" name="min_subscription_amount" type="number" min="1" autocomplete="off" class="form-control onlyNumber">
              </div>
            </div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.max_subscription_amount') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->max_subscription_amount }}" name="max_subscription_amount" type="number" min="1" autocomplete="off" class="form-control onlyNumber">
              </div>
            </div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('general.min_tip_amount') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->min_tip_amount }}" name="min_tip_amount" type="number" min="1" autocomplete="off" class="form-control onlyNumber">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('general.max_tip_amount') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->max_tip_amount }}" name="max_tip_amount" type="number" min="1" autocomplete="off" class="form-control onlyNumber">
              </div>
            </div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('general.min_ppv_amount') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->min_ppv_amount }}" name="min_ppv_amount" type="number" min="1" autocomplete="off" class="form-control onlyNumber">
              </div>
            </div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('general.max_ppv_amount') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->max_ppv_amount }}" name="max_ppv_amount" type="number" min="1" autocomplete="off" class="form-control onlyNumber">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('general.min_deposits_amount') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->min_deposits_amount }}" name="min_deposits_amount" type="number" min="1" autocomplete="off" class="form-control">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('general.max_deposits_amount') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->max_deposits_amount }}" name="max_deposits_amount" type="number" min="1" autocomplete="off" class="form-control">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('general.amount_min_withdrawal') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->amount_min_withdrawal }}" name="amount_min_withdrawal" type="number" min="1" autocomplete="off" class="form-control">
              </div>
            </div>

			<div class="row mb-3">
				<label class="col-sm-2 col-form-label text-lg-end">{{ trans('general.amount_max_withdrawal') }}</label>
				<div class="col-sm-10">
				  <input value="{{ $settings->amount_max_withdrawal }}" name="amount_max_withdrawal" type="number" autocomplete="off" class="form-control">
				  <small class="d-block">{{ trans('general.info_max_withdrawal') }}</small>
				</div>
			  </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.currency_position') }}</label>
		          <div class="col-sm-10">
		            <select name="currency_position" class="form-select">
									<option @if ($settings->currency_position == 'left') selected="selected" @endif value="left">{{$settings->currency_symbol}}99 - {{trans('admin.left')}}</option>
									<option @if ($settings->currency_position == 'left_space') selected="selected" @endif value="left_space">{{$settings->currency_symbol}} 99 - {{trans('general.left_with_space')}}</option>
									<option @if ($settings->currency_position == 'right') selected="selected" @endif value="right">99{{$settings->currency_symbol}} - {{trans('admin.right')}}</option>
									<option @if ($settings->currency_position == 'right_space') selected="selected" @endif value="right_space">99 {{$settings->currency_symbol}} - {{trans('general.right_with_space')}}</option>
		           </select>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('general.decimal_format') }}</label>
		          <div class="col-sm-10">
		            <select name="decimal_format" class="form-select">
                  <option @if ($settings->decimal_format == 'dot') selected="selected" @endif value="dot">1,999.95</option>
                  <option @if ($settings->decimal_format == 'comma') selected="selected" @endif value="comma">1.999,95</option>
                </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('general.specific_day_payment_withdrawals') }}</label>
		          <div class="col-sm-10">
		            <select name="specific_day_payment_withdrawals" class="form-select">
									<option @if (! $settings->specific_day_payment_withdrawals) selected="selected" @endif>
										{{ trans('general.not_specified') }}
									</option>
									@for ($i=1; $i <= 25; ++$i)
										<option @if ($settings->specific_day_payment_withdrawals == $i) selected="selected" @endif value="{{$i}}">{{ trans('general.day_of_each_month', ['day' => $i]) }}</option>
										@endfor
                </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.days_process_withdrawals') }}</label>
		          <div class="col-sm-10">
		            <select name="days_process_withdrawals" class="form-select">
									@for ($i=1; $i <= 30; ++$i)
										<option @if( $settings->days_process_withdrawals == $i ) selected="selected" @endif value="{{$i}}">{{$i}} ({{trans_choice('general.days', $i)}})</option>
										@endfor
                </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('general.type_withdrawals') }}</label>
		          <div class="col-sm-10">
		            <select name="type_withdrawals" class="form-select">
									<option @if ($settings->type_withdrawals == 'custom') selected="selected" @endif value="custom">{{ trans('general.custom_amount') }}</option>
									<option @if ($settings->type_withdrawals == 'balance') selected="selected" @endif value="balance">{{ trans('general.total_balance') }}</option>
                </select>
		          </div>
		        </div>

				<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('users.payout_method') }} (PayPal)</label>
		          <div class="col-sm-10">
		            <select name="payout_method_paypal" class="form-select">
                  <option @if ($settings->payout_method_paypal == 'on') selected="selected" @endif value="on">{{ __('general.enabled') }}</option>
                  <option @if ($settings->payout_method_paypal == 'off') selected="selected" @endif value="off">{{ __('general.disabled') }}</option>
                </select>
					<small class="d-block">{{ trans('general.payout_method_desc') }}</small>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('users.payout_method') }} (Payoneer)</label>
		          <div class="col-sm-10">
		            <select name="payout_method_payoneer" class="form-select">
                  <option @if ($settings->payout_method_payoneer == 'on') selected="selected" @endif value="on">{{ __('general.enabled') }}</option>
                  <option @if ($settings->payout_method_payoneer == 'off') selected="selected" @endif value="off">{{ __('general.disabled') }}</option>
                </select>
								<small class="d-block">{{ trans('general.payout_method_desc') }}</small>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('users.payout_method') }} (Zelle)</label>
		          <div class="col-sm-10">
		            <select name="payout_method_zelle" class="form-select">
                  <option @if ($settings->payout_method_zelle == 'on') selected="selected" @endif value="on">{{ __('general.enabled') }}</option>
                  <option @if ($settings->payout_method_zelle == 'off') selected="selected" @endif value="off">{{ __('general.disabled') }}</option>
                </select>
								<small class="d-block">{{ trans('general.payout_method_desc') }}</small>
		          </div>
		        </div>

				<div class="row mb-3">
					<label class="col-sm-2 col-form-labe text-lg-end">{{ __('users.payout_method') }} (Western Union)</label>
					<div class="col-sm-10">
					  <select name="payout_method_western_union" class="form-select">
					<option @if ($settings->payout_method_western_union == 'on') selected="selected" @endif value="on">{{ __('general.enabled') }}</option>
					<option @if ($settings->payout_method_western_union == 'off') selected="selected" @endif value="off">{{ __('general.disabled') }}</option>
				  </select>
					  <small class="d-block">{{ trans('general.payout_method_desc') }}</small>
					</div>
				  </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('users.payout_method') }} ({{ __('general.bank') }})</label>
		          <div class="col-sm-10">
		            <select name="payout_method_bank" class="form-select">
                  <option @if ($settings->payout_method_bank == 'on') selected="selected" @endif value="on">{{ __('general.enabled') }}</option>
                  <option @if ($settings->payout_method_bank == 'off') selected="selected" @endif value="off">{{ __('general.disabled') }}</option>
                </select>
								<small class="d-block">{{ trans('general.payout_method_desc') }}</small>
		          </div>
		        </div>

				<div class="row mb-3">
					<label class="col-sm-2 col-form-labe text-lg-end">{{ __('users.payout_method') }} (Bitcoin)</label>
					<div class="col-sm-10">
					  <select name="payout_method_crypto" class="form-select">
					<option @if ($settings->payout_method_crypto == 'on') selected="selected" @endif value="on">{{ __('general.enabled') }}</option>
					<option @if ($settings->payout_method_crypto == 'off') selected="selected" @endif value="off">{{ __('general.disabled') }}</option>
				  </select>
					  <small class="d-block">{{ trans('general.payout_method_desc') }}</small>
					</div>
				  </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.wallet') }}</label>
		          <div class="col-sm-10">
		            <select name="disable_wallet" class="form-select">
									<option @if( $settings->disable_wallet == 'off' ) selected="selected" @endif value="off">{{ trans('general.enabled') }}</option>
									<option @if( $settings->disable_wallet == 'on' ) selected="selected" @endif value="on">{{ trans('general.disabled') }}</option>
                </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.apply_taxes_wallet') }}</label>
		          <div class="col-sm-10">
		            <select name="tax_on_wallet" class="form-select">
                  <option @if ($settings->tax_on_wallet) selected="selected" @endif value="1">{{ __('general.enabled') }}</option>
                  <option @if (! $settings->tax_on_wallet) selected="selected" @endif value="0">{{ __('general.disabled') }}</option>
                </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.money_format') }}</label>
		          <div class="col-sm-10">
		            <select name="wallet_format" class="form-select">
									<option @if( $settings->wallet_format == 'real_money' ) selected="selected" @endif value="real_money">{{ trans('general.real_money') }} ({{ $settings->currency_symbol }})</option>
									<option @if( $settings->wallet_format == 'credits' ) selected="selected" @endif value="credits">{{ trans('general.credits') }}</option>
									<option @if( $settings->wallet_format == 'points' ) selected="selected" @endif value="points">{{ trans('general.points') }}</option>
									<option @if( $settings->wallet_format == 'tokens' ) selected="selected" @endif value="tokens">{{ trans('general.tokens') }}</option>
                </select>
								<small class="d-block">
 								 {{ trans('general.equivalent_money_format') }} {{ Helper::amountFormatDecimal(1)}} {{$settings->currency_code}}
 							 </small>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">Stripe Connect</label>
		          <div class="col-sm-10">
		            <select name="stripe_connect" class="form-select">
                  <option @if ($settings->stripe_connect) selected="selected" @endif value="1">{{ __('general.enabled') }}</option>
                  <option @if (! $settings->stripe_connect) selected="selected" @endif value="0">{{ __('general.disabled') }}</option>
                </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('general.stripe_connect_countries') }}</label>
		          <div class="col-sm-10">
		            <select name="stripe_connect_countries[]" multiple class="form-select stripeConnectCountries">
									@foreach (Countries::orderBy('country_name')->get() as $country)
										<option @if (in_array($country->country_code, $stripeConnectCountries)) selected="selected" @endif value="{{$country->country_code}}">{{ $country->country_name }}</option>
									@endforeach
		           </select>
							 <small class="d-block">
								 {{ trans('general.info_stripe_connect_countries') }} <a href="https://dashboard.stripe.com/settings/connect/express" target="_blank">https://dashboard.stripe.com/settings/connect/express</a>
							 </small>
		          </div>
		        </div>

						<div class="row mb-3">
		          <div class="col-sm-10 offset-sm-2">
		            <button type="submit" class="btn btn-dark mt-3 px-5">{{ __('admin.save') }}</button>
		          </div>
		        </div>

		       </form>

				 </div><!-- card-body -->
 			</div><!-- card  -->
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection

@section('javascript')
  <script>
  $('.stripeConnectCountries').select2({
  tags: false,
  tokenSeparators: [','],
  placeholder: '{{ trans('general.country') }}',
});
</script>
@endsection
