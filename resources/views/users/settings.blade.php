@extends('layouts.app')

@section('title') {{trans('users.settings')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="fa fa-tools mr-2"></i> {{trans('users.settings')}}</h2>
          <p class="lead text-muted mt-0">{{trans('users.settings_desc')}}</p>
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

          <form method="POST" action="{{ url('settings') }}">

            @csrf

          <div class="form-group">
                <input class="form-control" disabled value="{{Auth::user()->email}}" type="text">
            </div>

            <div class="form-group">
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user-tie"></i></span>
                  </div>
                  <input class="form-control" name="profession" placeholder="{{trans('users.profession_ocupation')}}" value="{{Auth::user()->profession}}" type="text">
                </div>
              </div>

              <div class="form-group">
                <div class="input-group mb-4">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-globe"></i></span>
                </div>
                <select name="countries_id" class="form-control custom-select">
                  <option value="">{{trans('general.select_your_country')}}</option>
                      @foreach(  Countries::orderBy('country_name')->get() as $country )
                        <option @if( Auth::user()->countries_id == $country->id ) selected="selected" @endif value="{{$country->id}}">{{ $country->country_name }}</option>
                        @endforeach
                      </select>
                      </div>
                </div>

                <div class="custom-control custom-checkbox mb-3">
                  <input name="email_new_subscriber" value="yes" id="customCheckLogin" @if (Auth::user()->email_new_subscriber == 'yes') checked @endif class="custom-control-input" type="checkbox">
                  <label class="custom-control-label" for="customCheckLogin">
                    <h6 class="font-weight-light">{{ trans('general.email_notification_new_subscriber') }}</h6>
                  </label>
                </div>

                <button class="btn btn-1 btn-success btn-block" type="submit">{{trans('general.save_changes')}}</button>
          </form>
        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection
