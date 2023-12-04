@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.theme') }}</span>
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

					 <form method="post" action="{{{ url('panel/admin/theme') }}}" enctype="multipart/form-data">
             @csrf

						 <fieldset class="row mb-5">
			         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.home_style') }}</legend>
			         <div class="col-sm-10">
			           <div class="form-check mb-3">
			             <input class="form-check-input" type="radio" name="home_style" id="radio1" @if ($settings->home_style == 0) checked="checked" @endif value="0">
			             <label class="form-check-label" for="radio1">
			               <img class="border" src="{{url('/public/img/homepage-1.jpg')}}">
			             </label>
			           </div>
			           <div class="form-check">
			             <input class="form-check-input" type="radio" name="home_style" id="radio2" @if ($settings->home_style == 1) checked="checked" @endif value="1">
			             <label class="form-check-label" for="radio2">
							<img class="border" src="{{url('/public/img/homepage-2.jpg')}}">
			             </label>
			           </div>
			         </div>
			       </fieldset><!-- end row -->

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.logo') }}</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img', $settings->logo)}}" class="bg-secondary" style="width:150px">
                </div>

                <div class="input-group mb-1">
                  <input name="logo" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('general.recommended_size') }} 487x144 px (PNG, SVG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.logo_blue') }}</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img', $settings->logo_2)}}" style="width:150px">
                </div>

                <div class="input-group mb-1">
                  <input name="logo_2" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('general.recommended_size') }} 487x144 px (PNG, SVG)</small>
		          </div>
		        </div>

				<div class="row mb-3">
					<label class="col-sm-2 col-form-label text-lg-end">{{ __('general.watermak_video') }}</label>
					<div class="col-lg-5 col-sm-10">
				  <div class="d-block mb-2">
					<img src="{{url('/public/img', $settings->watermak_video)}}" class="bg-dark" style="width:150px">
				  </div>
  
				  <div class="input-group mb-1">
					<input name="watermak_video" type="file" class="form-control custom-file rounded-pill">
				  </div>
				  <small class="d-block">{{ __('general.recommended_size') }} 487x144 px (PNG)</small>
					</div>
				  </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Favicon</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img', $settings->favicon)}}">
                </div>

                <div class="input-group mb-1">
                  <input name="favicon" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('general.recommended_size') }} 48x48 px (PNG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.index_image_top') }}</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img', $settings->home_index)}}" style="width:200px">
                </div>

                <div class="input-group mb-1">
                  <input name="index_image_top" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('general.recommended_size') }} 884x592 px</small>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.background') }}</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img class="img-fluid" src="{{ url('/public/img', $settings->bg_gradient) }}" style="width:400px">
                </div>

                <div class="input-group mb-1">
                  <input name="background" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('general.recommended_size') }} 1441x480 px</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.image_index_1') }}</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img', $settings->img_1)}}" style="width:120px">
                </div>

                <div class="input-group mb-1">
                  <input name="image_index_1" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('general.recommended_size') }} 120x120 px</small>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.image_index_2') }}</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img', $settings->img_2)}}" style="width:120px">
                </div>

                <div class="input-group mb-1">
                  <input name="image_index_2" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('general.recommended_size') }} 120x120 px</small>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.image_index_3') }}</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img', $settings->img_3)}}" style="width:120px">
                </div>

                <div class="input-group mb-1">
                  <input name="image_index_3" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('general.recommended_size') }} 120x120 px</small>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.image_index_4') }}</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img', $settings->img_4)}}" style="width:120px">
                </div>

                <div class="input-group mb-1">
                  <input name="image_index_4" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('general.recommended_size') }} 362x433 px</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.avatar_default') }}</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{ Helper::getFile(config('path.avatar').$settings->avatar) }}" style="width:200px">
                </div>

                <div class="input-group mb-1">
                  <input name="avatar" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('general.recommended_size') }} 250x250 px</small>
		          </div>
		        </div>

            <div class="row mb-4">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.cover_default') }}</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <div style="max-width: 400px; height: 150px; margin-bottom: 10px; display: block; border-radius: 6px; background: #505050 @if ($settings->cover_default) url('{{ Helper::getFile(config('path.cover').$settings->cover_default) }}') no-repeat center center; background-size: cover; @endif ;">
                </div>

                <div class="input-group mb-1">
                  <input name="cover_default" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('general.recommended_size') }} 1500x800 px</small>
		          </div>
		        </div>
						</div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.default_color') }}</label>
		          <div class="col-sm-10">
                <input type="color" name="color" class="form-control form-control-color" value="{{ $settings->color_default }}">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.navbar_background_color') }}</label>
		          <div class="col-sm-10">
                <input type="color" name="navbar_background_color" class="form-control form-control-color" value="{{ $settings->navbar_background_color }}">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.navbar_text_color') }}</label>
		          <div class="col-sm-10">
                <input type="color" name="navbar_text_color" class="form-control form-control-color" value="{{ $settings->navbar_text_color }}">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.footer_background_color') }}</label>
		          <div class="col-sm-10">
                <input type="color" name="footer_background_color" class="form-control form-control-color" value="{{ $settings->footer_background_color }}">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.footer_text_color') }}</label>
		          <div class="col-sm-10">
                <input type="color" name="footer_text_color" class="form-control form-control-color" value="{{ $settings->footer_text_color }}">
		          </div>
		        </div>

						<fieldset class="row mb-3">
							<legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.button_style') }}</legend>
							<div class="col-sm-10">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="button_style" id="button_style1" @if ($settings->button_style == 'rounded') checked="checked" @endif value="rounded" checked>
									<label class="form-check-label" for="button_style1">
										{{ trans('general.button_style_rounded') }}
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="button_style" id="button_style2" @if ($settings->button_style == 'normal') checked="checked" @endif value="normal">
									<label class="form-check-label" for="button_style2">
										{{ trans('admin.normal') }}
									</label>
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
