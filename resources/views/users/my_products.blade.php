@extends('layouts.app')

@section('title') {{trans('general.products')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="bi-tag mr-2"></i> {{trans('general.products')}}</h2>
          <p class="lead text-muted m-0">{{trans('general.all_products_published')}}</p>

          <div class="mt-2">
            @if ($settings->digital_product_sale && ! $settings->custom_content && ! $settings->physical_products)
              <a class="btn btn-primary" href="{{ url('add/product') }}">
                <i class="bi-plus"></i> {{ __('general.add_product') }}
              </a>

            @elseif (! $settings->digital_product_sale && $settings->custom_content && ! $settings->physical_products)
              <a class="btn btn-primary" href="{{ url('add/custom/content') }}">
                <i class="bi-plus"></i> {{ __('general.add_custom_content') }}
              </a>

            @elseif (! $settings->digital_product_sale && $settings->physical_products && ! $settings->custom_content)
              <a class="btn btn-primary" href="{{ url('add/physical/product') }}">
                <i class="bi-plus"></i> {{ __('general.add_physical_product') }}
              </a>

            @else
              <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#addItemForm">
                <i class="bi-plus"></i> {{ __('general.add_new') }}
              </a>
            @endif
          </div>

        </div>
      </div>
      <div class="row">

        <div class="col-md-12 mb-5 mb-lg-0">

          @if ($products->count() != 0)
          <div class="card shadow-sm">
          <div class="table-responsive">
            <table class="table table-striped m-0">
              <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">{{trans('admin.name')}}</th>
                  <th scope="col">{{trans('admin.type')}}</th>
                  <th scope="col">{{trans('general.price')}}</th>
                  <th scope="col">{{trans('general.sales')}}</th>
                  <th scope="col">{{trans('admin.date')}}</th>
                  <th scope="col">{{trans('admin.status')}}</th>
                </tr>
              </thead>

              <tbody>

                @foreach ($products as $product)

                  <tr>
                    <td>{{ $product->id }}</td>

                    <td>
                    <a href="{{ url('shop/product', $product->id) }}" target="_blank">
                      {{ str_limit($product->name, 20, '...') }} <i class="bi bi-box-arrow-up-right ml-1"></i>
                    </a>
                    </td>
                    <td>{{ ($product->type == 'digital') ? __('general.digital_download') : (($product->type == 'physical') ? __('general.physical_products') : __('general.custom_content')) }}</td>
                    <td>{{ Helper::amountFormatDecimal($product->price) }}</td>
                    <td>{{ $product->purchases->count() }}</td>
                    <td>{{Helper::formatDate($product->created_at)}}</td>
                    <td>
                      @if ($product->status)
                        <span class="badge badge-pill badge-success text-uppercase">{{trans('general.active')}}</span>
                      @else
                        <span class="badge badge-pill badge-secondary text-uppercase">{{trans('admin.disabled')}}</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          </div><!-- card -->

          @if ($products->hasPages())
  			    	{{ $products->onEachSide(0)->links() }}
  			    	@endif

        @else
          <div class="my-5 text-center">
            <span class="btn-block mb-3">
              <i class="bi-tag ico-no-result"></i>
            </span>
            <h4 class="font-weight-light">{{trans('general.no_results_found')}}</h4>
          </div>
        @endif
        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>

  @includeWhen(auth()->check() && auth()->user()->verified_id == 'yes', 'shop.modal-add-item')

@endsection
