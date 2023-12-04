@if (! isset($single) && $messages->count() == 10)
<div class="btn-block text-center wrap-container containerLoadMore" data-id="{{ $user->id }}">
  <a href="javascript:void(0)" class="loadMoreMessages d-none" id="paginatorChat">
    — {{ __('general.load_messages') }}
  </a>
</div>
@endif

@foreach ($messages as $msg)
  @php
  $mediaCount = $msg->media->count();
  $allFiles = $msg->media()->groupBy('type')->get();
  $getFirstFile =$msg->media()->whereIn('type', ['image', 'video'])->first();
  $countFilesImage = $msg->media->where('type', 'image')->count();
	$countFilesVideo = $msg->media->where('type', 'video')->count();
	$countFilesAudio = $msg->media->where('type', 'music')->count();
  $mediaImageVideoTotal = $msg->media->whereIn('type', ['image', 'video'])->count();
  $chatMessage = $msg->message ? Helper::linkText(Helper::checkText($msg->message)) : null;
  $classInvisible = ! request()->ajax() ? 'invisible' : null;
  $nth = 0; // nth foreach nth-child(3n-1)
  $mediaImageVideo = $msg->media()
				->whereIn('type', ['image', 'video'])
				->get();

  if ($getFirstFile && $getFirstFile->type == 'image') {
    $urlMedia =  url('media/storage/focus/message', $getFirstFile->id);
    $backgroundPostLocked = 'background: url('.$urlMedia.') no-repeat center center #b9b9b9; background-size: cover;';
    $textWhite = 'text-white';

  } elseif ($getFirstFile && $getFirstFile->type == 'video' && $getFirstFile->video_poster) {
      $videoPoster = url('media/storage/focus/message', $getFirstFile->id);
      $backgroundPostLocked = 'background: url('.$videoPoster.') no-repeat center center #b9b9b9; background-size: cover;';
      $textWhite = 'text-white';

  } else {
    $backgroundPostLocked = null;
    $textWhite = null;
  }
@endphp

@if ($msg->sender->id == auth()->user()->id)
<div data="{{$msg->id}}" class="media py-2 chatlist">
<div class="media-body position-relative">
  @if ($msg->tip == 'no')
  <a href="javascript:void(0);" class="btn-removeMsg removeMsg" data="{{$msg->id}}" title="{{__('general.delete')}}">
    <i class="fa fa-trash-alt"></i>
    </a>
  @endif
  <div class="@if ($mediaCount == 0) float-right @else wrapper-msg-left @endif message position-relative text-word-break @if ($mediaCount == 0 && $msg->tip == 'no') bg-primary @else media-container @endif text-white @if ($msg->format == 'zip') w-50 @else w-auto @endif  rounded-bottom-right-0">
      @include('includes.media-messages')
  </div>

  @if ($mediaCount != 0 && $msg->message != '')
    <div class="w-100 d-inline-block">
      <div class="w-auto position-relative text-word-break message bg-primary float-right text-white rounded-top-right-0">
        {!! $chatMessage !!}
      </div>
    </div>
@endif
    <span class="w-100 d-block text-muted float-right text-right pr-1 small">
      @if ($msg->price != 0.00)
        {{ Helper::formatPrice($msg->price) }} <i class="feather icon-lock mr-1"></i> -
      @endif
      <span class="timeAgo" data="{{ date('c', strtotime($msg->created_at)) }}"></span>
    </span>
</div><!-- media-body -->

<a href="{{url($msg->sender->username)}}" class="align-self-end ml-3 d-none">
  <img src="{{Helper::getFile(config('path.avatar').$msg->sender->avatar)}}" class="rounded-circle" width="50" height="50">
</a>
</div><!-- media -->
@else
<div data="{{$msg->id}}" class="media py-2 chatlist">
<a href="{{url($msg->sender->username)}}" class="align-self-end mr-3">
  <img src="{{Helper::getFile(config('path.avatar').$msg->sender->avatar)}}" class="rounded-circle avatar-chat" width="50" height="50">
</a>
<div class="media-body position-relative">
  @if ($msg->price != 0.00 && ! auth()->user()->checkPayPerViewMsg($msg->id))
    <div class="btn-block p-sm text-center content-locked mb-2 pt-lg pb-lg px-3 {{$textWhite}} custom-rounded float-left" style="{{$backgroundPostLocked}} max-width: 500px;">
    		<span class="btn-block text-center mb-3">
          <i class="feather ico-no-result border-0 icon-lock {{$textWhite}}"></i></span>
        <a href="javascript:void(0);" data-toggle="modal" data-target="#payPerViewForm" data-mediaid="{{$msg->id}}" data-price="{{Helper::formatPrice($msg->price, true)}}" data-subtotalprice="{{Helper::formatPrice($msg->price)}}" data-pricegross="{{$msg->price}}" class="btn btn-primary w-100">
          <i class="feather icon-unlock mr-1"></i> {{ __('general.unlock_for') }} {{Helper::formatPrice($msg->price)}}
        </a>

    <ul class="list-inline mt-3">
          @if ($mediaCount == 0)
      			<li class="list-inline-item"><i class="bi bi-file-font"></i> {{ __('admin.text') }}</li>
      		@endif

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
        </ul>

      </div><!-- btn-block parent -->
    @endif

@if ($msg->price == 0.00 || $msg->price != 0.00 && auth()->user()->checkPayPerViewMsg($msg->id))
  <div class="@if ($mediaCount == 0) float-left @else wrapper-msg-right @endif message position-relative text-word-break @if ($mediaCount == 0 && $msg->tip == 'no') bg-light @else media-container @endif @if ($msg->format == 'zip') w-50 @else w-auto @endif rounded-bottom-left-0">
        @include('includes.media-messages')
  </div>
  @endif

  @if ($mediaCount != 0 && $msg->message != '')
    <div class="w-100 d-inline-block">
      <div class="w-auto position-relative text-word-break message bg-light float-left rounded-top-left-0">
        {!! $chatMessage !!}
      </div>
  </div>
@endif

<span class="w-100 d-block text-muted float-left pl-1 small">
    <span class="timeAgo" data="{{ date('c', strtotime($msg->created_at)) }}"></span>
  @if ($msg->price != 0.00)
    - {{ Helper::formatPrice($msg->price) }} {{ auth()->user()->checkPayPerViewMsg($msg->id) ? __('general.paid') : null }} <i class="feather icon-lock mr-1"></i>
  @endif
</span>
</div><!-- media-body -->
</div><!-- media -->
@endif
@endforeach
