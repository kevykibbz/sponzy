@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.transactions') }} ({{$data->total()}})</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-4">

					@if ($data->total() !=  0)
					<div class="d-block mb-2 w-100">
						<!-- form -->
            <form class="mt-lg-0 mt-2 position-relative" role="search" autocomplete="off" action="{{ url('panel/admin/transactions') }}" method="get">
							<i class="bi bi-search btn-search bar-search"></i>
             <input type="text" name="q" class="form-control ps-5 w-auto" value="" placeholder="{{ __('admin.transaction_id') }}">
          </form><!-- form -->
				</div>
			@endif

					<div class="table-responsive p-0">
						<table class="table table-hover">
						 <tbody>

               @if ($data->total() !=  0 && $data->count() != 0)
                  <tr>
					<th class="active">ID</th>
					<th class="active">{{ __('admin.transaction_id') }}</th>
					<th class="active">{{ __('general.user') }}</th>
					<th class="active">{{ __('general.creator') }}</th>
					<th class="active">{{ __('admin.type') }}</th>
					<th class="active">{{ __('admin.amount') }}</th>
					<th class="active">{{ __('admin.earnings_admin') }}</th>
					<th class="active">{{ __('general.payment_gateway') }}</th>
					<th class="active">{{ __('admin.date') }}</th>
					<th class="active">{{ __('admin.status') }}</th>
                   </tr>

                 @foreach ($data as $transaction)
									 <tr>
										<td>{{ str_pad($transaction->id, 4, "0", STR_PAD_LEFT) }}</td>
										 <td>
											@if ($transaction->approved == 1)
											<a href="{{ url('payments/invoice', $transaction->id) }} " target="_blank" title="{{ __('general.invoice') }} - {{ $transaction->txn_id }}">
												{{ str_limit($transaction->txn_id, 25) }}  <i class="bi-box-arrow-up-right"></i>
											</a>
											@else
											{{ str_limit($transaction->txn_id, 25) }}
											@endif
										 </td>
										 <td>
											 @if (! isset($transaction->user()->username))
												 <em>{{ __('general.no_available') }}</em>
											 @else
												 <a href="{{url($transaction->user()->username)}}" target="_blank">
												 {{$transaction->user()->name}} <i class="bi-box-arrow-up-right"></i>
											 </a>
											 @endif
									 </td>
									 <td>
										 @if (! isset($transaction->subscribed()->username))
											 <em>{{ __('general.no_available') }}</em>
										 @else
											 <a href="{{url($transaction->subscribed()->username)}}" target="_blank">
											 {{$transaction->subscribed()->name}} <i class="bi-box-arrow-up-right"></i>
										 </a>
										 @endif
								 </td>
										 <td>{{ __('general.'.$transaction->type) }}
										 </td>
										 <td>{{ Helper::amountFormatDecimal($transaction->amount) }}</td>
										 <td>
											 {{ Helper::amountFormatDecimal($transaction->earning_net_admin) }}

											 @if ($transaction->referred_commission)
													 <i class="fa fa-info-circle text-muted showTooltip" title="{{__('general.referral_commission_applied')}}"></i>
											 @endif
										 </td>
										 <td>{{ $transaction->payment_gateway }}</td>
										 <td>{{ Helper::formatDate($transaction->created_at) }}</td>
										 <td>
											 @if ($transaction->approved == '0')
											 <span class="rounded-pill badge bg-warning mb-2 text-uppercase">{{__('admin.pending')}}</span>
										 @elseif ($transaction->approved == '1')
											 <span class="rounded-pill badge bg-success mb-2 text-uppercase">{{__('admin.approved')}}</span>
										 @else
											 <span class="rounded-pill badge bg-danger mb-2 text-uppercase">{{__('general.canceled')}}</span>
										 @endif

									 @if ($transaction->approved == '1')
												 {!! Form::open([
												 'method' => 'POST',
												 'url' => url('panel/admin/transactions/cancel', $transaction->id),
												 'class' => 'displayInline'
											 ]) !!}
											{!! Form::button(__('admin.cancel'), ['class' => 'btn btn-danger btn-sm padding-btn rounded-pill cancel_payment']) !!}

												{!! Form::close() !!}
											@endif
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

			@if ($data->lastPage() > 1)
			{{ $data->onEachSide(0)->links() }}
		@endif
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
