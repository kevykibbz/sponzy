@if ($settings->payout_method_paypal == 'off' && auth()->user()->payment_gateway == 'PayPal'
    || $settings->payout_method_payoneer == 'off' && auth()->user()->payment_gateway == 'Payoneer'
    || $settings->payout_method_zelle == 'off' && auth()->user()->payment_gateway == 'Zelle'
    || $settings->payout_method_bank == 'off' && auth()->user()->payment_gateway == 'Bank'
    || $settings->payout_method_crypto == 'off' && auth()->user()->payment_gateway == 'Bitcoin'
)
<div class="alert alert-danger d-block w-100" role="alert">
    <i class="bi-exclamation-triangle-fill mr-2"></i> {!! __('general.payment_method_configured_disabled', ['payment' => '<a href="'.url('settings/payout/method').'" class="link-border text-white">'. __('users.payout_method') .'</a>']) !!}
    </div>
@endif
