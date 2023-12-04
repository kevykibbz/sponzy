@extends('layouts.app')
@section('content')

  <section class="section section-sm">
      <div class="container pt-5">
  <div class="row">

    <div class="col-md-12 col-lg-12 mb-5 mb-lg-0">
      <div class="text-center">
        <h3><i class="feather icon-wifi-off mr-2"></i> {{ __('general.error_internet_disconnected_pwa') }}</h3>
        <p>
          {{ __('general.error_internet_disconnected_pwa_2') }}
        </p>
      </div>
    </div><!-- end col-md-12 -->
  </div>
</div>
</section>
@endsection
