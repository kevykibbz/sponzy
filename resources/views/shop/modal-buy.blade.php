<!-- Start Modal payPerViewForm -->
<div class="modal fade" id="buyNowForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered @if ($product->type == 'digital') modal-sm @endif" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card bg-white shadow border-0">

					<div class="card-body px-lg-5 py-lg-5 position-relative">

						<div class="mb-4">
							<i class="bi-cart-plus mr-1"></i> <strong>{{ $product->name }}</strong>
							<small class="w-100 d-block font-12">* {{ __('general.in_currency', ['currency_code' => $settings->currency_code]) }}</small>
						</div>

						<form method="post" action="{{url('buy/now/product')}}" id="shopProductForm">

							<input type="hidden" name="id" value="{{ $product->id }}" />
							@csrf

							<div class="custom-control custom-radio mb-3">
								<input name="payment_gateway_buy" @if (Helper::userWallet('balance') == 0) disabled @else checked @endif value="wallet" id="buy_radio0" class="custom-control-input" type="radio">
								<label class="custom-control-label" for="buy_radio0">
									<span>
										<strong>
										<i class="fas fa-wallet mr-1 icon-sm-radio"></i> {{ __('general.wallet') }}
										<span class="w-100 d-block font-weight-light">
											{{ __('general.available_balance') }}: <span class="font-weight-bold mr-1 balanceWallet">{{Helper::userWallet()}}</span>

											@if (Helper::userWallet('balance') != 0 && $settings->wallet_format <> 'real_money')
												<i class="bi bi-info-circle text-muted" data-toggle="tooltip" data-placement="top" title="{{Helper::equivalentMoney($settings->wallet_format)}}"></i>
											@endif

											@if (Helper::userWallet('balance') == 0)
											<a href="{{ url('my/wallet') }}" class="link-border">{{ __('general.recharge') }}</a>
										@endif
										</span>
									</strong>
									</span>
								</label>
							</div>

							@if ($product->type == 'custom')
							<div class="form-group mb-2">
								<textarea class="form-control textareaAutoSize" name="description_custom_content" id="descriptionCustomContent" placeholder="{{ __('general.description_custom_content') }}" rows="4"></textarea>
							</div>

							<div class="alert alert-warning" role="alert">
							 <i class="bi-exclamation-triangle mr-2"></i> {{ __('general.alert_buy_custom_content') }}
							</div>
						@endif

						@if ($product->type == 'physical')
							<h6 class="mb-3"><i class="bi-truck mr-1"></i> {{ __('general.shipping') }}</h6>

							<div class="row form-group mb-0">

							<div class="col-md-6">
									<div class="input-group mb-4">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fa fa-map-marked-alt"></i></span>
										</div>
										<input class="form-control" name="address" placeholder="{{__('general.address')}}"  value="{{auth()->user()->address}}" type="text">
									</div>
								</div><!-- ./col-md-6 -->

								<div class="col-md-6">
										<div class="input-group mb-4">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fa fa-map-pin"></i></span>
											</div>
											<input class="form-control" name="city" placeholder="{{__('general.city')}}"  value="{{auth()->user()->city}}" type="text">
										</div>
									</div><!-- ./col-md-6 -->

										<div class="col-md-6">
												<div class="input-group mb-4">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
													</div>
													<input class="form-control" name="zip" placeholder="{{__('general.zip')}}"  value="{{auth()->user()->zip}}" type="text">
												</div>
											</div><!-- ./col-md-6 -->

											<div class="col-md-6">
													<div class="input-group mb-4">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="bi-telephone"></i></span>
														</div>
														<input class="form-control" name="phone" placeholder="{{__('general.phone')}}" type="tel">
													</div>
												</div><!-- ./col-md-6 -->

											</div><!-- ./row -->

						<div class="alert alert-warning" role="alert">
						 <i class="bi-exclamation-triangle mr-2"></i> {{ __('general.alert_buy_custom_content') }}
						</div>
					@endif

							@if (auth()->user()->isTaxable()->count() || $product->shipping_fee <> 0.00 && $product->country_free_shipping <> auth()->user()->countries_id)
								<ul class="list-group list-group-flush border-dashed-radius">

									<li class="list-group-item py-1 list-taxes">
								    <div class="row">
								      <div class="col">
								        <small>{{__('general.subtotal')}}:</small>
								      </div>
								      <div class="col-auto">
								        <small class="subtotal font-weight-bold">
								        {{Helper::amountFormatDecimal($product->price)}}
								        </small>
								      </div>
								    </div>
								  </li>

									@foreach (auth()->user()->isTaxable() as $tax)
										<li class="list-group-item py-1 list-taxes isTaxable">
									    <div class="row">
									      <div class="col">
									        <small>{{ $tax->name }} {{ $tax->percentage }}%:</small>
									      </div>
									      <div class="col-auto percentageAppliedTax">
									        <small class="font-weight-bold">
									        	{{ Helper::amountFormatDecimal(Helper::calculatePercentage($product->price, $tax->percentage)) }}
									        </small>
									      </div>
									    </div>
									  </li>
									@endforeach

									@if ($product->shipping_fee <> 0.00 && $product->country_free_shipping <> auth()->user()->countries_id)
										<li class="list-group-item py-1 list-taxes">
									    <div class="row">
									      <div class="col">
									        <small>{{__('general.shipping_fee')}}:</small>
									      </div>
									      <div class="col-auto">
									        <small class="totalPPV font-weight-bold">
									        {{ Helper::amountFormatDecimal($product->shipping_fee) }}
									        </small>
									      </div>
									    </div>
									  </li>
									@endif


									<li class="list-group-item py-1 list-taxes">
								    <div class="row">
								      <div class="col">
								        <small class="font-weight-bold">{{__('general.total')}}:</small>
								      </div>
								      <div class="col-auto">
								        <small class="totalPPV font-weight-bold">
								        {{Helper::calculateProductPriceOnStore($product->price, $product->country_free_shipping <> auth()->user()->countries_id ? $product->shipping_fee : 0.00)}}
								        </small>
								      </div>
								    </div>
								  </li>
								</ul>
							@endif

							<div class="alert alert-danger display-none mb-0 mt-2" id="errorShopProduct">
									<ul class="list-unstyled m-0" id="showErrorsShopProduct"></ul>
								</div>

							<div class="text-center">
								<button type="submit" @if (Helper::userWallet('balance') == 0) disabled @endif id="shopProductBtn" class="btn btn-primary mt-4 BuyNowBtn">
									<i></i> {{__('general.pay')}} {{Helper::calculateProductPriceOnStore($product->price, $product->country_free_shipping <> auth()->user()->countries_id ? $product->shipping_fee : 0.00)}} <small>{{$settings->currency_code}}</small>
								</button>

								<div class="w-100 mt-2">
									<a href="#" class="btn e-none p-0" data-dismiss="modal">{{__('admin.cancel')}}</a>
								</div>
							</div>
							@include('includes.site-billing-info')
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- End Modal BuyNow -->
