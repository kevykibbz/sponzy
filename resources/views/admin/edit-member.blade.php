@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <a class="text-reset" href="{{ url('panel/admin/members') }}">{{ __('admin.members') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.edit') }}</span>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ $user->username }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-9 mb-4">

    @include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form class="form-horizontal" method="POST" action="{{ url('panel/admin/members/edit', $user->id) }}" enctype="multipart/form-data">
             @csrf
             <input type="hidden" name="id" value="{{$user->id}}">

             <div class="row mb-3">
 		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.avatar') }}</label>
 		          <div class="col-sm-10">
 		            <img src="{{ Helper::getFile(config('path.avatar').$user->avatar) }}" width="120" height="120" class="rounded-circle" />
 		          </div>
 		        </div>

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.name') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $user->name }}" name="name" type="text" class="form-control">
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('auth.username') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $user->username }}" disabled type="text" class="form-control">
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('auth.email') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $user->email }}" name="email" type="text" class="form-control">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.verified') }} ({{__('general.creator')}})</label>
		          <div class="col-sm-10">
		            <select name="verified" class="form-select">
									<option @if ($user->verified_id == 'no') selected="selected" @endif value="no">{{ __('admin.pending') }}</option>
							  	<option @if ($user->verified_id == 'yes') selected="selected" @endif value="yes">{{ __('admin.verified') }}</option>
							  	<option @if ($user->verified_id == 'reject') selected="selected" @endif value="reject">{{ __('admin.reject') }}</option>
		           </select>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.status') }}</label>
		          <div class="col-sm-10">
		            <select name="status" class="form-select">
                  <option @if ($user->status == 'active') selected="selected" @endif value="active">{{ __('admin.active') }}</option>
                  <option @if ($user->status == 'pending') selected="selected" @endif value="pending">{{ __('admin.pending') }}</option>
                  <option @if ($user->status == 'suspended') selected="suspended" @endif value="suspended">{{ __('admin.suspended') }}</option>
		           </select>
		          </div>
		        </div>

						@if ($user->verified_id == 'yes')
						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.custom_fee') }}</label>
		          <div class="col-sm-10">
		            <select name="custom_fee" class="form-select">
									<option @if ($user->custom_fee == 0) selected="selected" @endif value="0" >{{__('general.none')}}</option>
                  @for ($i=1; $i <= 50; ++$i)
                    <option @if ($user->custom_fee == $i) selected="selected" @endif value="{{$i}}">{{$i}}%</option>
                    @endfor
		           </select>
		          </div>
		        </div>
						@endif

						<div class="row mb-3">
 							<label class="col-sm-2 col-form-label text-lg-end">{{ __('general.balance') }}</label>
 							<div class="col-sm-10">
 								<input value="{{ $user->balance }}" name="balance" type="text" class="form-control isNumber" autocomplete="off">
 							</div>
 						</div>

						<div class="row mb-3">
 							<label class="col-sm-2 col-form-label text-lg-end">{{ __('general.wallet') }}</label>
 							<div class="col-sm-10">
 								<input value="{{ $user->wallet }}" name="wallet" type="text" class="form-control isNumber" autocomplete="off">
 							</div>
 						</div>

			@if ($user->verified_id == 'yes')
            <fieldset class="row mb-3">
              <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.featured') }}</legend>
              <div class="col-sm-10">
                <div class="form-check form-switch form-switch-md">
                 <input class="form-check-input" type="checkbox" name="featured" @if ($user->featured == 'yes') checked="checked" @endif value="yes" role="switch">
               </div>
              </div>
            </fieldset><!-- end row -->
			@endif

						<div class="row mb-3">
		          <div class="col-sm-10 offset-sm-2">
		            <button type="submit" class="btn btn-dark mt-3 px-5 me-2">{{ __('admin.save') }}</button>
                <a href="{{ url($user->username) }}" target="_blank" class="btn btn-link text-reset mt-3 px-3 e-none text-decoration-none">{{ __('admin.view') }} <i class="bi-box-arrow-up-right ms-1"></i></a>
		          </div>
		        </div>

		       </form>

				 </div><!-- card-body -->
 			</div><!-- card  -->
 		</div><!-- col-lg-9 -->

		<div class="col-md-3">
      <div class="d-block text-center mb-3">

      <a href="{{url('panel/admin/members/roles-and-permissions', $user->id)}}" class="btn btn-lg btn-primary rounded-pill w-100 mb-3">
        {{__('general.role_and_permissions')}}
      </a>

      @if ($user->status == 'pending')
        <a href="{{url('panel/admin/resend/email', $user->id)}}" class="btn btn-lg btn-light border rounded-pill w-100 mb-3">
          {{__('general.resend_confirmation_email')}}
        </a>
      @endif

      {!! Form::open([
            'method' => 'post',
            'url' => ['panel/admin/login/user', $user->id],
            'class' => 'displayInline'
          ]) !!}
  	        {!! Form::submit(__('general.login_as_user'), ['class' => 'btn btn-lg btn-success rounded-pill w-100 mb-3 loginAsUser']) !!}
  	    {!! Form::close() !!}

  		{!! Form::open([
            'method' => 'post',
            'url' => url('panel/admin/members', $user->id),
            'class' => 'displayInline'
          ]) !!}
  	        {!! Form::submit(__('admin.delete'), ['data-url' => $user->id, 'class' => 'btn btn-lg btn-danger rounded-pill w-100 mb-3 actionDelete']) !!}
  	    {!! Form::close() !!}
	        </div>

          @php

          if ($user->status == 'pending') {
            $_status = __('admin.pending');
          } elseif ($user->status == 'active') {
            $_status = __('admin.active');
          } else {
            $_status = __('admin.suspended');
          }

        @endphp

          <ol class="list-group">
            <li class="list-group-item border-none"> <strong>{{__('admin.registered')}}</strong>: <span class="pull-right color-strong">{{ Helper::formatDate($user->date) }}</span></li>
            <li class="list-group-item border-none"> <strong>{{__('general.last_login')}}</strong>: <span class="pull-right color-strong">{{ Helper::formatDate($user->last_seen) }}</span></li>
            <li class="list-group-item border-none"> <strong>{{__('admin.status')}}</strong>: <span class="pull-right color-strong">{{ $_status }}</span></li>
            <li class="list-group-item border-none"> <strong>{{__('admin.role')}}</strong>: <span class="pull-right color-strong">{{ $user->role == 'admin' ? __('admin.role_admin') : __('admin.normal') }}</span></li>
            <li class="list-group-item border-none"> <strong>{{__('general.country')}}</strong>: <span class="pull-right color-strong">@if ($user->countries_id != '') {{ $user->country()->country_name }} @else {{ __('admin.not_established') }} @endif</span></li>
            <li class="list-group-item border-none"> <strong>{{__('general.gender')}}</strong>: <span class="pull-right color-strong">{{ $user->gender ? __('general.'.$user->gender) : __('general.not_specified') }}</span></li>
            <li class="list-group-item border-none"> <strong>{{__('general.birthdate')}}</strong>: <span class="pull-right color-strong">{{ $user->birthdate ? $user->birthdate : __('general.no_available') }}</span></li>
          </ol>

        </div><!-- col-md-3 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
