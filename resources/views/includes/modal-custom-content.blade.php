<!-- Start Modal payPerViewForm -->
<div class="modal fade" id="customContentForm{{$sale->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
	<div class="modal-dialog modal- modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card bg-white shadow border-0">

					<div class="card-body px-lg-5 py-lg-5 position-relative">

						<div class="mb-4 position-relative">
							 <strong>{{ $sale->products()->type == 'physical' ? __('general.shipping_information') : __('general.details_custom_content') }}</strong>
							 <small data-dismiss="modal" class="btn-cancel-msg"><i class="bi bi-x-lg"></i></small>
						</div>

						<h6>
							{{ __('auth.email') }}:

							@if (! isset($sale->user()->email))
								<em>{{ __('general.no_available') }}</em>
							@else
							{{ $sale->user()->email }}
						@endif
						</h6>

						@if (isset($sale->user()->name) && $sale->products()->type == 'physical')
						<h6>
							{{ __('auth.name') }}: {{ $sale->user()->name }}<br>					
						</h6>
						@endif

						@if ($sale->products()->type == 'physical')
							<h6 class="font-weight-light">{{ __('general.address') }} : {{ $sale->address }}</h6>
							<h6 class="font-weight-light">{{ __('general.city') }} : {{ $sale->city }}</h6>
							<h6 class="font-weight-light">{{ __('general.zip') }} : {{ $sale->zip }}</h6>
							<h6 class="font-weight-light">{{ __('general.phone') }} : {{ $sale->phone }}</h6>
						@endif

						<p>
							{!! Helper::checkText($sale->description_custom_content) !!}
						</p>

					</div><!-- End card-body -->
				</div><!-- End card -->
			</div><!-- End modal-body -->
		</div><!-- End modal-content -->
	</div><!-- End Modal-dialog -->
</div><!-- End Modal BuyNow -->
