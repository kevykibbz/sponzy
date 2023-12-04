@extends('admin.layout')

@section('content')
<h5 class="mb-4 fw-light">
	<a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
	<i class="bi-chevron-right me-1 fs-6"></i>
	<span class="text-muted">{{ __('general.live_streaming_private_requests') }} ({{$lives->total()}})</span>
</h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			@if (session('success_message'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<i class="bi bi-check2 me-1"></i> {{ session('success_message') }}

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

								@if ($lives->total() != 0 && $lives->count() != 0)
								<tr>
									<th class="active">{{__('general.buyer')}}</th>
                                    <th class="active">{{__('general.creator')}}</th>
									<th class="active text-capitalize">{{__('general.minutes')}}</th>
									<th class="active">{{__('general.price')}}</th>
                                    <th class="active">{{__('admin.status')}}</th>
									<th class="active">{{__('admin.date')}}</th>
								</tr>

								@foreach ($lives as $live)
								<tr>
									<td>
										@if (! isset($live->user->username))
										{{ __('general.no_available') }}
										@else
										<a href="{{ url($live->user->username) }}" target="_blank">
                                        {{ $live->user->name }} <i class="bi-box-arrow-up-right"></i>
                                            </a>
										@endif
									</td>
									<td>
										@if (! isset($live->creator->username))
										{{ __('general.no_available') }}
										@else
										<a href="{{ url($live->creator->username) }}" target="_blank">
                                            {{ $live->creator->name }} <i class="bi-box-arrow-up-right"></i>
                                            </a>
										@endif
									</td>
                                    <td>
										{{ $live->minutes }}
									</td>
                                    <td>{{ Helper::amountFormatDecimal($live->transaction->amount) }}</td>
									<td>
										<span class="badge bg-{{ $live->status->label()}} rounded-pill text-uppercase">
                                            {{ $live->status->locale()}}
                                        </span>
									</td>
									<td>{{Helper::formatDate($live->created_at)}}</td>

								</tr>
								@endforeach

								@else
								<h5 class="text-center p-5 text-muted fw-light m-0">{{ __('general.no_results_found')
									}}</h5>
								@endif

							</tbody>
						</table>
					</div><!-- /.box-body -->

				</div><!-- card-body -->
			</div><!-- card  -->

			@if ($lives->lastPage() > 1)
			{{ $lives->onEachSide(0)->links() }}
			@endif

		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection