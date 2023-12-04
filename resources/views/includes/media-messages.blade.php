@if ($mediaImageVideoTotal == 1)

@foreach ($mediaImageVideo as $media)
	@php
		$urlImg = url('files/messages', $msg->id).'/'.$media->file;

		if ($media->width && $media->height > $media->width) {
			$styleImgVertical = 'img-vertical-lg';
		} else {
			$styleImgVertical = null;
		}
	@endphp

	@if ($media->type == 'image')
		<div class="media-grid-1">
			<a href="{{ $urlImg }}" class="media-wrapper glightbox {{$styleImgVertical}}" data-gallery="gallery{{$msg->id}}" style="background-image: url('{{$urlImg}}?w=960&h=980')">
					<img src="{{$urlImg}}?w=960&h=980" {!! $media->width ? 'width="'. $media->width .'"' : null !!} {!! $media->height ? 'height="'. $media->height .'"' : null !!} class="post-img-grid">
			</a>
		</div>
@endif

@if ($media->type == 'video')
	<div class="container-media-msg h-auto">
		<video class="js-player {{$classInvisible}}" controls style="height: 400px;" @if (!$media->video_poster) preload="metadata" @endif @if ($media->video_poster) preload="none" data-poster="{{ Helper::getFile(config('path.messages').$media->video_poster) }}" @endif>
		<source src="{{Helper::getFile(config('path.messages').$media->file)}}" type="video/mp4" />
	</video>
</div>
@endif

@endforeach

@endif

@if ($mediaImageVideoTotal >= 2)

	<div class="media-grid-{{ $mediaImageVideoTotal > 4 ? 4 : $mediaImageVideoTotal }}">

@foreach ($mediaImageVideo as $media)
	@php

	if ($media->type == 'video') {
		$urlMedia =  Helper::getFile(config('path.messages').$media->file);
		$videoPoster = $media->video_poster ? Helper::getFile(config('path.messages').$media->video_poster) : false;
	} else {
		$urlMedia =  url("files/messages", $msg->id).'/'.$media->file;
		$videoPoster = null;
	}

		$nth++;
	@endphp

		@if ($media->type == 'image' || $media->type == 'video')

			<a href="{{ $urlMedia }}" class="media-wrapper glightbox" data-gallery="gallery{{$msg->id}}" style="background-image: url('{{ $videoPoster ?? $urlMedia }}?w=960&h=980')">

				@if ($nth == 4 && $mediaImageVideoTotal > 4)
		        <span class="more-media">
							<h2>+{{ $mediaImageVideoTotal - 4 }}</h2>
						</span>
		    @endif

				@if ($media->type == 'video')
					<span class="button-play">
						<i class="bi bi-play-fill text-white"></i>
					</span>
				@endif

				@if (! $videoPoster && $media->type == 'video')
					<video playsinline muted preload="metadata" class="video-poster-html">
						<source src="{{ $urlMedia }}" type="video/mp4" />
					</video>
				@endif

				@if ($videoPoster)
					<img src="{{ $videoPoster ?? $urlMedia }}?w=960&h=980" {!! $media->width ? 'width="'. $media->width .'"' : null !!} {!! $media->height ? 'height="'. $media->height .'"' : null !!} class="post-img-grid">
				@endif
			</a>

		@endif

@endforeach

</div><!-- img-grid -->

@endif

@foreach ($msg->media as $media)

	@if ($media->type == 'music')
	<div class="wrapper-media-music @if ($mediaCount >= 2) mt-2 @endif">
		<audio class="js-player {{$classInvisible}}" preload="metadata" controls>
		<source src="{{Helper::getFile(config('path.messages').$media->file)}}" type="audio/mp3">
		Your browser does not support the audio tag.
	</audio>
</div>
	@endif

@if ($media->type == 'zip')
	<a href="{{url('download/message/file', $msg->id)}}" class="d-block text-decoration-none @if ($mediaCount >= 2) mt-2 @endif">
	 <div class="card">
		 <div class="row no-gutters">
			 <div class="col-md-3 text-center bg-primary">
				 <i class="far fa-file-archive m-2 text-white" style="font-size: 40px;"></i>
			 </div>
			 <div class="col-md-9">
				 <div class="card-body py-2 px-4">
					 <h6 class="card-title text-primary text-truncate mb-0">
						 {{$media->file_name}}.zip
					 </h6>
					 <p class="card-text">
						 <small class="text-muted">{{$media->file_size}}</small>
					 </p>
				 </div>
			 </div>
		 </div>
	 </div>
	 </a>
	 @endif

 @endforeach

 @if ($msg->tip == 'yes')
	<div class="card">
		 <div class="row no-gutters">
			 <div class="col-md-12">
				 <div class="card-body py-2 px-4">
					 <h6 class="card-title text-primary text-truncate mb-0">
						 <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-coin mr-1" viewBox="0 0 16 16">
							 <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
							 <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
							 <path fill-rule="evenodd" d="M8 13.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
						 </svg> {{__('general.tip'). ' -- ' .Helper::priceWithoutFormat($msg->tip_amount)}}
					 </h6>
				 </div>
			 </div>
		 </div>
	 </div>
	 @endif

@if ($mediaCount == 0)
	{!! $chatMessage !!}
@endif
