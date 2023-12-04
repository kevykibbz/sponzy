@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('general.video_encoding') }}</span>
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

					 <form method="POST" action="{{ url('panel/admin/video/encoding') }}" enctype="multipart/form-data">
						 @csrf

				<fieldset class="row mb-3">
			         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('admin.status') }} <i class="bi-info-circle showTooltip ms-1" title="{{ __('general.video_encoding_alert') }} {{ __('general.or_activate_coconut') }}"></i></legend>
			         <div class="col-sm-10">
			           <div class="form-check form-switch form-switch-md">
			            <input class="form-check-input" type="checkbox" name="video_encoding" @if ($settings->video_encoding == 'on') checked="checked" @endif value="on" role="switch">
			          </div>
			         </div>
			       </fieldset><!-- end row -->

                   <fieldset class="row mb-3">
                    <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.encoding_method') }}</legend>
                    <div class="col-sm-10">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="encoding_method" id="radioWho1" @if ($settings->encoding_method == 'ffmpeg') checked="checked" @endif value="ffmpeg">
                        <label class="form-check-label" for="radioWho1">
                          FFMPEG
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="encoding_method" id="radioWho2" @if ($settings->encoding_method == 'coconut') checked="checked" @endif value="coconut">
                        <label class="form-check-label" for="radioWho2">
                        Coconut®
                        </label>
                      </div>
                    </div>
                  </fieldset><!-- end row -->

                   <fieldset class="row mb-4">
                    <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.watermark_on_videos') }}</legend>
                    <div class="col-sm-10">
                      <div class="form-check form-switch form-switch-md">
                       <input class="form-check-input" type="checkbox" name="watermark_on_videos" @if ($settings->watermark_on_videos == 'on') checked="checked" @endif value="on" role="switch">
                     </div>
                    </div>
                  </fieldset><!-- end row -->

                  <fieldset class="row mb-3">
                    <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.watermark_position') }} (Coconut®)</legend>
                    <div class="col-sm-10">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="watermark_position" id="watermark_position1" @checked($settings->watermark_position == 'center') value="center">
                        <label class="form-check-label" for="watermark_position1">
                          {{ __('general.position_center') }}
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="watermark_position" id="watermark_position2" @checked($settings->watermark_position == 'bottomleft') value="bottomleft">
                        <label class="form-check-label" for="watermark_position2">
                        {{ __('general.position_bottomleft') }}
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="watermark_position" id="watermark_position3" @checked($settings->watermark_position == 'bottomright') value="bottomright">
                        <label class="form-check-label" for="watermark_position3">
                        {{ __('general.position_bottomright') }}
                        </label>
                      </div>
                    </div>
                  </fieldset><!-- end row -->

              <div class="row mb-3">
 		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.ffmpeg_path') }}</label>
 		          <div class="col-sm-10">
 		            <input value="{{ config('laravel-ffmpeg.ffmpeg.binaries') }}" name="ffmpeg_path" type="text" class="form-control">
                     <p class="d-block m-0">
                        <a href="{{ url('ffmpeg.php') }}" target="_blank" rel="noopener noreferrer">
                            {{ __('general.verify_ffmpeg_path') }} <i class="bi-box-arrow-up-right ms-1"></i>
                        </a>
                    </p>
 		          </div>
 		        </div>

             <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.ffprobe_path') }}</label>
              <div class="col-sm-10">
                <input value="{{ config('laravel-ffmpeg.ffprobe.binaries') }}" name="ffprobe_path" type="text" class="form-control">
              </div>
            </div>

				<div class="row mb-3">
 		          <label class="col-sm-2 col-form-label text-lg-end">Coconut® API Keys</label>
 		          <div class="col-sm-10">
 		            <input value="{{ $settings->coconut_key }}" name="coconut_key" type="text" class="form-control">
                     <p class="d-block m-0">
                        <a href="https://app.coconut.co/api" target="_blank" rel="noopener noreferrer">
                            https://app.coconut.co/api <i class="bi-box-arrow-up-right ms-1"></i>
                        </a>
                    </p>
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
