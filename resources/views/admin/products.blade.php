@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('general.products') }} ({{$data->total()}})</span>
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
										 <th class="active">{{ __('general.item') }}</th>
										 <th class="active">{{ __('general.creator') }}</th>
										 <th class="active">{{__('admin.type')}}</th>
										 <th class="active">{{__('general.price')}}</th>
										 <th class="active">{{__('general.sales')}}</th>
										 <th class="active">{{ __('admin.date') }}</th>
										 <th class="active">{{ __('admin.actions') }}</th>
                   </tr>

                 @foreach ($data as $product)
									 <tr>
										 <td>{{ $product->id }}</td>
										 <td>
											 <a href="{{url('shop/product', $product->id)}}" target="_blank">
												 {{ Str::limit($product->name, 25, '...') }} <i class="fa fa-external-link-alt"></i>
											 </a>
											 </td>
										 <td>
											 @if (isset($product->user()->username))
											 <a href="{{url($product->user()->username)}}" target="_blank">
												 {{$product->user()->name}} <i class="fa fa-external-link-alt"></i>
											 </a>
										 @else
											 <em>{{ __('general.no_available') }}</em>
										 @endif
										 </td>
										 <td>{!! $product->type == 'digital' ? '<a href="'.url('product/download', $product->id).'"><i class="bi-download me-1"></i> '. __('general.digital_download').'</a>' : (($product->type == 'physical') ? __('general.physical_products') : __('general.custom_content')) !!}</td>

										 <td>{{ Helper::amountFormatDecimal($product->price) }}</td>
										 <td>{{ $product->purchases->count() }}</td>
										 <td>{{date($settings->date_format, strtotime($product->created_at))}}</td>

										 <td>
											 {!! Form::open([
												 'method' => 'POST',
												 'url' => url('panel/admin/product/delete', $product->id),
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

			@if ($data->lastPage() > 1)
				{{ $data->onEachSide(0)->links() }}
		@endif
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
