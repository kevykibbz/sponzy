@if ($response->comments->count() > $settings->number_comments_show && $counter >= 1)
<div class="btn-block mb-4 text-center wrap-container" data-total="{{ $response->comments->count() }}" data-id="{{ $response->id }}">
  <a href="javascript:void(0)" class="loadMoreComments">
    <span class="line-replies"></span>{{ __('general.load_comments') }}
    (<span class="counter">{{$counter}}</span>)
  </a>
</div>
@endif

@foreach ($dataComments as $comment)
@php
   $replies = $comment->replies;
   $totalReplies = $replies->count();
@endphp
<div class="wrap-comments{{$comment->id}} wrapComments">
<div class="comments isCommentWrap media li-group pt-3 pb-3" data="{{$comment->id}}">
  <a class="float-left" href="{{url($comment->user->username)}}">
    <img class="rounded-circle mr-3 avatarUser" src="{{Helper::getFile(config('path.avatar').$comment->user->avatar)}}" width="40"></a>
    <div class="media-body">
      <h6 class="media-heading mb-0">
      <a href="{{url($comment->user->username)}}">
        {{$comment->user->hide_name == 'yes' ? $comment->user->username : $comment->user->name}} 
      </a>

      @if ($comment->user->verified_id == 'yes')
        <small class="verified">
  						<i class="bi bi-patch-check-fill"></i>
  					</small>
      @endif

    </h6>
        <p class="list-grid-block p-text mb-0 text-word-break updateComment isComment{{$comment->id}}">{!! Helper::linkText(Helper::checkText($comment->reply)) !!}</p>
        <span class="small sm-font sm-date text-muted timeAgo mr-2" data="{{date('c', strtotime($comment->date))}}"></span>
        <span class="small sm-font sm-date text-muted mr-2 c-pointer font-weight-bold replyButton" data="{{$comment->id}}" data-username="{{'@'.$comment->user->username}}">{{ __('general.reply') }}</span>
        @if ($comment->user_id == auth()->id() || $response->creator->id == auth()->id())
        <div class="dropdown d-inline align-middle">
          <span href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bi-three-dots"></i>
          </span>
        
          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            @if ($comment->user_id == auth()->id())
            <a class="dropdown-item editComment{{$comment->id}}" href="javascript:void(0);" data-toggle="modal" data-target="#modalEditComment{{$comment->id}}">
              <i class="bi-pencil mr-2"></i> {{ __('admin.edit') }}
            </a>
            @endif
            <a class="dropdown-item delete-comment" data="{{$comment->id}}" data-type="isComment" href="javascript:void(0);">
              <i class="feather icon-trash-2 mr-2"></i> {{ __('general.delete') }}
            </a>
          </div>
        </div>
      @endif

      <span class="likeComment c-pointer float-right pulse-btn" data-id="{{$comment->id}}" data-type="isComment">
        <i class="@if (auth()->check() && $comment->likes->where('user_id', auth()->id())->first()) fas fa-heart text-red mr-1 @else far fa-heart mr-1 @endif"></i>
          <span class="countCommentsLikes">{{ $comment->likes->count() != 0 ? $comment->likes->count() : null }}</span>
      </span>
      </div><!-- media-body -->
    </div>

    @include('includes.modal-edit-comment', ['data' => $comment, 'isReply' => false, 'modalId' => 'modalEditComment'.$comment->id])

    @include('includes.replies')
  </div>
  @endforeach
