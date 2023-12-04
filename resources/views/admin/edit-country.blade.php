@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <a class="text-reset" href="{{ url('panel/admin/countries') }}">{{ __('general.countries') }}</a>
			<i class="bi-chevron-right me-1 fs-6"></i>
			<span class="text-muted">{{ __('admin.edit') }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

              @include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form method="post" action="{{ url('panel/admin/countries/update') }}" enctype="multipart/form-data">
						 @csrf
						 <input type="hidden" name="id" value="{{ $country->id }}">

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.name') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $country->country_name }}" name="name" type="text" class="form-control">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.iso_code') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $country->country_code }}" name="iso_code" type="text" class="form-control">
								<small class="d-block">{{ __('general.iso_code_country') }} <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank">(ISO 3166-1 alpha-2) <i class="bi-box-arrow-up-right ms-1"></i></a></small>
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
