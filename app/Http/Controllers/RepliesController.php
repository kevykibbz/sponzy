<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Models\User;
use App\Models\Replies;
use App\Models\Comments;
use App\Models\Updates;
use App\Models\Notifications;
use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RepliesController extends Controller 
{
	 public function __construct(Request $request)
	 {
		$this->request = $request;
	}

	/**
   * Load Ajax Replies
   *
   * @return View
   */
	public function loadmore()
	{
		$id       = $this->request->input('id');
		$skip     = $this->request->input('skip');
		$comment  = Comments::with([
			'user:id,name,username,avatar,hide_name,verified_id', 
			'updates',
			'replies' => [
				'user',
				'likes',
			],
		])->findOrFail($id);

		$page     = $this->request->input('page');
		$replies  = $comment->replies()->skip($skip)->take(config('settings.number_comments_show'))->orderBy('id', 'DESC')->get();
		$data     = [];

		if ($replies->count()) {
			$data['reverse'] = collect($replies->values())->reverse();
		} else {
			$data['reverse'] = $replies;
		}

		$dataReplies  = $data['reverse'];
		$totalReplies = ($comment->replies()->count() - config('settings.number_comments_show') - $skip);

		return response()->json([
			'replies' => view('includes.replies',
			[
				'dataReplies'  => $dataReplies,
				'comment'      => $comment,
				'totalReplies' => $totalReplies,
				'getReplies'   => true
				]
				)->render()
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
		$reply = Replies::findOrFail($id);

		if ($reply->user_id == auth()->user()->id || $reply->updates()->user_id == auth()->id()) {

			$reply->delete();

			$totalComments = $reply->updates()->totalComments();

			return response()->json([
				'success' => true,
				'total' => trans_choice('general.comment_comments', $totalComments, ['total' => $totalComments])
			]);

		} else {
			return response()->json([
				'success' => false,
				'error' => __('general.error')
			]);
		}
	}//<--- End Method

}