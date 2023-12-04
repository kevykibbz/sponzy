@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('general.stories') }}</span>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('general.google_fonts') }} ({{$data->total()}})</span>

			<a class="btn btn-sm btn-dark float-lg-end mt-1 mt-lg-0" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addFont">
				<i class="bi-plus-lg"></i> {{ trans('general.add_new') }}
			</a>
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

				<div class="card-body p-lg-4">

          <div class="alert alert-secondary py-2">
            <i class="bi-info-circle me-2"></i> {{ __('general.alert_story_fonts') }} <a href="https://fonts.google.com/" class="text-decoration-underline" target="_blank">https://fonts.google.com/ <i class="bi-box-arrow-up-right"></i></a>
           </div>

					<div class="table-responsive p-0">
						<table class="table table-hover">
						 <tbody>

               @if ($data->count() !=  0)
                  <tr>
                     <th class="active">ID</th>
                     <th class="active">{{ __('auth.name') }}</th>
                     <th class="active">{{ trans('admin.actions') }}</th>
                   </tr>

                 @foreach ($data as $font)
                   <tr>
                     <td>{{ $font->id }}</td>
                     <td>{{ $font->name }}</td>
                     <td>
                      <form method="POST" action="{{ url('panel/admin/stories/fonts/delete', $font->id) }}" accept-charset="UTF-8" class="d-inline-block align-top">
                        @csrf
                        <button class="btn btn-danger rounded-pill btn-sm actionDelete" type="button">
                          <i class="bi-trash-fill me-1"></i> {{ __('general.delete') }}
                        </button>
                        </form>

										</td>
                   </tr><!-- /.TR -->
                   @endforeach

									@else
										<h5 class="text-center p-5 text-muted fw-light m-0">{{ trans('general.no_results_found') }}</h5>
									@endif

								</tbody>
								</table>
							</div><!-- /.box-body -->

				 </div><!-- card-body -->
 			</div><!-- card  -->

       @if ($data->lastPage() > 1)
       {{ $data->onEachSide(0)->links() }}
     @endif
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->

  <div class="modal fade" id="addFont" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header border-bottom-0">
          <h5 class="modal-title">{{trans('general.add_new')}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ url('panel/admin/stories/fonts/add') }}" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
              <label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.name') }}</label>
              <div class="col-sm-10">
                <input type="text" name="name" required class="form-control" placeholder="Barlow">
              </div>
            </div><!-- end row -->
  
          <div class="modal-footer">
            <button type="submit" class="btn btn-dark rounded-pill float-right"><i></i> {{trans('users.save')}}</button>
          </div>
        </form>
      </div><!-- modal-body -->
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div><!-- modal -->
</div><!-- end content -->
@endsection
