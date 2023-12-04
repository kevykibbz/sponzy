@extends('layouts.app')

@section('title') {{ __('general.story_image') }} -@endsection

@section('css')
<style type="text/css">
  .fileuploader { display:block; padding: 0; }
  .fileuploader-items-list {margin: 10px 0 0 0;}
  .fileuploader-theme-dragdrop .fileuploader-input {
    background: {{ auth()->user()->dark_mode == 'on'? '#222' : '#fff' }};  
  }
</style>
@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-12 py-5">
          <h2 class="mb-0 font-montserrat">
            {{ __('general.story_image') }}
          </h2>
          <p class="lead text-muted mt-0">
            {{ __('general.add_story_image_subtitle') }}
        </p>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-7">
            <form action="{{ url()->current() }}" method="post" enctype="multipart/form-data" id="addStoryForm">
              @csrf

              <div class="form-group">
                <input type="file" name="media" accept="image/*,video/mp4,video/x-m4v,video/quicktime">
              </div>

              <div class="form-group mb-4">
                <input type="text" class="form-control" name="title" id="title" placeholder="{{ __('general.title') }} ({{ __('general.optional') }})">
              </div>

              <!-- Alert -->
            <div class="alert alert-danger my-3 display-none" id="errorCreateStory">
               <ul class="list-unstyled m-0" id="showErrorsCreateStory"><li></li></ul>
             </div><!-- Alert -->

              <button class="btn btn-1 btn-primary btn-block" id="createStoryBtn" type="submit"><i></i> {{ __('users.create') }}</button>
            </form>
        </div><!-- end col-md-12 -->
      </div>
    </div>
  </section>
@endsection

@section('javascript')
  <script src="{{ asset('public/js/fileuploader/fileuploader-story-file.js') }}"></script> 
  <script src="{{ asset('public/js/story/create-story.js') }}"></script>
@endsection
