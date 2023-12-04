@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.payment_settings') }}</span>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">Stripe</span>
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

					 <form method="POST" action="{{ url()->current() }}" enctype="multipart/form-data">
             @csrf

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.fee') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $data->fee }}" name="fee" type="text" class="form-control">
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.fee_cents') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $data->fee_cents }}" name="fee_cents" type="text" class="form-control">
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Stripe Publishable Key</label>
		          <div class="col-sm-10">
		            <input value="{{ $data->key }}" name="key" type="password" class="form-control">
                <small class="d-block"><a href="https://dashboard.stripe.com/account/apikeys" target="_blank">https://dashboard.stripe.com/account/apikeys</a></small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Stripe Secret Key</label>
		          <div class="col-sm-10">
		            <input value="{{ $data->key_secret }}" name="key_secret" type="password" class="form-control">
                <small class="d-block"><a href="https://dashboard.stripe.com/account/apikeys" target="_blank">https://dashboard.stripe.com/account/apikeys</a></small>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Stripe Webhook Secret</label>
		          <div class="col-sm-10">
		            <input value="{{ $data->webhook_secret }}" name="webhook_secret" type="password" class="form-control">
                <small class="d-block"><a href="https://dashboard.stripe.com/webhooks" target="_blank">https://dashboard.stripe.com/webhooks</a></small>
		          </div>
		        </div>

            <fieldset class="row mb-3">
              <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('admin.status') }}</legend>
              <div class="col-sm-10">
                <div class="form-check form-switch form-switch-md">
                 <input class="form-check-input" type="checkbox" name="enabled" @if ($data->enabled) checked="checked" @endif value="1" role="switch">
               </div>
              </div>
            </fieldset>

			<fieldset class="row mb-3">
				<legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.allow_payments_alipay') }} <i class="bi-info-circle showTooltip ms-1" title="{{ __('general.only_wallet') }}"></i></legend>
				<div class="col-sm-10">
				  <div class="form-check form-switch form-switch-md">
				   <input class="form-check-input" type="checkbox" name="allow_payments_alipay" @if ($data->allow_payments_alipay) checked="checked" @endif value="1" role="switch">
				 </div>
				</div>
			  </fieldset>

						<fieldset class="row mb-3">
		          <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('admin.subscription') }}</legend>
		          <div class="col-sm-10">
		            <div class="form-check">
		              <input class="form-check-input" type="radio" name="subscription" id="radio1" @if ($data->subscription == 'yes') checked="checked" @endif value="yes">
		              <label class="form-check-label" for="radio1">
		                {{ __('admin.active') }}
		              </label>
		            </div>
		            <div class="form-check">
		              <input class="form-check-input" type="radio" name="subscription" id="radio2" @if ($data->subscription == 'no') checked="checked" @endif value="no">
		              <label class="form-check-label" for="radio2">
		                {{ __('admin.disabled') }}
		              </label>
		            </div>
								<small class="d-block">{{ __('general.note_disable_subs_payment') }}</small>
		          </div>
		        </fieldset><!-- end row -->

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
