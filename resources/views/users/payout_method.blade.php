@extends('layouts.app')

@section('title') {{__('users.payout_method')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="bi bi-credit-card mr-2"></i> {{__('users.payout_method')}}</h2>
          <p class="lead text-muted mt-0">{{__('general.default_payout_method')}}:
            @if(auth()->user()->payment_gateway != '')
              <strong class="text-success">
              {{auth()->user()->payment_gateway == 'Bank' ? __('users.bank_transfer') : auth()->user()->payment_gateway}}
            </strong>
            @else <strong class="text-danger">{{__('general.none')}}</strong> @endif
            </p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          @if (session('status'))
                  <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                			<span aria-hidden="true">×</span>
                			</button>
                    <i class="bi-check2 mr-2"></i> {{ session('status') }}
                  </div>
                @endif

                @if (session('error'))
                        <div class="alert alert-danger">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      			<span aria-hidden="true">×</span>
                      			</button>
                          <i class="bi-exclamation-triangle mr-2"></i> {{ session('error') }}
                        </div>
                      @endif

          @include('errors.errors-forms')

      @if (auth()->user()->verified_id != 'yes' && auth()->user()->balance == 0.00)
      <div class="alert alert-danger mb-3">
               <ul class="list-unstyled m-0">
                 <li><i class="fa fa-exclamation-triangle"></i> {{__('general.verified_account_info')}} <a href="{{url('settings/verify/account')}}" class="text-white link-border">{{__('general.verify_account')}}</a></li>
               </ul>
             </div>
             @endif

      @if (auth()->user()->verified_id == 'yes' || auth()->user()->balance != 0.00)
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
          <i class="fa fa-info-circle mr-2"></i>
          <span> {{ __('general.payout_method_info') }}
          <small class="btn-block">
            @if (! $settings->specific_day_payment_withdrawals)
              * {{ __('general.payment_process_days', ['days' => $settings->days_process_withdrawals]) }}

            @else
              * {{ __('users.date_paid') }} {{ Helper::formatDate(Helper::paymentDateOfEachMonth($settings->specific_day_payment_withdrawals)) }}
            @endif
          </small>
            </span>
          </div>

          @if( $settings->payout_method_paypal == 'on' )
          <!--============ START PAYPAL ============-->
          <div class="custom-control custom-radio mb-3">
                <input name="payment_gateway" value="PayPal" id="radio1" class="custom-control-input" @if (auth()->user()->payment_gateway == 'PayPal') checked @endif type="radio">
                <label class="custom-control-label" for="radio1">
                  <span><img src="{{url('public/img/payments', auth()->user()->dark_mode == 'off' ? 'paypal.png' : 'paypal-white.png')}}" width="70"/></span>
                  <small class="w-100 d-block">* {{__('general.processor_fees_may_apply')}}</small>
                </label>
              </div>

              <form method="POST" action="{{ url('settings/payout/method/paypal') }}" id="PayPal" @if (auth()->user()->payment_gateway != 'PayPal') class="display-none" @endif>
                @csrf

                <div class="form-group">
                    <div class="input-group mb-4">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fab fa-paypal"></i></span>
                      </div>
                      <input class="form-control" name="email_paypal" value="{{auth()->user()->paypal_account == '' ? old('email_paypal') : auth()->user()->paypal_account}}" placeholder="{{__('general.email_paypal')}}" required type="email">
                    </div>
                  </div>

                  <div class="form-group">
                      <div class="input-group mb-4">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-envelope"></i></span>
                        </div>
                        <input class="form-control" name="email_paypal_confirmation" placeholder="{{__('general.confirm_email_paypal')}}" required type="email">
                      </div>
                    </div>
                    <button class="btn btn-1 btn-success btn-block" type="submit">{{__('general.save_payout_method')}}</button>
              </form>
            <!--============ END PAYPAL ============-->
            @endif

            @if( $settings->payout_method_payoneer == 'on' )
            <!--============ START PAYONEER ============-->
            <div class="custom-control custom-radio mb-3 mt-3">
                  <input name="payment_gateway" value="Payoneer" id="radio2" class="custom-control-input" @if (auth()->user()->payment_gateway == 'Payoneer') checked @endif type="radio">
                  <label class="custom-control-label" for="radio2">
                    <span><img src="{{url('public/img/payments', auth()->user()->dark_mode == 'off' ? 'payoneer.png' : 'payoneer-white.png')}}" width="110"/></span>
                    <small class="w-100 d-block">* {{__('general.processor_fees_may_apply')}}</small>
                  </label>
                </div>

                <form method="POST" action="{{ url('settings/payout/method/payoneer') }}" id="Payoneer" @if (auth()->user()->payment_gateway != 'Payoneer') class="display-none" @endif>
                  @csrf

                  <div class="form-group">
                      <div class="input-group mb-4">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-envelope"></i></span>
                        </div>
                        <input class="form-control" name="email_payoneer" value="{{auth()->user()->payoneer_account == '' ? old('email_payoneer') : auth()->user()->payoneer_account}}" placeholder="{{__('general.email_payoneer')}}" required type="email">
                      </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group mb-4">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-envelope"></i></span>
                          </div>
                          <input class="form-control" name="email_payoneer_confirmation" placeholder="{{__('general.confirm_email_payoneer')}}" required type="email">
                        </div>
                      </div>
                      <button class="btn btn-1 btn-success btn-block" type="submit">{{__('general.save_payout_method')}}</button>
                </form>
              <!--============ END PAYONEER ============-->
              @endif

              @if ($settings->payout_method_zelle == 'on')
              <!--============ START ZELLE ============-->
              <div class="custom-control custom-radio mb-3 mt-3">
                    <input name="payment_gateway" value="Zelle" id="radio3" class="custom-control-input" @if (auth()->user()->payment_gateway == 'Zelle') checked @endif type="radio">
                    <label class="custom-control-label" for="radio3">
                      <span><img src="{{url('public/img/payments', auth()->user()->dark_mode == 'off' ? 'zelle.png' : 'zelle-white.png')}}" width="50"/></span>
                      <small class="w-100 d-block">* {{__('general.processor_fees_may_apply')}}</small>
                    </label>
                  </div>

                  <form method="POST" action="{{ url('settings/payout/method/zelle') }}" id="Zelle" @if (auth()->user()->payment_gateway != 'Zelle') class="display-none" @endif>
                    @csrf

                    <div class="form-group">
                        <div class="input-group mb-4">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-envelope"></i></span>
                          </div>
                          <input class="form-control" name="email_zelle" value="{{auth()->user()->zelle_account == '' ? old('email_zelle') : auth()->user()->zelle_account}}" placeholder="{{__('general.email_zelle')}}" required type="email">
                        </div>
                      </div>

                      <div class="form-group">
                          <div class="input-group mb-4">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-envelope"></i></span>
                            </div>
                            <input class="form-control" name="email_zelle_confirmation" placeholder="{{__('general.confirm_email_zelle')}}" required type="email">
                          </div>
                        </div>
                        <button class="btn btn-1 btn-success btn-block" type="submit">{{__('general.save_payout_method')}}</button>
                  </form>
                <!--============ END ZELLE ============-->
                @endif

                @if ($settings->payout_method_western_union == 'on')
              <!--============ START WESTERN ============-->
              <div class="custom-control custom-radio mb-3 mt-3">
                    <input name="payment_gateway" value="Western" id="radioWestern" class="custom-control-input" @if (auth()->user()->payment_gateway == 'Western Union') checked @endif type="radio">
                    <label class="custom-control-label" for="radioWestern">
                      <span><img src="{{url('public/img/payments/western.png')}}" width="150"/></span>
                      <small class="w-100 d-block">* {{__('general.processor_fees_may_apply')}}</small>
                    </label>
                  </div>

                  <form method="POST" action="{{ url('settings/payout/method/western') }}" id="Western" @if (auth()->user()->payment_gateway != 'Western Union') class="display-none" @endif>
                    @csrf

                    <div class="form-group">
                        <div class="input-group mb-4">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-user"></i></span>
                          </div>
                          <input class="form-control" name="name" value="{{auth()->user()->document_id == '' ? old('name') : auth()->user()->name}}" placeholder="{{__('auth.full_name')}}" required type="text">
                        </div>
                      </div>

                      <div class="form-group">
                          <div class="input-group mb-4">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-address-card"></i></span>
                            </div>
                            <input class="form-control" name="document_id" value="{{auth()->user()->document_id == '' ? old('document_id') : auth()->user()->document_id}}" placeholder="{{__('general.document_id')}}" required type="text">
                          </div>
                        </div>
                        <button class="btn btn-1 btn-success btn-block" type="submit">{{__('general.save_payout_method')}}</button>
                  </form>
                <!--============ END WESTERN ============-->
                @endif

                @if ($settings->payout_method_crypto == 'on')
              <!--============ START BITCOIN ============-->
              <div class="custom-control custom-radio mb-3 mt-3">
                    <input name="payment_gateway" value="Bitcoin" id="BitcoinInput" class="custom-control-input" @if (auth()->user()->payment_gateway == 'Bitcoin') checked @endif type="radio">
                    <label class="custom-control-label" for="BitcoinInput">
                      <span><img src="{{url('public/img/payments', auth()->user()->dark_mode == 'off' ? 'bitcoin.png' : 'bitcoin-white.png')}}" width="100"/></span>
                      <small class="w-100 d-block">* {{__('general.processor_fees_may_apply')}}</small>
                    </label>
                  </div>

                  <form method="POST" action="{{ url('settings/payout/method/bitcoin') }}" id="Bitcoin" @if (auth()->user()->payment_gateway != 'Bitcoin') class="display-none" @endif>
                    @csrf

                    <div class="form-group">
                        <div class="input-group mb-4">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="bi-currency-bitcoin"></i></span>
                          </div>
                          <input class="form-control" name="crypto_wallet" value="{{auth()->user()->crypto_wallet == '' ? old('crypto_wallet') : auth()->user()->crypto_wallet}}" placeholder="{{__('general.bitcoin_wallet')}}" required type="text">
                        </div>
                      </div>
                        <button class="btn btn-1 btn-success btn-block" type="submit">{{__('general.save_payout_method')}}</button>
                  </form>
                <!--============ END BITCOIN ============-->
                @endif

            @if( $settings->payout_method_bank == 'on' )
            <!--============ START BANK TRANSFER ============-->
              <div class="custom-control custom-radio mb-3 mt-3">
                    <input name="payment_gateway" value="Bank" id="radio4" class="custom-control-input" @if (auth()->user()->payment_gateway == 'Bank') checked @endif type="radio">
                    <label class="custom-control-label" for="radio4">
                      <span><strong><i class="fa fa-university mr-1 icon-sm-radio"></i> {{__('users.bank_transfer')}}</strong></span>
                      <small class="w-100 d-block">* {{__('general.processor_fees_may_apply')}}</small>
                    </label>
                  </div>

                  <form method="POST"  action="{{ url('settings/payout/method/bank') }}" id="Bank" @if (auth()->user()->payment_gateway != 'Bank') class="display-none" @endif>

                    @csrf
                      <div class="form-group">
                        <textarea name="bank_details" rows="5" cols="40" class="form-control" required placeholder="{{__('users.bank_details')}}">{{auth()->user()->bank == '' ? old('bank_details') : auth()->user()->bank}}</textarea>
                        </div>

                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                          <i class="fa fa-info-circle mr-2"></i>
                          <span>{{__('users.bank_details')}}</span>
                          </div>

                        <button class="btn btn-1 btn-success btn-block" type="submit">{{__('general.save_payout_method')}}</button>
                  </form>
                  <!--============ END BANK TRANSFER ============-->
                @endif

      @endif

      @if (auth()->user()->verified_id == 'yes'
          && $settings->stripe_connect
          && isset(auth()->user()->country()->country_code)
          && in_array(auth()->user()->country()->country_code, $stripeConnectCountries)
          )

      <h6 class="mt-5">Stripe Connect @if (auth()->user()->completed_stripe_onboarding) <span class="badge badge-pill badge-success font-weight-light opacity-75">{{ __('general.connected') }}</span> @else <span class="badge badge-pill badge-danger font-weight-light opacity-75">{{ __('general.not_connected') }}</span>  @endif </h6>
        <small class="d-block w-100 mb-3">{{ __('general.stripe_connect_desc') }}</small>


          <a href="{{ route('redirect.stripe') }}" class="btn w-100 btn-lg btn-primary btn-arrow">

            @if (! auth()->user()->completed_stripe_onboarding)
            {{ __('general.connect_stripe_account') }}

          @else
            {{ __('general.view_stripe_account') }}
            @endif
          </a>

      @endif

        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection

@section('javascript')
  <script type="text/javascript">

  $('input[name=payment_gateway]').on('click', function() {

		if($(this).val() == 'PayPal') {
			$('#PayPal').slideDown();
		} else {
				$('#PayPal').slideUp();
		}

    if($(this).val() == 'Payoneer') {
      $('#Payoneer').slideDown();
    } else {
      $('#Payoneer').slideUp();
    }

    if($(this).val() == 'Zelle') {
      $('#Zelle').slideDown();
    } else {
      $('#Zelle').slideUp();
    }

    if($(this).val() == 'Western') {
      $('#Western').slideDown();
    } else {
      $('#Western').slideUp();
    }

    if($(this).val() == 'Bitcoin') {
      $('#Bitcoin').slideDown();
    } else {
      $('#Bitcoin').slideUp();
    }

    if($(this).val() == 'Bank') {
      $('#Bank').slideDown();
    } else {
      $('#Bank').slideUp();
    }

  });
  </script>
@endsection
