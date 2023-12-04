@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('general.referrals') }} ({{$data->total()}})</span>
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
										 <th class="active">{{ trans('admin.user') }}</th>
										 <th class="active">{{ trans('general.referred_by') }}</th>
										 <th class="active">{{ trans('general.earnings') }}</th>
										 <th class="active">{{ trans('admin.date') }}</th>
                   </tr>

                 @foreach ($data as $referral)
									 <tr>
										 <td>{{ $referral->id }}</td>
										 <td>
											 @if (isset($referral->user()->username))
											 <a href="{{url($referral->user()->username)}}" target="_blank">
												 {{$referral->user()->name}} <i class="fa fa-external-link-alt"></i>
											 </a>
										 @else
											 <em>{{ trans('general.no_available') }}</em>
										 @endif
										 </td>
										 <td>
											 @if (isset($referral->referredBy()->username))
											 <a href="{{url($referral->referredBy()->username)}}" target="_blank">
												 {{$referral->referredBy()->name}} <i class="fa fa-external-link-alt"></i>
											 </a>
										 @else
											 <em>{{ trans('general.no_available') }}</em>
										 @endif
										 </td>
										 <td>{{ Helper::amountFormatDecimal($referral->earnings()) }}</td>
										 <td>{{date($settings->date_format, strtotime($referral->created_at))}}</td>
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
