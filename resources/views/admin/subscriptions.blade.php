@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.subscriptions') }} ({{$data->total()}})</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-4">

					<div class="table-responsive p-0">
						<table class="table table-hover">
						 <tbody>

               @if ($data->total() !=  0 && $data->count() != 0)
                  <tr>
                     <th class="active">ID</th>
										 <th class="active">{{ trans('general.subscriber') }}</th>
										 <th class="active">{{ trans('general.creator') }}</th>
										 <th class="active">{{ trans('admin.date') }}</th>
										 <th class="active">{{trans('admin.status')}}</th>
                   </tr>

                 @foreach ($data as $subscription)
									 <tr>
										 <td>{{ $subscription->id }}</td>
										 <td>
											 @if ( ! isset($subscription->user()->username))
												 {{ trans('general.no_available') }}
											 @else
											 <a href="{{url($subscription->user()->username)}}" target="_blank">
												 {{$subscription->user()->name}}
											 </a>
											 @endif
										 </td>
										 <td>
											 @if ( ! isset($subscription->subscribed()->username))
												 {{ trans('general.no_available') }}
											 @else
											 <a href="{{url($subscription->subscribed()->username)}}" target="_blank">
												 {{$subscription->subscribed()->name}} <i class="fa fa-external-link-square"></i>
											 </a>
										 @endif
										 </td>
										 <td>{{ Helper::formatDate($subscription->created_at) }}</td>
										 <td>
											 @if ($subscription->stripe_id == ''
												 && strtotime($subscription->ends_at) > strtotime(now()->format('Y-m-d H:i:s'))
												 && $subscription->cancelled == 'no'
													 || $subscription->stripe_id != '' && $subscription->stripe_status == 'active'
													 || $subscription->stripe_id == '' && $subscription->free == 'yes'
												 )
												 <span class="rounded-pill badge bg-success">{{trans('general.active')}}</span>
											 @elseif ($subscription->stripe_id != '' && $subscription->stripe_status == 'incomplete')
												 <span class="rounded-pill badge bg-warning">{{trans('general.incomplete')}}</span>
											 @else
												 <span class="rounded-pill badge bg-danger">{{trans('general.cancelled')}}</span>
											 @endif
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
