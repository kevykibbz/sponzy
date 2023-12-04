<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Models\User;
use App\Models\Comments;
use App\Models\CommentsLikes;
use App\Models\Notifications;
use App\Models\Replies;
use App\Models\Updates;
use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CommentsController extends Controller
{
	 public function __construct(Request $request)
	 {
		$this->request = $request;
	}

	 protected function validator(array $data)
	 {
    	Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		$messages = [
			'comment.required' => trans('general.please_write_something'),
		];

			return Validator::make($data, [
	        	'comment' =>  'required|max:'.config('settings.comment_length').'|min:2',
	        ], $messages);

    }

	 /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
	 public function store(Request $request)
	 {
		$input = $request->all();
		$validator = $this->validator($input);

	    $update = Updates::where('id', $request->update_id)->first();

	   if (! isset($update)) {
	   		return response()->json([
			        'success' => false,
			        'errors' => ['error' => trans('general.error')],
			    ]);
				exit;
	   }

	    if ($validator->fails()) {
	        return response()->json([
			        'success' => false,
			        'errors' => $validator->getMessageBag()->toArray(),
			    ]);
	    }

		$isReply = $request->isReply ? Comments::find($request->isReply) : null;

		if ($isReply) {
			$commentId = $request->isReply;
				$sql              = new Replies();
				$sql->reply       = trim(Helper::checkTextDb($request->comment));
				$sql->comments_id = $commentId;
				$sql->user_id     = auth()->id();
				$sql->updates_id  = $request->update_id;
				$sql->save();

				$idComment = $commentId;
				$typeComment = 'isReply';
				$paddingReply = 'pl-5 isCommentReply';
				$deleteComment = 'delete-replies';
				$idReply = $sql->id;
				$wrapComments = null;
				$wrapCommentsClose = null;
				$modelEditCommentReply = '#modalEditReply'.$sql->id;
				$modal = view('includes.modal-edit-comment', [
					'data' => $sql, 
					'isReply' => true, 
					'modalId' => 'modalEditReply'.$sql->id
				]);
		} else {
			$sql            = new Comments();
			$sql->reply     = trim(Helper::checkTextDb($request->comment));
			$sql->updates_id = $request->update_id;
			$sql->user_id   = auth()->id();
			$sql->save();

			$idComment = $sql->id;
			$typeComment = 'isComment';
			$paddingReply = null;
			$deleteComment = 'delete-comment';
			$idReply = null;
			$wrapComments = '<div class="wrap-comments'.$idComment.' wrapComments">';
			$wrapCommentsClose = '</div>';
			$modelEditCommentReply = '#modalEditComment'.$sql->id;
			$modal = view('includes.modal-edit-comment', [
				'data' => $sql, 
				'isReply' => false, 
				'modalId' => 'modalEditComment'.$sql->id
			]);
		}

		$commentReplyId = $isReply ? $idReply : $idComment;

		/*------* SEND NOTIFICATION * ------*/
		if (auth()->id() != $update->user_id  && $update->user()->notify_commented_post == 'yes') {
			// Send Notification //destination, author, type, target
			Notifications::send($update->user_id, auth()->id(), '3', $update->id);
		}

		$nameUser = auth()->user()->hide_name == 'yes' ? auth()->user()->username : auth()->user()->name;
		$verifiedId = auth()->user()->verified_id == 'yes' ? '<small class="verified"> <i class="bi bi-patch-check-fill"></i> </small>' : null;

		$totalComments = $update->totalComments();
		
		// Send Notification Mention
		Helper::sendNotificationMention($sql->reply, $request->update_id);

		return response()->json([
		'success' => true,
		'isReply' => $isReply ? true : false,
		'idComment' => $idComment,
		'total' => trans_choice('general.comment_comments', $totalComments, ['total' => $totalComments]),
		'data' => ''.$wrapComments.'
			<div class="comments media li-group pt-3 pb-3 '.$paddingReply.'" data="'.$idComment.'">
				<a class="float-left" href="'.url(auth()->user()->username).'">
					<img class="rounded-circle mr-3" src="'.Helper::getFile(config('path.avatar').auth()->user()->avatar).'" width="40"></a>
					<div class="media-body">
						<h6 class="media-heading mb-0">
						<a href="'.url(auth()->user()->username).'">
							'.$nameUser.'</a>
							'.$verifiedId.'
							</h6>
							<p class="list-grid-block p-text mb-0 text-word-break updateComment '.$typeComment.$commentReplyId.'">'.Helper::linkText(Helper::checkText($sql->reply)).'</p>
							<span class="small sm-font sm-date text-muted timeAgo mr-2" data="'.date('c', time()).'"></span>
							<span class="small sm-font sm-date text-muted mr-2 c-pointer font-weight-bold replyButton" data="'.$idComment.'" data-username="@'.auth()->user()->username.'">'.__('general.reply').'</span>

							<div class="dropdown d-inline align-middle">
							<span href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="bi-three-dots"></i>
							</span>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
								<a class="dropdown-item editComment'.$commentReplyId.'" href="javascript:void(0);" data-toggle="modal" data-target="'.$modelEditCommentReply.'">
								<i class="bi-pencil mr-2"></i> '.__('admin.edit').'
								</a>
								<a class="dropdown-item delete-comment" data="'.$commentReplyId.'" data-type="'.$typeComment.'" href="javascript:void(0);">
								<i class="feather icon-trash-2 mr-2"></i> '.__('general.delete').'
								</a>
							</div>
							</div>
							<span class="likeComment c-pointer float-right pulse-btn" data-id="'.$commentReplyId.'" data-type="'.$typeComment.'">
							<i class="far fa-heart mr-1"></i> <span class="countCommentsLikes"></span>
							</span>
						</div><!-- media-body -->
					</div>
					'.$wrapCommentsClose.'
					'.$modal.'
					', ]);

	}//<--- End Method

	 /**
   * Edit comment.
   *
   * @return Response
   */
  public function edit(Request $request)
  {
	 $input = $request->all();
	 $validator = $this->validator($input);
	 $comment = $request->isReply ? Replies::whereId($request->id)->whereUserId(auth()->id())->first() : Comments::whereId($request->id)->whereUserId(auth()->id())->first();

	if (! isset($comment)) {
			return response()->json([
				 'success' => false,
				 'errors' => ['error' => trans('general.error')],
			 ]);
			 exit;
	}

	 if ($validator->fails()) {
		 return response()->json([
				 'success' => false,
				 'errors' => $validator->getMessageBag()->toArray(),
			 ]);
	 }

	 $comment->reply = trim(Helper::checkTextDb($request->comment));
	 $comment->save();

	 // Send Notification Mention
	 Helper::sendNotificationMention($comment->reply, $comment->updates_id);

	 return response()->json([
		'success' => true,
		'target' => $request->isReply ? '.isReply'.$comment->id : '.isComment'.$comment->id,
		'comment' => Helper::linkText(Helper::checkText($comment->reply)),
	 ]);
	}//<--- End Method


	/**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
	public function destroy($id)
	{
		$comment = Comments::with(['updates:id,user_id'])->findOrFail($id);

		if ($comment->user_id == auth()->id() || $comment->updates->user_id == auth()->id()) {

			$comment->likes()->delete();

			// Delete Notification
			Notifications::where('author', $comment->user_id)
			->where('target', $comment->updates_id)
			->where('created_at', $comment->date)
			->delete();

			$comment->delete();

			// Delete replies
			$comment->replies()->delete();

			$totalComments = $comment->updates->totalComments();

			return response()->json([
				'success' => true,
				'total' => trans_choice('general.comment_comments', $totalComments, ['total' => $totalComments])
			]);

		} else {
			return response()->json([
				'success' => false,
				'error' => trans('general.error')
			]);
		}

	}//<--- End Method

	/**
   * Load More Comments
   *
   * @param  \Illuminate\Http\Request  $request
   * @return Response
   */
	public function loadmore(Request $request)
	{
		$id       = $request->input('id');
		$postId   = $request->input('post');
		$skip     = $request->input('skip');
		$response = Updates::findOrFail($postId);

		$page     = $request->input('page');
		$comments = $response->comments()->skip($skip)->take(config('settings.number_comments_show'))->orderBy('id', 'DESC')->get();
	    $data = [];

	  if ($comments->count()) {
	      $data['reverse'] = collect($comments->values())->reverse();
	  } else {
	      $data['reverse'] = $comments;
	  }

	 	$dataComments = $data['reverse'];
		$counter = ($response->comments()->count() - config('settings.number_comments_show') - $skip);

		return response()->json([
			'comments' => view('includes.comments',
					[
						'dataComments' => $dataComments,
						'comments' => $comments,
						'response' => $response,
						'counter' => $counter
						]
					)->render()
		]);

	}//<--- End Method

	public function like()
	{
		$id   = $this->request->comment_id;
		$type = $this->request->typeComment;

		// Find Comment
		$comment = $type == 'isComment' ? Comments::whereId($id)->with(['user'])->firstOrFail() : Replies::whereId($id)->with(['user'])->firstOrFail();

		// Find Like on comments likes if exists
		$commentLike = CommentsLikes::whereUserId(auth()->id())
		->whereCommentsId($id)
		->orWhere('replies_id', $id)
		->whereUserId(auth()->id())
		->first();

		if ($commentLike) {
			 $commentLike->delete();

			 Notifications::where('destination', $comment->user_id)
			 ->where('author', auth()->id())
			 ->where('target', $comment->updates_id)
			 ->where('type','4')
			 ->delete();

			 return response()->json([
				 'success' => true,
				 'type' => 'unlike',
				 'count' => $comment->likes()->count()
		 ]);

	 } else {
			$sql = new CommentsLikes();
			$sql->user_id = auth()->id();

			if ($type == 'isComment') {
				$sql->comments_id = $comment->id;
			} else {
				$sql->replies_id = $comment->id;
			}

			$sql->save();

		 if ($comment->user_id != auth()->id() && $comment->user->notify_liked_comment == 'yes') {
			 Notifications::send($comment->user_id, auth()->id(), '4', $comment->updates_id);
		 }

		 return response()->json([
			 'success' => true,
			 'type' => 'like',
			 'count' => $comment->likes()->count()
	 ]);

	 }

	}//<--- End Method

}
