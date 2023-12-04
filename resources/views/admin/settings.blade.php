@extends('admin.layout')

@section('css')
<link href="{{ asset('public/js/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/js/select2/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.general_settings') }}</span>
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

              @include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

      <form method="POST" action="{{ url('panel/admin/settings') }}" enctype="multipart/form-data">
        @csrf

       <div class="row mb-3">
         <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.name_site') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->title }}" name="title" class="form-control">
         </div>
       </div><!-- end row -->

			 <div class="row mb-3">
         <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.email_admin') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->email_admin }}" name="email_admin" class="form-control">
         </div>
       </div><!-- end row -->

       <div class="row mb-3">
         <label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.link_terms') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->link_terms }}" name="link_terms" class="form-control">
         </div>
       </div><!-- end row -->

       <div class="row mb-3">
         <label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.link_privacy') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->link_privacy }}" name="link_privacy" class="form-control">
           <small class="d-block"></small>
         </div>
       </div><!-- end row -->

       <div class="row mb-3">
         <label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.link_cookies') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->link_cookies }}" name="link_cookies" class="form-control">
         </div>
       </div><!-- end row -->

			 <div class="row mb-3">
				 <label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.date_format') }}</label>
				 <div class="col-sm-10">
					 <select name="date_format" class="form-select">
						 <option @if( $settings->date_format == 'M d, Y' ) selected="selected" @endif value="M d, Y"><?php echo date('M d, Y'); ?></option>
							 <option @if( $settings->date_format == 'd M, Y' ) selected="selected" @endif value="d M, Y"><?php echo date('d M, Y'); ?></option>
						 <option @if( $settings->date_format == 'Y-m-d' ) selected="selected" @endif value="Y-m-d"><?php echo date('Y-m-d'); ?></option>
							 <option @if( $settings->date_format == 'm/d/Y' ) selected="selected" @endif  value="m/d/Y"><?php echo date('m/d/Y'); ?></option>
								 <option @if( $settings->date_format == 'd/m/Y' ) selected="selected" @endif  value="d/m/Y"><?php echo date('d/m/Y'); ?></option>
						 </select>
				 </div>
			 </div>

			 <div class="row mb-3">
				 <label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.genders') }}</label>
				 <div class="col-sm-10">
					 <select name="genders[]" multiple class="form-select gendersSelect">
						 <option @if (in_array('male', $genders)) selected="selected" @endif value="male">{{ __('general.male') }}</option>
						 <option @if (in_array('female', $genders)) selected="selected" @endif value="female">{{ __('general.female') }}</option>
						 <option @if (in_array('gay', $genders)) selected="selected" @endif value="gay">{{ __('general.gay') }}</option>
						 <option @if (in_array('lesbian', $genders)) selected="selected" @endif value="lesbian">{{ __('general.lesbian') }}</option>
						 <option @if (in_array('bisexual', $genders)) selected="selected" @endif value="bisexual">{{ __('general.bisexual') }}</option>
						 <option @if (in_array('transgender', $genders)) selected="selected" @endif value="transgender">{{ __('general.transgender') }}</option>
						 <option @if (in_array('metrosexual', $genders)) selected="selected" @endif value="metrosexual">{{ __('general.metrosexual') }}</option>
						 <option @if (in_array('no_binary', $genders)) selected="selected" @endif value="no_binary">{{ __('general.no_binary') }}</option>
						 <option @if (in_array('couple', $genders)) selected="selected" @endif value="couple">{{ __('general.couple') }}</option>
					</select>
				 </div>
			 </div>

			 <div class="row mb-3">
				 <label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.default_language') }}</label>
				 <div class="col-sm-10">
					 <select name="default_language" class="form-select">
						 @foreach (Languages::orderBy('name')->get() as $language)
 							<option @if ($language->abbreviation == config('app.fallback_locale')) selected="selected" @endif value="{{$language->abbreviation}}">{{ $language->name }}</option>
 						@endforeach
					</select>
					<small class="d-block">{{ __('general.default_language_info') }}</small>
				 </div>
			 </div>

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.show_errors') }}</legend>
         <div class="col-sm-10">
           <div class="form-check">
             <input class="form-check-input" type="radio" name="app_debug" id="radio1" @if (config('app.debug') == true) checked="checked" @endif value="true">
             <label class="form-check-label" for="radio1">
               On <small class="text-danger"><i class="bi-exclamation-triangle-fill mx-1"></i> <strong>{{ __('general.info_show_errors') }}</strong></small>
             </label>
           </div>
           <div class="form-check">
             <input class="form-check-input" type="radio" name="app_debug" id="radio2" @if (config('app.debug') == false) checked="checked" @endif value="false">
             <label class="form-check-label" for="radio2">
               Off
             </label>
           </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.who_can_see_content') }}</legend>
         <div class="col-sm-10">
           <div class="form-check">
             <input class="form-check-input" type="radio" name="who_can_see_content" id="radioWho1" @if ($settings->who_can_see_content == 'all') checked="checked" @endif value="all">
             <label class="form-check-label" for="radioWho1">
               {{ __('general.all') }}
             </label>
           </div>
           <div class="form-check">
             <input class="form-check-input" type="radio" name="who_can_see_content" id="radioWho2" @if ($settings->who_can_see_content == 'users') checked="checked" @endif value="users">
             <label class="form-check-label" for="radioWho2">
               {{ __('admin.only_users') }}
             </label>
           </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('admin.email_verification') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="email_verification" @if ($settings->email_verification) checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('admin.account_verification') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="account_verification" @if ($settings->account_verification) checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">Captcha</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="captcha" @if ($settings->captcha == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.captcha_contact') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="captcha_contact" @if ($settings->captcha_contact == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.disable_tips') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="disable_tips" @if ($settings->disable_tips == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('admin.new_registrations') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="registration_active" @if ($settings->registration_active) checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.show_counter') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="show_counter" @if ($settings->show_counter == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.disable_registration_login_email') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="disable_login_register_email" @if ($settings->disable_login_register_email) checked="checked" @endif value="1" role="switch">
          </div>
          <small class="d-block w-100 float-start mt-2">
            <i class="bi-info-circle me-1"></i>
            {{ __('auth.login') }} ({{ __('admin.role_admin') }}) <strong>{{ url('login/admin') }}</strong>
          </small>
         </div>
        
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.show_widget_creators') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="widget_creators_featured" @if ($settings->widget_creators_featured == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.show_earnings_simulator') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="earnings_simulator" @if ($settings->earnings_simulator == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.receive_verification_requests') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="requests_verify_account" @if ($settings->requests_verify_account == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.hide_admin_profile') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="hide_admin_profile" @if ($settings->hide_admin_profile == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.watermark_on_images') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="watermark" @if ($settings->watermark == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.show_alert_adult') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="alert_adult" @if ($settings->alert_adult == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.users_can_edit_post') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="users_can_edit_post" @if ($settings->users_can_edit_post == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.disable_banner_cookies') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="disable_banner_cookies" @if ($settings->disable_banner_cookies == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.referral_system') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="referral_system" @if ($settings->referral_system == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.disable_contact') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="disable_contact" @if ($settings->disable_contact) checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.disable_new_post_email_notification') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="disable_new_post_notification" @if ($settings->disable_new_post_notification) checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.disable_creators_search') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="disable_search_creators" @if ($settings->disable_search_creators) checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.browse_creators_by_gender_age') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="search_creators_genders" @if ($settings->search_creators_genders) checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.allow_qr_code_generate') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="generate_qr_code" @if ($settings->generate_qr_code) checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.auto_follow_admin') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="autofollow_admin" @if ($settings->autofollow_admin) checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-4">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.allow_zip_files') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="allow_zip_files" @if ($settings->allow_zip_files) checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-4">
        <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.zip_verification_creator') }}</legend>
        <div class="col-sm-10">
          <div class="form-check form-switch form-switch-md">
           <input class="form-check-input" type="checkbox" name="zip_verification_creator" @if ($settings->zip_verification_creator) checked="checked" @endif value="1" role="switch">
         </div>
        </div>
      </fieldset><!-- end row -->

      <fieldset class="row mb-4">
        <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('general.allow_scheduled_posts') }}</legend>
        <div class="col-sm-10">
          <div class="form-check form-switch form-switch-md">
           <input class="form-check-input" type="checkbox" name="allow_scheduled_posts" @if ($settings->allow_scheduled_posts) checked="checked" @endif value="1" role="switch">
         </div>
        </div>
      </fieldset><!-- end row -->

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
  $('.gendersSelect').select2({
  tags: false,
  tokenSeparators: [','],
  placeholder: '{{ __('general.genders') }}',
});
</script>
@endsection
