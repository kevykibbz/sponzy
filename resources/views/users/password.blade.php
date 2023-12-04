@extends('layouts.app')

@section('title') {{__('auth.password')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="iconmoon icon-Key mr-2"></i> {{__('auth.password')}}</h2>
          <p class="lead text-muted mt-0">{{__('auth.update_your_password')}}</p>
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

                @if (session('incorrect_pass'))
 			<div class="alert alert-danger">
 				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             		{{ session('incorrect_pass') }}
             		</div>
             	@endif

          @include('errors.errors-forms')

          <form method="POST" action="{{ url('settings/password') }}">

            @csrf

            @if (auth()->user()->password != '')
            <div class="form-group">
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="feather icon-unlock"></i></span>
                  </div>
                  <input class="form-control" name="old_password" placeholder="{{__('general.old_password')}}" type="password" required>
                </div>
              </div>
              @endif

              <div class="form-group">
                  <div class="input-group mb-4" id="showHidePassword">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-lock"></i></span>
                    </div>
                    <input class="form-control" name="new_password" placeholder="{{__('general.new_password')}}" type="password" required>
                    <div class="input-group-append">
                      <span class="input-group-text c-pointer"><i class="feather icon-eye-off"></i></span>
                  </div>
                  </div>
                </div>

                <button class="btn btn-1 btn-success btn-block buttonActionSubmit" type="submit">{{__('general.save_changes')}}</button>

          </form>
        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection
