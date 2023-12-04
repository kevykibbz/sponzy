@extends('layouts.app')

@section('content')
  <section class="section section-sm">
    <div class="container container-lg-3 pt-lg-5 pt-2">
      <div class="row">

        <div class="col-md-2">
          @include('includes.menu-sidebar-home')
        </div>

        <div class="col-md-6 p-0 second wrap-post">

          @if ($stories->count() || $settings->story_status && auth()->user()->verified_id == 'yes')
          <div id="stories" class="storiesWrapper mb-2 p-2">
            @if ($settings->story_status && auth()->user()->verified_id == 'yes')
            <div class="add-story" title="{{ __('general.add_story') }}">
              <a class="item-add-story" href="#" data-toggle="modal" data-target="#addStory">
                <span class="add-story-preview">
                  <img lazy="eager" width="100" src="{{ Helper::getFile(config('path.avatar').auth()->user()->avatar) }}">
                </span>
                <span class="info py-3 text-center text-white bg-primary">
                  <strong class="name" style="text-shadow: none;"><i class="bi-plus-circle-dotted mr-1"></i> {{ __('general.add_story') }}</strong>
                </span>
              </a>
            </div>
            @endif
          </div>
          @endif

        @if ($settings->announcement != ''
            && $settings->announcement_show == 'creators'
            && auth()->user()->verified_id == 'yes'
            || $settings->announcement != ''
            && $settings->announcement_show == 'all'
            )
          <div class="alert alert-{{$settings->type_announcement}} announcements display-none card-border-0" role="alert">
            <button type="button" class="close" id="closeAnnouncements">
              <span aria-hidden="true">
                <i class="bi bi-x-lg"></i>
              </span>
            </button>

            <h4 class="alert-heading"><i class="bi bi-megaphone mr-2"></i> {{ __('general.announcements') }}</h4>
            <p class="update-text">
              {!! $settings->announcement !!}
            </p>
          </div><!-- end announcements -->
        @endif

          @if ($payPerViewsUser != 0)
            <div class="col-md-12 d-none">
              <ul class="list-inline">
                <li class="list-inline-item text-uppercase h5">
                  <a href="{{ url('/') }}" class="text-decoration-none @if (request()->is('/')) link-border @else text-muted  @endif">{{ __('admin.home') }}</a>
                </li>
                <li class="list-inline-item text-uppercase h5">
                  <a href="{{ url('my/purchases') }}" class="text-decoration-none @if (request()->is('my/purchases')) link-border @else text-muted @endif" >{{ __('general.purchased') }}</a>
                </li>
              </ul>
            </div>
          @endif

        @if (auth()->user()->verified_id == 'yes')
        
          @include('includes.modal-add-story')

          @include('includes.form-post')
        @endif

          @if ($updates->count() != 0)
          <div class="grid-updates position-relative" id="updatesPaginator">
              @include('includes.updates')
          </div>

        @else
          <div class="grid-updates position-relative" id="updatesPaginator"></div>

        <div class="my-5 text-center no-updates">
          <span class="btn-block mb-3">
            <i class="fa fa-photo-video ico-no-result"></i>
          </span>
        <h4 class="font-weight-light">{{__('general.no_posts_posted')}}</h4>

          <a href="{{ url('creators') }}" class="btn btn-primary mb-3 mt-2 px-5 d-lg-none">
            {{ __('general.explore_creators') }}
          </a>

          <a href="{{ url('explore') }}" class="btn btn-primary px-5 d-lg-none">
            {{ __('general.explore_posts') }}
          </a>
        </div>

        @endif
        </div><!-- end col-md-12 -->

        <div class="col-md-4 @if ($users->count() != 0) mb-4 @endif d-lg-block d-none">
          <div class="d-lg-block sticky-top">
            @if ($users->count() == 0)
            <div class="panel panel-default panel-transparent mb-4 d-lg-block d-none">
          	  <div class="panel-body">
          	    <div class="media none-overflow">
          			  <div class="d-flex my-2 align-items-center">
          			      <img class="rounded-circle mr-2" src="{{Helper::getFile(config('path.avatar').auth()->user()->avatar)}}" width="60" height="60">

          						<div class="d-block">
          						<strong>{{auth()->user()->name}}</strong>

          							<div class="d-block">
          								<small class="media-heading text-muted btn-block margin-zero">
                            <a href="{{url('settings/page')}}">
                  						{{ auth()->user()->verified_id == 'yes' ? __('general.edit_my_page') : __('users.edit_profile')}}
                              <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small>
                            </a>
                          </small>
          							</div>
          						</div>
          			  </div>
          			</div>
          	  </div>
          	</div>
          @endif

            @if ($users->count() != 0)
                @include('includes.explore_creators')
            @endif

            <div class="d-lg-block d-none">
              @include('includes.footer-tiny')
            </div>
         </div><!-- sticky-top -->

        </div><!-- col-md -->
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

 @if (session('success_verify'))
 <script type="text/javascript">
    swal({
      title: "{{ __('general.welcome') }}",
      text: "{{ __('users.account_validated') }}",
      type: "success",
      confirmButtonText: "{{ __('users.ok') }}"
      });
      </script>
   @endif

   @if (session('error_verify'))
   <script type="text/javascript">
    swal({
      title: "{{ __('general.error_oops') }}",
      text: "{{ __('users.code_not_valid') }}",
      type: "error",
      confirmButtonText: "{{ __('users.ok') }}"
      });
      </script>
   @endif

   @if ($settings->story_status && $stories->count())
       <script>
        let stories = new Zuck('stories', {
          skin: 'snapssenger',      // container class
          avatars: false,         // shows user photo instead of last story item preview
          list: false,           // displays a timeline instead of carousel
          openEffect: true,      // enables effect when opening story
          cubeEffect: false,     // enables the 3d cube effect when sliding story
          autoFullScreen: false, // enables fullscreen on mobile browsers
          backButton: true,      // adds a back button to close the story viewer
          backNative: false,     // uses window history to enable back button on browsers/android
          previousTap: true,     // use 1/3 of the screen to navigate to previous item when tap the story
          localStorage: true,    // set true to save "seen" position. Element must have a id to save properly.

          stories: [

          @foreach ($stories as $story)
            {
            id: "{{ $story->user->username }}",               // story id
            photo: "{{ Helper::getFile(config('path.avatar').$story->user->avatar) }}",            // story photo (or user photo)
            name: "{{ $story->user->hide_name == 'yes' ? $story->user->username : $story->user->name }}",             // story name (or user name)
            link: "{{ url($story->user->username) }}",             // story link (useless on story generated by script)
            lastUpdated: {{ $story->created_at->timestamp }},      // last updated date in unix time format

            items: [
              // story item example

              @foreach ($story->media as $media)
              {
                id: "{{ $story->user->username }}-{{ $story->id }}",       // item id
                type: "{{ $media->type }}",     // photo or video
                length: {{ $media->type == 'photo' ? 5 : ($media->video_length ?: $settings->story_max_videos_length)	}},    // photo timeout or video length in seconds - uses 3 seconds timeout for images if not set
                src: "{{ Helper::getFile(config('path.stories').$media->name) }}",      // photo or video src
                preview: "{{ $media->type == 'photo' ? route('resize', ['path' => 'stories', 'file' => $media->name, 'size' => 280]) : ($media->video_poster ? route('resize', ['path' => 'stories', 'file' => $media->video_poster, 'size' => 280]) : route('resize', ['path' => 'avatar', 'file' => $story->user->avatar, 'size' => 200])) }}",  // optional - item thumbnail to show in the story carousel instead of the story defined image
                link: "",     // a link to click on story
                linkText: '{{ $story->title }}', // link text
                time: {{ $media->created_at->timestamp }},     // optional a date to display with the story item. unix timestamp are converted to "time ago" format
                seen: false,   // set true if current user was read
                story: "{{ $media->id }}",
                text: "{{ $media->text }}",
                color: "{{ $media->font_color }}",
                font: "{{ $media->font }}",
              },
              @endforeach
            ]
          },
          @endforeach

          ],

          callbacks:  {
            onView (storyId) {
              getItemStoryId(storyId);
            },

            onEnd (storyId, callback) {
              getItemStoryId(storyId);
              callback();  // on end story
            },

            onClose (storyId, callback) {
              getItemStoryId(storyId);
              callback();  // on close story viewer
            },

            onNavigateItem (storyId, nextStoryId, callback) {
              getItemStoryId(storyId);
              callback();  // on navigate item of story
            },
          },
        
          language: { // if you need to translate :)
            unmute: '{{ __("general.touch_unmute") }}',
            keyboardTip: 'Press space to see next',
            visitLink: 'Visit link',
            time: {
              ago:'{{ __("general.ago") }}', 
              hour:'{{ __("general.hour") }}', 
              hours:'{{ __("general.hours") }}', 
              minute:'{{ __("general.minute") }}', 
              minutes:'{{ __("general.minutes") }}', 
              fromnow: '{{ __("general.fromnow") }}', 
              seconds:'{{ __("general.seconds") }}', 
              yesterday: '{{ __("general.yesterday") }}', 
              tomorrow: 'tomorrow', 
              days:'days'
            }
          }
        });

        function getItemStoryId(storyId) {
          let userActive = '{{ auth()->user()->username }}';
          if (userActive !== storyId) {
            let itemId = $('#zuck-modal .story-viewer[data-story-id="'+storyId+'"]').find('.itemStory.active').data('id-story');
            insertViewStory(itemId);
          }
          insertTextStory();
        }

        insertTextStory();

        function insertTextStory() {
          $('.previewText').each(function() {
          let text = $(this).find('.items>li:first-child>a').data('text');
          let font = $(this).find('.items>li:first-child>a').data('font');
          let color = $(this).find('.items>li:first-child>a').data('color');
          $(this).find('.text-story-preview').css({fontFamily: font, color: color }).html(text);
        });
        }

        function insertViewStory(itemId) {
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          $.post(URL_BASE+"/story/views/"+itemId+"");
        }

        $(document).on('click','.profilePhoto, .info>.name', function() {
          let element = $(this);
          let username = element.parents('.story-viewer').data('story-id');
          if (username) {
            window.location.href = URL_BASE+'/'+username;
          }
        });
       </script>
   @endif

 @endsection
