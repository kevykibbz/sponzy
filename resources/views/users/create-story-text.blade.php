@extends('layouts.app')

@section('title') {{ __('general.story_text') }} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-12 py-5">
          <h2 class="mb-0 font-montserrat">
            {{ __('general.story_text') }}
          </h2>
          <p class="lead text-muted mt-0">
            {{ __('general.add_story_text_subtitle') }}
        </p>
        </div>
      </div>
      <div class="row">

        <div class="col-lg-5 second">
          <form action="{{ url()->current() }}" method="post" enctype="multipart/form-data" id="addStoryForm">
            @csrf
            <input class="inputBackground" type="hidden" name="background" value="{{ $storyBackgrounds[0]['name'] }}">
            <input class="inputColor" type="hidden" name="color" value="#ffffff">
            
            <div class="form-group position-relative">

              <div>
                <span class="triggerEmoji" style="top: 10px; right: 8px;" data-toggle="dropdown">
                  <i class="bi-emoji-smile"></i>
                </span>

                <div class="dropdown-menu dropdown-menu-right dropdown-emoji custom-scrollbar" aria-labelledby="dropdownMenuButton">
                  @include('includes.emojis')
                </div>
              </div>

              <textarea class="form-control textareaAutoSize emojiArea addTextStory" style="padding-right: 30px;" maxlength="300" name="text" placeholder="{{ __('general.start_typing') }}" rows="4"></textarea>
            </div>

            @if ($storyFonts->count())
            <div class="form-group">
              <div class="input-group mb-4">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="bi-type"></i></span>
              </div>
              <select name="font" class="form-control custom-select" id="storyFont">
                <option selected="selected" value="Arial">Arial</option>
                @foreach ($storyFonts as $font)
                  <option value="{{$font->name}}">{{$font->name}}</option>
                @endforeach
                </select>
                </div>
              </div><!-- ./form-group -->
              @endif

            <h6>{{ __('general.backgrounds') }}</h6>

            <div class="my-3 container-backgrounds d-block">
              @foreach ($storyBackgrounds as $background)
                <img src="{{ url('public/img/stories-bg', $background->name) }}" data-bg-name="{{ $background->name }}" data-bg="{{ url('public/img/stories-bg', $background->name) }}" class="mr-1 mb-2 storyBackgrounds storyBg">
            @endforeach
            </div>

            <h6>{{ __('general.font_color') }}</h6>

            <div class="my-3 container-backgrounds d-block">
              <span class="fontColor fontColor-white active border mr-1" data-color="#ffffff"></span>
              <span class="fontColor fontColor-black" data-color="#000000"></span>
            </div>

            <!-- Alert -->
          <div class="alert alert-danger my-3 display-none" id="errorCreateStory">
             <ul class="list-unstyled m-0" id="showErrorsCreateStory"><li></li></ul>
           </div><!-- Alert -->

            <button class="btn btn-1 btn-primary btn-block" id="createStoryBtn" type="submit"><i></i> {{ __('users.create') }}</button>
          </form>
        </div>

        <div class="col-lg-7 first">
          <div class="d-block w-100">
            <div class="bg-current w-100 bg-black mb-3 d-block py-4 px-lg-0 px-4">
              <div class="bg-inside text-center mx-auto" style="background: #6a6a6a url('{{ url('public/img/stories-bg', $storyBackgrounds[0]['name']) }}') no-repeat center center; background-size: cover;">
                <div class="flex-column d-flex justify-content-center text-center h-100 text-story px-4">
                  {{ __('general.start_typing') }}
                </div>
              </div>
            </div>
          </div>
        </div><!-- end col-md-12 -->
      </div>
    </div>
  </section>
@endsection

@section('javascript') 
  <script src="{{ asset('public/js/story/create-story.js') }}"></script>
@endsection
