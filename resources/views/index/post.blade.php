@extends('layouts.app')

@section('title') {{ $response->title }} | {{ trans('general.blog') }} @endsection
  @section('description_custom'){{strip_tags($response->content)}}@endsection
    @section('keywords_custom'){{$response->tags ? $response->tags.',' : null}}@endsection

  @section('css')
    <meta property="og:type" content="website" />
    <meta property="og:image:width" content="650"/>
    <meta property="og:image:height" content="430"/>

    <!-- Current locale and alternate locales -->
    <meta property="og:locale" content="en_US" />
    <meta property="og:locale:alternate" content="es_ES" />

    <!-- Og Meta Tags -->
    <link rel="canonical" href="{{url()->current()}}"/>
    <meta property="og:site_name" content="{{ $response->title }}"/>
    <meta property="og:url" content="{{url()->current()}}"/>
    <meta property="og:image" content="{{Helper::getFile(config('path.admin').$response->image)}}"/>

    <meta property="og:title" content="{{ $response->title }}"/>
    <meta property="og:description" content="{{strip_tags($response->content)}}"/>
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:image" content="{{Helper::getFile(config('path.admin').$response->image)}}" />
    <meta name="twitter:title" content="{{ $response->title  }}" />
    <meta name="twitter:description" content="{{strip_tags($response->content)}}"/>
    @endsection

@section('content')
  <section class="section section-sm">
    <div class="container">
      <div class="row">
            <div class="@if ($blogs->count() == 0) col-md-12 @else col-md-8 @endif py-5">
              <div class="row no-gutters border rounded flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                <div class="card-cover w-100 img-post" style="background: @if ($response->image != '') url({{ Helper::getFile(config('path.admin').$response->image) }})  @endif #505050 no-repeat center center; background-size: cover; "></div>
                <div class="col p-4 d-flex flex-column position-static">
                  <small class="d-inline-block mb-2">{{ trans('general.by') }} {{ $response->user()->name }} </small>
                  <h3 class="mb-0">{{ $response->title }}</h3>
                  <div class="mb-3 text-muted">{{ Helper::formatDate($response->date) }}</div>
                  <div class="card-text mb-auto content-p">{!! $response->content !!}</div>

                  <div class="mt-4 justify-content-middle">
                    <hr>
                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{url()->current()}}" class="mr-2">
                      <i class="fab fa-facebook mr-1"></i> {{trans('general.share')}}
                    </a>

                    <a target="_blank" href="https://twitter.com/intent/tweet?url={{url()->current()}}&text={{ $response->title }}">
                      <i class="bi-twitter-x mr-1"></i> Tweet
                    </a>
                  </div>

                </div>
              </div>
            </div>

            @if ($blogs->count() != 0)

              <div class="col-md-4 mb-4 py-lg-5">

              <h6 class="mb-3 text-muted font-weight-light">{{ trans('general.others_posts') }}</h6>

              @foreach ($blogs as $response)

                <a href="{{url('blog/post', $response->id).'/'.$response->slug}}">
                <div class="w-100 d-block" style="background: @if ($response->image != '') url({{ Helper::getFile(config('path.admin').$response->image) }})  @endif #505050 center center; border-radius: 6px; background-size: cover;">

                  <div class="card-cover position-relative" style="height: 110px"></div>

                  <li class="list-group-item mb-2 border-0" style="background: rgba(0,0,0,.40);">
                         <div class="media">
                          <div class="media-body">
                            <h5 class="media-heading mb-1">
                              <a href="{{url('blog/post', $response->id).'/'.$response->slug}}" class="stretched-link text-white">
                                <strong>{{$response->title}}</strong>
                              </a>
                              <small class="text-white w-100 d-block mb-2">{{'@'.$response->user()->name}} - {{ Helper::formatDate($response->date) }}</small>
                              <p class="text-white font-weight-light">{{ Str::limit(strip_tags($response->content), 60, '...') }}</p>
                            </h5>
                          </div>
                      </div>
                  </li>
                	</div>
                  </a>
              @endforeach

              </div><!-- end col-md-4 -->

              @endif


      </div>
    </div>
  </section>
@endsection
