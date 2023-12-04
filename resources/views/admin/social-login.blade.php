@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.social_login') }}</span>
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

					 <form method="POST" action="{{ url('panel/admin/social-login') }}" enctype="multipart/form-data">
						 @csrf

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Facebook Client ID</label>
		          <div class="col-sm-10">
		            <input value="{{ config('services.facebook.client_id') }}" name="FACEBOOK_CLIENT_ID" type="password" class="form-control">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Facebook Client Secret</label>
		          <div class="col-sm-10">
		            <input value="{{ config('services.facebook.client_secret') }}" name="FACEBOOK_CLIENT_SECRET" type="password" class="form-control">
								<small class="d-block text-muted">URL Callback: <strong>{{url('oauth/facebook/callback')}}</strong></small>
		          </div>
		        </div>

						<fieldset class="row mb-3">
		          <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('admin.status') }}</legend>
		          <div class="col-sm-10">
		            <div class="form-check form-switch form-switch-md">
		             <input class="form-check-input" type="checkbox" name="facebook_login" @if ($settings->facebook_login == 'on') checked="checked" @endif value="on" role="switch">
		           </div>
		          </div>
		        </fieldset><!-- end row -->

						<hr />

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Twitter API key</label>
		          <div class="col-sm-10">
		            <input value="{{ config('services.twitter.client_id') }}" name="TWITTER_CLIENT_ID" type="password" class="form-control">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Twitter API secret key</label>
		          <div class="col-sm-10">
		            <input value="{{ config('services.twitter.client_secret') }}" name="TWITTER_CLIENT_SECRET" type="password" class="form-control">
								<small class="d-block text-muted">URL Callback: <strong>{{url('oauth/twitter/callback')}}</strong></small>
		          </div>
		        </div>

						<fieldset class="row mb-3">
		          <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('admin.status') }}</legend>
		          <div class="col-sm-10">
		            <div class="form-check form-switch form-switch-md">
		             <input class="form-check-input" type="checkbox" name="twitter_login" @if ($settings->twitter_login == 'on') checked="checked" @endif value="on" role="switch">
		           </div>
		          </div>
		        </fieldset><!-- end row -->

						<hr />

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Google Client ID</label>
		          <div class="col-sm-10">
		            <input value="{{ config('services.google.client_id') }}" name="GOOGLE_CLIENT_ID" type="password" class="form-control">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Google Client Secret</label>
		          <div class="col-sm-10">
		            <input value="{{ config('services.google.client_secret') }}" name="GOOGLE_CLIENT_SECRET" type="password" class="form-control">
								<small class="d-block text-muted">URL Callback: <strong>{{url('oauth/google/callback')}}</strong></small>
		          </div>
		        </div>

						<fieldset class="row mb-3">
		          <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('admin.status') }}</legend>
		          <div class="col-sm-10">
		            <div class="form-check form-switch form-switch-md">
		             <input class="form-check-input" type="checkbox" name="google_login" @if ($settings->google_login == 'on') checked="checked" @endif value="on" role="switch">
		           </div>
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
