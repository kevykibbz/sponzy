@extends('layouts.app')

@section('title') {{trans('general.purchased_items')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="bi-bag-check mr-2"></i> {{trans('general.purchased_items')}}</h2>
          <p class="lead text-muted mt-0">{{trans('general.purchased_items_subtitle')}}</p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          @if ($purchases->count() != 0)

            @if (session('message'))
            <div class="alert alert-success mb-3">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
              </button>
              <i class="fa fa-check mr-1"></i> {{ session('message') }}
            </div>
            @endif

            @if (session('error_message'))
            <div class="alert alert-danger mb-3">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
              </button>
              <i class="fa fa-check mr-1"></i> {{ session('error_message') }}
            </div>
            @endif

          <div class="card shadow-sm">
          <div class="table-responsive">
            <table class="table table-striped m-0">
              <thead>
                <tr>
                  <th scope="col">{{trans('general.item')}}</th>
                  <th scope="col">{{trans('general.type')}}</th>
                  <th scope="col">{{trans('general.delivery_status')}}</th>
                  <th scope="col">{{trans('general.price')}}</th>
                  <th scope="col">{{trans('admin.date')}}</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($purchases as $purchase)
                  <tr>
                    <td>
                      <a href="{{url('shop/product', $purchase->products()->id)}}">
                        {{ Str::limit($purchase->products()->name, 25, '...') }}
                      </a>
                      </td>
                      <td>{{ $purchase->products()->type == 'digital' ? __('general.digital_download') : (($purchase->products()->type == 'physical') ? __('general.physical_products') : __('general.custom_content')) }}</td>
                      <td>
                        @if ($purchase->delivery_status == 'delivered')
                          <span class="badge badge-pill badge-success text-uppercase">{{ __('general.delivered') }}</span>

                        @else
                          <span class="badge badge-pill badge-warning text-uppercase">{{ __('general.pending') }}</span>
                        @endif
                      </td>
                    <td>{{ Helper::amountFormatDecimal($purchase->transactions()->amount) }}</td>
                    <td>{{Helper::formatDate($purchase->created_at)}}</td>

                  </tr>
                @endforeach

              </tbody>
            </table>
          </div>
          </div><!-- card -->

            @if ($purchases->hasPages())
              <div class="mt-2">
    			    	{{ $purchases->onEachSide(0)->links() }}
                </div>
    			    	@endif

        @else
          <div class="my-5 text-center">
            <span class="btn-block mb-3">
              <i class="bi-bag-x ico-no-result"></i>
            </span>
            <h4 class="font-weight-light">{{trans('general.no_results_found')}}</h4>
          </div>
        @endif

        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection
