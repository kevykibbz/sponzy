@extends('layouts.app')

@section('title'){{ $user->hide_name == 'yes' ? $user->username : $user->name }} -@endsection
  @section('description_custom'){{$user->username}} - {{strip_tags($user->story)}}@endsection

  @section('css')

  <meta property="og:type" content="website" />
  <meta property="og:image:width" content="200"/>
  <meta property="og:image:height" content="200"/>

  <!-- Current locale and alternate locales -->
  <meta property="og:locale" content="en_US" />
  <meta property="og:locale:alternate" content="es_ES" />

  <!-- Og Meta Tags -->
  <link rel="canonical" href="{{url($user->username)}}"/>
  <meta property="og:site_name" content="{{ $user->hide_name == 'yes' ? $user->username : $user->name }} - {{$settings->title}}"/>
  <meta property="og:url" content="{{url($user->username)}}"/>
  <meta property="og:image" content="{{Helper::getFile(config('path.avatar').$user->avatar)}}"/>

  <meta property="og:title" content="{{ $user->hide_name == 'yes' ? $user->username : $user->name }} - {{$settings->title}}"/>
  <meta property="og:description" content="{{ str_limit($updates[0]->description, 20) }} {{ __('general.by') }} {{ $user->hide_name == 'yes' ? '@'.$user->username : $user->name }}"/>
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:image" content="{{Helper::getFile(config('path.avatar').$user->avatar)}}" />
  <meta name="twitter:title" content="{{ $user->hide_name == 'yes' ? $user->username : $user->name }}" />
  <meta name="twitter:description" content="{{ str_limit($updates[0]->description, 20) }} {{ __('general.by') }} {{ $user->hide_name == 'yes' ? '@'.$user->username : $user->name }}"/>
  @endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row">
        <div class="col-md-8 mb-lg-0 py-5 wrap-post">
          @foreach ($updates as $response)
            @include('includes.updates')
          @endforeach

          @if($updatesCount == 0) 
            {{__('general.no_results_found')}}
          @endif
        </div><!-- end col-md-9 -->

        <div class="col-md-4 pb-4 py-lg-5">

          @if ($users->count() != 0)
              @include('includes.explore_creators')
          @endif

            @include('includes.footer-tiny')

        </div>

      </div>
    </div>
  </section>
@endsection

@section('javascript')

 @if (session('noty_error'))
   <script type="text/javascript">
   		swal({
   			title: "{{ __('general.error_oops') }}",
   			text: "{{ __('general.already_sent_report') }}",
   			type: "error",
   			confirmButtonText: "{{ __('users.ok') }}"
   			});
        </script>
  		 @endif

  @if (session('noty_success'))
    <script type="text/javascript">
   		swal({
   			title: "{{ __('general.thanks') }}",
   			text: "{{ __('general.reported_success') }}",
   			type: "success",
   			confirmButtonText: "{{ __('users.ok') }}"
   			});
  </script>
  @endif

@endsection
