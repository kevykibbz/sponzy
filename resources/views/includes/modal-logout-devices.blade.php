<div class="modal fade" id="logoutDevices" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header border-bottom-0">
          <h5 class="modal-title">{{__('general.enter_password')}}</h5>
          <button type="button" class="close close-inherit" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">
              <i class="bi bi-x-lg"></i>
            </span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('logout/devices')}}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
              <input class="form-control" name="password" required minlength="6" placeholder="{{ __('auth.password') }}" type="password">
            </div>
            
          <div class="d-inline-block w-100">
            <button type="submit" class="btn btn-sm btn-primary rounded-pill float-right"><i></i> {{__('auth.send')}}</button>
          </div>

        </form>
      </div><!-- modal-body -->
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div><!-- modal -->