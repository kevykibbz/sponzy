@extends('layouts.app')

@section('css')
  <script type="text/javascript">
      var error_scrollelement = {{ count($errors) > 0 ? 'true' : 'false' }};
  </script>
@endsection

@section('content')
  <div class="jumbotron home m-0 bg-gradient">
    <div class="container pt-lg-md">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <div class="card bg-white shadow border-0 b-radio-custom">

            <div class="p-4">
              <h4 class="text-center mb-0 font-weight-bold">
                {{__('auth.reset_password')}}
              </h4>
              <small class="btn-block text-center mt-2">{{ __('auth.reset_pass_subtitle') }}</small>
            </div>

            <div class="card-body px-lg-5 py-lg-5">

              @if (session('status'))
                      <div class="alert alert-success">
                        {{ session('status') }}
                      </div>
                    @endif

              @include('errors.errors-forms')

              <form method="POST" action="{{url('password/reset')}}" id="passwordResetForm">
                  @csrf
                  <input type="hidden" name="token" value="{{$token}}">

                <div class="form-group mb-3">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-mail"></i></span>
                    </div>
                    <input class="form-control" value="{{ old('email')}}" placeholder="{{__('auth.email')}}" name="email" required type="text">
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group input-group-alternative" id="showHidePassword">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="iconmoon icon-Key"></i></span>
                    </div>
                    <input name="password" type="password" class="form-control" required placeholder="{{__('auth.password')}}">
                    <div class="input-group-append">
                      <span class="input-group-text c-pointer"><i class="feather icon-eye-off"></i></span>
                  </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="iconmoon icon-Key"></i></span>
                    </div>
                    <input name="password_confirmation" type="password" class="form-control" required placeholder="{{__('auth.confirm_password')}}">
                  </div>
                </div>

                <div class="text-center">
                  @if ($settings->captcha == 'on')
                  {!! NoCaptcha::displaySubmit('passwordResetForm', __('auth.reset_password'), ['data-size' => 'invisible', 'class' => 'btn btn-primary my-4 w-100']) !!}

                  {!! NoCaptcha::renderJs() !!}
                  @else
                  <button type="submit" class="btn btn-primary my-4 w-100">{{__('auth.reset_password')}}</button>
                  @endif
                </div>
              </form>

              @if ($settings->captcha == 'on')
                <small class="btn-block text-center">{{__('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{__('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{__('general.terms')}}</a></small>
              @endif

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
