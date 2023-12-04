<!-- Start Modal liveStreamingForm -->
<div class="modal fade" id="liveStreamingForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
	<div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card bg-white shadow border-0">

					<div class="card-body px-lg-5 py-lg-5 position-relative">

						<div class="mb-3">
							<i class="bi bi-broadcast mr-1"></i> <strong>{{trans('general.create_live_stream')}}</strong>
						</div>

						<form method="post" action="{{url('create/live')}}" id="formSendLive">

							@csrf

							<div class="form-group">
		            <div class="input-group mb-4">
		            <div class="input-group-prepend">
		              <span class="input-group-text"><i class="bi bi-lightning-charge"></i></span>
		            </div>
		                <input type="text" autocomplete="off" class="form-control" name="name" placeholder="{{ __('auth.name') }} *">
		            </div>
		          </div><!-- End form-group -->

							<div class="form-group">
                <div class="input-group mb-2" id="AvailabilityGroup">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="bi bi-eye"></i></span>
                </div>
                <select name="availability" id="Availability" class="form-control custom-select">
                  <option value="all_pay" data-text="{{ trans('general.desc_available_everyone_paid') }}">{{trans('general.available_everyone_paid')}}</option>
									<option value="free_paid_subscribers" data-text="{{ trans('general.info_price_live') }}">{{trans('general.available_free_paid_subscribers')}}</option>

									@if ($settings->live_streaming_free)
										<option value="everyone_free" data-text="{{ trans('general.desc_everyone_free') }}">{{trans('general.available_everyone_free')}}</option>
									@endif
                  </select>
                  </div>

									@if ($settings->limit_live_streaming_paid != 0)
										<small class="form-text text-danger" id="LimitLiveStreamingPaid">
											<i class="bi bi-exclamation-triangle-fill mr-1"></i> <strong>{{ trans('general.limit__minutes_per_transmission_paid', ['min' => $settings->limit_live_streaming_paid]) }}</strong>
											</small>
									@endif

									@if ($settings->limit_live_streaming_free != 0)
										<small class="form-text display-none text-danger" id="everyoneFreeAlert">
											<i class="bi bi-exclamation-triangle-fill mr-1"></i> <strong>{{ trans('general.limit__minutes_per_transmission_free', ['min' => $settings->limit_live_streaming_free]) }}</strong>
											</small>
									@endif

                </div><!-- ./form-group -->

							<div class="form-group mb-0">
		            <div class="input-group">
		            <div class="input-group-prepend">
		              <span class="input-group-text">{{$settings->currency_symbol}}</span>
		            </div>
		                <input type="number" min="{{$settings->live_streaming_minimum_price}}" autocomplete="off" id="onlyNumber" class="form-control priceLive" name="price" placeholder="{{ __('general.price') }} ({{ __('general.minimum') }} {{ Helper::priceWithoutFormat($settings->live_streaming_minimum_price) }})">
		            </div>
		          </div><!-- End form-group -->
							<small class="form-text mb-4" id="descAvailability">{{ trans('general.desc_available_everyone_paid') }}</small>

							<div class="alert alert-danger display-none mb-0 mt-3" id="errorLive">
									<ul class="list-unstyled m-0" id="showErrorsLive"></ul>
								</div>

							<div class="text-center">
								<button type="button" class="btn e-none mt-4" data-dismiss="modal">{{trans('admin.cancel')}}</button>
								<button type="submit" id="liveBtn" class="btn btn-primary mt-4 liveBtn"><i></i> {{trans('users.create')}}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- End Modal liveStreamingForm -->
