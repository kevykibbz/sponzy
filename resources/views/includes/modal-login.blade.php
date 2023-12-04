<div class="modal fade" id="loginFormModal" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-sm modal-login" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
					<div class="card-body px-lg-5 py-lg-5 position-relative">

						<h6 class="modal-title text-center mb-3" id="loginRegisterContinue">{{ __('general.login_continue') }}</h6>

						@if ($settings->facebook_login == 'on' || $settings->google_login == 'on' || $settings->twitter_login == 'on')
						<div class="mb-2 w-100">

							@if ($settings->facebook_login == 'on')
								<a href="{{url('oauth/facebook')}}" class="btn btn-facebook auth-form-btn flex-grow mb-2 w-100">
									<i class="fab fa-facebook mr-2"></i> <span class="loginRegisterWith">{{ __('auth.login_with') }}</span> Facebook
								</a>
							@endif

							@if ($settings->twitter_login == 'on')
							<a href="{{url('oauth/twitter')}}" class="btn btn-twitter auth-form-btn mb-2 w-100">
								<i class="bi-twitter-x mr-2"></i> <span class="loginRegisterWith">{{ __('auth.login_with') }}</span> Twitter
							</a>
						@endif

								@if ($settings->google_login == 'on')
								<a href="{{url('oauth/google')}}" class="btn btn-google auth-form-btn flex-grow w-100">
									<img src="{{ url('public/img/google.svg') }}" class="mr-2" width="18" height="18"> <span class="loginRegisterWith">{{ __('auth.login_with') }}</span> Google
								</a>
							@endif
							</div>

						@if (! $settings->disable_login_register_email)
							<small class="btn-block text-center my-3 text-uppercase or">{{__('general.or')}}</small>
						@endif
					@endif
						
			@if (! $settings->disable_login_register_email)
				<form method="POST" action="{{ route('login') }}" data-url-login="{{ route('login') }}" data-url-register="{{ route('register') }}" id="formLoginRegister" enctype="multipart/form-data">
						@csrf

						@if (request()->route()->named('profile'))
							<input type="hidden" name="isProfile" value="{{ $user->username }}">
						@endif

						<input type="hidden" name="isModal" id="isModal" value="true">

						<div class="form-group mb-3 display-none" id="full_name">
							<div class="input-group input-group-alternative">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="feather icon-user"></i></span>
								</div>
								<input class="form-control"  value="{{ old('name')}}" placeholder="{{__('auth.full_name')}}" name="name" type="text">
							</div>
						</div>

					<div class="form-group mb-3 display-none" id="email">
						<div class="input-group input-group-alternative">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="feather icon-mail"></i></span>
							</div>
							<input class="form-control" value="{{ old('email')}}" placeholder="{{__('auth.email')}}" name="email" type="text">
						</div>
					</div>

					<div class="form-group mb-3" id="username_email">
						<div class="input-group input-group-alternative">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="feather icon-mail"></i></span>
							</div>
							<input class="form-control" value="{{ old('username_email') }}" placeholder="{{ __('auth.username_or_email') }}" name="username_email" type="text">

						</div>
					</div>
					<div class="form-group">
						<div class="input-group input-group-alternative" id="showHidePassword">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="iconmoon icon-Key"></i></span>
							</div>
							<input name="password" type="password" class="form-control" placeholder="{{ __('auth.password') }}">
							<div class="input-group-append">
								<span class="input-group-text c-pointer"><i class="feather icon-eye-off"></i></span>
						</div>
					</div>
					<small class="form-text text-muted">
						<a href="{{url('password/reset')}}" id="forgotPassword">
							{{__('auth.forgot_password')}}
						</a>
					</small>
					</div>

					<div class="custom-control custom-control-alternative custom-checkbox" id="remember">
						<input class="custom-control-input" id=" customCheckLogin" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
						<label class="custom-control-label" for=" customCheckLogin">
							<span>{{__('auth.remember_me')}}</span>
						</label>
					</div>

					<div class="custom-control custom-control-alternative custom-checkbox display-none" id="agree_gdpr">
						<input class="custom-control-input" id="customCheckRegister" type="checkbox" name="agree_gdpr">
							<label class="custom-control-label" for="customCheckRegister">
								<span>
									{{__('admin.i_agree_gdpr')}}
									<a href="{{$settings->link_terms}}" target="_blank">{{__('admin.terms_conditions')}}</a>
                        			{{ __('general.and') }}
									<a href="{{$settings->link_privacy}}" target="_blank">{{__('admin.privacy_policy')}}</a>
								</span>
							</label>
					</div>

					<div class="alert alert-danger display-none mb-0 mt-3" id="errorLogin">
							<ul class="list-unstyled m-0" id="showErrorsLogin"></ul>
						</div>

						<div class="alert alert-success display-none mb-0 mt-3" id="checkAccount"></div>

					<div class="text-center">
						@if ($settings->captcha == 'on')
						{!! NoCaptcha::displaySubmit('formLoginRegister', '<i></i> '.__('auth.login'), ['data-size' => 'invisible', 'id' => 'btnLoginRegister', 'class' => 'btn btn-primary mt-4 w-100']) !!}

                  		{!! NoCaptcha::renderJs() !!}
						
						@else
						<button type="submit" id="btnLoginRegister" class="btn btn-primary mt-4 w-100"><i></i> {{__('auth.login')}}</button>
						@endif

						<div class="w-100 mt-2">
							<button type="button" class="btn e-none p-0" data-dismiss="modal">{{ __('admin.cancel') }}</button>
						</div>
					</div>
				</form>

				@if ($settings->captcha == 'on')
					<small class="btn-block text-center mt-3">{{__('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{__('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{__('general.terms')}}</a></small>
				@endif

				@if ($settings->registration_active == '1')
				<div class="row mt-3">
					<div class="col-12 text-center">
						<a href="javascript:void(0);" id="toggleLogin" data-not-account="{{__('auth.not_have_account')}}" data-already-account="{{__('auth.already_have_an_account')}}" data-text-login="{{__('auth.login')}}" data-text-register="{{__('auth.sign_up')}}">
							<strong>{{__('auth.not_have_account')}}</strong>
						</a>
					</div>
				</div>
				@endif

			@else
				<div class="row mt-3">
					<div class="col-12 text-center">
						<a href="javascript:void(0);" id="toggleLogin" data-not-account="{{__('auth.not_have_account')}}" data-already-account="{{__('auth.already_have_an_account')}}" data-text-login="{{__('auth.login')}}" data-text-register="{{__('auth.sign_up')}}">
							<strong>{{__('auth.not_have_account')}}</strong>
						</a>
					</div>
				</div>
			@endif

			</div><!-- ./ card-body -->
		</div>
	</div>
 </div>
</div>
