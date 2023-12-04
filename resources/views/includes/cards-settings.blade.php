<div class="col-md-6 col-lg-3 mb-3">

<button type="button" class="btn-menu-expand btn btn-primary btn-block mb-2 d-lg-none" type="button" data-toggle="collapse" data-target="#navbarUserHome" aria-controls="navbarCollapse" aria-expanded="false">
		<i class="fa fa-bars mr-2"></i> {{__('general.menu')}}
	</button>

	<div class="navbar-collapse collapse d-lg-block" id="navbarUserHome">

		<!-- Start Account -->
		<div class="card shadow-sm card-settings mb-3">
				<div class="list-group list-group-sm list-group-flush">

    <small class="text-muted px-4 pt-3 text-uppercase mb-1 font-weight-bold">{{ __('general.account') }}</small>

					@if (auth()->user()->verified_id == 'yes')
					<a href="{{url('dashboard')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('dashboard')) active @endif">
							<div>
									<i class="bi bi-speedometer2 mr-2"></i>
									<span>{{__('admin.dashboard')}}</span>
							</div>
							<div>
									<i class="feather icon-chevron-right"></i>
							</div>
					</a>
				@endif

				<a href="{{url(auth()->user()->username)}}" class="list-group-item list-group-item-action d-flex justify-content-between url-user">
						<div>
								<i class="feather icon-user mr-2"></i>
								<span>{{ auth()->user()->verified_id == 'yes' ? __('general.my_page') : __('users.my_profile') }}</span>
						</div>
						<div>
								<i class="feather icon-chevron-right"></i>
						</div>
				</a>

					<a href="{{url('settings/page')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('settings/page')) active @endif">
							<div>
									<i class="bi bi-pencil mr-2"></i>
									<span>{{ auth()->user()->verified_id == 'yes' ? __('general.edit_my_page') : __('users.edit_profile')}}</span>
							</div>
							<div>
									<i class="feather icon-chevron-right"></i>
							</div>
					</a>

				@if (auth()->user()->verified_id == 'yes')
				  <a href="{{url('settings/conversations')}}" @class([ 'list-group-item list-group-item-action d-flex justify-content-between', 'active' => request()->is('settings/conversations')])>
					<div>
						<i class="feather icon-send mr-2"></i>
						<span>{{__('general.conversations')}}</span>
					</div>
					<div>
						<i class="feather icon-chevron-right"></i>
					</div>
				</a>
			  @endif

					@if ($settings->disable_wallet == 'off')
						<a href="{{url('my/wallet')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/wallet')) active @endif">
								<div>
										<i class="iconmoon icon-Wallet mr-2"></i>
										<span>{{__('general.wallet')}}</span>
								</div>
								<div>
										<i class="feather icon-chevron-right"></i>
								</div>
						</a>
					@endif

          @if ($settings->referral_system == 'on' || auth()->user()->referrals()->count() != 0)
  					<a href="{{url('my/referrals')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/referrals')) active @endif">
  							<div>
  									<i class="bi-person-plus mr-2"></i>
  									<span>{{__('general.referrals')}}</span>
  							</div>
  							<div>
  									<i class="feather icon-chevron-right"></i>
  							</div>
  					</a>
  				@endif

				  @if ($settings->story_status && auth()->user()->verified_id == 'yes')
				  <a href="{{url('my/stories')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/stories')) active @endif">
						  <div>
								  <i class="bi-clock-history mr-2"></i>
								  <span>{{__('general.my_stories')}}</span>
						  </div>
						  <div>
								  <i class="feather icon-chevron-right"></i>
						  </div>
				  </a>
			  @endif

					<a href="{{url('settings/verify/account')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('settings/verify/account')) active @endif">
							<div>
									<i class="@if (auth()->user()->verified_id == 'yes') feather icon-check-circle @else bi-star @endif mr-2"></i>
									<span>{{ auth()->user()->verified_id == 'yes' ? __('general.verified_account') : __('general.become_creator')}}</span>
							</div>
							<div>
									<i class="feather icon-chevron-right"></i>
							</div>
					</a>
					
				</div>
			</div><!-- End Account -->

			@if ($settings->live_streaming_private == 'on')
			<!-- Start Live Streaming private -->
			<div class="card shadow-sm card-settings mb-3">
				<div class="list-group list-group-sm list-group-flush">

				<small class="text-muted px-4 pt-3 text-uppercase mb-1 font-weight-bold">{{ __('general.live_streaming_private') }}</small>

				@if (auth()->user()->verified_id == 'yes')
				  <a href="{{url('my/live/private/settings')}}"
					class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/live/private/settings')) active @endif">
					<div>
						<i class="bi-gear mr-2"></i>
						<span>{{__('general.settings')}}</span>
					</div>
					<div>
						<i class="feather icon-chevron-right"></i>
					</div>
				</a>

				<a href="{{url('my/live/private/requests')}}"
					class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/live/private/requests')) active @endif">
					<div>
						<i class="bi-box-arrow-in-down mr-2"></i>
						<span>{{__('general.requests_received')}}</span>

							<span class="badge badge-warning">{{ auth()->user()->liveStreamingPrivateRequestPending() ?: null }}</span>
					</div>
					<div>
						<i class="feather icon-chevron-right"></i>
					</div>
				</a>
			  @endif

			<a href="{{url('my/live/private/requests/sended')}}"
					class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/live/private/requests/sended')) active @endif">
					<div>
						<i class="bi-box-arrow-in-up mr-2"></i>
						<span>{{__('general.requests_sent')}}</span>
					</div>
					<div>
						<i class="feather icon-chevron-right"></i>
					</div>
				</a>
			  

				</div>
			</div><!-- End Live Streaming private -->
			@endif

			<!-- Start Subscription -->
			<div class="card shadow-sm card-settings mb-3">
					<div class="list-group list-group-sm list-group-flush">

			<small class="text-muted px-4 pt-3 text-uppercase mb-1 font-weight-bold">{{ __('general.subscription') }}</small>

			@if (auth()->user()->verified_id == 'yes')
			<a href="{{url('settings/subscription')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('settings/subscription')) active @endif">
					<div>
							<i class="bi bi-cash-stack mr-2"></i>
							<span>{{__('general.subscription_price')}}</span>
					</div>
					<div>
							<i class="feather icon-chevron-right"></i>
					</div>
			</a>
		@endif

			@if (auth()->user()->verified_id == 'yes')
			<a href="{{url('my/subscribers')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/subscribers')) active @endif">
					<div>
							<i class="feather icon-users mr-2"></i>
							<span>{{__('users.my_subscribers')}}</span>
					</div>
					<div>
							<i class="feather icon-chevron-right"></i>
					</div>
			</a>
		@endif

			<a href="{{url('my/subscriptions')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/subscriptions')) active @endif">
					<div>
							<i class="feather icon-user-check mr-2"></i>
							<span>{{__('users.my_subscriptions')}}</span>
					</div>
					<div>
							<i class="feather icon-chevron-right"></i>
					</div>
			</a>

		</div>
	</div><!-- End Subscription -->

	<!-- Start Privacy and security -->
	<div class="card shadow-sm card-settings mb-3">
			<div class="list-group list-group-sm list-group-flush">

	<small class="text-muted px-4 pt-3 text-uppercase mb-1 font-weight-bold">{{ __('general.privacy_security') }}</small>

	<a href="{{url('privacy/security')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('privacy/security')) active @endif">
			<div>
					<i class="bi bi-shield-check mr-2"></i>
					<span>{{__('general.privacy_security')}}</span>
			</div>
			<div>
					<i class="feather icon-chevron-right"></i>
			</div>
	</a>

	<a href="{{url('settings/password')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('settings/password')) active @endif">
			<div>
					<i class="iconmoon icon-Key mr-2"></i>
					<span>{{__('auth.password')}}</span>
			</div>
			<div>
					<i class="feather icon-chevron-right"></i>
			</div>
	</a>

	@if (auth()->user()->verified_id == 'yes')
	<a href="{{url('block/countries')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('block/countries')) active @endif">
			<div>
					<i class="bi bi-eye-slash mr-2"></i>
					<span>{{__('general.block_countries')}}</span>
			</div>
			<div>
					<i class="feather icon-chevron-right"></i>
			</div>
	</a>
@endif

<a href="{{url('settings/restrictions')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('settings/restrictions')) active @endif">
		<div>
				<i class="feather icon-slash mr-2"></i>
				<span>{{__('general.restricted_users')}}</span>
		</div>
		<div>
				<i class="feather icon-chevron-right"></i>
		</div>
</a>

			</div>
		</div><!-- End Privacy and security -->

			<!-- Start Payments -->
			<div class="card shadow-sm card-settings mb-3">
					<div class="list-group list-group-sm list-group-flush">

	    <small class="text-muted px-4 pt-3 text-uppercase mb-1 font-weight-bold">{{ __('general.payments') }}</small>

			<a href="{{url('my/payments')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/payments')) active @endif">
					<div>
							<i class="bi bi-receipt mr-2"></i>
							<span>{{__('general.payments')}}</span>
					</div>
					<div>
							<i class="feather icon-chevron-right"></i>
					</div>
			</a>

			@if (auth()->user()->verified_id == 'yes')
			<a href="{{url('my/payments/received')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/payments/received')) active @endif">
					<div>
							<i class="bi bi-receipt mr-2"></i>
							<span>{{__('general.payments_received')}}</span>
					</div>
					<div>
							<i class="feather icon-chevron-right"></i>
					</div>
			</a>
		@endif

			@if ($showSectionMyCards)
				<a href="{{url('my/cards')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/cards')) active @endif">
						<div>
								<i class="feather icon-credit-card mr-2"></i>
								<span>{{__('general.my_cards')}}</span>
						</div>
						<div>
								<i class="feather icon-chevron-right"></i>
						</div>
				</a>
				@endif

				@if (auth()->user()->verified_id == 'yes' || $settings->referral_system == 'on' || auth()->user()->balance != 0.00)
				<a href="{{url('settings/payout/method')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('settings/payout/method')) active @endif">
						<div>
								<i class="bi bi-credit-card mr-2"></i>
								<span>{{__('users.payout_method')}}</span>
						</div>
						<div>
								<i class="feather icon-chevron-right"></i>
						</div>
				</a>

				<a href="{{url('settings/withdrawals')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('settings/withdrawals')) active @endif">
						<div>
								<i class="bi bi-arrow-left-right mr-2"></i>
								<span>{{__('general.withdrawals')}}</span>
						</div>
						<div>
								<i class="feather icon-chevron-right"></i>
						</div>
				</a>
			@endif

					</div>
				</div><!-- End Payments -->

	@if ($settings->shop
			|| auth()->user()->sales()->count() != 0 && auth()->user()->verified_id == 'yes'
			|| auth()->user()->sales()->count() != 0 && auth()->user()->verified_id == 'yes'
			|| auth()->user()->purchasedItems()->count() != 0)
	<!-- Start Shop -->
	<div class="card shadow-sm card-settings">
			<div class="list-group list-group-sm list-group-flush">

				<small class="text-muted px-4 pt-3 text-uppercase mb-1 font-weight-bold">{{ __('general.shop') }}</small>

					@if ($settings->shop && auth()->user()->verified_id == 'yes' || auth()->user()->sales()->count() != 0 && auth()->user()->verified_id == 'yes')
					<a href="{{url('my/sales')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/sales')) active @endif">
							<div>
									<i class="bi-cart2 mr-2"></i>
									<span class="mr-1">{{__('general.sales')}}</span>

										<span class="badge badge-warning">{{ auth()->user()->sales()->whereDeliveryStatus('pending')->count() ?: null }}</span>
							</div>
							<div>
									<i class="feather icon-chevron-right"></i>
							</div>
					</a>
				@endif

				@if ($settings->shop && auth()->user()->verified_id == 'yes' || auth()->user()->products()->count() != 0 && auth()->user()->verified_id == 'yes')
				<a href="{{url('my/products')}}" class="list-group-item list-group-item-action d-flex justify-content-between">
						<div>
								<i class="bi-tag mr-2"></i>
								<span>{{__('general.products')}}</span>
						</div>
						<div>
								<i class="feather icon-chevron-right"></i>
						</div>
				</a>
			@endif

					@if ($settings->shop || auth()->user()->purchasedItems()->count() != 0)
					<a href="{{url('my/purchased/items')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/purchased/items')) active @endif">
							<div>
									<i class="bi-bag-check mr-2"></i>
									<span>{{__('general.purchased_items')}}</span>
							</div>
							<div>
									<i class="feather icon-chevron-right"></i>
							</div>
					</a>
				@endif
			</div>
	</div><!-- End Shop -->
	@endif

	</div>
</div>
