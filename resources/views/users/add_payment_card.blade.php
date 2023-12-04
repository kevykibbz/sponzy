@extends('layouts.app')

@section('title') {{trans('general.payment_card')}} -@endsection

@section('css')
  <script type="text/javascript">
      var key_stripe = "{{ $key }}";
  </script>
@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 pt-5 pb-4">
          <h2 class="mb-0 font-montserrat"><i class="feather icon-credit-card mr-2"></i> {{trans('general.payment_card')}}</h2>
          <p class="lead text-muted mt-0">{{trans('general.payment_card_subtitle')}}</p>
        </div>
      </div>
      <div class="row">

        <div class="col-md-8 mx-auto mb-lg-0">

          <div class="bg-white rounded-lg shadow-sm p-5">

            <div class="alert alert-success display-none" id="success">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>

                {{ trans('general.payment_card_success') }}
            </div>

            @php
              switch (auth()->user()->pm_type) {
                case 'amex':
                  $paymentDefault = '<img src="'.asset('public/img/payments/brands/amex.svg').'"> •••• •••• •••• '.auth()->user()->pm_last_four;
                  break;

                case 'diners':
                $paymentDefault = '<img src="'.asset('public/img/payments/brands/diners.svg').'"> •••• •••• •••• '.auth()->user()->pm_last_four;
                break;

                case 'discover':
                $paymentDefault = '<img src="'.asset('public/img/payments/brands/discover.svg').'"> •••• •••• •••• '.auth()->user()->pm_last_four;
                break;

                case 'jcb':
                $paymentDefault = '<img src="'.asset('public/img/payments/brands/jcb.svg').'"> •••• •••• •••• '.auth()->user()->pm_last_four;
                break;

                case 'mastercard':
                $paymentDefault = '<img src="'.asset('public/img/payments/brands/mastercard.svg').'"> •••• •••• •••• '.auth()->user()->pm_last_four;
                break;

                case 'unionpay':
                $paymentDefault = '<img src="'.asset('public/img/payments/brands/unionpay.svg').'"> •••• •••• •••• '.auth()->user()->pm_last_four;
                break;

                case 'visa':
                $paymentDefault = '<img src="'.asset('public/img/payments/brands/visa.svg').'"> •••• •••• •••• '.auth()->user()->pm_last_four;
                break;

                default:
                  $paymentDefault = trans('general.not_card_added');
                  break;
              }
            @endphp

            <h5 class="text-center mb-2">{{ trans('general.default_payment_card') }}</h5>
            <h6 class="text-center mb-3">
              <small>{!! $paymentDefault !!}</small>
            </h6>

            <!-- Stripe Elements Placeholder -->
            <div id="card-element"></div>
            <div id="card-errors" class="alert alert-danger display-none" role="alert"></div>

            <button id="card-button" class="btn btn-1 btn-primary btn-block" data-secret="{{ $intent->client_secret }}">
                <i></i> {{ trans('general.save_payment_card') }}
            </button>
            <div class="mt-2 text-center">
              <a href="{{ url()->previous() }}"><i class="fa fa-long-arrow-alt-left"></i> {{ trans('general.go_back') }}</a>
            </div>
          </div>

          <div class="btn-block text-center mt-2">
            <small><i class="fa fa-lock text-success mr-1"></i> {{ trans('general.info_payment_card') }}</small>
          </div>

        </div><!-- end col-md-8 -->

      </div>
    </div>
  </section>
@endsection

@section('javascript')
<script src="{{ asset('public/js/add-payment-card.js') }}"></script>
@endsection
