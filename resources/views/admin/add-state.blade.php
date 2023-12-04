@extends('admin.layout')

@section('css')
<link href="{{ asset('public/js/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/js/select2/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <a class="text-reset" href="{{ url('panel/admin/states') }}">{{ __('general.states') }}</a>
			<i class="bi-chevron-right me-1 fs-6"></i>
			<span class="text-muted">{{ __('general.add_new') }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

              @include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					<div class="row">
						<div class="col-sm-10 offset-sm-2">
							<div class="alert alert-info py-2">
							 <i class="bi-info-circle me-2"></i> {{ __('general.alert_store_state') }}
							</div>
						</div>
					</div>

					 <form method="post" action="{{ url('panel/admin/states/add') }}" enctype="multipart/form-data">
						 @csrf

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.country') }}</label>
		          <div class="col-sm-10">
		            <select name="country" class="form-select select" id="country">
									@foreach (Countries::orderBy('country_name')->get() as $country)
										<option value="{{$country->id}}">{{ $country->country_name }}</option>
									@endforeach
		           </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.name') }}</label>
		          <div class="col-sm-10">
		            <input  value="{{ old('name') }}" name="name" type="text" class="form-control">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.iso_code') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ old('iso_code') }}" name="iso_code" type="text" class="form-control">
								<small class="d-block">{{ __('general.iso_code_states') }} <a href="https://en.wikipedia.org/wiki/ISO_3166-2" target="_blank">(ISO 3166-2 subdivision code) <i class="bi-box-arrow-up-right ms-1"></i></a></small>
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
