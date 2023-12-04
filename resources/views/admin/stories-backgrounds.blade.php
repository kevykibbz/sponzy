@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('general.stories') }}</span>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('general.backgrounds') }} ({{$data->total()}})</span>

			<a href="javascript:void(0);" id="btnFileAddBg" class="btn btn-sm btn-dark float-lg-end mt-1 mt-lg-0">
				<i class="bi-plus-lg"></i> {{ trans('general.add_new') }}
			</a>

      <form method="POST" class="d-none" action="{{ url('panel/admin/stories/backgrounds/add') }}" accept-charset="UTF-8" enctype="multipart/form-data" id="formAddBg">
        @csrf
        <input type="file" name="image" id="fileAddBg" accept="image/*">
        </form>
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

          <div class="alert alert-info py-2">
            <i class="bi-info-circle me-2"></i> {{ __('general.recommended_size') }} 1350x2400px (JPG, PNG)
           </div>

					<div class="table-responsive p-0">
						<table class="table table-hover">
						 <tbody>

               @if ($data->count() !=  0)
                  <tr>
                     <th class="active">ID</th>
                     <th class="active">{{ trans('general.image') }}</th>
                     <th class="active">{{ trans('admin.actions') }}</th>
                   </tr>

                 @foreach ($data as $background)
                   <tr>
                     <td>{{ $background->id }}</td>
                     <td><img src="{{ url('public/img/stories-bg', $background->name) }}" width="50"></td>
                     <td>
                      <form method="POST" action="{{ url('panel/admin/stories/backgrounds/delete', $background->id) }}" accept-charset="UTF-8" class="d-inline-block align-top">
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
</div><!-- end content -->
@endsection
