@extends('admin.layout')

@section('css')
<link href="{{ asset('public/js/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/js/select2/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.email_settings') }}</span>
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

					 <form method="POST" action="{{ url('panel/admin/settings/email') }}" enctype="multipart/form-data">
						 @csrf

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.email_no_reply') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ config('mail.from.address') }}" name="MAIL_FROM_ADDRESS" type="email" class="form-control">
		          </div>
		        </div>

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.email_driver') }}</label>
		          <div class="col-sm-10">
		            <select name="MAIL_MAILER" id="emailDriver" class="form-select">
									<option @if (config('mail.default') == 'sendmail') selected @endif value="sendmail">Sendmail</option>
									<option @if (config('mail.default') == 'smtp') selected @endif value="smtp">SMTP</option>
									<option @if (config('mail.default') == 'mailgun') selected @endif value="mailgun">Mailgun</option>
									<option @if (config('mail.default') == 'ses') selected @endif value="ses">Amazon Simple Email Service (SES)</option>
									<option @if (config('mail.default') == 'log') selected @endif value="log">Log</option>
		           </select>
							 <small class="w-100 @if (config('mail.default') != 'ses') display-none @endif" id="ses">
								 {{ __('admin.info_ses_email') }}
	 							<a href="{{ url('panel/admin/storage') }}" target="_blank">{{ __('admin.storage') }}</a>
						</small>
		          </div>
		        </div>

						<div id="mailgun" class="@if (config('services.mailgun.domain') == '') display-none @endif">
							<div class="row mb-3">
			          <label class="col-sm-2 col-form-label text-lg-end">Mailgun Domain</label>
			          <div class="col-sm-10">
			            <input value="{{ config('services.mailgun.domain') }}" name="MAILGUN_DOMAIN" type="text" class="form-control" placeholder="mg.example.com">
			          </div>
			        </div>

							<div class="row mb-3">
			          <label class="col-sm-2 col-form-label text-lg-end">Mailgun Secret</label>
			          <div class="col-sm-10">
			            <input value="{{ config('services.mailgun.secret') }}" name="MAILGUN_SECRET" type="text" class="form-control" placeholder="*************">
			          </div>
			        </div>
					</div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.mail_host') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ config('mail.mailers.smtp.host') }}" name="MAIL_HOST" type="text" class="form-control">
		          </div>
		        </div>

						<div class="row mb-3">
							<label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.mail_port') }}</label>
							<div class="col-sm-10">
								<input value="{{ config('mail.mailers.smtp.port') }}" name="MAIL_PORT" type="text" class="form-control">
							</div>
						</div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('auth.username') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ config('mail.mailers.smtp.username') }}" name="MAIL_USERNAME" type="text" class="form-control">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{__('auth.password')}}</label>
		          <div class="col-sm-10">
		            <input value="{{ config('mail.mailers.smtp.password') }}" name="MAIL_PASSWORD" type="password" class="form-control" id="inputPassword3">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.mail_encryption') }}</label>
		          <div class="col-sm-10">
		            <select name="MAIL_ENCRYPTION" class="form-select">
									<option @if (config('mail.mailers.smtp.encryption') == '') selected @endif value="">{{__('general.none')}}</option>
									<option @if (config('mail.mailers.smtp.encryption') == 'tls') selected @endif value="tls">TLS</option>
									<option @if (config('mail.mailers.smtp.encryption') == 'ssl') selected @endif value="ssl">SSL</option>
		           </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.default_timezone') }}</label>
		          <div class="col-sm-10">
		            <select name="TIMEZONE" class="form-select select">
									@include('admin.timezone')
		           </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <div class="col-sm-10 offset-sm-2">
		            <button type="submit" class="btn btn-dark mt-3 px-5">{{ __('admin.save') }}</button>

							@if (config('mail.default') != 'sendmail' && config('mail.default') != 'log')
								<button type="button" id="testSMTP" class="btn btn-success mt-3 ms-lg-3"><i class="bi-envelope-exclamation me-1"></i> {{__('general.testing_smtp')}}</button>
							@endif

		          </div>
		        </div>
		       </form>

			@if (config('mail.default') != 'sendmail' && config('mail.default') != 'log')
			<form method="POST" id="formTestSMTP" action="{{ url('panel/admin/settings/test-smtp') }}">
				@csrf
			</form>
			@endif

				 </div><!-- card-body -->
 			</div><!-- card  -->
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
