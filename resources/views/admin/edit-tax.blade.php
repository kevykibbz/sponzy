@extends('admin.layout')

@section('css')
<link href="{{ asset('public/js/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/js/select2/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <a class="text-reset" href="{{ url('panel/admin/tax-rates') }}">{{ __('general.tax_rates') }}</a>
			<i class="bi-chevron-right me-1 fs-6"></i>
			<span class="text-muted">{{ __('admin.edit') }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

     @include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form method="post" action="{{ url('panel/admin/tax-rates/update') }}" enctype="multipart/form-data">
						 @csrf
						 <input type="hidden" name="id" value="{{ $tax->id }}">

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.name') }}</label>
		          <div class="col-sm-10">
		            <input  value="{{ $tax->name }}" name="name" type="text" class="form-control" placeholder="(VAT, GST, IVA)">
		          </div>
		        </div>

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.country') }}</label>
		          <div class="col-sm-10">
		            <select disabled name="country" class="form-select select" id="country">
									@foreach (Countries::orderBy('country_name')->get() as $country)
										<option @if ($tax->country == $country->country_code) selected @endif value="{{$country->id}}">{{ $country->country_name }}</option>
									@endforeach
		           </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.state') }}</label>
		          <div class="col-sm-10">
		            <select disabled name="state" class="form-select" id="state">

									@if ($tax->country()->states()->count())

										@foreach ($tax->country()->states()->get() as $state)
											<option @if ($tax->state && $tax->state == $state->name) selected @endif value="{{ $state->code }}">{{ $state->name }}</option>
										@endforeach

									@endif

									<option value="" @if(! $tax->state) selected @endif>{{ __('general.all_states') }}</option>
		           </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.percentage') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $tax->percentage }}" disabled name="percentage" type="text" class="form-control isNumber" autocomplete="off">
		          </div>
		        </div>

						<fieldset class="row mb-3">
		          <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.enabled') }}</legend>
		          <div class="col-sm-10">
		            <div class="form-check form-switch form-switch-md">
		             <input class="form-check-input" type="checkbox" name="status" @if ($tax->status) checked="checked" @endif value="1" role="switch">
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

@section('javascript')

<script type="text/javascript">
$('#country').change(function () {
	var id = $(this).find(':selected').val();
	$.ajax({
		headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type: 'POST',
			url: URL_BASE+'/panel/admin/ajax/states',
			data: {
					'id': id
			},
			success: function (data) {
					// the next thing you want to do
					var $state = $('#state');
					$state.empty();

					for (var i = 0; i < data.length; i++) {
							$state.append('<option value=' + data[i].code + '>' + data[i].name + '</option>');
					}

					$state.append('<option value="">{{ __('general.all_states') }}</option>');
			}
	});
});
</script>
  @endsection
