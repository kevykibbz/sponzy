@extends('layouts.app')

@section('title') {{__('auth.password_recover')}} -@endsection

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

              <h4 class="text-center mb-0 font-weight-bold pt-4 px-4">
                {{__('auth.password_recover')}}
              </h4>
              <small class="btn-block text-center mt-2 px-4">{{ __('auth.recover_pass_subtitle') }}</small>

            <div class="card-body px-lg-5 py-lg-5">
              @if (session('status'))
                      <div class="alert alert-success">
                        {{{ session('status') }}}
                      </div>
                    @endif

              @include('errors.errors-forms')

              <form method="POST" action="{{ route('password.email') }}" id="passwordEmailForm">
                  @csrf
                <div class="form-group mb-3">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-mail"></i></span>
                    </div>
                    <input class="form-control @if (count($errors) > 0) is-invalid @endif" value="{{ old('email')}}" placeholder="{{__('auth.email')}}" name="email" required type="text">

                  </div>
                </div>

                <div class="text-center">
                  @if ($settings->captcha == 'on')
                  {!! NoCaptcha::displaySubmit('passwordEmailForm', __('auth.send_pass_reset'), ['data-size' => 'invisible', 'class' => 'btn btn-primary my-4 w-100']) !!}

                  {!! NoCaptcha::renderJs() !!}
                  @else
                  <button type="submit" class="btn btn-primary my-4 w-100">{{__('auth.send_pass_reset')}}</button>
                  @endif
                </div>
              </form>

              @if ($settings->captcha == 'on')
                <small class="btn-block text-center">{{__('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{__('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{__('general.terms')}}</a></small>
              @endif

            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <a href="{{ url()->previous() }}" class="text-light">
                <small><i class="fas fa-arrow-left"></i> {{__('general.go_back')}}</small>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
