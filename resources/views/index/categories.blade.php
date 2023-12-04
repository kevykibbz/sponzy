@extends('layouts.app')

@section('title') {{$title}} -@endsection

    @section('description_custom'){{$description ? $description : trans('seo.description')}}@endsection
    @section('keywords_custom'){{$keywords ? $keywords.',' : null}}@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-12 py-5">
          <h2 class="mb-0 font-montserrat">
            <img src="{{url('public/img-category', $image)}}" class="mr-2 rounded" width="30" /> {{$title}}</h2>
          <p class="lead text-muted mt-0">{{trans_choice('users.creators_in_this_category', 2 )}}</p>
        </div>
      </div>

<div class="row">

<div class="col-md-3 mb-4">

  @include('includes.menu-filters-creators')

    @include('includes.listing-categories')

        </div><!-- end col-md-3 -->

        @if ($users->count() != 0)
          <div class="col-md-9 mb-4">
            <div class="row" id="containerWrapCreators">

              @foreach ($users as $response)
              <div class="col-md-6 mb-4">
                @include('includes.listing-creators')
              </div><!-- end col-md-4 -->
              @endforeach

              @include('includes.paginator-creators')

            </div><!-- row -->
          </div><!-- col-md-9 -->

        @else
          <div class="col-md-9">
            <div class="my-5 text-center no-updates">
              <span class="btn-block mb-3">
                <i class="fa fa-user-slash ico-no-result"></i>
              </span>
            <h4 class="font-weight-light">{{trans('general.not_found_creators_category')}}</h4>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
@endsection

@section('javascript')
<script src="{{ url('public/js/paginator-creators.js') }}?v={{$settings->version}}"></script>
@endsection
