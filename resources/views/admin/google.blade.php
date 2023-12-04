@extends('admin.layout')

@section('content')
<h5 class="mb-4 fw-light">
	<a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
	<i class="bi-chevron-right me-1 fs-6"></i>
	<span class="text-muted">Google</span>

</h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			@if (session('success_message'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<i class="bi bi-check2 me-1"></i> {{ session('success_message') }}

				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
					<i class="bi bi-x-lg"></i>
				</button>
			</div>
			@endif

			@include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					<form method="POST" action="{{ url('panel/admin/google') }}" enctype="multipart/form-data">
						@csrf

						<div class="row mb-3">
							<label class="col-sm-2 col-form-label text-lg-end">reCAPTCHA Key</label>
							<div class="col-sm-10">
								<input value="{{ config('captcha.sitekey') }}" name="NOCAPTCHA_SITEKEY" type="password"
									class="form-control">
							</div>
						</div>

						<div class="row mb-3">
							<label class="col-sm-2 col-form-label text-lg-end">reCAPTCHA Secret Key</label>
							<div class="col-sm-10">
								<input value="{{ config('captcha.secret') }}" name="NOCAPTCHA_SECRET" type="password"
									class="form-control">
								<small class="d-block"><a href="https://www.google.com/recaptcha/admin/create"
										target="_blank">https://www.google.com/recaptcha/admin/create</a></small>
							</div>
						</div>

						<div class="row mb-3">
							<label class="col-sm-2 col-form-labe text-lg-end">Google Analytics</label>
							<div class="col-sm-10">
								<textarea class="form-control" name="google_analytics"
									rows="4">{{ $settings->google_analytics }}</textarea>
							</div>
						</div>

						<div class="row mb-3">
							<label class="col-sm-2 col-form-labe text-lg-end">Google Tag Manager (head)</label>
							<div class="col-sm-10">
								<textarea class="form-control" name="google_tag_manager_head"
									rows="4">{{ $settings->google_tag_manager_head }}</textarea>
							</div>
						</div>

						<div class="row mb-3">
							<label class="col-sm-2 col-form-labe text-lg-end">Google Tag Manager (body)</label>
							<div class="col-sm-10">
								<textarea class="form-control" name="google_tag_manager_body"
									rows="4">{{ $settings->google_tag_manager_body }}</textarea>
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