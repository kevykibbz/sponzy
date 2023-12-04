@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('general.announcements') }}</span>

  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

    @if (session('success_message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check2 me-1"></i>	{{ session('success_message') }}

              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="bi bi-x-lg"></i>
              </button>
              </div>
            @endif

		@include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form method="POST" action="{{ url('panel/admin/announcements') }}" enctype="multipart/form-data">
             @csrf

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.announcement_content') }}</label>
		          <div class="col-sm-10">
                <textarea class="form-control" name="announcement_content" rows="8">{{ $settings->announcement }}</textarea>

                <small class="d-block">
                    {{ __('general.announcement_info') }}
                </small>
		          </div>
		        </div>

                <fieldset class="row mb-3">
                    <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.type_announcement') }}</legend>
                    <div class="col-sm-10">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="type_announcement" id="radio1" @if ($settings->type_announcement == 'primary') checked="checked" @endif value="primary" checked>
                        <label class="form-check-label" for="radio1">
                          {{ trans('general.informative') }}
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="type_announcement" id="radio2" @if ($settings->type_announcement == 'danger') checked="checked" @endif value="danger">
                        <label class="form-check-label" for="radio2">
                          {{ trans('general.important') }}
                        </label>
                      </div>
                    </div>
                  </fieldset><!-- end row -->

						<fieldset class="row mb-3">
	                    <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.show_announcement_to') }}</legend>
	                    <div class="col-sm-10">
	                      <div class="form-check">
	                        <input class="form-check-input" type="radio" name="announcement_show" id="radio3" @if ($settings->announcement_show == 'all') checked="checked" @endif value="all" checked>
	                        <label class="form-check-label" for="radio3">
	                          {{ trans('general.all_users') }}
	                        </label>
	                      </div>
	                      <div class="form-check">
	                        <input class="form-check-input" type="radio" name="announcement_show" id="radio4" @if ($settings->announcement_show == 'creators') checked="checked" @endif value="creators">
	                        <label class="form-check-label" for="radio4">
	                          {{ trans('general.only_creators') }}
	                        </label>
	                      </div>
	                    </div>
	                  </fieldset><!-- end row -->

						<div class="row mb-3">
		          <div class="col-sm-10 offset-sm-2">
		            <button type="submit" class="btn btn-dark mt-3 px-5">{{ __('admin.save') }}</button>
		          </div>
		        </div>

		       </form>

				 </div><!-- card-body -->
 			</div><!-- card  -->
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
