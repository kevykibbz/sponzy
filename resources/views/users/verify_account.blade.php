@extends('layouts.app')

@section('title') {{trans('general.verify_account')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="feather icon-check-circle mr-2"></i> {{trans('general.verify_account')}}</h2>
          <p class="lead text-muted mt-0">{{Auth::user()->verified_id != 'yes' ? trans('general.verified_account_desc') : trans('general.verified_account')}}</p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          @if (session('status'))
                  <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                			<span aria-hidden="true">×</span>
                			</button>

                    {{ session('status') }}
                  </div>
                @endif

          @include('errors.errors-forms')

        @if ($settings->requests_verify_account == 'on'
            && auth()->user()->verified_id != 'yes'
            && auth()->user()->verificationRequests() != 1
            && auth()->user()->verified_id != 'reject')

            @if (auth()->user()->countries_id != ''
                && auth()->user()->birthdate != ''
                && auth()->user()->cover != ''
                && auth()->user()->cover != $settings->cover_default
                && auth()->user()->avatar != $settings->avatar
              )

          <div class="alert alert-warning mr-1">
          <span class="alert-inner--text"><i class="fa fa-exclamation-triangle"></i> {{trans('general.warning_verification_info')}}</span>
        </div>

          <form method="POST" id="formVerify" action="{{ url('settings/verify/account') }}" accept-charset="UTF-8" enctype="multipart/form-data">

            @csrf

          <div class="form-group">
            <div class="input-group mb-4">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-map-marked-alt"></i></span>
              </div>
              <input class="form-control" name="address" placeholder="{{trans('general.address')}}" value="{{old('address')}}" type="text">
            </div>
            </div>

            <div class="form-group">
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-map-pin"></i></span>
                  </div>
                  <input class="form-control" name="city" placeholder="{{trans('general.city')}}" value="{{old('city')}}" type="text">
                </div>
              </div>

              @if ($settings->zip_verification_creator)
              <div class="form-group">
                  <div class="input-group mb-4">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                    </div>
                    <input class="form-control" name="zip" placeholder="{{trans('general.zip')}}" value="{{old('zip')}}" type="text">
                  </div>
                </div>
                @endif

                @if (auth()->user()->countries_id == 1)
                  <div class="mb-5 text-center">

                    <h6 class="text-muted">{{trans('general.form_w9_required')}}</h6>
                      <input type="file" name="form_w9" id="fileVerifiyAccountFormW9" accept="application/pdf" class="visibility-hidden">
                      <button class="btn btn-1 w-100 btn-outline-primary mb-2 border-dashed" type="button" id="btnFileFormW9">{{trans('general.upload_form_w9')}} (PDF) {{trans('general.maximum')}}: {{Helper::formatBytes($settings->file_size_allowed_verify_account * 1024)}}</button>
                      <span class="btn-block mb-2" id="previewImageFormW9"></span>

                    <h6 class="btn-block text-center font-weight-bold">
                      <a href="https://www.irs.gov/pub/irs-pdf/fw9.pdf" target="_blank">{{ __('general.complete_form_W9_here') }} <i class="feather icon-external-link"></i></a>
                    </h6>
                  </div>
                @endif
                <!-- Image Front -->
                <div class="mb-5 text-center previewImageVerification">
                  <h6 class="text-muted">{{trans('general.info_verification_user')}}</h6>
                    <input type="file" name="image" id="fileVerifiyAccount" accept="image/*" class="fileVerifiyAccount visibility-hidden">
                    <button class="btn btn-1 w-100 btn-outline-primary mb-2 border-dashed btnFilePhoto" type="button" id="btnFilePhoto">{{trans('general.upload_image')}} (JPG, PNG, GIF) - {{trans('general.maximum')}}: {{Helper::formatBytes($settings->file_size_allowed_verify_account * 1024)}}</button>
                    <span class="btn-block mb-2 previewImage" id="previewImage"></span>
                </div>

                <!-- Image Reverse -->
                <div class="mb-5 text-center previewImageVerification">
                  <h6 class="text-muted">{{trans('general.info_verification_user_reverse_id')}}</h6>
                    <input type="file" name="image_reverse" id="fileVerifiyAccount" accept="image/*" class="fileVerifiyAccount visibility-hidden">
                    <button class="btn btn-1 w-100 btn-outline-primary mb-2 border-dashed btnFilePhoto" type="button" id="btnFilePhoto">{{trans('general.upload_image')}} (JPG, PNG, GIF) - {{trans('general.maximum')}}: {{Helper::formatBytes($settings->file_size_allowed_verify_account * 1024)}}</button>
                    <span class="btn-block mb-2 previewImage" id="previewImage"></span>
                </div>

                <!-- Image Selfie -->
                <div class="mb-3 text-center previewImageVerification">
                  <h6 class="text-muted">{{trans('general.info_verification_user_selfie', ['sitename' => $settings->title])}}</h6>
                    <input type="file" name="image_selfie" id="fileVerifiyAccount" accept="image/*" class="fileVerifiyAccount visibility-hidden">
                    <button class="btn btn-1 w-100 btn-outline-primary mb-2 border-dashed btnFilePhoto" type="button" id="btnFilePhoto">{{trans('general.upload_image')}} (JPG, PNG, GIF) - {{trans('general.maximum')}}: {{Helper::formatBytes($settings->file_size_allowed_verify_account * 1024)}}</button>
                    <span class="btn-block mb-2 previewImage" id="previewImage"></span>
                </div>

                <button class="btn btn-1 btn-success btn-block mt-5" id="sendData" type="submit">{{trans('general.send_approval')}}</button>
          </form>

        @else

          <div class="alert alert-danger">
          <span class="alert-inner--text"><i class="fa fa-exclamation-triangle mr-1"></i> {{trans('general.complete_profile_alert')}}</span>

          <ul class="list-unstyled">
            <br>

            @if (auth()->user()->avatar == $settings->avatar)
              <li>
                <i class="far fa-times-circle"></i> {{ __('general.set_avatar') }} <a href="{{ url(auth()->user()->username) }}" class="text-white link-border">{{ __('general.upload') }} <i class="feather icon-arrow-right"></i></a>
              </li>
            @endif

            @if (auth()->user()->cover == '' || auth()->user()->cover == $settings->cover_default)
            <li>
              <i class="far fa-times-circle"></i> {{ __('general.set_cover') }} <a href="{{ url(auth()->user()->username) }}" class="text-white link-border">{{ __('general.upload') }} <i class="feather icon-arrow-right"></i></a>
            </li>
          @endif

          @if (auth()->user()->countries_id == '')
            <li>
              <i class="far fa-times-circle"></i> {{ __('general.set_country') }} <a href="{{ url('settings/page') }}" class="text-white link-border">{{ __('admin.edit') }} <i class="feather icon-arrow-right"></i></a>
            </li>
            @endif

            @if (auth()->user()->birthdate == '')
            <li>
              <i class="far fa-times-circle"></i> {{ __('general.set_birthdate') }} <a href="{{ url('settings/page') }}" class="text-white link-border">{{ __('admin.edit') }} <i class="feather icon-arrow-right"></i></a>
            </li>
          @endif
          </ul>
        </div>

            @endif

        @elseif (auth()->user()->verificationRequests() == 1)
          <div class="alert alert-primary alert-dismissible text-center fade show" role="alert">
            <span class="alert-inner--icon mr-2"><i class="fa fa-info-circle"></i></span>
          <span class="alert-inner--text">{{trans('admin.pending_request_verify')}}</span>
        </div>
      @elseif (auth()->user()->verified_id == 'reject')
        <div class="alert alert-danger alert-dismissible text-center fade show" role="alert">
          <span class="alert-inner--icon mr-2"><i class="fa fa-info-circle"></i></span>
        <span class="alert-inner--text">{{trans('admin.rejected_request')}}</span>
      </div>
    @elseif (auth()->user()->verified_id != 'yes' && $settings->requests_verify_account == 'off')
      <div class="alert alert-primary alert-dismissible text-center fade show" role="alert">
        <span class="alert-inner--icon mr-2"><i class="fa fa-info-circle"></i></span>
      <span class="alert-inner--text">{{trans('general.info_receive_verification_requests')}}</span>
    </div>

        @else
          <div class="alert alert-success alert-dismissible text-center fade show" role="alert">
            <span class="alert-inner--icon mr-2"><i class="feather icon-check-circle"></i></span>
          <span class="alert-inner--text">{{trans('general.verified_account_success')}}</span>
        </div>

        @endif

        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection
