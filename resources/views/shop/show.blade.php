@extends('layouts.app')

@section('title') {{ $product->name }} -@endsection

  @section('description_custom'){{$product->description ? $product->description : trans('seo.description')}}@endsection
  @section('keywords_custom'){{$product->tags ? $product->tags.',' : null}}@endsection

    @section('css')
    <meta property="og:type" content="website" />
    <meta property="og:image:width" content="800"/>
    <meta property="og:image:height" content="600"/>

    <!-- Current locale and alternate locales -->
    <meta property="og:locale" content="en_US" />
    <meta property="og:locale:alternate" content="es_ES" />

    <!-- Og Meta Tags -->
    <link rel="canonical" href="{{url()->current()}}"/>
    <meta property="og:site_name" content="{{ $product->name }} - {{$settings->title}}"/>
    <meta property="og:url" content="{{url()->current()}}"/>
    <meta property="og:image" content="{{Helper::getFile(config('path.shop').$product->previews[0]->name)}}"/>

    <meta property="og:title" content="{{ $product->name }} - {{$settings->title}}"/>
    <meta property="og:description" content="{{strip_tags($product->description)}}"/>
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:image" content="{{Helper::getFile(config('path.shop').$product->previews[0]->name)}}" />
    <meta name="twitter:title" content="{{ $product->name }}" />
    <meta name="twitter:description" content="{{strip_tags($product->description)}}"/>

    <link href="{{ asset('public/js/splide/splide.min.css')}}?v={{$settings->version}}" rel="stylesheet" type="text/css" />
    @endsection

@section('content')
<section class="section section-sm">
    <div class="container py-5">
      <div class="row">

        <div class="col-md-7 mb-lg-0 mb-4">

          @if ($previews == 1)
          <div class="text-center mb-4 position-relative bg-light rounded-large shadow-large">
            <a href="{{ Helper::getFile(config('path.shop').$product->previews[0]->name) }}" class="glightbox w-100" data-gallery="gallery{{$product->id}}">
              <img class="img-fluid rounded-large" src="{{ Helper::getFile(config('path.shop').$product->previews[0]->name) }}" style="max-height:600px; cursor: zoom-in;">
            </a>
          </div>
          @endif

          @if ($previews > 1)
          <section id="mainCarousel" class="splide text-center rounded-large">
            <div class="splide__track">
              <ul class="splide__list">
                @for ($i=0; $i < $previews; $i++)
                <li class="splide__slide">
                  <a href="{{ Helper::getFile(config('path.shop').$product->previews[$i]->name) }}" class="glightbox" data-gallery="gallery{{$product->id}}">
                    <img class="img-fluid rounded-large" src="{{ route('resize', ['path' => 'shop', 'file' => $product->previews[$i]->name, 'size' => 600, 'crop' => 'fit']) }}" style="cursor: zoom-in;">
                  </a>
                </li>
                @endfor
              </ul>
            </div>
          </section>

          <ul id="thumbnails-shop" class="thumbnails-shop mb-3">
            @for ($i=0; $i < $previews; $i++)
            <li class="thumbnail-shop">
              <img class="img-fluid rounded" src="{{ route('resize', ['path' => 'shop', 'file' => $product->previews[$i]->name, 'size' => 80, 'crop' => 'fit']) }}">
            </li>
            @endfor
          </ul>
          @endif

          <h4 class="mb-3">{{ __('general.description') }}</h4>
          <p class="text-break">
            {!! Helper::checkText($product->description)  !!}
          </p>

        </div><!-- end col-md-7 -->


    <div class="col-md-5">

      <div class="card rounded-large shadow-large card-border-0">

        <div class="card-body p-lg-5 p-4">

          <h3 class="mb-2 font-weight-bold text-break">{{ $product->name }}</h3>

      <div class="card bg-transparent mb-4 border-0">
    	  <div class="card-body p-0">
    	    <div class="d-flex">
    			  <div class="d-flex my-2 align-items-center">
              <a href="{{ url($product->user()->username) }}">
    			      <img class="rounded-circle mr-2" src="{{ Helper::getFile(config('path.avatar').$product->user()->avatar) }}" width="60" height="60">
              </a>

    						<div class="d-block">
    						<a href="{{ url($product->user()->username) }}">
                  <strong>{{ $product->user()->username }}</strong>

                  <small class="verified mr-1">
        						<i class="bi bi-patch-check-fill"></i>
        					</small>
                </a>

    							<div class="d-block">
    								<small class="media-heading text-muted btn-block margin-zero">{{ Helper::formatDate($product->created_at) }}</small>
    							</div>
    						</div>
    			  </div>
    			</div>
    	  </div>
    	</div><!-- end card -->

      <h3>
        {{ Helper::amountFormatDecimal($product->price) }} <small>{{ $settings->currency_code }}</small>
      </h3>

      @if (auth()->check()
          && auth()->id() != $product->user()->id
          && ! $verifyPurchaseUser
          || auth()->check()
          && auth()->id() != $product->user()->id
          && $verifyPurchaseUser
          && $product->type == 'custom'
          || auth()->check()
          && auth()->id() != $product->user()->id
          && $verifyPurchaseUser
          && $product->type == 'physical'
          || auth()->guest()
          )
      <button class="btn btn-1 btn-primary btn-block mt-4" @if ($product->quantity == 0 && $product->type == 'physical') disabled @endif type="button" data-toggle="modal" @auth data-target="#buyNowForm" @else data-target="#loginFormModal" @endauth>
        {{ $product->quantity == 0 && $product->type == 'physical' ? __('general.sold_out') : __('general.buy_now') }}
      </button>

    @elseif (auth()->check() && auth()->id() != $product->user()->id && $verifyPurchaseUser && $product->type == 'digital')
      <a class="btn btn-1 btn-primary btn-block mt-4" href="{{ url('product/download', $product->id) }}">
        {{ __('general.download') }}
      </a>

    @elseif (auth()->check() && auth()->id() == $product->user()->id)
      <a class="btn btn-1 btn-primary btn-block mt-4" href="#" data-toggle="modal" data-target="#editForm">
        <i class="bi-pencil mr-1"></i> {{ __('admin.edit') }}
      </a>

      <form method="post" action="{{ url('delete/product', $product->id) }}">
        @csrf
        <button class="btn btn-1 btn-outline-danger btn-block mt-2 actionDeleteItem" type="button">
          <i class="bi-trash mr-1"></i> {{ __('admin.delete') }}
        </button>
      </form>

      @include('shop.modal-edit')

    @endif

      <div class="w-100 d-block mt-3">
        <i class="bi-cart2 mr-2"></i> {{ __('general.purchases') }} ({{ $product->purchases()->count() }})
      </div>

      @if ($product->type == 'digital')
        <div class="w-100 d-block mt-3">
          <i class="bi-cloud-download mr-2"></i> {{ __('general.digital_download') }}
        </div>

        <div class="w-100 d-block mt-3">
          <i class="bi-box-seam mr-2"></i> {{ __('general.file') }} <span class="text-uppercase">{{ $product->extension }}</span> - <small>{{ $product->size }}</small>
        </div>

      @elseif ($product->type == 'custom')
        <div class="w-100 d-block mt-4">
          <i class="fa fa-fire-alt mr-2"></i> {{ __('general.delivery_time') }} ({{$product->delivery_time}} {{ trans_choice('general.days', $product->delivery_time) }})
        </div>

      @else

        @if ($product->quantity <> 0)
          <div class="w-100 d-block mt-4">
            <i class="bi-boxes mr-2"></i> {{ __('general.quantity') }} <span class="badge badge-pill badge-success">{{ $product->quantity }}</span>
          </div>
        @else
          <div class="w-100 d-block mt-4 text-danger">
            <i class="bi-boxes mr-2"></i> <em>{{ __('general.sold_out') }}</em>
          </div>
        @endif

        @if ($product->shipping_fee <> 0.00)
          <div class="w-100 d-block mt-4">
            <i class="bi-truck mr-2"></i> {{ __('general.shipping_fee') }} - {{ Helper::amountFormatDecimal($product->shipping_fee) }} <small>{{ $settings->currency_code }}</small>

            @if ($product->country_free_shipping)
              <small><em>({{ __('general.free_shipping') }} {{ $product->country()->country_name }})</em></small>
            @endif
          </div>

        @else
          <div class="w-100 d-block mt-4">
            <i class="bi-truck mr-2"></i> {{ __('general.free_shipping') }}
          </div>
        @endif

        <div class="w-100 d-block mt-4">
          <i class="bi-box-seam mr-2"></i> {{ $product->box_contents }}
        </div>

      @endif

      @if ($product->category)
        <div class="w-100 d-block mt-4">
          <i class="bi-tag mr-2"></i>
          <a href="{{url("shop?cat=")}}{{$product->categoryId->slug}}" >
            {{ Lang::has('shop-categories.' . $product->categoryId->slug) ? __('shop-categories.' . $product->categoryId->slug) : $product->categoryId->name }}
          </a>
        </div>
      @endif

      <div class="w-100 d-block mt-4">
        @for ($i = 0; $i < count($tags); ++$i)
          <a href="{{ url('shop?tags=').trim($tags[$i]) }}">#{{ trim($tags[$i]) }}</a>
        @endfor
      </div>

      <div class="w-100 d-block mt-4">
        <i class="feather icon-share mr-2"></i> <span class="mr-2">{{ __('general.share') }}</span>

        <a href="https://www.facebook.com/sharer/sharer.php?u={{url()->current().Helper::referralLink()}}" title="Facebook" target="_blank" class="d-inline-block mr-2 h5">
          <i class="fab fa-facebook facebook-btn"></i>
        </a>

        <a href="https://twitter.com/intent/tweet?url={{url()->current().Helper::referralLink()}}&text={{ $product->name }}" title="Twitter" target="_blank" class="d-inline-block mr-2 h5">
          <i class="bi-twitter-x"></i>
        </a>

        <a href="whatsapp://send?text={{url()->current().Helper::referralLink()}}" data-action="share/whatsapp/share" class="d-inline-block h5" title="WhatsApp">
          <i class="fab fa-whatsapp btn-whatsapp"></i>
        </a>
      </div><!-- Share -->

      @if (auth()->check() && auth()->id() != $product->user()->id)
        <div class="w-100 d-block mt-4">
          <button type="button" class="btn e-none btn-link text-danger p-0" data-toggle="modal" data-target="#reportItem">
                <small><i class="bi-flag mr-1"></i> {{ __('general.report_item') }}</small>
              </button>
        </div>
      @endif

      </div><!-- card-body -->
    </div><!-- card -->


    </div><!-- end col-5 -->

      </div><!-- row -->
    </div><!-- container -->

    @auth
      @include('shop.modal-buy')
    @endauth

    @if (auth()->check() && auth()->id() != $product->user()->id)
    <div class="modal fade modalReport" id="reportItem" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-danger modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title font-weight-light" id="modal-title-default">
              <i class="fas fa-flag mr-1"></i> {{trans('general.report_item')}}
            </h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </button>
          </div>

        <!-- form start -->
        <form method="POST" action="{{url('report/item', $product->id)}}" enctype="multipart/form-data">
        <div class="modal-body">
          @csrf
          <!-- Start Form Group -->
          <div class="form-group">
            <label>{{trans('admin.please_reason')}}</label>
              <select name="reason" class="form-control custom-select">
                @if ($verifyPurchaseUser && $product->type != 'digital')
                <option value="item_not_received">{{trans('general.item_not_received')}}</option>
              @endif
                <option value="spoofing">{{trans('admin.spoofing')}}</option>
                  <option value="copyright">{{trans('admin.copyright')}}</option>
                  <option value="privacy_issue">{{trans('admin.privacy_issue')}}</option>
                  <option value="violent_sexual">{{trans('admin.violent_sexual_content')}}</option>
                  <option value="fraud">{{trans('general.fraud')}}</option>
                </select>

                <textarea name="message" rows="" cols="40" maxlength="200" placeholder="{{__('general.message')}} ({{ __('general.optional') }})" class="form-control mt-2 textareaAutoSize"></textarea>
                
                </div><!-- /.form-group-->
            </div><!-- Modal body -->

            <div class="modal-footer">
              <button type="button" class="btn border text-white" data-dismiss="modal">{{trans('admin.cancel')}}</button>
              <button type="submit" class="btn btn-xs btn-white sendReport ml-auto"><i></i> {{trans('general.report_item')}}</button>
            </div>
            </form>
          </div><!-- Modal content -->
        </div><!-- Modal dialog -->
      </div><!-- Modal -->
    @endif

@if ($totalProducts > 1)
<div class="container pt-5 border-top">
		 <div class="row">

       <div class="col-md-12 mb-4">

         <div class="d-flex justify-content-between align-items-center">
    		 <h4 class="font-weight-light">{{ __('general.other_items_of') }} {{ '@'.$product->user()->username }}</h4>

         @if ($totalProducts > 4)
         <h5 class="font-weight-light">
           <a href="{{ url($product->user()->username, 'shop') }}">
             {{ __('general.view_all') }}
           </a>
         </h5>
       @endif
      </div>

    	 </div>

       @foreach ($userProducts->where('id', '<>', $product->id)->take(3)->inRandomOrder()->get() as $product)
       <div class="col-md-4 mb-4">
         @include('shop.listing-products')
       </div><!-- end col-md-4 -->
       @endforeach

     </div><!-- row -->
	 </div><!-- container -->
@endif
</section>

@endsection

@section('javascript')
  @auth
    <script src="{{ asset('public/js/shop.js') }}"></script>
  @endauth

  @if ($previews > 1)
    <script src="{{ asset('public/js/splide/splide.min.js') }}"></script>
    <script src="{{ asset('public/js/splide/splide-init.js') }}"></script>
  @endif
@endsection
