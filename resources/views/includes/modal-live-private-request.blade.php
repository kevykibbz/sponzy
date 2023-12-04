<div class="modal fade" id="modalLivePrivateRequest" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
	<div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card bg-white shadow border-0">
					<div class="card-body px-lg-5 py-lg-5 position-relative">

						<div class="text-muted text-center mb-3 position-relative modal-offset">
							<img src="{{Helper::getFile(config('path.avatar').$user->avatar)}}" width="100" class="avatar-modal rounded-circle mb-1">
							<h6>
								{{__('general.request_private_live_stream')}} {{ '@' . $user->username }}
								<small class="w-100 d-block font-12">* {{ __('general.in_currency', ['currency_code' => $settings->currency_code]) }}</small>
							</h6>
						</div>

						<form method="post" action="{{route('request.live_private', ['user' => $user->id])}}" id="formRequestLivePrivate">

							@csrf

                            <div class="input-group mb-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi-clock"></i></span>
                                </div>
                                <select @disabled(Helper::userWallet('balance') == 0) required name="minutes" class="form-control custom-select minutes" data-price-minute="{{ $user->price_live_streaming_private }}">
                                    <option value="">{{ __('general.select_the_minutes') }}</option>
                                    @for ($i = 10; $i <= $settings->limit_live_streaming_private; $i+=5)
                                    <option value="{{ $i }}">{{$i}} {{ __('general.minutes') }}</option>
                                    @endfor
                                </select>
                                <span class="w-100 btn-block">
                                    {{ __('general.price_per_minute') }}: <strong>{{ Helper::priceWithoutFormat($user->price_live_streaming_private) }}</strong>
                                </span>
                            </div>
                            

							<div class="custom-control custom-radio mb-3">
								<input name="payment_gateway_live_private" @if (Helper::userWallet('balance') == 0) disabled @else checked @endif value="wallet" id="radioLive0" class="custom-control-input" type="radio">
								<label class="custom-control-label" for="radioLive0">
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

							@include('includes.modal-taxes')

							<div class="alert alert-danger display-none mt-2" id="errorLivePrivate">
									<ul class="list-unstyled m-0" id="showLivePrivate"></ul>
								</div>

							<div class="text-center">
								<button type="button" class="btn e-none mt-4" data-dismiss="modal">{{__('admin.cancel')}}</button>
								<button type="submit" @disabled(Helper::userWallet('balance') == 0) id="livePrivateBtn" class="btn btn-primary mt-4 livePrivateBtn"><i></i> {{__('general.send_request')}}</button>
							</div>
							@include('includes.site-billing-info')
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- End Modal Tip -->
