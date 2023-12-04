@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('general.blog') }}</span>

			<a href="{{ url('panel/admin/blog/create') }}" class="btn btn-sm btn-dark float-lg-end mt-1 mt-lg-0">
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

                  @if ($data->count() !=  0)
                     <tr>
											 <th class="active">ID</th>
											 <th class="active">{{ trans('general.title') }}</th>
											 <th class="active">{{ trans('admin.date') }}</th>
											 <th class="active">{{ trans('admin.actions') }}</th>
                      </tr>

                    @foreach ($data as $blog)
                      <tr>
                        <td>{{ $blog->id }}</td>
                        <td><a href="{{ url('blog/post', $blog->id).'/'.$blog->slug }}" title="{{$blog->title}}" target="_blank">{{ $blog->title }} <i class="bi-box-arrow-up-right"></i></a></td>
                        <td>{{ date('d M, Y', strtotime($blog->date)) }}</td>
                        <td>
                        	<div class="d-flex">
														<a href="{{ url('panel/admin/blog', $blog->id) }}" class="btn btn-success rounded-pill btn-sm me-2">
	                        		<i class="bi-pencil"></i>
	                        	</a>

														{!! Form::open([
		        			            'method' => 'post',
		        			            'url' => url('panel/admin/blog/delete', $blog->id),
		        			            'class' => 'displayInline'
		        				        ]) !!}
		        	            	{!! Form::button('<i class="bi-trash-fill"></i>', ['class' => 'btn btn-danger rounded-pill btn-sm actionDeleteBlog']) !!}
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
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
