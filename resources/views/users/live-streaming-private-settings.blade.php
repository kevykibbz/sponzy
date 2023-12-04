@extends('layouts.app')

@section('title') {{__('general.live_stream_private_settings')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="bi-gear mr-2"></i> {{__('general.live_stream_private_settings')}}</h2>
          <p class="lead text-muted mt-0">{{__('general.subtitle_live_stream_private_settings')}}</p>
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

          <form method="POST" action="{{ url()->current() }}">
            @csrf
              <div class="form-group">
                <div class="btn-block mb-4">
                    <div class="custom-control custom-switch custom-switch-lg">
                      <input type="checkbox" class="custom-control-input" name="allow_live_streaming_private" value="on" @if (auth()->user()->allow_live_streaming_private == 'on') checked @endif id="allow_live_streaming_private">
                      <label class="custom-control-label switch" for="allow_live_streaming_private">{{ __('general.allow_live_streaming_private') }}</label>
                    </div>
                  </div>
                </div>

                <div class="form-group mb-4">
                  <label class="w-100 ">{{__('general.price_live_streaming_private')}} *</label>
                  <div class="input-group mb-2">
                    
                    <div class="input-group-prepend">
                      <span class="input-group-text">{{$settings->currency_symbol}}</span>
                    </div>
                        <input value="{{ auth()->user()->price_live_streaming_private }}" class="form-control form-control-lg isNumber" required name="price_live_streaming_private" autocomplete="off" placeholder="{{__('general.price_live_streaming_private')}}" type="text">
                    </div>
                    <small class="btn-block">
                      * {{ __('general.minimum') }} {{ Helper::priceWithoutFormat($settings->live_streaming_minimum_price_private) }} - {{ __('general.maximum') }} {{ Helper::priceWithoutFormat($settings->live_streaming_max_price_private) }}

                      @if ($settings->wallet_format != 'real_money')
											  <strong>({{Helper::equivalentMoney($settings->wallet_format)}})</strong>
										  @endif
                    </small>
                </div>

                <button class="btn btn-1 btn-success btn-block buttonActionSubmit" type="submit">{{__('general.save_changes')}}</button>

          </form>
        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection
