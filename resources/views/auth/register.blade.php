@extends('layouts.app')

@section('title') {{__('auth.sign_up')}} -@endsection

@section('content')
  <div class="jumbotron home m-0 bg-gradient">
    <div class="container pt-lg-md">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <div class="card bg-white shadow border-0 b-radio-custom">

            <div class="card-body px-lg-5 py-lg-5">

              <h4 class="text-center mb-0 font-weight-bold">
                {{__('auth.sign_up')}}
              </h4>
              <small class="btn-block text-center mt-2 mb-4">{{ __('auth.signup_welcome') }}</small>

              @if (session('status'))
                      <div class="alert alert-success">
                        {{ session('status') }}
                      </div>
                    @endif

              @include('errors.errors-forms')

              @if($settings->facebook_login == 'on' || $settings->google_login == 'on' || $settings->twitter_login == 'on')
              <div class="mb-2 w-100">

                @if ($settings->facebook_login == 'on')
                  <a href="{{url('oauth/facebook')}}" class="btn btn-facebook auth-form-btn flex-grow mb-2 w-100">
                    <i class="fab fa-facebook mr-2"></i> {{ __('auth.sign_up_with') }} Facebook
                  </a>
                @endif

                @if ($settings->twitter_login == 'on')
                <a href="{{url('oauth/twitter')}}" class="btn btn-twitter auth-form-btn mb-2 w-100">
                  <i class="bi-twitter-x mr-2"></i> {{ __('auth.sign_up_with') }} Twitter
                </a>
              @endif

                  @if ($settings->google_login == 'on')
                  <a href="{{url('oauth/google')}}" class="btn btn-google auth-form-btn flex-grow w-100">
                    <img src="{{ url('public/img/google.svg') }}" class="mr-2" width="18" height="18"> {{ __('auth.sign_up_with') }} Google
                  </a>
                @endif
                </div>

                @if (! $settings->disable_login_register_email)
                  <small class="btn-block text-center my-3 text-uppercase or">{{__('general.or')}}</small>
                @endif

              @endif

        @if (! $settings->disable_login_register_email)
              <form method="POST" action="{{ route('register') }}" id="formLoginRegister">
                  @csrf
                  <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="feather icon-user"></i></span>
                      </div>
                      <input class="form-control" value="{{ old('name')}}" placeholder="{{__('auth.full_name')}}" name="name" type="text" required>
                    </div>
                  </div>

                <div class="form-group mb-3">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-mail"></i></span>
                    </div>
                    <input class="form-control" value="{{ old('email')}}" placeholder="{{__('auth.email')}}" name="email" type="text" required>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group input-group-alternative" id="showHidePassword">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="iconmoon icon-Key"></i></span>
                    </div>
                    <input name="password" type="password" class="form-control" placeholder="{{__('auth.password')}}" required>
                    <div class="input-group-append">
                      <span class="input-group-text c-pointer"><i class="feather icon-eye-off"></i></span>
                  </div>
                  </div>
                </div>

                <div class="custom-control custom-control-alternative custom-checkbox">
                  <input class="custom-control-input" id="customCheckRegister" type="checkbox" name="agree_gdpr" required>
                    <label class="custom-control-label" for="customCheckRegister">
                      <span>
                        {{__('admin.i_agree_gdpr')}}
                        <a href="{{$settings->link_terms}}" target="_blank">{{__('admin.terms_conditions')}}</a>
                        {{ __('general.and') }}
                        <a href="{{$settings->link_privacy}}" target="_blank">{{__('admin.privacy_policy')}}</a>
                      </span>
                    </label>
                </div>

                <div class="alert alert-danger display-none mb-0 mt-3" id="errorLogin">
                    <ul class="list-unstyled m-0" id="showErrorsLogin"></ul>
                  </div>

                <div class="alert alert-success mb-0 mt-3 display-none" id="checkAccount"></div>

                <div class="text-center">
                  @if ($settings->captcha == 'on')
                  {!! NoCaptcha::displaySubmit('formLoginRegister', '<i></i> '.__('auth.sign_up'), ['data-size' => 'invisible', 'id' => 'btnLoginRegister', 'class' => 'btn btn-primary mt-4 w-100']) !!}

                  {!! NoCaptcha::renderJs() !!}

                  @else
                  <button type="submit" class="btn btn-primary mt-4 w-100" id="btnLoginRegister"><i></i> {{__('auth.sign_up')}}</button>
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
            <div class="col-12 text-center">
              <a href="{{url('login')}}" class="text-light">
                <small>{{__('auth.already_have_an_account')}}</small>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
