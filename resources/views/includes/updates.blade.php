@include('includes.advertising')
  
  @foreach ($updates as $response)
		@php
			if (auth()->check()) {
				$checkUserSubscription = auth()->user()->checkSubscription($response->creator);
				$checkPayPerView = auth()->user()->payPerView()->where('updates_id', $response->id)->first();
			}

		$creatorLive = Helper::isCreatorLive($getCurrentLiveCreators , $response->creator->id);

		$totalLikes = number_format($response->likes->count());
		$totalComments = $response->totalComments();
		$mediaCount = $response->media->count();
		$allFiles = $response->media()->groupBy('type')->get();
		$getFirstFile = $response->media()->whereIn('type', ['image', 'video'])->where('video_embed', '')->first();

		$mediaImageVideo = $response->media()
				->whereIn('type', ['image', 'video'])
				->where('video_embed', '')
				->get();

		if ($getFirstFile && $getFirstFile->type == 'image') {
			$urlMedia =  url('media/storage/focus/photo', $getFirstFile->id);
			$backgroundPostLocked = 'background: url('.$urlMedia.') no-repeat center center #b9b9b9; background-size: cover;';
			$textWhite = 'text-white';

		} elseif ($getFirstFile && $getFirstFile->type == 'video' && $getFirstFile->video_poster) {
				$videoPoster = url('media/storage/focus/video', $getFirstFile->video_poster);
				$backgroundPostLocked = 'background: url('.$videoPoster.') no-repeat center center #b9b9b9; background-size: cover;';
				$textWhite = 'text-white';

		} else {
			$backgroundPostLocked = null;
			$textWhite = null;
		}

		$countFilesImage = $response->media->where('type', 'image')->count();
		$countFilesVideo = $response->media->whereIn('type', ['video', 'video_embed'])->count();
		$countFilesAudio = $response->media->where('type', 'music')->count();
		$mediaImageVideoTotal = $response->media->whereIn('type', ['image', 'video'])->count();

		$isVideoEmbed = $response->media[0]->video_embed ?? false;

		$nth = 0; // nth foreach nth-child(3n-1)
		
	@endphp
	<div class="card mb-3 card-updates views rounded-large shadow-large card-border-0 @if ($response->status == 'pending') post-pending @endif @if ($response->fixed_post == '1' && request()->path() == $response->creator->username || auth()->check() && $response->fixed_post == '1' && $response->creator->id == auth()->user()->id) pinned-post @endif" data="{{$response->id}}">
	<div class="card-body">
		<div class="pinned_post text-muted small w-100 mb-2 {{ $response->fixed_post == '1' && request()->path() == $response->creator->username || auth()->check() && $response->fixed_post == '1' && $response->creator->id == auth()->user()->id ? 'pinned-current' : 'display-none' }}">
			<i class="bi bi-pin mr-2"></i> {{ __('general.pinned_post') }}
		</div>

		@if ($response->status == 'pending')
			<h6 class="text-muted w-100 mb-4">
				<i class="bi bi-eye-fill mr-1"></i> <em>{{ __('general.post_pending_review') }}</em>
			</h6>
		@endif

		@if ($response->status == 'schedule')
			<h6 class="text-muted w-100 mb-4">
				<i class="bi-calendar-fill mr-1"></i> <em>{{ __('general.date_schedule') }} {{ Helper::formatDateSchedule($response->scheduled_date) }}</em>
			</h6>
		@endif

	<div class="media">
		<span class="rounded-circle mr-3 position-relative">
			<a href="{{$creatorLive ? url('live', $response->creator->username) : url($response->creator->username)}}">

				@if (auth()->check() && $creatorLive)
					<span class="live-span">{{ __('general.live') }}</span>
				@endif

				<img src="{{ Helper::getFile(config('path.avatar').$response->creator->avatar) }}" alt="{{$response->creator->hide_name == 'yes' ? $response->creator->username : $response->creator->name}}" class="rounded-circle avatarUser" width="60" height="60">
				</a>
		</span>

		<div class="media-body">
				<h5 class="mb-0 font-montserrat">
					<a href="{{url($response->creator->username)}}">
					{{$response->creator->hide_name == 'yes' ? $response->creator->username : $response->creator->name}}
				</a>

				@if($response->creator->verified_id == 'yes')
					<small class="verified" title="{{__('general.verified_account')}}"data-toggle="tooltip" data-placement="top">
						<i class="bi bi-patch-check-fill"></i>
					</small>
				@endif

				<small class="text-muted font-14">{{'@'.$response->creator->username}}</small>

				@if (auth()->check() && auth()->user()->id == $response->creator->id)
				<a href="javascript:void(0);" class="text-muted float-right" id="dropdown_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
					<i class="fa fa-ellipsis-h"></i>
				</a>

				<!-- Target -->
				<button class="d-none copy-url" id="url{{$response->id}}" data-clipboard-text="{{url($response->creator->username.'/post', $response->id)}}">{{__('general.copy_link')}}</button>

				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown_options">
					@if (request()->path() != $response->creator->username.'/post/'.$response->id)
						<a class="dropdown-item mb-1" href="{{url($response->creator->username.'/post', $response->id)}}"><i class="bi bi-box-arrow-in-up-right mr-2"></i> {{__('general.go_to_post')}}</a>
					@endif

					@if ($response->status == 'active')
						<a class="dropdown-item mb-1 pin-post" href="javascript:void(0);" data-id="{{$response->id}}">
							<i class="bi bi-pin mr-2"></i> {{$response->fixed_post == '0' ? __('general.pin_to_your_profile') : __('general.unpin_from_profile') }}
						</a>
					@endif

					<button class="dropdown-item mb-1" onclick="$('#url{{$response->id}}').trigger('click')"><i class="feather icon-link mr-2"></i> {{__('general.copy_link')}}</button>

					<button type="button" class="dropdown-item mb-1" data-toggle="modal" data-target="#editPost{{$response->id}}">
						<i class="bi bi-pencil mr-2"></i> {{__('general.edit_post')}}
					</button>

					{!! Form::open([
						'method' => 'POST',
						'url' => "update/delete/$response->id",
						'class' => 'd-inline'
					]) !!}

					@if (isset($inPostDetail))
					{!! Form::hidden('inPostDetail', 'true') !!}
				@endif

					{!! Form::button('<i class="feather icon-trash-2 mr-2"></i> '.__('general.delete_post'), ['class' => 'dropdown-item mb-1 actionDelete']) !!}
					{!! Form::close() !!}
	      </div>

				<div class="modal fade modalEditPost" id="editPost{{$response->id}}" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header border-bottom-0">
							<h5 class="modal-title">{{__('general.edit_post')}}</h5>
							<button type="button" class="close close-inherit" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">
									<i class="bi bi-x-lg"></i>
								</span>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" action="{{url('update/edit')}}" enctype="multipart/form-data" class="formUpdateEdit">
								@csrf
								<input type="hidden" name="id" value="{{$response->id}}" />
							<div class="card mb-4">
								<div class="blocked display-none"></div>
								<div class="card-body pb-0">

									<div class="media">
										<div class="media-body">
										<textarea name="description" rows="{{ mb_strlen($response->description) >= 500 ? 10 : 5 }}" cols="40" placeholder="{{__('general.write_something')}}" class="form-control border-0 updateDescription custom-scrollbar">{{$response->description}}</textarea>
									</div>
								</div><!-- media -->

										<input class="custom-control-input d-none customCheckLocked" type="checkbox" {{$response->locked == 'yes' ? 'checked' : ''}}  name="locked" value="yes">

										<!-- Alert -->
										<div class="alert alert-danger my-3 display-none errorUdpate">
										 <ul class="list-unstyled m-0 showErrorsUdpate small"></ul>
									 </div><!-- Alert -->

								</div><!-- card-body -->

								<div class="card-footer bg-white border-0 pt-0">
									<div class="justify-content-between align-items-center">

										<div class="form-group @if ($response->price == 0.00) display-none @endif price">
											<div class="input-group mb-2">
											<div class="input-group-prepend">
												<span class="input-group-text">{{$settings->currency_symbol}}</span>
											</div>
													<input class="form-control isNumber" value="{{$response->price != 0.00 ? $response->price : null}}" autocomplete="off" name="price" placeholder="{{__('general.price')}}" type="text">
											</div>
										</div><!-- End form-group -->

										@if ($mediaCount == 0 && $response->locked == 'yes')
										<div class="form-group @if (! $response->title) display-none @endif titlePost">
											<div class="input-group mb-2">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="bi-type"></i></span>
											</div>
													<input class="form-control @if ($response->title) active @endif" value="{{$response->title ? $response->title : null}}" maxlength="100" autocomplete="off" name="title" placeholder="{{__('admin.title')}}" type="text">
											</div>
											<small class="form-text text-muted mb-4 font-13">
				                {{ __('general.title_post_info', ['numbers' => 100]) }}
				              </small>
										</div><!-- End form-group -->
									@endif

										@if ($response->price == 0.00)
										<button type="button" class="btn btn-upload btn-tooltip e-none align-bottom setPrice @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.price_post_ppv')}}">
											<i class="feather icon-tag f-size-25 align-bottom"></i>
										</button>
									@endif

									@if ($response->price == 0.00)
										<button type="button" class="contentLocked btn e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill btn-upload btn-tooltip {{$response->locked == 'yes' ? '' : 'unlock'}}" data-toggle="tooltip" data-placement="top" title="{{__('users.locked_content')}}">
											<i class="feather align-bottom icon-{{$response->locked == 'yes' ? '' : 'un'}}lock f-size-25"></i>
										</button>
									@endif

								@if ($mediaCount == 0 && $response->locked == 'yes')
									<button type="button" class="btn btn-upload btn-tooltip e-none align-bottom @if ($response->title) btn-active-hover @endif setTitle @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.title_post_block')}}">
										<i class="bi-type align-bottom f-size-25"></i>
									</button>
								@endif

										<div class="d-inline-block float-right mt-1">
											<button type="submit" class="btn btn-sm btn-primary rounded-pill float-right btnEditUpdate"><i></i> {{__('users.save')}}</button>
										</div>

									</div>
								</div><!-- card footer -->
							</div><!-- card -->
						</form>
					</div><!-- modal-body -->
					</div><!-- modal-content -->
				</div><!-- modal-dialog -->
			</div><!-- modal -->
			@endif

				@if(auth()->check()
					&& auth()->user()->id != $response->creator->id
					&& $response->locked == 'yes'
					&& $checkUserSubscription && $response->price == 0.00

					|| auth()->check()
						&& auth()->user()->id != $response->creator->id
						&& $response->locked == 'yes'
						&& $checkUserSubscription
						&& $response->price != 0.00
						&& $checkPayPerView

					|| auth()->check()
						&& auth()->user()->id != $response->creator->id
						&& $response->price != 0.00
						&& ! $checkUserSubscription
						&& $checkPayPerView

					|| auth()->check() && auth()->user()->id != $response->creator->id && auth()->user()->role == 'admin' && auth()->user()->permission == 'all'
					|| auth()->check() && auth()->user()->id != $response->creator->id && $response->locked == 'no'
					)
					<a href="javascript:void(0);" class="text-muted float-right" id="dropdown_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						<i class="fa fa-ellipsis-h"></i>
					</a>

					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown_options">

						<!-- Target -->
						<button class="d-none copy-url" id="url{{$response->id}}" data-clipboard-text="{{url($response->creator->username.'/post', $response->id).Helper::referralLink()}}">
							{{__('general.copy_link')}}
						</button>

						@if (request()->path() != $response->creator->username.'/post/'.$response->id)
							<a class="dropdown-item" href="{{url($response->creator->username.'/post', $response->id)}}">
								<i class="bi bi-box-arrow-in-up-right mr-2"></i> {{__('general.go_to_post')}}
							</a>
						@endif

						<button class="dropdown-item" onclick="$('#url{{$response->id}}').trigger('click')">
							<i class="feather icon-link mr-2"></i> {{__('general.copy_link')}}
						</button>

						<button type="button" class="dropdown-item" data-toggle="modal" data-target="#reportUpdate{{$response->id}}">
							<i class="bi bi-flag mr-2"></i>  {{__('admin.report')}}
						</button>

					</div>

			<div class="modal fade modalReport" id="reportUpdate{{$response->id}}" tabindex="-1" role="dialog" aria-hidden="true">
     		<div class="modal-dialog modal-danger modal-sm">
     			<div class="modal-content">
						<div class="modal-header">
              <h6 class="modal-title font-weight-light" id="modal-title-default">
								<i class="fas fa-flag mr-1"></i> {{__('admin.report_update')}}
							</h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="fa fa-times"></i>
              </button>
            </div>

					<!-- form start -->
					<form method="POST" action="{{url('report/update', $response->id)}}" enctype="multipart/form-data">
				  <div class="modal-body">
						@csrf
				    <!-- Start Form Group -->
            <div class="form-group">
              <label>{{__('admin.please_reason')}}</label>
              	<select name="reason" class="form-control custom-select">
                    <option value="copyright">{{__('admin.copyright')}}</option>
                    <option value="privacy_issue">{{__('admin.privacy_issue')}}</option>
                    <option value="violent_sexual">{{__('admin.violent_sexual_content')}}</option>
                  </select>

				  <textarea name="message" rows="" cols="40" maxlength="200" placeholder="{{__('general.message')}} ({{ __('general.optional') }})" class="form-control mt-2 textareaAutoSize"></textarea>
                  </div><!-- /.form-group-->
				      </div><!-- Modal body -->

							<div class="modal-footer">
								<button type="button" class="btn border text-white" data-dismiss="modal">{{__('admin.cancel')}}</button>
								<button type="submit" class="btn btn-xs btn-white sendReport ml-auto"><i></i> {{__('admin.report_update')}}</button>
							</div>
							</form>
     				</div><!-- Modal content -->
     			</div><!-- Modal dialog -->
     		</div><!-- Modal -->
				@endif
			</h5>

				<small class="timeAgo text-muted" data="{{date('c', strtotime($response->date))}}"></small>

				@if ($response->locked == 'no')
				<small class="text-muted type-post" title="{{__('general.public')}}">
					<i class="iconmoon icon-WorldWide mr-1"></i>
				</small>
				@endif

			@if ($response->locked == 'yes')

				<small class="text-muted type-post" title="{{__('users.content_locked')}}">

					<i class="feather icon-lock mr-1"></i>

					@if (auth()->check() && $response->price != 0.00
							&& $checkUserSubscription
							&& ! $checkPayPerView
							|| auth()->check() && $response->price != 0.00
							&& ! $checkUserSubscription
							&& ! $checkPayPerView
						)
						{{ Helper::formatPrice($response->price) }}

					@elseif (auth()->check() && $checkPayPerView)
						{{ __('general.paid') }}
					@endif
				</small>
			@endif
		</div><!-- media body -->
	</div><!-- media -->
</div><!-- card body -->

@if (auth()->check() && auth()->user()->id == $response->creator->id
	|| $response->locked == 'yes' && $mediaCount != 0

	|| auth()->check() && $response->locked == 'yes'
	&& $checkUserSubscription
	&& $response->price == 0.00

	|| auth()->check() && $response->locked == 'yes'
	&& $checkUserSubscription
	&& $response->price != 0.00
	&& $checkPayPerView

	|| auth()->check() && $response->locked == 'yes'
	&& $response->price != 0.00
	&& ! $checkUserSubscription
	&& $checkPayPerView

	|| auth()->check() && auth()->user()->role == 'admin' && auth()->user()->permission == 'all'
	|| $response->locked == 'no'
	)
	<div class="card-body pt-0 pb-3">
		<p class="mb-0 truncated position-relative text-word-break">
			{!! Helper::linkText(Helper::checkText($response->description, $isVideoEmbed ?? null)) !!}
		</p>
		<a href="javascript:void(0);" class="display-none link-border">{{ __('general.view_all') }}</a>
	</div>

@else
	@if ($response->title)
	<div class="card-body pt-0 pb-3">
		<p class="mb-0 update-text position-relative text-word-break font-weight-bold">
			{!! Helper::linkText($response->title) !!}
		</p>
	</div>
	@endif
@endif

		@if (auth()->check() && auth()->user()->id == $response->creator->id

		|| auth()->check() && $response->locked == 'yes'
		&& $checkUserSubscription
		&& $response->price == 0.00

		|| auth()->check() && $response->locked == 'yes'
		&& $checkUserSubscription
		&& $response->price != 0.00
		&& $checkPayPerView

		|| auth()->check() && $response->locked == 'yes'
		&& $response->price != 0.00
		&& ! $checkUserSubscription
		&& $checkPayPerView

		|| auth()->check() && auth()->user()->role == 'admin' && auth()->user()->permission == 'all'
		|| $response->locked == 'no'
		)

	<div class="btn-block">

		@if ($mediaImageVideoTotal <> 0)
			@include('includes.media-post')
		@endif

		@foreach ($response->media as $media)
			@if ($media->music != '')
			<div class="mx-3 border rounded @if ($mediaCount > 1) mt-3 @endif">
				<audio id="music-{{$media->id}}" preload="metadata" class="js-player w-100 @if (!request()->ajax())invisible @endif" controls>
					<source src="{{ Helper::getFile(config('path.music').$media->music) }}" type="audio/mp3">
					Your browser does not support the audio tag.
				</audio>
			</div>
			@endif

			@if ($media->file != '')
			<a href="{{url('download/file', $response->id)}}" class="d-block text-decoration-none @if ($mediaCount > 1) mt-3 @endif">
				<div class="card mb-3 mx-3">
					<div class="row no-gutters">
						<div class="col-md-2 text-center bg-primary">
							<i class="far fa-file-archive m-4 text-white" style="font-size: 48px;"></i>
						</div>
						<div class="col-md-10">
							<div class="card-body">
								<h5 class="card-title text-primary text-truncate mb-0">
									{{ $media->file_name }}.zip
								</h5>
								<p class="card-text">
									<small class="text-muted">{{ $media->file_size }}</small>
								</p>
							</div>
						</div>
					</div>
				</div>
				</a>
			@endif
		@endforeach

		@if ($isVideoEmbed)
				@if (in_array(Helper::videoUrl($isVideoEmbed), array('youtube.com','www.youtube.com','youtu.be','www.youtu.be', 'm.youtube.com')))
					<div class="embed-responsive embed-responsive-16by9 mb-2">
						<iframe class="embed-responsive-item" height="360" src="https://www.youtube.com/embed/{{ Helper::getYoutubeId($isVideoEmbed) }}" allowfullscreen></iframe>
					</div>
				@endif

				@if (in_array(Helper::videoUrl($isVideoEmbed), array('vimeo.com','player.vimeo.com')))
					<div class="embed-responsive embed-responsive-16by9">
						<iframe class="embed-responsive-item" src="https://player.vimeo.com/video/{{ Helper::getVimeoId($isVideoEmbed) }}" allowfullscreen></iframe>
					</div>
				@endif

		@endif

	</div><!-- btn-block -->

@else

	<div class="btn-block p-sm text-center content-locked pt-lg pb-lg px-3 {{$textWhite}}" style="{{$backgroundPostLocked}}">
		<span class="btn-block text-center mb-3"><i class="feather icon-lock ico-no-result border-0 {{$textWhite}}"></i></span>

		@if ($response->creator->planActive() && $response->price == 0.00
				|| $response->creator->free_subscription == 'yes' && $response->price == 0.00)
			<a href="{{ request()->route()->named('profile') ? 'javascript:void(0);' : url($response->creator->username) }}" @guest data-toggle="modal" data-target="#loginFormModal" @else @if (request()->route()->named('profile')) @if ($response->creator->free_subscription == 'yes') data-toggle="modal" data-target="#subscriptionFreeForm" @else data-toggle="modal" data-target="#subscriptionForm" @endif @endif @endguest class="btn btn-primary w-100">
				{{ __('general.content_locked_user_logged') }}
			</a>
		@elseif ($response->creator->planActive() && $response->price != 0.00
				|| $response->creator->free_subscription == 'yes' && $response->price != 0.00)
				<a href="javascript:void(0);" @guest data-toggle="modal" data-target="#loginFormModal" @else @if ($response->status == 'active') data-toggle="modal" data-target="#payPerViewForm" data-mediaid="{{$response->id}}" data-price="{{Helper::formatPrice($response->price, true)}}" data-subtotalprice="{{Helper::formatPrice($response->price)}}" data-pricegross="{{$response->price}}" @endif @endguest class="btn btn-primary w-100">
					@guest
						{{ __('general.content_locked_user_logged') }}
					@else

						@if ($response->status == 'active')
								<i class="feather icon-unlock mr-1"></i> {{ __('general.unlock_post_for') }} {{Helper::formatPrice($response->price)}}

							@else
								{{ __('general.post_pending_review') }}
						@endif
						@endguest
				</a>
		@else
			<a href="javascript:void(0);" class="btn btn-primary disabled w-100">
				{{ __('general.subscription_not_available') }}
			</a>
		@endif

		<ul class="list-inline mt-3">

		@if ($mediaCount == 0)
			<li class="list-inline-item"><i class="bi bi-file-font"></i> {{ __('admin.text') }}</li>
		@endif

@if ($mediaCount != 0)
	@foreach ($allFiles as $media)

		@if ($media->type == 'image')
			<li class="list-inline-item"><i class="feather icon-image"></i> {{$countFilesImage}}</li>
		@endif

		@if ($media->type == 'video')
			<li class="list-inline-item"><i class="feather icon-video"></i> {{$countFilesVideo}} @if ($media->duration_video && $countFilesVideo == 1 || $media->quality_video && $countFilesVideo == 1) <small class="ml-1">@if ($media->quality_video)<span class="quality-video">{{ $media->quality_video }}</span>@endif {{ $media->duration_video }}</small> @endif</li>
		@endif

		@if ($media->type == 'music')
			<li class="list-inline-item"><i class="feather icon-mic"></i> {{$countFilesAudio}}</li>
			@endif

			@if ($media->type == 'file')
			<li class="list-inline-item"><i class="far fa-file-archive"></i> {{$media->file_size}}</li>
		@endif

	@endforeach
	@endif
</ul>

</div><!-- btn-block parent -->

	@endif

@if ($response->status == 'active')
<div class="card-footer bg-white border-top-0 rounded-large">
    <h4 class="mb-2">
			@php
			$likeActive = auth()->check() && auth()->user()->likes()->where('updates_id', $response->id)->where('status','1')->first();
			$bookmarkActive = auth()->check() && auth()->user()->bookmarks()->where('updates_id', $response->id)->first();

			if(auth()->check() && auth()->user()->id == $response->creator->id

			|| auth()->check() && $response->locked == 'yes'
			&& $checkUserSubscription
			&& $response->price == 0.00

			|| auth()->check() && $response->locked == 'yes'
			&& $checkUserSubscription
			&& $response->price != 0.00
			&& $checkPayPerView

			|| auth()->check() && $response->locked == 'yes'
			&& $response->price != 0.00
			&& ! $checkUserSubscription
			&& $checkPayPerView

			|| auth()->check() && auth()->user()->role == 'admin' && auth()->user()->permission == 'all'
			|| auth()->check() && $response->locked == 'no') {
				$buttonLike = 'likeButton';
				$buttonBookmark = 'btnBookmark';
			} else {
				$buttonLike = null;
				$buttonBookmark = null;
			}
			@endphp

			<a class="pulse-btn btnLike @if ($likeActive)active @endif {{$buttonLike}} text-muted mr-14px" href="javascript:void(0);" @guest data-toggle="modal" data-target="#loginFormModal" @endguest @auth data-id="{{$response->id}}" @endauth>
				<i class="@if($likeActive)fas @else far @endif fa-heart"></i>
			</a>

			<span class="@auth @if (auth()->user()->checkRestriction($response->creator->id)) buttonDisabled @else text-muted @endif @else text-muted @endauth disabled mr-14px @auth @if (! isset($inPostDetail) && $buttonLike) pulse-btn toggleComments @endif @endauth">
				<i class="far fa-comment"></i>
			</span>

			<a class="pulse-btn text-muted text-decoration-none mr-14px" href="javascript:void(0);" title="{{__('general.share')}}" data-toggle="modal" data-target="#sharePost{{$response->id}}">
				<i class="feather icon-share"></i>
			</a>

			<!-- Share modal -->
			<div class="modal fade" id="sharePost{{$response->id}}" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header border-bottom-0">
						<button type="button" class="close close-inherit" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
						</button>
					</div>
					<div class="modal-body">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-3 col-6 mb-3">
									<a href="https://www.facebook.com/sharer/sharer.php?u={{url($response->creator->username.'/post', $response->id).Helper::referralLink()}}" title="Facebook" target="_blank" class="social-share text-muted d-block text-center h6">
										<i class="fab fa-facebook-square facebook-btn"></i>
										<span class="btn-block mt-3">Facebook</span>
									</a>
								</div>
								<div class="col-md-3 col-6 mb-3">
									<a href="https://twitter.com/intent/tweet?url={{url($response->creator->username.'/post', $response->id).Helper::referralLink()}}&text={{ e( $response->creator->hide_name == 'yes' ? $response->creator->username : $response->creator->name ) }}" data-url="{{url($response->creator->username.'/post', $response->id)}}" class="social-share text-muted d-block text-center h6" target="_blank" title="Twitter">
										<i class="bi-twitter-x text-dark"></i> <span class="btn-block mt-3">Twitter</span>
									</a>
								</div>
								<div class="col-md-3 col-6 mb-3">
									<a href="whatsapp://send?text={{url($response->creator->username.'/post', $response->id).Helper::referralLink()}}" data-action="share/whatsapp/share" class="social-share text-muted d-block text-center h6" title="WhatsApp">
										<i class="fab fa-whatsapp btn-whatsapp"></i> <span class="btn-block mt-3">WhatsApp</span>
									</a>
								</div>

								<div class="col-md-3 col-6 mb-3">
									<a href="sms:?&body={{ __('general.check_this') }} {{url($response->creator->username.'/post', $response->id).Helper::referralLink()}}" class="social-share text-muted d-block text-center h6" title="{{ __('general.sms') }}">
										<i class="fa fa-sms"></i> <span class="btn-block mt-3">{{ __('general.sms') }}</span>
									</a>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			</div>
			<!-- modal share -->

	@auth
		@if (auth()->user()->id != $response->creator->id
					&& $checkUserSubscription && $response->price == 0.00
					&& $settings->disable_tips == 'off'

					|| auth()->user()->id != $response->creator->id
					&& $checkUserSubscription
					&& $response->price != 0.00
					&& $checkPayPerView
					&& $settings->disable_tips == 'off'

					|| auth()->check() && $response->locked == 'yes'
					&& $response->price != 0.00
					&& ! $checkUserSubscription
					&& $checkPayPerView
					&& $settings->disable_tips == 'off'

					|| auth()->user()->id != $response->creator->id
					&& $response->locked == 'no'
					&& $settings->disable_tips == 'off'
					)
<a href="javascript:void(0);" data-toggle="modal" title="{{__('general.tip')}}" data-target="#tipForm" class="pulse-btn text-muted text-decoration-none" @auth data-id="{{$response->id}}" data-cover="{{Helper::getFile(config('path.cover').$response->creator->cover)}}" data-avatar="{{Helper::getFile(config('path.avatar').$response->creator->avatar)}}" data-name="{{$response->creator->hide_name == 'yes' ? $response->creator->username : $response->creator->name}}" data-userid="{{$response->creator->id}}" @endauth>
<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
  <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
  <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path fill-rule="evenodd" d="M8 13.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
</svg>

				<h6 class="d-inline font-weight-lighter">@lang('general.tip')</h6>
			</a>
		@endif
	@endauth

			<a href="javascript:void(0);" @guest data-toggle="modal" data-target="#loginFormModal" @endguest class="pulse-btn @if ($bookmarkActive) text-primary @else text-muted @endif float-right {{$buttonBookmark}}" @auth data-id="{{$response->id}}" @endauth>
				<i class="@if ($bookmarkActive)fas @else far @endif fa-bookmark"></i>
			</a>
		</h4>

		<div class="w-100 mb-3 containerLikeComment">
			<span class="countLikes text-muted dot-item">
				{{ trans_choice('general.like_likes', $totalLikes, ['total' => number_format($totalLikes)]) }}
			</span> 
			<span class="text-muted totalComments dot-item @auth @if (! isset($inPostDetail) && $buttonLike)toggleComments @endif @endauth">
				{{ trans_choice('general.comment_comments', $totalComments, ['total' => number_format($totalComments)]) }}
			</span>

			@if ($response->video_views)
			<span class="text-muted dot-item">
				<i class="bi-play mr-1"></i> {{ Helper::formatNumber($response->video_views) }}
			</span>
			@endif
		</div>

@auth

@if (! auth()->user()->checkRestriction($response->creator->id))
<div class="container-comments @if ( ! isset($inPostDetail)) display-none @endif">

<div class="container-media">
@if($response->comments->count() != 0)

	@php
	  $comments = $response->comments()
	  	->with(['user:id,name,username,avatar,hide_name,verified_id', 'replies', 'likes'])
		->take($settings->number_comments_show)
		->orderBy('id', 'DESC')->get();

	  $data = [];

	  if ($comments->count()) {
	      $data['reverse'] = collect($comments->values())->reverse();
	  } else {
	      $data['reverse'] = $comments;
	  }

	  $dataComments = $data['reverse'];
	  $counter = ($response->comments()->count() - $settings->number_comments_show);
	@endphp

	@if (auth()->user()->id == $response->creator->id

		|| $response->locked == 'yes'
		&& $checkUserSubscription
		&& $response->price == 0.00

		|| $response->locked == 'yes'
		&& $checkUserSubscription
		&& $response->price != 0.00
		&& $checkPayPerView

		|| auth()->check() && $response->locked == 'yes'
		&& $response->price != 0.00
		&& ! $checkUserSubscription
		&& $checkPayPerView

		|| auth()->user()->role == 'admin'
		&& auth()->user()->permission == 'all'
		|| $response->locked == 'no')

		@include('includes.comments')

@endif

@endif
	</div><!-- container-media -->

	@if (auth()->user()->id == $response->creator->id

		|| $response->locked == 'yes'
		&& $checkUserSubscription
		&& $response->price == 0.00

		|| $response->locked == 'yes'
		&& $checkUserSubscription
		&& $response->price != 0.00
		&& $checkPayPerView

		|| auth()->check() && $response->locked == 'yes'
		&& $response->price != 0.00
		&& ! $checkUserSubscription
		&& $checkPayPerView

		|| auth()->user()->role == 'admin'
		&& auth()->user()->permission == 'all'
		|| $response->locked == 'no')

		<div class="alert alert-danger alert-small dangerAlertComments display-none">
			<ul class="list-unstyled m-0 showErrorsComments"></ul>
		</div><!-- Alert -->

		<div class="isReplyTo display-none w-100 bg-light py-2 px-3 mb-3 rounded">
			{{ __('general.replying_to') }} <span class="username-reply"></span>

			<span class="float-right c-pointer cancelReply" title="{{ __('admin.cancel') }}">
				<i class="bi-x-lg"></i>
			</span>
		</div>

		<div class="media position-relative pt-3 border-top">
			<div class="blocked display-none"></div>
			<span href="#" class="float-left">
				<img src="{{ Helper::getFile(config('path.avatar').auth()->user()->avatar) }}" class="rounded-circle mr-1 avatarUser" width="40">
			</span>
			<div class="media-body">
				<form action="{{url('comment/store')}}" method="post" class="comments-form">
					@csrf
					<input type="hidden" name="update_id" value="{{$response->id}}" />
					<input class="isReply" type="hidden" name="isReply" value="" />

					<div>
					<span class="triggerEmoji" data-toggle="dropdown">
						<i class="bi-emoji-smile"></i>
					</span>

					<div class="dropdown-menu dropdown-menu-right dropdown-emoji custom-scrollbar" aria-labelledby="dropdownMenuButton">
				    @include('includes.emojis')
				  </div>
				</div>

				<input type="text" name="comment" class="form-control comments inputComment emojiArea border-0" autocomplete="off" placeholder="{{__('general.write_comment')}}"></div>
				</form>
			</div>
			@endif

			</div><!-- container-comments -->
		@endif

			@endauth
  </div><!-- card-footer -->
	@endif
</div><!-- card -->

@if (request()->is('/') && $loop->first && $users->count() != 0
	|| request()->is('explore') && $loop->first && $users->count() != 0
	|| request()->is('my/bookmarks') && $loop->first && $users->count() != 0
	|| request()->is('my/purchases') && $loop->first && $users->count() != 0
	|| request()->is('my/likes') && $loop->first && $users->count() != 0
	)
	<div class="p-3 d-lg-none">
		@include('includes.explore_creators')
	</div>
@endif

@endforeach

@if (! isset($singlePost))
<div class="card mb-3 pb-4 loadMoreSpin d-none rounded-large shadow-large">
	<div class="card-body">
		<div class="media">
		<span class="rounded-circle mr-3">
			<span class="item-loading position-relative loading-avatar"></span>
		</span>
		<div class="media-body">
			<h5 class="mb-0 item-loading position-relative loading-name"></h5>
			<small class="text-muted item-loading position-relative loading-time"></small>
		</div>
	</div>
</div>
	<div class="card-body pt-0 pb-3">
		<p class="mb-1 item-loading position-relative loading-text-1"></p>
		<p class="mb-1 item-loading position-relative loading-text-2"></p>
		<p class="mb-0 item-loading position-relative loading-text-3"></p>
	</div>
</div>
@endif

@php
if (request()->ajax()) {
	$getHasPages = $updates->count() < $settings->number_posts_show ? false : true;
} else {

	if (request()->route()->named('profile')) {
		$getHasPages = $updates->count() < $settings->number_posts_show ? false : true;
	} else {
		$getHasPages = $hasPages ?? null;
	}
}
@endphp

@if ($getHasPages && ! isset($isPostPinned))
	<button rel="next" class="btn btn-primary w-100 text-center loadPaginator d-none" id="paginator">
		{{__('general.loadmore')}}
	</button>
@endif
