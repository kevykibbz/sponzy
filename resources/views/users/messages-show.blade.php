@extends('layouts.app')

@section('title'){{__('general.messages')}} -@endsection

@section('css')
  <script type="text/javascript">
      var subscribed_active = {{ $subscribedToYourContent || $subscribedToMyContent || auth()->user()->isSuperAdmin() || $user->isSuperAdmin() ? 'true' : 'false' }};
      var user_id_chat = {{ $user->id }};
      var msg_count_chat = {{ $messages->count() }};
  </script>

  <style>
    @media (min-width: 991px) {
    .fileuploader-theme-thumbnails .fileuploader-thumbnails-input,
    .fileuploader-theme-thumbnails .fileuploader-items-list .fileuploader-item {
      width: calc(14% - 16px);
      padding-top: 12%;
      }
    }
  </style>
@endsection

@section('content')
<section class="section section-sm pb-0 h-100 section-msg position-fixed">
    <div class="container container-full-w h-100">
      <div class="row justify-content-center h-100">

        <div class="col-md-3 h-100 p-0 border-left second wrapper-msg-inbox" id="messagesContainer">
          @include('includes.sidebar-messages-inbox')
        </div>

  <div class="col-md-9 h-100 p-0 first">

  <div class="card w-100 rounded-0 h-100 border-top-0">
    <div class="card-header bg-white pt-4">
      <div class="media">
        <a href="{{url()->previous()}}" class="mr-3"><i class="fa fa-arrow-left"></i></a>
        <a href="{{url($user->username)}}" class="mr-3">
          <span class="position-relative user-status @if ($user->active_status_online == 'yes') @if (Helper::isOnline($user->id)) user-online @else user-offline @endif @endif d-block">
            <img src="{{Helper::getFile(config('path.avatar').$user->avatar)}}" class="rounded-circle" width="40" height="40">
          </span>
      </a>

        <div class="media-body">
          <h6 class="m-0">
            <a href="{{url($user->username)}}">
              {{$user->hide_name == 'yes' ? $user->username : $user->name}}
            </a>

            @if ($user->verified_id == 'yes')
              <small class="verified">
                   <i class="bi bi-patch-check-fill"></i>
                 </small>
            @endif
          </h6>

        @if ($user->active_status_online == 'yes')

          @if ($user->hide_last_seen == 'no')
           <small>{{ __('general.active') }}</small>

           <span id="timeAgo">
             <small class="timeAgo @if (Helper::isOnline($user->id)) display-none @endif" id="lastSeen" data="{{ date('c', strtotime($user->last_seen ?? $user->date)) }}"></small>
            </span>
          @else
            {{'@'.$user->username}}
            @endif

          @else
            {{'@'.$user->username}}
            @endif

        </div>

        @if ($user->verified_id == 'yes' 
            && $settings->live_streaming_private == 'on' 
            && $user->allow_live_streaming_private == 'on' 
            && !auth()->user()->isRestricted($user->id)
            )
        <a href="javascript:void(0);" class="f-size-20 text-muted float-right mr-3 text-decoration-none @if (Helper::isOnline($user->id)) requestLivePrivateModal @else buttonDisabled @endif" @if (Helper::isOnline($user->id)) data-toggle="tooltip" data-placement="bottom" title="{{ __('general.request_private_live_stream') }}" @endif role="button">
					<i class="feather icon-video"></i>
				</a>
        @endif

        <a href="javascript:void(0);" class="f-size-20 text-muted float-right" id="dropdown_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
					<i class="fa fa-ellipsis-h"></i>
				</a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown_options">

        @if ($messages->count() != 0)
					{!! Form::open([
						'method' => 'POST',
						'url' => "conversation/delete/$user->id",
						'class' => 'd-inline'
					]) !!}

					{!! Form::button('<i class="feather icon-trash-2 mr-2"></i> '.__('general.delete'), ['class' => 'dropdown-item actionDelete']) !!}
					{!! Form::close() !!}

          @endif

          @if (auth()->user()->isRestricted($user->id))
            <button type="button" class="dropdown-item removeRestriction" data-user="{{$user->id}}" id="restrictUser">
              <i class="fas fa-ban mr-2"></i> {{__('general.remove_restriction')}}
            </button>

          @else
            <button type="button" class="dropdown-item" data-user="{{$user->id}}" id="restrictUser">
              <i class="fas fa-ban mr-2"></i> {{__('general.restrict')}}
            </button>
          @endif
	      </div>

      </div>

    </div>

    <div class="content px-4 py-3 d-scrollbars container-msg" id="contentDIV" data="{{$user->id}}">

      @if ($messages->count() != 0)
      <div class="flex-column d-flex justify-content-center text-center h-100">
        <div class="w-100" id="loadAjaxChat">
          <div class="spinner-border text-primary" role="status"></div>
        </div>
      </div>
    @endif
      </div><!-- contentDIV -->

      @if (!auth()->user()->checkRestriction($user->id) && $user->allow_dm)
          <div class="card-footer bg-white position-relative">

          @if ($subscribedToYourContent || $subscribedToMyContent || auth()->user()->isSuperAdmin() || $user->isSuperAdmin())

            <div class="w-100 display-none" id="previewFile">
              <div class="previewFile d-inline"></div>
              <a href="javascript:;" class="text-danger" id="removeFile"><i class="fa fa-times-circle"></i></a>
            </div>

            <div class="progress-upload-cover" style="width: 0%; top:0;"></div>

            <div class="blocked display-none"></div>

            <!-- Alert -->
            <div class="alert alert-danger my-3" id="errorMsg" style="display: none;">
             <ul class="list-unstyled m-0" id="showErrorMsg"></ul>
           </div><!-- Alert -->

            <form action="{{url('message/send')}}" method="post" accept-charset="UTF-8" id="formSendMsg" enctype="multipart/form-data">
              <input type="hidden" name="id_user" id="id_user" value="{{$user->id}}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="file" name="zip" id="zipFile" accept="application/x-zip-compressed" class="visibility-hidden">

              <div class="w-100 mr-2 position-relative">
                <div>
                <span class="triggerEmoji" data-toggle="dropdown">
                  <i class="bi-emoji-smile"></i>
                </span>

                <div class="dropdown-menu dropdown-menu-right dropdown-emoji custom-scrollbar" aria-labelledby="dropdownMenuButton">
                  @include('includes.emojis')
                </div>
              </div>
                <textarea class="form-control textareaAutoSize emojiArea border-0" data-post-length="{{$settings->update_length}}" rows="1" placeholder="{{__('general.write_something')}}" id="message" name="message"></textarea>
              </div>

              <div class="form-group display-none mt-2" id="price">
                <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text">{{$settings->currency_symbol}}</span>
                </div>
                    <input class="form-control isNumber" autocomplete="off" name="price" placeholder="{{__('general.price')}}" type="text">
                </div>
              </div><!-- End form-group -->

              <div class="w-100">
                <span id="previewImage"></span>
                <a href="javascript:void(0)" id="removePhoto" class="text-danger p-1 px-2 display-none btn-tooltip" data-toggle="tooltip" data-placement="top" title="{{__('general.delete')}}"><i class="fa fa-times-circle"></i></a>
              </div>

              <input type="file" name="media[]" id="file" accept="image/*,video/mp4,video/x-m4v,video/quicktime,audio/mp3" multiple class="visibility-hidden filepond">

              <div class="justify-content-between mt-3 align-items-center">

                    <button type="button" class="btnMultipleUpload btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.upload_media')}} ({{ __('general.media_type_upload') }})">
                      <i class="feather icon-image align-bottom f-size-25"></i>
                    </button>

                    @if ($settings->allow_zip_files)
                    <button type="button" class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.upload_file_zip')}}" onclick="$('#zipFile').trigger('click')">
                      <i class="bi bi-file-earmark-zip align-bottom f-size-25"></i>
                    </button>
                  @endif

                  @if (auth()->user()->verified_id == 'yes')
                  <button type="button" id="setPrice" class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.set_price_for_msg')}}">
                    <i class="feather icon-tag align-bottom" style="font-size: 27px;"></i>
                  </button>
                @endif

                @if ($user->verified_id == 'yes' && $settings->disable_tips == 'off')
                  <button type="button" class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="modal" title="{{__('general.tip')}}" data-target="#tipForm" data-cover="{{Helper::getFile(config('path.cover').$user->cover)}}" data-avatar="{{Helper::getFile(config('path.avatar').$user->avatar)}}" data-name="{{$user->hide_name == 'yes' ? $user->username : $user->name}}" data-userid="{{$user->id}}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
                      <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
                      <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                      <path fill-rule="evenodd" d="M8 13.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                    </svg>
                  </button>
                @endif

          <div class="d-inline-block float-right rounded-pill mt-1 position-relative">
            <div class="btn-blocked display-none"></div>
            <button type="submit" id="button-reply-msg" disabled data-send="{{ __('auth.send') }}" data-wait="{{ __('general.send_wait') }}" class="btn btn-sm btn-primary rounded-pill float-right e-none">
              <i class="far fa-paper-plane"></i>
            </button>
            </div>

          </div><!-- media -->
        </form>
      @else
        <div class="alert alert-primary m-0 alert-dismissible fade show" role="alert">
          <i class="fa fa-info-circle mr-2"></i>
          @php
            $nameUser = $user->hide_name == 'yes' ? $user->username : $user->first_name;
          @endphp
        {!! __('general.show_form_msg_error_subscription_', ['user' => '<a href="'.url($user->username).'" class="link-border text-white">'.$nameUser.'</a>']) !!}
      </div>
        @endif

      </div><!-- card footer -->

      @else

      <div class="card-footer bg-white position-relative">
        <div class="alert alert-primary m-0 alert-dismissible fade show" role="alert">
          <i class="fa fa-info-circle mr-2"></i>
          {{ __('general.chat_unavailable') }}
        </div>
      </div>
    @endif

    </div><!-- card -->
  </div><!-- end col-md-8 -->

  </div><!-- end row -->
</div><!-- end container -->
</section>
@include('includes.modal-new-message')

  @if ($user->verified_id == 'yes' 
            && $settings->live_streaming_private == 'on' 
            && $user->allow_live_streaming_private == 'on' 
            && !auth()->user()->isRestricted($user->id)
            )
    @include('includes.modal-live-private-request')
  @endif

@endsection

@section('javascript')
<script src="{{ asset('public/js/messages.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/fileuploader/fileuploader-msg.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/paginator-messages.js') }}"></script>

@if ($user->verified_id == 'yes' 
            && $settings->live_streaming_private == 'on' 
            && $user->allow_live_streaming_private == 'on' 
            && !auth()->user()->isRestricted($user->id)
            )
<script src="{{ asset('public/js/live-private-request.js') }}"></script>
@endif

@endsection
