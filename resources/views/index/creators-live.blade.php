@extends('layouts.app')

@section('title') {{ trans('general.creators_live') }} -@endsection

@section('content')
  <section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-12 py-5">
          <h2 class="mb-0 text-break">{{ trans('general.creators_live') }}</h2>
          <p class="lead text-muted mt-0">{{trans('users.the_best_creators_is_here')}}
            @guest
              @if ($settings->registration_active == '1')
                <a href="{{url('signup')}}" class="link-border">{{ trans('general.join_now') }}</a>
              @endif
          @endguest</p>
        </div>
      </div>

<div class="row">

  <div class="col-md-3 mb-4">

    @include('includes.menu-filters-creators')

    @include('includes.listing-categories')
  </div><!-- end col-md-3 -->


@if( $users->total() != 0 )
          <div class="col-md-9 mb-4">
            <div class="row">

              @foreach ($users as $response)
              <div class="col-md-6 mb-4">
                @include('includes.listing-creators-live')
              </div><!-- end col-md-4 -->
              @endforeach

              @if($users->hasPages())
                <div class="w-100 d-block">
                  {{ $users->onEachSide(0)->appends([
                    'q' => request('q'),
                    'gender' => request('gender'),
                    'min_age' => request('min_age'),
                    'max_age' => request('max_age')
                    ])->links() }}
                </div>
              @endif
            </div><!-- row -->
          </div><!-- col-md-9 -->

        @else
          <div class="col-md-9">
            <div class="my-5 text-center no-updates">
              <span class="btn-block mb-3">
                <i class="bi bi-broadcast ico-no-result"></i>
              </span>
            <h4 class="font-weight-light">{{trans('general.no_live_streams')}}</h4>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
@endsection
