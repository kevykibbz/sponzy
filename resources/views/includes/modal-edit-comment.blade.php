<div class="modal fade modalEditComment" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header border-bottom-0">
          <h5 class="modal-title">{{trans('general.edit_comment')}}</h5>
          <button type="button" class="close close-inherit" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">
              <i class="bi bi-x-lg"></i>
            </span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('comment/edit')}}" enctype="multipart/form-data" class="formCommentEdit">
            @csrf
            <input class="commentId" type="hidden" name="id" value="{{ $data->id }}" />
            <input class="isReplyInputModal" type="hidden" name="isReply" value="{{ $isReply }}" />
          <div class="card mb-3">
            <div class="blocked display-none"></div>
            <div class="card-body pb-0">

              <div class="media">
                <div class="media-body">
                <textarea name="comment" rows="8" cols="40" placeholder="{{trans('general.write_something')}}" class="form-control border-0 updateDescription commentText custom-scrollbar">{{ $data->reply }}</textarea>
              </div>
            </div><!-- media -->

            <!-- Alert -->
            <div class="alert alert-danger my-3 display-none errorComment">
              <ul class="list-unstyled m-0 showErrorsComment small"></ul>
            </div><!-- Alert -->
            </div><!-- card-body -->
          </div><!-- card -->

          <div class="d-inline-block w-100">
            <button type="submit" class="btn btn-sm btn-primary rounded-pill float-right btnEditComment"><i></i> {{trans('users.save')}}</button>
          </div>

        </form>
      </div><!-- modal-body -->
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div><!-- modal -->