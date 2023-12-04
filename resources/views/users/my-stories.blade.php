@extends('layouts.app')

@section('title') {{trans('general.my_stories')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="bi-clock-history mr-2"></i> {{trans('general.my_stories')}}</h2>
          <p class="lead text-muted mt-0">{{trans('general.my_stories_subtitle')}}</p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          @if ($stories->count() != 0)
          <div class="mb-2 p-2">
            @foreach ($stories as $story)
              @foreach ($story->media()->get() as $media)
              <div class="add-story mb-3 position-relative">
                <span class="delete-history c-pointer" data-id="{{ $story->id }}" title="{{ __('general.delete') }}"><i class="bi-trash"></i></span>
                <div class="item-add-story">
                  <span class="add-story-preview">
                    <div class="text-story-preview user-select-none" style="z-index: 5; font-family:{{ $media->font }}; color:{{ $media->font_color }};">{{ $media->text }}</div>
                    <img lazy="eager" width="100" src="{{ $media->type == 'photo' ? Helper::getFile(config('path.stories').$media->name) : ($media->video_poster ? Helper::getFile(config('path.stories').$media->video_poster) : Helper::getFile(config('path.avatar').auth()->user()->avatar)) }}">
                  </span>
                  <span class="info py-2 text-center text-white bg-dark-transparent c-pointer getViews" data-id="{{ $media->id }}" data-total="{{ $media->views()->count()}}" data-toggle="modal" data-target="#storyViews" title="{{ __('general.people_seen_story') }}">
                    <strong class="name" style="text-shadow: none;"><i class="bi-eye mr-1"></i> {{ $media->views()->count() }}</strong>
                  </span>
                </div>
              </div>
              @endforeach
            @endforeach
          </div>

          @include('includes.modal-story-views')

          @else
          <div class="my-5 text-center">
            <span class="btn-block mb-3">
              <i class="bi-clock-history ico-no-result"></i>
            </span>
          <h4 class="font-weight-light">{{trans('general.no_results_found')}}</h4>
          <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#addStory">
            <i class="bi-plus"></i> {{ __('general.add_story') }}
          </a>
          </div>

          @include('includes.modal-add-story')
        @endif

        @if ($stories->hasPages())
        {{ $stories->onEachSide(0)->links() }}
        @endif

        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection
