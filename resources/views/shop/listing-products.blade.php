<a href="{{ url('shop/product', $product->id) }}" class="link-shop">
	<div class="card card-updates h-100 card-user-profile shadow-sm">
		<span class="badge type-item p-2 badge-pill">
			{!! ($product->type == 'digital')
				? '<i class="bi-cloud-download mr-2"></i>'. __('general.digital_download')
				: (($product->type == 'physical')
				? '<i class="bi-controller mr-2"></i>'. __('general.physical_products')
				: '<i class="bi-lightning-charge mr-2"></i>'. __('general.custom_content'))
			!!}
		</span>
	<div class="card-cover position-relative" style="background: url({{ route('resize', ['path' => 'shop', 'file' => $product->previews[0]->name, 'size' => 480]) }}) #efefef center center; background-size: cover; height:300px;">

		<span @class(['price-shop', 'bg-danger' => $product->type == 'physical' && $product->quantity == 0])>
			@if ($product->type == 'physical' && $product->quantity == 0)
				{{ __('general.sold_out') }}
			@else
				{{ Helper::amountFormatDecimal($product->price) }}
			@endif
		</span>
	</div>

	<div class="card-body">
			<h5 class="card-title mb-2 text-truncate-2">{{$product->name }}</h5>
			<p class="my-2 text-muted card-text text-truncate-2">{{ Str::limit($product->description, 100, '...') }}</p>
	</div><!-- card-body -->

	<div class="card-footer pt-0 bg-transparent border-top-0">
		<div class="d-flex align-items-end justify-content-between">
				<div class="d-flex align-items-center">
						<img class="rounded-circle mr-3" src="{{ Helper::getFile(config('path.avatar').$product->user()->avatar) }}" width="40" height="40" alt="{{$product->user()->username}}">
						<div class="small">
								<div><strong>{{ '@'.$product->user()->username }}</strong></div>
								<div class="text-muted">{{ Helper::formatDate($product->created_at) }}</div>
						</div>
				</div>
		</div>
</div>
</div><!-- End Card -->
</a>
