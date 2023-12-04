@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('general.withdrawals') }} ({{$data->total()}})</span>
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

               @if ($data->total() !=  0 && $data->count() != 0)
                  <tr>
                     <th class="active">ID</th>
             <th class="active">{{ trans('admin.user') }}</th>
               <th class="active">{{ trans('admin.amount') }}</th>
               <th class="active">{{ trans('admin.method') }}</th>
               <th class="active">{{ trans('admin.status') }}</th>
               <th class="active">{{ trans('admin.date') }}</th>
               <th class="active">{{ trans('admin.actions') }}</th>
                   </tr><!-- /.TR -->

            @foreach ($data as $withdrawal)

                   <tr>
                     <td>{{ $withdrawal->id }}</td>
                     <td>
                       @if (isset($withdrawal->user()->username))
                           
                       <a href="{{ url($withdrawal->user()->username) }}" target="_blank">
                        {{ $withdrawal->user()->username }} <i class="bi-box-arrow-up-right"></i>
                      </a>
                           @else
                               {{ __('general.no_available') }}
                       @endif
                       
                       </td>
                     <td>{{ Helper::amountFormatDecimal($withdrawal->amount) }}</td>
                     <td>{{ $withdrawal->gateway == 'Bank' ? trans('general.bank_transfer') : $withdrawal->gateway }}</td>
                     <td>
                       @if ($withdrawal->status == 'paid')
                       <span class="badge bg-success">{{trans('general.paid')}}</span>
                       @else
                       <span class="badge bg-warning">{{trans('general.pending_to_pay')}}</span>
                       @endif
                     </td>
                     <td>{{ date('d M, Y', strtotime($withdrawal->date)) }}</td>
                     <td>

                       <a href="{{ url('panel/admin/withdrawal',$withdrawal->id) }}" class="btn btn-1 btn-sm btn-outline-dark" title="{{trans('admin.view')}}">
                        <i class="bi-eye me-1"></i>  {{trans('admin.view')}}
                       </a>
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
			{{ $data->links() }}
		@endif
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
