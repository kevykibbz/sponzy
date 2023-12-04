@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('general.sales') }} ({{$sales->total()}})</span>
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

               @if ($sales->total() !=  0 && $sales->count() != 0)
                  <tr>
										<th class="active">ID</th>
										<th class="active">{{trans('general.item')}}</th>
										<th class="active">{{trans('general.creator')}}</th>
										<th class="active">{{trans('general.buyer')}}</th>
										<th class="active">{{trans('general.delivery_status')}}</th>
										<th class="active">{{trans('general.price')}}</th>
										<th class="active">{{trans('admin.date')}}</th>
										<th class="active">{{trans('admin.actions')}}</th>
                   </tr>

                 @foreach ($sales as $sale)
									 <tr>
										 <td>
											 {{ $sale->id }}
										 </td>
										 <td>
											 <a href="{{url('shop/product', $sale->products()->id)}}">
												 {{ Str::limit($sale->products()->name, 25, '...') }}
											 </a>
											 </td>
											 <td>
												 @if (! isset($sale->products()->user()->username))
													 {{ trans('general.no_available') }}
												 @else
												 <a href="{{ url($sale->products()->user()->username) }}" target="_blank">{{ '@'.$sale->products()->user()->username }}</a>
											 @endif
											 </td>
											 <td>
												 @if (! isset($sale->user()->username))
													 {{ trans('general.no_available') }}
												 @else
												 <a href="{{ url($sale->user()->username) }}" target="_blank">{{ '@'.$sale->user()->username }}</a>
											 @endif
											 </td>
											 <td>
												 @if ($sale->delivery_status == 'delivered')
													 <span class="badge bg-success rounded-pill text-uppercase">{{ __('general.delivered') }}</span>

												 @else
													 <span class="badge bg-warning rounded-pill text-uppercase">{{ __('general.pending') }}</span>
												 @endif
											 </td>
										 <td>{{ Helper::amountFormatDecimal($sale->transactions()->amount) }}</td>
										 <td>{{Helper::formatDate($sale->created_at)}}</td>

										 <td>
											 @if ($sale->products()->type == 'custom' || $sale->products()->type == 'physical')
											 <div class="d-flex">

												 <a title="{{ __('general.see_details') }}" class="d-inline-block myicon-right btn btn-success rounded-pill me-2" data-bs-toggle="modal" data-bs-target="#customContentForm{{$sale->id}}" href="#">
												 <i class="fa fa-eye"></i>
												 </a>
												 @endif

												 <form class="d-inline-block" method="post" action="{{ url('panel/admin/sales/refund', $sale->id) }}">
													 @csrf
													 <button title="{{ __('general.refund') }}" class="btn btn-danger rounded-pill actionRefund" type="button">
														 {{ __('general.refund') }}
													 </button>
												 </form>
										 </td>
									 </tr>

									 @include('includes.modal-custom-content')

                   @endforeach

									@else
										<h5 class="text-center p-5 text-muted fw-light m-0">{{ trans('general.no_results_found') }}</h5>
									@endif

								</tbody>
								</table>
							</div><!-- /.box-body -->

				 </div><!-- card-body -->
 			</div><!-- card  -->

			@if ($sales->lastPage() > 1)
		 	{{ $sales->onEachSide(0)->links() }}
		 @endif

 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
