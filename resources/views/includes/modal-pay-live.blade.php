<!-- Start Modal payPerViewForm -->
<div class="modal fade" id="payLiveForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
	<div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card bg-white shadow border-0">

					<div class="card-body px-lg-5 py-lg-5 position-relative">

						<div class="mb-4 text-center position-relative">
							<div class="text-center position-relative mb-3 modal-offset">
								<div class="wrapper-live">
									<span class="live-span">{{ trans('general.live') }}</span>
									<div class="live-pulse"></div>
									<img src="{{Helper::getFile(config('path.avatar').$creator->avatar)}}" width="100" class="rounded-circle mb-1">
								</div>
						</div>

							<i class="bi bi-broadcast mr-1"></i> <strong>{{trans('general.Join_live_stream')}} {{ '@'.$creator->username }}</strong>

							<small class="w-100 d-block">
								"{{ $live->name }}"
							</small>

							<small class="w-100 d-block font-12">* {{ __('general.in_currency', ['currency_code' => $settings->currency_code]) }}</small>
						</div>

						<form method="post" action="{{url('send/payment/live')}}" id="formPayLive">

							<input type="hidden" name="id" value="{{ $live->id }}" />
							@csrf

							<div class="custom-control custom-radio mb-3">
								<input name="payment_gateway_live" @if (Helper::userWallet('balance') == 0) disabled @else checked @endif value="wallet" id="live_radio0" class="custom-control-input" type="radio">
								<label class="custom-control-label" for="live_radio0">
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

							@if (auth()->user()->isTaxable()->count())
								<ul class="list-group list-group-flush border-dashed-radius">

									<li class="list-group-item py-1 list-taxes">
								    <div class="row">
								      <div class="col">
								        <small>{{trans('general.subtotal')}}:</small>
								      </div>
								      <div class="col-auto">
								        <small class="subtotal font-weight-bold">
								        {{Helper::formatPrice($live->price)}}
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
									        	{{ Helper::formatPrice(Helper::calculatePercentage($live->price, $tax->percentage)) }}
									        </small>
									      </div>
									    </div>
									  </li>
									@endforeach

									<li class="list-group-item py-1 list-taxes">
								    <div class="row">
								      <div class="col">
								        <small>{{trans('general.total')}}:</small>
								      </div>
								      <div class="col-auto">
								        <small class="totalPPV font-weight-bold">
								        {{Helper::formatPrice($live->price, true)}}
								        </small>
								      </div>
								    </div>
								  </li>

								</ul>

							@endif

							<div class="alert alert-danger display-none mb-0" id="errorPayLive">
									<ul class="list-unstyled m-0" id="showErrorsPayLive"></ul>
								</div>

							<div class="text-center">
								<button type="submit" @if (Helper::userWallet('balance') == 0) disabled @endif id="payLiveBtn" class="btn btn-primary mt-4 payLiveBtn">
									<i></i> {{trans('general.pay')}} {{Helper::formatPrice($live->price, true)}}
								</button>

								<div class="w-100 mt-2">
									<a href="{{ url('/') }}" class="btn e-none p-0">{{trans('admin.cancel')}}</a>
								</div>
							</div>
							@include('includes.site-billing-info')
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- End Modal PayLive -->
