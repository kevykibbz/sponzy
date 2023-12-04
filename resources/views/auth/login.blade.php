@extends('layouts.app')

@section('title') {{__('auth.login')}} -@endsection

@section('content')
  <div class="jumbotron home m-0 bg-gradient">
    <div class="container pt-lg-md">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <div class="card bg-white shadow border-0 b-radio-custom">

            <div class="card-body px-lg-5 py-lg-5">

              <h4 class="text-center mb-0 font-weight-bold">
                {{__('auth.welcome_back')}}
              </h4>
              <small class="btn-block text-center mt-2 mb-4">{{ __('auth.login_welcome') }}</small>

              @if (session('login_required'))
    			<div class="alert alert-danger" id="dangerAlert">
                		<i class="fa fa-exclamation-triangle"></i> {{__('auth.login_required')}}
                		</div>
                	@endif

              @include('errors.errors-forms')

              @if ($settings->facebook_login == 'on' || $settings->google_login == 'on' || $settings->twitter_login == 'on')
              <div class="mb-2 w-100">

                @if ($settings->facebook_login == 'on')
                  <a href="{{url('oauth/facebook')}}" class="btn btn-facebook auth-form-btn flex-grow mb-2 w-100">
                    <i class="fab fa-facebook mr-2"></i> {{ __('auth.login_with') }} Facebook
                  </a>
                @endif

                @if ($settings->twitter_login == 'on')
                <a href="{{url('oauth/twitter')}}" class="btn btn-twitter auth-form-btn mb-2 w-100">
                  <i class="bi-twitter-x mr-2"></i> {{ __('auth.login_with') }} Twitter
                </a>
              @endif

                  @if ($settings->google_login == 'on')
                  <a href="{{url('oauth/google')}}" class="btn btn-google auth-form-btn flex-grow w-100">
                    <img src="{{ url('public/img/google.svg') }}" class="mr-2" width="18" height="18"> {{ __('auth.login_with') }} Google
                  </a>
                @endif
                </div>

                @if (! $settings->disable_login_register_email)
                  <small class="btn-block text-center my-3 text-uppercase or">{{__('general.or')}}</small>
                @endif

              @endif

              @if (! $settings->disable_login_register_email || request()->route()->named('login.admin'))

              <form method="POST" action="{{ route('login') }}" id="formLoginRegister" enctype="multipart/form-data">
                  @csrf

                  <input type="hidden" name="return" value="{{ count($errors) > 0 ? old('return') : url()->previous() }}">

                <div class="form-group mb-3">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-mail"></i></span>
                    </div>
                    <input class="form-control" required value="{{ old('username_email') }}" placeholder="{{ __('auth.username_or_email') }}" name="username_email" type="text">

                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-alternative" id="showHidePassword">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="iconmoon icon-Key"></i></span>
                    </div>
                    <input name="password" required type="password" class="form-control" placeholder="{{ __('auth.password') }}">
                    <div class="input-group-append">
                      <span class="input-group-text c-pointer"><i class="feather icon-eye-off"></i></span>
                  </div>
                </div>
                </div>

                <div class="custom-control custom-control-alternative custom-checkbox">
                  <input class="custom-control-input" id=" customCheckLogin" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="custom-control-label" for=" customCheckLogin">
                    <span>{{__('auth.remember_me')}}</span>
                  </label>
                </div>

                <div class="alert alert-danger display-none mb-0 mt-3" id="errorLogin">
                    <ul class="list-unstyled m-0" id="showErrorsLogin"></ul>
                  </div>

                <div class="text-center">
                  @if ($settings->captcha == 'on')
                  {!! NoCaptcha::displaySubmit('formLoginRegister', '<i></i> '.__('auth.login'), ['data-size' => 'invisible', 'id' => 'btnLoginRegister', 'class' => 'btn btn-primary mt-4 w-100']) !!}

                  {!! NoCaptcha::renderJs() !!}

                  @else
                  <button id="btnLoginRegister" type="submit" class="btn btn-primary mt-4 w-100">
                    <i></i> {{__('auth.login')}}
                  </button>

                  @endif
                  

                  
                  
                  
                </div>
              </form>

              @if ($settings->captcha == 'on')
                <small class="btn-block text-center mt-3">{{__('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{__('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{__('general.terms')}}</a></small>
              @endif

          @endif

            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <a href="{{url('password/reset')}}" class="text-light">
                <small>{{__('auth.forgot_password')}}</small>
              </a>
            </div>
            @if ($settings->registration_active == '1')
            <div class="col-6 text-right">
              <a href="{{url('signup')}}" class="text-light">
                <small>{{__('auth.not_have_account')}}</small>
              </a>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
