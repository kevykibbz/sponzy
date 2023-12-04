@if ($totalReplies > 0)
<div class="btn-block mb-4 text-left wrap-container" data-total="{{ $totalReplies }}" data-id="{{ $comment->id }}">
  <a href="javascript:void(0)" class="loadMoreReplies">
    <span class="line-replies"></span>{{ trans('general.view_replies') }}
    (<span class="counter">{{$totalReplies}}</span>)
  </a>
</div>
@endif

@if (isset($getReplies))
@foreach ($dataReplies as $reply)
<div class="comments media li-group pt-3 pb-3 pl-5 isCommentReply" data="{{$comment->id}}">
  <a class="float-left" href="{{url($reply->user->username)}}">
    <img class="rounded-circle mr-3 avatarUser" src="{{Helper::getFile(config('path.avatar').$reply->user->avatar)}}" width="40"></a>
    <div class="media-body">
      <h6 class="media-heading mb-0">
      <a href="{{url($reply->user->username)}}">
        {{$reply->user->hide_name == 'yes' ? $reply->user->username : $reply->user->name}}
      </a>

      @if ($reply->user->verified_id == 'yes')
        <small class="verified">
  						<i class="bi bi-patch-check-fill"></i>
  					</small>
      @endif

    </h6>
        <p class="list-grid-block p-text mb-0 text-word-break updateComment isReply{{$reply->id}}">{!! Helper::linkText(Helper::checkText($reply->reply)) !!}</p>
        <span class="small sm-font sm-date text-muted timeAgo mr-2" data="{{date('c', strtotime($reply->created_at))}}"></span>
        <span class="small sm-font sm-date text-muted mr-2 c-pointer font-weight-bold replyButton" data="{{$comment->id}}" data-username="{{'@'.$reply->user->username}}">{{ __('general.reply') }}</span>
        @if ($reply->user_id == auth()->id() || $comment->updates->user()->id == auth()->id())
        <div class="dropdown d-inline align-middle">
          <span href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bi-three-dots"></i>
          </span>
        
          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item editComment{{$comment->id}}" data-id="{{$reply->id}}" data-type="isReplies" data-comment="{{ $reply->reply }}"  href="javascript:void(0);" data-toggle="modal" data-target="#modalEditReply{{$reply->id}}">
              <i class="bi-pencil mr-2"></i> {{ __('admin.edit') }}
            </a>
            <a class="dropdown-item delete-replies" data="{{$reply->id}}" href="javascript:void(0);">
              <i class="feather icon-trash-2 mr-2"></i> {{ __('general.delete') }}
            </a>
          </div>
        </div>
      @endif

      <span class="likeComment c-pointer float-right pulse-btn" data-id="{{$reply->id}}">
        <i class="@if (auth()->check() && $reply->likes->where('user_id', auth()->id())->first()) fas fa-heart text-red mr-1 @else far fa-heart mr-1 @endif"></i>
          <span class="countCommentsLikes">{{ $reply->likes->count() != 0 ? $reply->likes->count() : null }}</span>
      </span>
      </div><!-- media-body -->

      @include('includes.modal-edit-comment', ['data' => $reply, 'isReply' => true, 'modalId' => 'modalEditReply'.$reply->id])

    </div>
  @endforeach
@endif