@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('general.shop') }}</span>
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

					 <form method="POST" action="{{ url('panel/admin/shop') }}" enctype="multipart/form-data">
						 @csrf

						 <fieldset class="row mb-3">
			         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('admin.status') }}</legend>
			         <div class="col-sm-10">
			           <div class="form-check form-switch form-switch-md">
			            <input class="form-check-input" type="checkbox" name="shop" @if ($settings->shop) checked="checked" @endif value="1" role="switch">
			          </div>
			         </div>
			       </fieldset><!-- end row -->

						 <div class="row mt-3 mb-2">
							<div class="col-sm-10 offset-sm-2">
								<h6>{{ __('general.type_sale') }}</h6>
							</div>
						</div>

						 <div class="row mb-2">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="digital_product_sale" value="1" type="checkbox" id="digital_product_sale" @if ($settings->digital_product_sale) checked="checked" @endif>
									<label class="form-check-label" for="digital_product_sale">
										{{ __('general.digital_products') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-2">
						 <div class="col-sm-10 offset-sm-2">
							 <div class="form-check">
								 <input class="form-check-input check" name="custom_content" value="1" type="checkbox" id="custom_content" @if ($settings->custom_content) checked="checked" @endif>
								 <label class="form-check-label" for="custom_content">
									 {{ __('general.custom_content') }}
								 </label>
							 </div>
						 </div>
					 </div>

					 <div class="row mb-3">
						<div class="col-sm-10 offset-sm-2">
							<div class="form-check">
								<input class="form-check-input check" name="physical_products" value="1" type="checkbox" id="physical_products" @if ($settings->physical_products) checked="checked" @endif>
								<label class="form-check-label" for="physical_products">
									{{ __('general.physical_products') }}
								</label>
							</div>
						</div>
					</div>

 						<div class="row mb-3">
 							<label class="col-sm-2 col-form-label text-lg-end">{{ __('general.minimum_price_of_sale') }}</label>
 							<div class="col-sm-10">
 								<input value="{{ $settings->min_price_product }}" name="min_price_product" type="number" min="1" class="form-control onlyNumber" autocomplete="off">
 							</div>
 						</div>

						<div class="row mb-3">
 							<label class="col-sm-2 col-form-label text-lg-end">{{ __('general.maximum_price_of_sale') }}</label>
 							<div class="col-sm-10">
 								<input value="{{ $settings->max_price_product }}" name="max_price_product" type="number" min="1" class="form-control onlyNumber" autocomplete="off">
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
