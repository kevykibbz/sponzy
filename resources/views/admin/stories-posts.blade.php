@extends('admin.layout')

@section('css')
<link href="{{ asset('public/js/plyr/plyr.css')}}?v={{$settings->version}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
	  <span class="text-muted">{{ __('general.stories') }}</span>
	  <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('general.posts') }} ({{$data->total()}})</span>
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
										<th class="active">{{ trans('admin.description') }}</th>
										<th class="active">{{ trans('admin.content') }}</th>
										<th class="active">{{ trans('general.creator') }}</th>
										<th class="active">{{ trans('admin.date') }}</th>
										<th class="active">{{ trans('admin.actions') }}</th>
									</tr>

								@foreach ($data as $story)

									@php
										$media = $story->media[0];
									@endphp

									<tr>
										<td>{{ $story->id }}</td>
										<td class="text-break">{{ $story->title ? $story->title : ($media->text ? $media->text : __('general.not_applicable')) }}</td>

										<td>
											@if ($media->type == 'photo' && ! $media->text)
												<i class="far fa-image"></i>
											@endif

											@if ($media->text)
												<i class="fa fa-font"></i>
											@endif

											@if ($media->type == 'video')
												<i class="far fa-play-circle"></i>
											@endif
										</td>

										<td>
											@if (isset($story->user->username))
												<a href="{{url($story->user->username)}}" target="_blank">
													{{$story->user->username}} <i class="fa fa-external-link-square-alt"></i>
												</a>
											@else
												<em>{{ trans('general.no_available') }}</em>
											@endif

											</td>
										<td>{{ Helper::formatDate($story->created_at) }}</td>
										<td>
											<div class="d-flex">
											@if (! $media->text)
											<a href="{{ Helper::getFile(config('path.stories').$media->name) }}" class="btn btn-success btn-sm rounded-pill me-2 glightbox" data-gallery="gallery{{$media->id}}">
												<i class="bi-eye"></i>
											</a>
											@endif

										 {!! Form::open([
											 'method' => 'POST',
											 'url' => "panel/admin/stories/posts/delete/$story->id",
											 'class' => 'displayInline'
										 ]) !!}
										 
										 {!! Form::button('<i class="bi-trash-fill"></i>', ['class' => 'btn btn-danger btn-sm padding-btn rounded-pill actionDelete']) !!}

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

		@if ($data->lastPage() > 1)
			{{ $data->onEachSide(0)->links() }}
		@endif
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection

@section('javascript')
<script src="{{ asset('public/js/plyr/plyr.min.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/plyr/plyr.polyfilled.min.js') }}?v={{$settings->version}}"></script>
@endsection