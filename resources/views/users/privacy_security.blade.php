@extends('layouts.app')

@section('title') {{trans('general.privacy_security')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="bi bi-shield-check mr-2"></i> {{trans('general.privacy_security')}}</h2>
          <p class="lead text-muted mt-0">{{trans('general.desc_privacy')}}</p>
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

          <h5>{{ __('general.login_sessions') }}</h5>
              <div class="card mb-4">
                <div class="card-body">

                  @if ($agents->count() || $currentSession)
                  <small class="w-100 d-block"><strong>{{ __('general.last_login_record') }}</strong></small>

                  @if ($currentSession)
                  <p class="card-text mb-4 border-bottom pb-2">
                    <i class="bi-{{ $currentSession->device_type == 'phone' ? 'phone' : 'display' }} mr-1"></i> 
                    <strong>{{ $currentSession->getNameBrowser() }} {{ __('general.on') }} {{ $currentSession->getNamePlatform() }}{{ $currentSession->device_type == 'phone' ? ', '.$currentSession->device : null }}</strong>
                  <span class="badge badge-pill badge-success">{{ __('general.active_now') }}</span>

                  <small class="text-muted w-100 d-block mt-2 mb-0">
                    {{ $currentSession->ip }} - {{ $currentSession->country ? $currentSession->country.' - ' : null }} <span class="timeAgo" data="{{date('c', strtotime($currentSession->updated_at))}}"></span> 
                  </small> 
                  </p>
                  @endif
                  
                  @foreach ($agents as $agent)
                  <p class="card-text mb-1">
                    <i class="bi-{{ $agent->device_type == 'phone' ? 'phone' : 'display' }} mr-1"></i> 
                    <strong>{{ $agent->getNameBrowser() }} {{ __('general.on') }}  {{ $agent->getNamePlatform() }} {{ $agent->device_type == 'phone' ? ', '.$agent->device : null }}</strong> 
                  </p>
                  <small class="text-muted w-100 d-block mb-2">
                    {{ $agent->ip }} - {{ $agent->country ? $agent->country.' - ' : null }} <span class="timeAgo" data="{{date('c', strtotime($agent->updated_at))}}"></span> 
                  </small> 
                  @endforeach
                
                  <small class="text-muted w-100 d-block my-3 font-weight-bold"> <i class="bi-exclamation-triangle mr-1"></i> {{ __('general.login_session_alert') }}</small> 

                  @if ($agents->count() != 0)
                  <a href="#" class="btn btn-sm btn-danger mt-2" data-toggle="modal" data-target="#logoutDevices">
                    <i class="bi-x-circle mr-1"></i> {{ __('general.close_all_sessions') }}
                  </a>

                  @include('includes.modal-logout-devices')

                  @endif

                  @else
                   {{ __('general.no_results_found') }}
                  @endif
                </div>
              </div>

          @if (auth()->user()->verified_id == 'yes')

            <h5>{{ __('general.privacy') }}</h5>

            <form method="POST" action="{{ url('privacy/security') }}">

              @csrf

              <div class="form-group">
                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="hide_profile" value="yes" @if (auth()->user()->hide_profile == 'yes') checked @endif id="customSwitch1">
                    <label class="custom-control-label switch" for="customSwitch1">{{ __('general.hide_profile') }} {{ __('general.info_hide_profile') }}</label>
                  </div>
                </div>

                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="hide_last_seen" value="yes" @if (auth()->user()->hide_last_seen == 'yes') checked @endif id="customSwitch2">
                    <label class="custom-control-label switch" for="customSwitch2">{{ __('general.hide_last_seen') }}</label>
                  </div>
                </div>

                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="active_status_online" value="yes" @if (auth()->user()->active_status_online == 'yes') checked @endif id="customSwitch6">
                    <label class="custom-control-label switch" for="customSwitch6">{{ __('general.active_status_online') }}</label>
                  </div>
                </div>

                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="hide_count_subscribers" value="yes" @if (auth()->user()->hide_count_subscribers == 'yes') checked @endif id="customSwitch3">
                    <label class="custom-control-label switch" for="customSwitch3">{{ __('general.hide_count_subscribers') }}</label>
                  </div>
                </div>

                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="hide_my_country" value="yes" @if (auth()->user()->hide_my_country == 'yes') checked @endif id="customSwitch4">
                    <label class="custom-control-label switch" for="customSwitch4">{{ __('general.hide_my_country') }}</label>
                  </div>
                </div>

                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="show_my_birthdate" value="yes" @if (auth()->user()->show_my_birthdate == 'yes') checked @endif id="customSwitch5">
                    <label class="custom-control-label switch" for="customSwitch5">{{ __('general.show_my_birthdate') }}</label>
                  </div>
                </div>

                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="posts_privacy" value="1" @if (auth()->user()->posts_privacy) checked @endif id="posts_privacy">
                    <label class="custom-control-label switch" for="posts_privacy">{{ __('general.posts_privacy') }}</label>
                  </div>
                </div>

                <h5 class="mt-5">{{ __('general.security') }}</h5>

                <div class="btn-block mb-4">
                  <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" class="custom-control-input" name="two_factor_auth" value="yes" @if (auth()->user()->two_factor_auth == 'yes') checked @endif id="customSwitch7">
                    <label class="custom-control-label switch" for="customSwitch7">
                      {{ __('general.two_step_auth') }}
                      <i class="bi bi-info-circle text-muted" data-toggle="tooltip" data-placement="top" title="{{trans('general.two_step_auth_info')}}"></i>
                    </label>
                  </div>
                </div>
              </div><!-- End form-group -->

              <button class="btn btn-1 btn-success btn-block" onClick="this.form.submit(); this.disabled=true; this.innerText='{{ __('general.please_wait')}}';" type="submit">{{ __('general.save_changes')}}</button>

            </form>
          @endif

          @if (! auth()->user()->isSuperAdmin())
          <h5 class="mt-5">{{ __('general.delete_account') }}</h5>
          <small class="w-100">{{ __('general.delete_account_alert') }}</small>

          <div class="w-100 d-block mt-2 mb-5">
            <a class="btn btn-main btn-danger pr-3 pl-3" href="{{ url('account/delete') }}">
              <i class="feather icon-user-x mr-1"></i> {{ __('general.delete_account') }}</small>
            </a>
          </div>
        @endif

        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection
