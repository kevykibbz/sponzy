@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.reports') }} ({{$data->count()}})</span>
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
                     <th class="active">{{ __('admin.report_by') }}</th>
                     <th class="active">{{ __('admin.reported') }}</th>
					 <th class="active">{{ __('admin.type') }}</th>
					 <th class="active">{{ __('general.message') }}</th>
                     <th class="active">{{ __('admin.reason') }}</th>
                     <th class="active">{{ __('admin.date') }}</th>
                     <th class="active">{{ __('admin.actions') }}</th>
                   </tr>

                 @foreach ($data as $report)
					<tr>
						<td>{{ $report->id }}</td>
						<td><a href="{{ url($report->user()->username) }}" target="_blank">{{ $report->user()->name }} <i class="fa fa-external-link-square-alt"></i></a></td>

						<td>
							@switch($report->type)
								@case('user')
									<a href="{{ url('panel/admin/members/edit', $report->report_id) }}" target="_blank">
										{{ str_limit($report->userReported()->name, 15, '...') }} <i class="fa fa-external-link-square-alt"></i>
									</a>
									@break

								@case('update')
									<a href="{{ url($report->updates()->user()->username.'/post', $report->report_id) }}" target="_blank">
										{{ str_limit($report->updates()->description, 40, '...') }} <i class="fa fa-external-link-square-alt"></i>
									</a>
									@break
							
								@case('item')
									<a href="{{ url('shop/product', $report->report_id) }}" target="_blank">
										{{ str_limit($report->products()->name, 40, '...') }} <i class="fa fa-external-link-square-alt"></i>
									</a>
									@break

								@case('live')
									<a href="{{ url('live', $report->live()->username) }}" target="_blank">
										{{ str_limit($report->live()->name, 40, '...') }} <i class="fa fa-external-link-square-alt"></i>
									</a>
									@break								
						@endswitch
					</td>

					<td>
						@switch($report->type)
							@case('user')
								{{ __('admin.user') }}
								@break

							@case('update')
								{{ __('general.post') }}
								@break

							@case('item')
								{{ __('general.item') }}
								@break							

							@case('live')
								{{ __('general.live') }}
								@break
						@endswitch
					</td>

					<td>
						{{ $report->message ?? '--' }}
					</td>

					@php
						switch ($report->reason) {
							case 'copyright':
								$reason = __('admin.copyright');
								break;

							case 'privacy_issue':
								$reason = __('admin.privacy_issue');
								break;

								case 'violent_sexual':
									$reason = __('admin.violent_sexual_content');
									break;

									case 'spoofing':
										$reason = __('admin.spoofing');
										break;

										case 'spam':
											$reason = __('general.spam');
											break;

											case 'fraud':
												$reason = __('general.fraud');
												break;

												case 'under_age':
													$reason = __('general.under_age');
													break;

													case 'item_not_received':
														$reason = __('general.item_not_received');
														break;
						}
					@endphp

						<td>{{ $reason }}</td>

						<td>{{ Helper::formatDate($report->created_at) }}</td>
						<td>


							{!! Form::open([
							'method' => 'POST',
							'url' => url('panel/admin/reports/delete',$report->id),
							'class' => 'displayInline'
						]) !!}
					{!! Form::button('<i class="bi-trash-fill"></i>', ['class' => 'btn btn-danger rounded-pill btn-sm actionDelete']) !!}

						{!! Form::close() !!}

								</td>

					</tr><!-- /.TR -->
                   @endforeach

						@else
							<h5 class="text-center p-5 text-muted fw-light m-0">{{ __('general.no_results_found') }}</h5>
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
