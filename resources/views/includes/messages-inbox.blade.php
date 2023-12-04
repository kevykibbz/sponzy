@foreach ($messagesInbox as $msg)
<div class="card msg-inbox border-bottom m-0 rounded-0">
	<div class="list-group list-group-sm list-group-flush rounded-0">

		<a href="{{url('messages', [$msg->remitter()->id, $msg->remitter()->username])}}" class="item-chat list-group-item list-group-item-action text-decoration-none p-4 @if ($msg->status == 'new' && $msg->sender->id != auth()->id()) font-weight-bold unread-chat @endif  @if (request()->id == $msg->remitter()->id) active disabled @endif">
			<div class="media">
			 <div class="media-left mr-3 position-relative @if ($msg->remitter()->active_status_online == 'yes') @if (Cache::has('is-online-' . $msg->remitter()->id)) user-online @else user-offline @endif @endif">
					 <img class="media-object rounded-circle" src="{{Helper::getFile(config('path.avatar').$msg->remitter()->avatar)}}"  width="50" height="50">
			 </div>

			 <div class="media-body overflow-hidden">
				 <div class="d-flex justify-content-between align-items-center">
					<h6 class="media-heading mb-2 text-truncate">
							 {{$msg->remitterName()}}

						@if ($msg->remitter()->verified_id == 'yes')
				         <small class="verified">
				   			<i class="bi bi-patch-check-fill"></i>
				   			</small>
				       @endif
					 </h6>
					 <small class="timeAgo text-truncate mb-2" data="{{ date('c',strtotime( $msg->created_at ) ) }}"></small>
				 </div>

				 <p class="text-truncate m-0">
					 @if ($msg->totalMsg() != 0)
					 <span class="badge badge-pill badge-primary mr-1">{{ $msg->totalMsg() }}</span>
				 @endif

					 @if ($msg->receiver->id != auth()->id())
					 	@if ($msg->status == 'readed')
						 <span><i class="bi bi-check2-all mr-1"></i></span>
						 @else
						 <span><i class="bi bi-reply mr-1"></i></span>
						 @endif
					 @endif

					 @if ($msg->media->count() == 1)
					 @foreach ($msg->media as $media)
						 @switch($media->type)
							 @case('image')
							 <i class="feather icon-image"></i>
							 @if ($msg->message == '') {{ __('general.image') }} @endif
								 @break
							 @case('video')
							 <i class="feather icon-video"></i>
							 @if ($msg->message == '') {{ __('general.video') }} @endif
							@break

							@case('music')
							 <i class="feather icon-mic"></i>
							 @if ($msg->message == '') {{ __('general.music') }} @endif
							@break

							@case('zip')
							 <i class="far fa-file-archive"></i>
							 @if ($msg->message == '') {{ __('general.zip') }} @endif
							@break
						 @endswitch
					 @endforeach

					 @elseif ($msg->media->count() > 1)
					 	<i class="bi bi-files"></i>
					 @endif

					 @if ($msg->tip == 'yes')
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16"> <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/> <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/> <path fill-rule="evenodd" d="M8 13.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/> </svg>
						{{ __('general.tip') }}
					@endif

					 @if ($msg->price != 0.00
					 		&& $msg->media->count() == 0
							&& $msg->receiver->id == auth()->id()
							&& ! auth()->user()->checkPayPerViewMsg($msg->id)
							)

						 <i class="feather icon-lock mr-1"></i> @lang('users.content_locked')

					 @else
						 {{ $msg->message }}
					 @endif

				 </p>
			 </div><!-- media-body -->
	 </div><!-- media -->
		 </a>
	</div><!-- list-group -->
</div><!-- card -->
@endforeach

@if ($messagesInbox->count() == 0)
	<div class="card border-0 text-center">
  <div class="card-body">
    <h4 class="mb-0 font-montserrat mt-2">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-send-exclamation" viewBox="0 0 16 16">
				<path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855a.75.75 0 0 0-.124 1.329l4.995 3.178 1.531 2.406a.5.5 0 0 0 .844-.536L6.637 10.07l7.494-7.494-1.895 4.738a.5.5 0 1 0 .928.372l2.8-7Zm-2.54 1.183L5.93 9.363 1.591 6.602l11.833-4.733Z"/>
				<path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1.5a.5.5 0 0 1-1 0V11a.5.5 0 0 1 1 0Zm0 3a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Z"/>
			</svg> {{ __('general.chats') }}
		</h4>
		<p class="lead text-muted mt-0">{{ __('general.no_chats') }}</p>
  </div>
</div>
@endif

@if ($messagesInbox->hasMorePages())
  <div class="btn-block text-center d-none">
    {{ $messagesInbox->appends(['q' => request('q')])->links('vendor.pagination.loadmore') }}
  </div>
  @endif
