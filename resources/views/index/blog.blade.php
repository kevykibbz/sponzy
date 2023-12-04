@extends('layouts.app')

@section('title') {{ trans('general.blog') }} -@endsection

@section('content')
  <section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-12 py-5">
          <h2 class="mb-0 text-break">{{ trans('general.latest_blog') }}</h2>
          <p class="lead text-muted mt-0">{{trans('general.subtitle_blog')}}</p>
        </div>
      </div>

      <div class="row">
        @if ($blogs->total() != 0)

          @foreach ($blogs as $response)
            <div class="col-md-4">
              <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                <div class="card-cover w-100" style="height:250px; background: @if ($response->image != '') url({{ route('resize', ['path' => 'admin', 'file' => $response->image, 'size' => 480]) }})  @endif #505050 center center;"></div>
                <div class="col p-4 d-flex flex-column position-static">
                  <small class="d-inline-block mb-2">{{ trans('general.by') }} {{ $response->user()->name }} </small>
                  <h3 class="mb-0">{{ $response->title }}</h3>
                  <div class="mb-1 text-muted">{{ Helper::formatDate($response->date) }}</div>
                  <p class="card-text mb-auto">{{ Str::limit(strip_tags($response->content), 120, '...') }}</p>
                  <a href="{{ url('blog/post', $response->id).'/'.$response->slug }}" class="stretched-link">{{ trans('general.continue_reading') }} <i class="bi-arrow-right"></i></a>
                </div>
              </div>
            </div>
          @endforeach

          @if ($blogs->hasPages())
            <div class="w-100 d-block">
              {{ $blogs->links() }}
            </div>
          @endif

        @else
          <div class="col-md-12">
            <div class="my-5 text-center no-updates">
              <span class="btn-block mb-3">
                <i class="fa fa-exclamation ico-no-result"></i>
              </span>
            <h4 class="font-weight-light">{{trans('general.no_results_found')}}</h4>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
@endsection
