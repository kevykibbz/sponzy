@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
			<span class="text-muted">{{ __('general.role_and_permissions') }}</span>
			<i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ $user->name }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			@if (session('success_message'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check2 me-1"></i>	{{ session('success_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
                </div>
              @endif

			@if (session('error_message'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi bi-check2 me-1"></i>	{{ session('error_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
                </div>
              @endif

			@include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form method="POST" action="{{ url('panel/admin/members/roles-and-permissions', $user->id) }}" enctype="multipart/form-data">
						 @csrf

						 <div class="row mb-3">
 		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.role') }}</label>
 		          <div class="col-sm-10">
 		            <select name="role" class="form-select">
									<option @if ($user->role == 'normal') selected="selected" @endif value="normal">{{trans('admin.normal')}}</option>
									<option @if ($user->role == 'admin') selected="selected" @endif value="admin">{{trans('admin.role_admin')}}</option>
 		           </select>
 		          </div>
 		        </div><!-- end row -->

						@if ($user->role == 'admin')

						<fieldset class="row mb-3">
								<legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.can_see_post_blocked') }}</legend>
								<div class="col-sm-10">
									<div class="form-check">
										<input class="form-check-input" type="radio" name="permission" id="radio1" @if ($user->permission == 'all') checked="checked" @endif value="all" checked>
										<label class="form-check-label" for="radio1">
											{{ trans('general.yes') }}
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="radio" name="permission" id="radio2" @if ($user->permission == 'none') checked="checked" @endif value="none">
										<label class="form-check-label" for="radio2">
											{{ trans('general.no') }}
										</label>
									</div>
									<small class="d-block">{{ __('general.info_can_see_post_blocked') }}</small>
								</div>
							</fieldset><!-- end row -->

						<div class="row mb-5">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input limitedAccess" name="permissions[]" value="limited_access" @if (isset($permissions) && in_array('limited_access', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck1">
									<label class="form-check-label" for="gridCheck1">
										{{ __('general.limited_access') }}
									</label>
								</div>
								<small class="d-block">{{ __('general.info_limited_access') }}</small>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" name="select_all" value="yes" id="select-all">
									<label class="form-check-label" for="select-all">
										<strong>{{ __('general.select_all') }}</strong>
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="dashboard" @if (isset($permissions) && in_array('dashboard', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck3">
									<label class="form-check-label" for="gridCheck3">
										{{ __('admin.dashboard') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="general_settings" @if (isset($permissions) && in_array('general_settings', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck4">
									<label class="form-check-label" for="gridCheck4">
										{{ __('admin.general_settings') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="announcements" @if (isset($permissions) && in_array('announcements', $permissions)) checked="checked" @endif type="checkbox" id="checkAnnouncements">
									<label class="form-check-label" for="checkAnnouncements">
										{{ __('general.announcements') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="maintenance" @if (isset($permissions) && in_array('maintenance', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck5">
									<label class="form-check-label" for="gridCheck5">
										{{ __('admin.maintenance_mode') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="billing" @if (isset($permissions) && in_array('billing', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck6">
									<label class="form-check-label" for="gridCheck6">
										{{ __('general.billing_information') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="tax" @if (isset($permissions) && in_array('tax', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck8">
									<label class="form-check-label" for="gridCheck8">
										{{ __('general.tax_rates') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="countries_states" @if (isset($permissions) && in_array('countries_states', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck9">
									<label class="form-check-label" for="gridCheck9">
										{{ __('general.countries_states') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="email" @if (isset($permissions) && in_array('email', $permissions)) checked="checked" @endif type="checkbox" id="email_settings">
									<label class="form-check-label" for="email_settings">
										{{ __('admin.email_settings') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="live_streaming" @if (isset($permissions) && in_array('live_streaming', $permissions)) checked="checked" @endif type="checkbox" id="live_streaming">
									<label class="form-check-label" for="live_streaming">
										{{ __('general.live_streaming') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="live_streaming_private_requests" @if (isset($permissions) && in_array('live_streaming_private_requests', $permissions)) checked="checked" @endif type="checkbox" id="live_streaming_private_requests">
									<label class="form-check-label" for="live_streaming_private_requests">
										{{ __('general.live_streaming_private_requests') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="push_notifications" @if (isset($permissions) && in_array('push_notifications', $permissions)) checked="checked" @endif type="checkbox" id="push_notifications">
									<label class="form-check-label" for="push_notifications">
										{{ __('general.push_notifications') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="stories" @if (isset($permissions) && in_array('stories', $permissions)) checked="checked" @endif type="checkbox" id="stories">
									<label class="form-check-label" for="stories">
										{{ __('general.stories') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="comments_replies" @if (isset($permissions) && in_array('comments_replies', $permissions)) checked="checked" @endif type="checkbox" id="comments_replies">
									<label class="form-check-label" for="comments_replies">
										{{ __('general.comments_replies') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="messages" @if (isset($permissions) && in_array('messages', $permissions)) checked="checked" @endif type="checkbox" id="messages">
									<label class="form-check-label" for="messages">
										{{ __('general.messages') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="shop" @if (isset($permissions) && in_array('shop', $permissions)) checked="checked" @endif type="checkbox" id="shop">
									<label class="form-check-label" for="shop">
										{{ __('general.shop') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="products" @if (isset($permissions) && in_array('products', $permissions)) checked="checked" @endif type="checkbox" id="products">
									<label class="form-check-label" for="products">
										{{ __('general.products') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="shop_categories" @if (isset($permissions) && in_array('shop_categories', $permissions)) checked="checked" @endif type="checkbox" id="shop_categories">
									<label class="form-check-label" for="shop_categories">
										{{ __('general.shop_categories') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="storage" @if (isset($permissions) && in_array('storage', $permissions)) checked="checked" @endif type="checkbox" id="storage">
									<label class="form-check-label" for="storage">
										{{ __('admin.storage') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="theme" @if (isset($permissions) && in_array('theme', $permissions)) checked="checked" @endif type="checkbox" id="theme">
									<label class="form-check-label" for="theme">
										{{ __('admin.theme') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="custom_css_js" @if (isset($permissions) && in_array('custom_css_js', $permissions)) checked="checked" @endif type="checkbox" id="checkCustomCssJs">
									<label class="form-check-label" for="checkCustomCssJs">
										{{ __('general.custom_css_js') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="posts" @if (isset($permissions) && in_array('posts', $permissions)) checked="checked" @endif type="checkbox" id="posts">
									<label class="form-check-label" for="posts">
										{{ __('general.posts') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="subscriptions" @if (isset($permissions) && in_array('subscriptions', $permissions)) checked="checked" @endif type="checkbox" id="subscriptions">
									<label class="form-check-label" for="subscriptions">
										{{ __('admin.subscriptions') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="transactions" @if (isset($permissions) && in_array('transactions', $permissions)) checked="checked" @endif type="checkbox" id="transactions">
									<label class="form-check-label" for="transactions">
										{{ __('admin.transactions') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="deposits" @if (isset($permissions) && in_array('deposits', $permissions)) checked="checked" @endif type="checkbox" id="deposits">
									<label class="form-check-label" for="deposits">
										{{ __('general.deposits') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="members" @if (isset($permissions) && in_array('members', $permissions)) checked="checked" @endif type="checkbox" id="members">
									<label class="form-check-label" for="members">
										{{ __('admin.members') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="advertising" @if (isset($permissions) && in_array('advertising', $permissions)) checked="checked" @endif type="checkbox" id="advertising">
									<label class="form-check-label" for="advertising">
										{{ __('general.advertising') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="referrals" @if (isset($permissions) && in_array('referrals', $permissions)) checked="checked" @endif type="checkbox" id="referrals">
									<label class="form-check-label" for="referrals">
										{{ __('general.referrals') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="languages" @if (isset($permissions) && in_array('languages', $permissions)) checked="checked" @endif type="checkbox" id="languages">
									<label class="form-check-label" for="languages">
										{{ __('admin.languages') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="categories" @if (isset($permissions) && in_array('categories', $permissions)) checked="checked" @endif type="checkbox" id="categories">
									<label class="form-check-label" for="categories">
										{{ __('admin.categories') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="reports" @if (isset($permissions) && in_array('reports', $permissions)) checked="checked" @endif type="checkbox" id="reports">
									<label class="form-check-label" for="reports">
										{{ __('admin.reports') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="withdrawals" @if (isset($permissions) && in_array('withdrawals', $permissions)) checked="checked" @endif type="checkbox" id="withdrawals">
									<label class="form-check-label" for="withdrawals">
										{{ __('general.withdrawals') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="verification_requests" @if (isset($permissions) && in_array('verification_requests', $permissions)) checked="checked" @endif type="checkbox" id="verification_requests">
									<label class="form-check-label" for="verification_requests">
										{{ __('admin.verification_requests') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="pages" @if (isset($permissions) && in_array('pages', $permissions)) checked="checked" @endif type="checkbox" id="pages">
									<label class="form-check-label" for="pages">
										{{ __('admin.pages') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="blog" @if (isset($permissions) && in_array('blog', $permissions)) checked="checked" @endif type="checkbox" id="blog">
									<label class="form-check-label" for="blog">
										{{ __('general.blog') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="payment_settings" @if (isset($permissions) && in_array('payment_settings', $permissions)) checked="checked" @endif type="checkbox" id="payment_settings">
									<label class="form-check-label" for="payment_settings">
										{{ __('admin.payment_settings') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="profiles_social" @if (isset($permissions) && in_array('profiles_social', $permissions)) checked="checked" @endif type="checkbox" id="profiles_social">
									<label class="form-check-label" for="profiles_social">
										{{ __('admin.profiles_social') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="social_login" @if (isset($permissions) && in_array('social_login', $permissions)) checked="checked" @endif type="checkbox" id="social_login">
									<label class="form-check-label" for="social_login">
										{{ __('admin.social_login') }}
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="google" @if (isset($permissions) && in_array('google', $permissions)) checked="checked" @endif type="checkbox" id="google">
									<label class="form-check-label" for="google">
										Google
									</label>
								</div>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-sm-10 offset-sm-2">
								<div class="form-check">
									<input class="form-check-input check" name="permissions[]" value="pwa" @if (isset($permissions) && in_array('pwa', $permissions)) checked="checked" @endif type="checkbox" id="pwa">
									<label class="form-check-label" for="pwa">
										PWA
									</label>
								</div>
							</div>
						</div>
					@endif


						<div class="row mb-3">
		          <div class="col-sm-10 offset-sm-2">
		            <button type="submit" class="btn btn-dark mt-3 px-5">{{ __('admin.save') }}</button>
		          </div>
		        </div>

		       </form>

				 </div><!-- card-body -->
 			</div><!-- card  -->
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection

@section('javascript')

<script>

var triggeredByChild = false;

$('.limitedAccess').on('change', function (event) {

	if ($(this).is(":checked")) {
		triggeredByChild = false;
    $('.check').prop('checked', false);
    $('#select-all').prop('checked', false);
	}

});

$('#select-all').on('change', function (event) {

	if ($(this).is(":checked")) {
    $('.check').prop('checked', true);
    $('.limitedAccess').prop('checked', false);
    triggeredByChild = false;
	}
});

$('.check').on('change', function (event) {
	if ($(this).is(":checked")) {
    triggeredByChild = false;
    $('.limitedAccess').prop('checked', false);
	}
});

$('#select-all').on('change', function (event) {

	if (! $(this).is(":checked")) {
    if (! triggeredByChild) {
        $('.check').prop('checked', false);
    }
    triggeredByChild = false;
	}
});
// Removed the checked state from "All" if any checkbox is unchecked
$('.check').on('change', function (event) {
	if (! $(this).is(":checked")) {
    triggeredByChild = true;
    $('#select-all').prop('checked', false);
	}
});
</script>
@endsection
