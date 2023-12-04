@extends('admin.layout')

@section('content')
<h5 class="mb-4 fw-light">
	<a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
	<i class="bi-chevron-right me-1 fs-6"></i>
	<span class="text-muted">{{ __('general.billing_information') }}</span>
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

					<form method="POST" action="{{ url('panel/admin/billing') }}" enctype="multipart/form-data">
						@csrf

						<div class="row mb-3">
							<label class="col-sm-2 col-form-label text-lg-end">{{ __('general.company') }}</label>
							<div class="col-sm-10">
								<input type="text" value="{{ $settings->company }}" name="company" class="form-control">
							</div>
						</div>

						<div class="row mb-3">
							<label for="select" class="col-sm-2 col-form-labe text-lg-end">{{
								__('general.select_your_country') }}</label>
							<div class="col-sm-10">
								<select name="country" class="form-select" id="select">
									<option value="">{{trans('general.select_your_country')}}</option>
									@foreach (Countries::orderBy('country_name')->get() as $country)
									<option @if ($settings->country == $country->country_name) selected="selected"
										@endif value="{{$country->country_name}}">{{ $country->country_name }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="row mb-3">
							<label class="col-sm-2 col-form-label text-lg-end">{{ __('general.address') }}</label>
							<div class="col-sm-10">
								<input type="text" value="{{ $settings->address }}" name="address" class="form-control">
							</div>
						</div>

						<div class="row mb-3">
							<label class="col-sm-2 col-form-label text-lg-end">{{ __('general.city') }}</label>
							<div class="col-sm-10">
								<input type="text" value="{{ $settings->city }}" name="city" class="form-control">
							</div>
						</div>

						<div class="row mb-3">
							<label class="col-sm-2 col-form-label text-lg-end">{{ __('general.zip') }}</label>
							<div class="col-sm-10">
								<input type="text" value="{{ $settings->zip }}" name="zip" class="form-control">
							</div>
						</div>

						<div class="row mb-3">
							<label class="col-sm-2 col-form-label text-lg-end">{{ __('general.vat') }}</label>
							<div class="col-sm-10">
								<input type="text" value="{{ $settings->vat }}" name="vat" class="form-control">
							</div>
						</div>

						<fieldset class="row mb-3">
							<legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.show_address_company_name') }}</legend>
							<div class="col-sm-10">
							  <div class="form-check form-switch form-switch-md">
							   <input class="form-check-input" type="checkbox" name="show_address_company_footer" @checked($settings->show_address_company_footer) value="1" role="switch">
							 </div>
							</div>
						  </fieldset>

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

<script type="text/javascript"></script>
@endsection