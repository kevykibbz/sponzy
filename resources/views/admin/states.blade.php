@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('general.states') }}</span>

			<a href="{{ url('panel/admin/states/add') }}" class="btn btn-sm btn-dark float-lg-end mt-1 mt-lg-0">
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

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-4">

					<div class="table-responsive p-0">
						<table class="table table-hover">
						 <tbody>

							@if ($states->count() !=  0)
								 <tr>
									  <th class="active">ID</th>
										<th class="active">{{ trans('general.country') }}</th>
										<th class="active">{{ trans('general.state') }}</th>
										<th class="active">{{ trans('general.iso_code') }}</th>
										<th class="active">{{ trans('admin.actions') }}</th>
									</tr>

								@foreach ($states as $state)
									<tr>
										<td>{{ $state->id }}</td>
										<td>{{ $state->country()->country_name }}</td>
										<td>{{ $state->name }}</td>
										<td>{{ $state->code }}</td>
										<td>
											<div class="d-flex">
											<a href="{{ url('panel/admin/states/edit', $state->id) }}" class="btn btn-success rounded-pill btn-sm me-2">
												<i class="bi-pencil"></i>
											</a>

											{!! Form::open([
												'method' => 'POST',
												'url' => "panel/admin/states/delete/$state->id",
												'class' => 'd-inline-block align-top'
											]) !!}

											{!! Form::button('<i class="bi-trash-fill"></i>', ['class' => 'btn btn-danger rounded-pill btn-sm actionDelete']) !!}
											{!! Form::close() !!}
											</div>
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

		@if ($states->lastPage() > 1)
			{{ $states->links() }}
		@endif
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
