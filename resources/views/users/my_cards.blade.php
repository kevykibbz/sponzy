@extends('layouts.app')

@section('title') {{trans('general.my_cards')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="feather icon-credit-card mr-2"></i> {{trans('general.my_cards')}}</h2>
          <p class="lead text-muted mt-0">{{trans('general.info_my_cards')}}</p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          @if (session('success_removed'))
            <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>

                {{ session('success_removed') }}
            </div>
          @endif

          @if (session('success_message'))
            <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>

                {{ trans('general.payment_card_success') }}
            </div>
          @endif

          @if (session('error_message'))
            <div class="alert alert-danger">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>

                {{ session('error_message') }}
            </div>
          @endif

        @if ($key_secret)

          <div class="card mb-4">
            <div class="card-body">
              <p class="card-text">
                @if (auth()->user()->pm_type != '')
                  <img src="{{ asset('public/img/payments/brands/'.strtolower(auth()->user()->pm_type).'.svg')}}" class="mr-1">
                  <strong class="text-capitalize">{{ auth()->user()->pm_type }}</strong> <br> •••• •••• •••• {{ auth()->user()->pm_last_four }}
                  <small class="float-right d-block">{{ trans('general.expiry') }}: {{ $expiration }}</small>

                @else
                  {{ trans('general.not_card_added') }}
                @endif
              </p>

              <a href="{{ url('settings/payments/card') }}" class="btn btn-success btn-sm">{{ auth()->user()->pm_type == '' ? __('general.add') : __('admin.edit') }}</a>

              @if (auth()->user()->pm_type != '')
              <form method="POST" action="{{ url('stripe/delete/card') }}" class="d-inline" id="formDeleteCardStripe">
                @csrf
                <input type="button" class="btn btn-danger btn-sm" id="deleteCardStripe" value="{{ __('admin.delete') }}">
              </form>
            @endif
            </div>
          </div>
          @endif

        @if ($paystackPayment)
          <div class="card">
            <div class="card-body">
              <p class="card-text">
                @if (auth()->user()->paystack_card_brand != '')
                  <img src="{{ asset('public/img/payments/brands/'.strtolower(auth()->user()->paystack_card_brand).'.svg')}}" class="mr-1">
                  <strong class="text-capitalize">{{ auth()->user()->paystack_card_brand }}</strong> <br> •••• •••• •••• {{ auth()->user()->paystack_last4 }}
                  <small class="float-right d-block">{{ trans('general.expiry') }}: {{ auth()->user()->paystack_exp }}</small>

                @else
                  {{ trans('general.not_card_added') }}
                @endif

                <small class="alert alert-primary w-100 d-block mt-1">
                  <i class="fa fa-info-circle mr-2"></i> {{ __('general.notice_charge_to_card', ['amount' => Helper::amountWithoutFormat($chargeAmountPaystack). ' '.$settings->currency_code ]) }}
                </small>

                <form method="POST" action="{{ url('paystack/card/authorization') }}" class="d-inline">
                  @csrf
                  <input type="submit" class="btn btn-success btn-sm" value="{{ auth()->user()->paystack_card_brand == '' ? __('general.add') : __('admin.edit') }}">
                </form>

                @if (auth()->user()->paystack_card_brand != '')
                <form method="POST" action="{{ url('paystack/delete/card') }}" class="d-inline" id="formDeleteCardPaystack">
                  @csrf
                  <input type="button" class="btn btn-danger btn-sm" id="deleteCardPaystack" value="{{ __('admin.delete') }}">
                </form>
              @endif
              </p>
            </div>
          </div>
          @endif

          <div class="btn-block mt-2">
            <small><i class="fa fa-lock text-success mr-1"></i> {{ trans('general.info_payment_card') }}</small>
          </div>

        @if (! $key_secret && ! $paystackPayment)

          <div class="alert alert-primary text-center" role="alert">
            <i class="fa fa-info-circle mr-2"></i> {{ trans('general.not_card_added') }}
          </div>
        @endif
        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection
