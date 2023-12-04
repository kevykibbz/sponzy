	<div class="card card-updates h-100 card-user-profile shadow-sm">
	<div class="card-cover" style="background: @if ($response->cover != '') url({{ route('resize', ['path' => 'cover', 'file' => $response->cover, 'size' => 480]) }})  @endif #505050 center center; background-size: cover;"></div>
	<div class="card-avatar @if (Helper::isCreatorLive($getCurrentLiveCreators, $response->id))liveLink @endif" @if (Helper::isCreatorLive($getCurrentLiveCreators, $response->id)) data-url="{{ url('live', $response->username) }}" @endif>

		@if (Helper::isCreatorLive($getCurrentLiveCreators, $response->id))
			<span class="live-span">{{ trans('general.live') }}</span>
			<div class="live-pulse"></div>
		@endif


		<a href="{{url($response->username)}}">
		<img src="{{Helper::getFile(config('path.avatar').$response->avatar)}}" width="95" height="95" alt="{{$response->name}}" class="img-user-small">
		</a>
	</div>
	<div class="card-body text-center">
			<h6 class="card-title @if (Helper::isCreatorLive($getCurrentLiveCreators, $response->id)) pt-4 mt-2 mb-1 @else pt-4 @endif">
				{{$response->hide_name == 'yes' ? $response->username : $response->name}}

				@if ($response->verified_id == 'yes')
					<small class="verified mr-1" title="{{trans('general.verified_account')}}"data-toggle="tooltip" data-placement="top">
						<i class="bi bi-patch-check-fill"></i>
					</small>
				@endif

				@if ($response->featured == 'yes')
				<small class="text-featured" title="{{trans('users.creator_featured')}}" data-toggle="tooltip" data-placement="top">
					<i class="fas fa fa-award"></i>
				</small>
			@endif
			</h6>

			<ul class="list-inline m-0">
				<li class="list-inline-item small"><i class="feather icon-image"></i> {{ Helper::formatNumber($response->media->where('type', 'image')->count()) }}</li>
				<li class="list-inline-item small"><i class="feather icon-video"></i> {{ Helper::formatNumber($response->media->whereIn('type', ['video', 'video_embed'])->count()) }}</li>
				<li class="list-inline-item small"><i class="feather icon-mic"></i> {{ Helper::formatNumber($response->media->where('type', 'music')->count()) }}</li>
				@if ($response->media->where('type', 'file')->groupBy('type')->count() != 0)
				<li class="list-inline-item small"><i class="far fa-file-archive"></i> {{ Helper::formatNumber($response->media->where('type', 'file')->count()) }}</li>
				@endif
			</ul>

			<p class="m-0 py-3 text-muted card-text text-truncate">
				{{ Str::limit($response->story, 100, '...') }}
			</p>
			<a href="{{url($response->username)}}" class="btn btn-1 btn-sm btn-outline-primary">{{trans('general.go_to_page')}}</a>

			<a href="{{url($response->username)}}" class="btn btn-1 btn-sm btn-outline-primary px-3 active">
				@if ($response->plans->where('status', '1')->first() && $response->free_subscription == 'no')
					{{ __('general.price_per_month', ['price' => Helper::priceWithoutFormat($response->getPlan('monthly', 'price'))]) }}
				@endif

				@if ($response->free_subscription == 'yes')
					{{ __('general.free') }}
				@endif
			</a>

	</div>
</div><!-- End Card -->
