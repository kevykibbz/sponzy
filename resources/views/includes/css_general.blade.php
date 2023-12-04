<link href="{{ asset('public/css/core.min.css') }}?v={{$settings->version}}" rel="stylesheet">
<link href="{{ asset('public/css/feather.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/bootstrap-icons.css') }}?v={{$settings->version}}" rel="stylesheet">
<link href="{{ asset('public/css/icomoon.css') }}" rel="stylesheet">

@if (auth()->check() && auth()->user()->dark_mode == 'on')
<link href="{{ asset('public/css/bootstrap-dark.min.css') }}" rel="stylesheet">
@else
<link href="{{ asset('public/css/bootstrap.min.css') }}" rel="stylesheet">
@endif

<link href="{{ asset('public/css/styles.css') }}?v={{$settings->version}}" rel="stylesheet">
<link href="{{ asset('public/js/plyr/plyr.css')}}?v={{$settings->version}}" rel="stylesheet" type="text/css" />

@auth
<link href="{{ asset('public/js/fileuploader/font/font-fileuploader.css')}}" media="all" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/js/fileuploader/jquery.fileuploader.min.css')}}" media="all" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/js/fileuploader/jquery.fileuploader-theme-thumbnails.css')}}" media="all" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/js/fileuploader/jquery.fileuploader-theme-dragdrop.css')}}" media="all" rel="stylesheet" type="text/css" />

<link href="{{ asset('public/js/jquery-ui/jquery-ui.min.css')}}" media="all" rel="stylesheet" type="text/css" />

@if (request()->path() == '/' && $settings->story_status)
<link href="{{ asset('public/js/story/zuck.min.css')}}?v={{$settings->version}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/js/story/snapssenger.css')}}?v={{$settings->version}}" rel="stylesheet" type="text/css" />
@endif

@if (request()->path() == '/' && $settings->story_status && $fonts || request()->is('create/story/text') && $settings->story_status && $fonts)
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family={{$fonts}}">
@endif

@if ($settings->push_notification_status)
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
  const myDeviceKeysId = {!! json_encode(auth()->user()->oneSignalDevices->pluck('player_id')->all()) !!};

  var OneSignal = window.OneSignal || [];
    var initConfig = {
      appId: "{{ $settings->onesignal_appid }}",
      autoResubscribe: true,
      safari_web_id: "web.onesignal.auto.0c986762-0fae-40b1-a5f6-ee95f7275a97",
      notifyButton: {
        enable: false,
      },
      welcomeNotification: {
        message: "{{ __('general.notifications_activated_successfully') }}"
      },
      persistNotification: true,

      promptOptions: {
      slidedown: {
        prompts: [
          {
            type: "push", // current types are "push" & "category"
            autoPrompt: true,
            text: {
              /* limited to 90 characters */
              actionMessage: "{{ __('general.push_notification_title', ['app' => $settings->title]) }}",
              /* acceptButton limited to 15 characters */
              acceptButton: "{{ __('general.activate') }}",
              /* cancelButton limited to 15 characters */
              cancelButton: "{{ __('general.maybe_later') }}"
            },
            delay: {
              pageViews: 1,
              timeDelay: 20
            }
          }
        ]
      }
    }
    // END promptOptions,
    };
 

  OneSignal.push(function () {
        OneSignal.SERVICE_WORKER_PARAM = { scope: '/public/js/' };
        OneSignal.SERVICE_WORKER_PATH = 'public/js/OneSignalSDKWorker.js'
        OneSignal.SERVICE_WORKER_UPDATER_PATH = 'public/js/OneSignalSDKWorker.js'
        OneSignal.init(initConfig);

        OneSignal.showSlidedownPrompt();
    });

  OneSignal.push(function() {

    // Get User Id
    OneSignal.getUserId(function(userId) {
      pushUserId = userId;

      if (pushUserId !== null) {
        var isRegisterDevice = $.inArray(pushUserId, myDeviceKeysId);
        if (isRegisterDevice === -1) {
          $.post("{{ url('api/device/register') }}", {player_id: pushUserId, user_id: {{ auth()->id() }} });
        }
      }
    });

    OneSignal.isPushNotificationsEnabled(function(isEnabled) {
    if (isEnabled)
      console.log("Push notifications are enabled!");
    else
      console.log("Push notifications are not enabled yet.");
  });
    
  // Subscription Change
	OneSignal.on("subscriptionChange", 
  function(isSubscribed) {

    OneSignal.push(function() {
        OneSignal.getUserId(function(userId) {
          pushUserId = userId;

        if (isSubscribed == false) {
        $.get("{{ url('api/device/delete') }}", {player_id: pushUserId});
      } else {
            $.post("{{ url('api/device/register') }}", {player_id: pushUserId, user_id: {{ auth()->id() }} });
          }});

        });
      });
});
</script>
@endif

@endauth

<script type="text/javascript">
// Global variables
  var URL_BASE = "{{ url('/') }}";
  var lang = '{{ auth()->user()->language ?? session('locale') }}';
  var _title = '@section("title")@show {{e($settings->title).' - '.__('seo.slogan')}}';
  var session_status = "{{ auth()->check() ? 'on' : 'off' }}";
  var ReadMore = "{{__('general.view_all')}}";
  var copiedSuccess = "{{__('general.copied_success')}}";
  var copied = "{{__('general.copied')}}";
  var copy_link = "{{__('general.copy_link')}}";
  var loading = "{{__('general.loading')}}";
  var please_wait = "{{__('general.please_wait')}}";
  var error_occurred = "{{__('general.error')}}";
  var error_oops = "{{ __('general.error_oops') }}";
  var error_reload_page = "{{ __('general.error_reload_page') }}";
  var ok = "{{__('users.ok')}}";
  var user_count_carousel = @if (auth()->guest() && request()->path() == '/' && config('settings.home_style') == 0) {{$users->count()}}@else 0 @endif;
  var no_results_found = "{{__('general.no_results_found')}}";
  var no_results = "{{__('general.no_results')}}";
  var no_one_seen_story_yet =  "{{__('general.no_one_seen_story_yet')}}";
  var is_profile = {{ request()->route()->named('profile') ? 'true' : 'false' }};
  var error_scrollelement = false;
  var captcha = {{ $settings->captcha == 'on' ? 'true' : 'false' }};
  var alert_adult = {{ $settings->alert_adult == 'on' ? 'true' : 'false' }};
  var error_internet_disconnected = "{{ __('general.error_internet_disconnected') }}";
  var announcement_cookie = "{{$settings->announcement_cookie}}";
  var resend_code = "{{ __('general.resend_code') }}";
  var resending_code = "{{ __('general.resending_code') }}";
  var query = "{{strlen(request()->get('q')) > 2 ? trim(str_replace('#', '%23', request()->get('q'))) : false}}";
  var sortBy = "{{ in_array(request()->get('sort'), ['oldest', 'unlockable', 'free']) ? trim(request()->get('sort')) : false}}";
  var login_continue = "{{ __('general.login_continue') }}";
  var register = "{{ __('auth.sign_up') }}";
  var login_with = "{{ __('auth.login_with') }}";
  var sign_up_with = "{{ __('auth.sign_up_with') }}";
  var currentPage = "{!! url()->full() !!}";
  var requestGender = {{ request()->get('gender') ? 'true' : 'false' }};
@auth
  var is_bookmarks = {{ request()->is('my/bookmarks') ? 'true' : 'false' }};
  var is_likes = {{ request()->is('my/likes') ? 'true' : 'false' }};
  var is_purchases = {{ request()->is('my/purchases') ? 'true' : 'false' }};
  var isMessageChat = {{ request()->is('messages/*') ? 'true' : 'false' }};
  var delete_confirm = "{{__('general.delete_confirm')}}";
  var confirm_delete_comment = "{{__('general.confirm_delete_comment')}}";
  var confirm_delete_update = "{{__('general.confirm_delete_update')}}";
  var yes_confirm = "{{__('general.yes_confirm')}}";
  var cancel_confirm = "{{__('general.cancel_confirm')}}";
  var formats_available = "{{__('general.formats_available')}}";
  var formats_available_images = "{{__('general.formats_available_images')}}";
  var formats_available_verification = "{{__('general.formats_available_verification_form_w9', ['formats' => 'JPG, PNG, GIF'])}}";
  var file_size_allowed = {{$settings->file_size_allowed * 1024}};
  var max_size_id = "{{__('general.max_size_id').' '.Helper::formatBytes($settings->file_size_allowed * 1024)}}";
  var max_size_id_lang = "{{__('general.max_size_id').' '.Helper::formatBytes($settings->file_size_allowed_verify_account * 1024)}}";
  var maxSizeInMb = "{{ floor($settings->file_size_allowed / 1024)}}";
  var file_size_allowed_verify_account = {{$settings->file_size_allowed_verify_account * 1024}};
  var error_width_min = "{{__('general.width_min',['data' => 20])}}";
  var story_length = {{$settings->story_length}};
  var payment_card_error = "{{ __('general.payment_card_error') }}";
  var confirm_delete_message = "{{__('general.confirm_delete_message')}}";
  var confirm_delete_conversation = "{{__('general.confirm_delete_conversation')}}";
  var confirm_cancel_subscription = "{!!__('general.confirm_cancel_subscription')!!}";
  var yes_confirm_cancel = "{{__('general.yes_confirm_cancel')}}";
  var confirm_delete_notifications = "{{__('general.confirm_delete_notifications')}}";
  var confirm_delete_withdrawal = "{{__('general.confirm_delete_withdrawal')}}";
  var change_cover = "{{__('general.change_cover')}}";
  var pin_to_your_profile = "{{__('general.pin_to_your_profile')}}";
  var unpin_from_profile = "{{__('general.unpin_from_profile')}}";
  var post_pinned_success = "{{__('general.post_pinned_success')}}";
  var post_unpinned_success = "{{__('general.post_unpinned_success')}}";
  var stripeKey = "{{ PaymentGateways::where('id', 2)->where('enabled', '1')->whereSubscription('yes')->first() ? env('STRIPE_KEY') : false }}";
  var stripeKeyWallet = "{{ PaymentGateways::where('id', 2)->where('enabled', '1')->first() ? env('STRIPE_KEY') : false }}";
  var thanks = "{{ __('general.thanks') }}";
  var tip_sent_success = "{{ __('general.tip_sent_success') }}";
  var error_payment_stripe_3d = "{{ __('general.error_payment_stripe_3d') }}";
  var colorStripe = {!! auth()->user()->dark_mode == 'on' ? "'#dcdcdc'" : "'#32325d'" !!};
  var full_name_user = '{{ auth()->user()->name }}';
  var color_default = '{{ $settings->color_default }}';
  var formats_available_upload_file = "{{__('general.formats_available_upload_file')}}";
  var cancel_subscription = "{{__('general.unsubscribe')}}";
  var your_subscribed = "{{__('general.your_subscribed')}}";
  var subscription_expire = "{{__('general.subscription_expire')}}";
  var formats_available_verification_form_w9 = "{{__('general.formats_available_verification_form_w9', ['formats' => 'PDF'])}}";
  var payment_was_successful = "{{__('general.payment_was_successful')}}";
  var public_post = "{{__('general.public')}}";
  var locked_post = "{{__('users.content_locked')}}";
  var maximum_files_post = {{$settings->maximum_files_post}};
  var maximum_files_msg = {{$settings->maximum_files_msg}};
  var great = "{{__('general.great')}}";
  var msg_success_sent_all_subscribers = "{{__('general.msg_success_sent_all_subscribers')}}";
  var is_explore = {{ request()->is('explore') ? 'true' : 'false' }};
  var video_on_way = "{{__('general.video_on_way')}}";
  var story_on_way = "{{__('general.story_on_way')}}";
  var video_processed_info = "{{__('general.video_processed_info')}}";
  var confirm_end_live = "{{__('general.confirm_end_live')}}";
  var yes_confirm_end_live = "{{__('general.yes_confirm_end_live')}}";
  var liveMode = false;
  var min_width_height_image = {{ $settings->min_width_height_image }};
  var min_width_image_error = '{{ __('general.width_min', ['data' => $settings->min_width_height_image]) }}';
  var decimalZero = {{ $settings->currency_code == 'JPY' ? 0 : 2 }};
  var confirm_exit_live = "{{__('general.confirm_exit_live')}}";
  var yes_confirm_exit_live = "{{__('general.yes_confirm_exit_live')}}";
  var purchase_processed_shortly = "{{__('general.purchase_processed_shortly')}}";
  var confirm_reject_order = "{{ __('general.confirm_reject_order') }}";
  var reject_order = "{{ __('general.reject_order') }}";
  var action_cannot_reversed = "{{ __('general.action_cannot_reversed') }}";
  var mark_as_delivered = "{{ __('general.mark_as_delivered') }}";
  var confirm_restrict = "{{ __('general.confirm_restrict') }}";
  var restrict = "{{ __('general.restrict') }}";
  var remove_restriction = "{{ __('general.remove_restriction') }}";
  var show_only_free = "{{ __('general.show_only_free') }}";
  var show_all = "{{ __('general.show_all') }}";
  @if ($settings->video_encoding == 'off')
  var extensionsPostMessage = ['png','jpeg','jpg','gif','ief','video/mp4','audio/x-matroska','audio/mpeg'];
  var extensionsStories = ['png','jpeg','jpg','gif','ief','video/mp4','audio/x-matroska','audio/mpeg'];
  @else
  var extensionsPostMessage = ['png','jpeg','jpg','gif','ief','video/mp4','video/quicktime','video/3gpp','video/mpeg','video/x-matroska','video/x-ms-wmv','video/vnd.avi','video/avi','video/x-flv','audio/x-matroska','audio/mpeg'];
  var extensionsStories = ['png','jpeg','jpg','gif','ief','video/mp4','video/quicktime','video/3gpp','video/mpeg','video/x-matroska','video/x-ms-wmv','video/vnd.avi','video/avi','video/x-flv'];
  @endif
  var errorStoryMaxVideosLength = "{{ __('general.error_story_max_videos_length', ['length' => $settings->story_max_videos_length]) }}";
  var storyMaxVideosLength = {{ $settings->story_max_videos_length }};
  var confirm_delete_image_cover = "{{ __('general.confirm_delete_image_cover') }}";
  var at = "{{ __('general.at') }}";
  var publish = "{{ __('general.publish') }}";
  var schedule = "{{ __('general.schedule') }}";
  var reject_request = "{{ __('general.reject_request') }}";
  var advertising = {{ $advertising->count() ? 'true' : 'false'  }};
@endauth
</script>

<style type="text/css">

@if ($settings->custom_css)
  {!! $settings->custom_css !!}
@endif

@if (auth()->check() && auth()->user()->dark_mode == 'on')
  body,
  .font-montserrat a,
  .font-montserrat a:hover { color: #FFF; }
  body a:not(.link-footer, .ico-social, .pulse-btn, .text-muted),
  body a:hover:not(.pulse-btn, .text-muted),
  .btn-link,
  .spinner-border.text-primary,
  .card-title.text-primary { color: #FFF !important; }
  .text-primary { color: #FFF !important; }
  .search-bar, .search-bar:focus {border: none !important; background-color: #474747 !important;}
  .text-notify {color: #8898aa;}
  .dd-menu-user:before { color: #222222; }
  .avatar-wrap, .card-avatar {background-color: #222;}
  .dropdown-item.balance:hover {background: #222 !important;color: #ffffff;}
  .blocked {background-color: transparent;}
  .btn-google, .btn-google:hover, .btn-google:active, .btn-google:focus {
  background: transparent;
  border-color: #ccc;
  color: #fff;
}
.line-replies { border-color: #fff !important;}
.wrapper-media-music {border-color: #222 !important;}
.dropdown-divider {
  border-top: 1px solid #2e2e2e;
}
.border {border-color: #222 !important;}
.btn-category:hover,
.active-category {
    border-color: #fff !important;
}
.custom-switch-pro .custom-control-input:focus ~ .custom-control-label::before {
  border-color: #adb5bd !important;
  box-shadow: 0 1px 3px rgb(50 50 93 / 15%), 0 1px 0 rgb(0 0 0 / 2%);
}

.img-user,
.avatar-modal,
.img-user-small { border-color: #303030; }
.actionDeleteNotify,
.actionDeleteNotify:hover { color: #FFF; }

.nav-profile a, .nav-profile li.active a:hover, .nav-profile li.active a:active, .nav-profile li.active a:focus,
.sm-btn-size, .verified {
  color: #fff;
}
.text-featured {color: #fff !important;}
.input-group-text {
  border-color: #222;
  background-color: #303030;
}
.datepicker.dropdown-menu {background-color: #303030 !important;}
.datepicker-dropdown.datepicker-orient-bottom:after {border-top: 6px solid #303030 !important;}
.datepicker-dropdown:after {border-bottom: 6px solid #303030 !important;}

.form-control:focus,
.custom-select:focus {
  border-color: #222 !important;

}
.custom-select {
  background: #303030 url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23a5a5a5' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e")
  no-repeat right .75rem center/8px 10px;
  color: #fff;
}
.navbar-toggler,
.sweet-alert h2,
.sweet-alert p,
.ico-no-result {
  color: #FFF;
}
.btn-notify,
.btn-notify:hover,
.btn-notify:focus,
.btn-notify:active {
  color: #FFF;
}
.sweet-alert { background-color: #2f2f2f;}
.content-locked {background: #444444;}

@media (max-width: 991px) {
.navbar .navbar-collapse {
  background: #222;
}
.navbar .navbar-collapse .navbar-nav .nav-item .nav-link:not(.btn) {
  color: #ffffff;
}

.navbar-collapse .navbar-toggler span {
  background: #fff;
}
}
.link-scroll a.nav-link:not(.btn) {
  color: #969696;
}
.btn-upload:hover,
.btn-post:hover,
.icons-live:hover {
background-color: #222222 !important;
}
.btn-active-hover {
background-color: #222222 !important;
}
.unread-chat {
  background-color: #444 !important;
}
.modal-danger .modal-content,
.wrapper-msg-inbox {
background-color: #303030;
}
h3, .h3 {font-size: 1.75rem;}
h2, .h2 {font-size: 2rem;}
h4, .h4 {font-size: 1.5rem;}
h5, .h5 {font-size: 1.25rem;}

@keyframes animate {
from {transition:none;}
to {background-color:#383838;transition: all 0.3s ease-out;}
}

.item-loading::before {
  background-color: #6b6b6b;
  content: ' ';
  display: block;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  animation-name: animate;
  animation-duration: 2s;
  animation-iteration-count: infinite;
  animation-timing-function: linear;
  background-image: none;
  border-radius: 0;
}
.loading-avatar::before {
border-radius: 50%;
}
.loading-avatar {background-color: inherit;}
.plyr--audio .plyr__controls {background: #212121; color: #ffffff;}
.readmore-js-collapsed:after {background-image: linear-gradient(hsla(0,0%,100%,0),#303030 95%);}
.sweet-alert .sa-icon.sa-success .sa-fix {background-color: #2f2f2f;}
.sweet-alert .sa-icon.sa-success::after, .sweet-alert .sa-icon.sa-success::before {background: #2f2f2f;}
.page-item.disabled .page-link, .page-link {background-color: #222222;}
.nav-pills .nav-link {background-color: #303030; color: #ffffff;}
a.social-share i {color: #dedede!important;}

.StripeElement {background-color: #222222; border: 1px solid #222222;}
.StripeElement--focus {border-color: #525252;}
.bg-autocomplete {background-color: #222;}

@endif

.bg-gradient {
  background: url('{{url('public/img', $settings->bg_gradient)}}');
  background-size: cover;
}

a.social-share i {color: #797979; font-size: 32px;}
a:hover.social-share { text-decoration: none; }
.btn-whatsapp {color: #50b154 !important;}
.close-inherit {color: inherit !important;}
.btn-twitter { background-color: #000;  color:#fff !important;}

@media (max-width: 991px) {
  .navbar-user-mobile {
    font-size: 20px;
  }
}
.or {
display:flex;
justify-content:center;
align-items: center;
color:grey;
}

.or:after,
.or:before {
  content: "";
  display: block;
  background: #adb5bd;
  width: 50%;
  height:1px;
  margin: 0 10px;
}

.icon-navbar { font-size: 22px; vertical-align: bottom; @if (auth()->check() && auth()->user()->dark_mode == 'on') color: #FFF !important; @endif }

{{ $settings->button_style == 'rounded' ? '.btn, .sa-button-container button {border-radius: 50rem!important;}' : null }}

@if (auth()->check() && auth()->user()->dark_mode == 'off' || auth()->guest())
.navbar_background_color { background-color: {{ $settings->navbar_background_color }} !important; }
.link-scroll a.nav-link:not(.btn), .navbar-toggler:not(.text-white) { color: {{ $settings->navbar_text_color }} !important; }

@media (max-width: 991px) {
  .navbar .navbar-collapse, .dd-menu-user, .dropdown-item.balance:hover
  { background-color: {{ $settings->navbar_background_color }} !important; color: {{ $settings->navbar_text_color }} !important; }
  .navbar-collapse .navbar-toggler span { background-color: {{ $settings->navbar_text_color }} !important; }
  .dropdown-divider { border-top-color: {{ $settings->navbar_background_color }} !important;}
  }

.footer_background_color { background-color: {{ $settings->footer_background_color }} !important; }
.footer_text_color, .link-footer:not(.footer-tiny) { color: {{ $settings->footer_text_color }}; }
@endif

@if ($settings->color_default <> '')

:root {
  --plyr-color-main: {{$settings->color_default}};
}
:root {
  --swiper-theme-color: {{$settings->color_default}};
}
:root {
  --color-media-wrapper: @if (auth()->check() && auth()->user()->dark_mode == 'off') #f1f1f1 @else #454545 @endif;
  --color-pulse-media-wrapper: @if (auth()->check() && auth()->user()->dark_mode == 'off') #f8f8f8 @else #373737 @endif;
}

.plyr--video.plyr--stopped .plyr__controls {display: none;}

@media (min-width: 767px) {
  .login-btn { padding-top: 12px !important;}
}

::selection{ background-color: {{$settings->color_default}}; color: white; }
::moz-selection{ background-color: {{$settings->color_default}}; color: white; }
::webkit-selection{ background-color: {{$settings->color_default}}; color: white; }

body a,
a:hover,
a:focus,
a.page-link,
.btn-outline-primary,
.btn-link {
    color: {{$settings->color_default}};
}
.text-primary {
    color: {{$settings->color_default}}!important;
}

a.text-primary.btnBookmark:hover, a.text-primary.btnBookmark:focus {
  color: {{$settings->color_default}}!important;
}
.dropdown-menu {
  font-size: 16px !important;
  line-height: normal !important;
  padding: .5rem !important;
}
.dropdown-item {
  border-radius: 5px;
}
.btn-primary:not(:disabled):not(.disabled).active,
.btn-primary:not(:disabled):not(.disabled):active,
.show>.btn-primary.dropdown-toggle,
.btn-primary:hover,
.btn-primary:focus,
.btn-primary:active,
.btn-primary,
.btn-primary.disabled,
.btn-primary:disabled,
.custom-checkbox .custom-control-input:checked ~ .custom-control-label::before,
.page-item.active .page-link,
.page-link:hover,
.owl-theme .owl-dots .owl-dot span,
.owl-theme .owl-dots .owl-dot.active span,
.owl-theme .owl-dots .owl-dot:hover span
 {
    background-color: {{$settings->color_default}};
    border-color: {{$settings->color_default}};
}
.bg-primary,
.dropdown-item:focus,
.dropdown-item:hover,
.dropdown-item.active,
.dropdown-item:active,
.tooltip-inner,
.custom-range::-webkit-slider-thumb,
.custom-range::-webkit-slider-thumb:active {
    background-color: {{$settings->color_default}}!important;
}

.custom-range::-moz-range-thumb:active,
.custom-range::-ms-thumb:active {
  background-color: {{$settings->color_default}}!important;
}

.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before,
.custom-control-input:focus:not(:checked) ~ .custom-control-label::before,
.btn-outline-primary {
  border-color: {{$settings->color_default}};
}
.custom-control-input:not(:disabled):active~.custom-control-label::before,
.custom-control-input:checked~.custom-control-label::before,
.btn-outline-primary:hover,
.btn-outline-primary:focus,
.btn-outline-primary:not(:disabled):not(.disabled):active,
.list-group-item.active,
.btn-outline-primary:not(:disabled):not(.disabled).active,
.badge-primary {
    color: #fff;
    background-color: {{$settings->color_default}};
    border-color: {{$settings->color_default}};
}
.popover .arrow::before { border-top-color: rgba(0,0,0,.35) !important; }
.bs-tooltip-bottom .arrow::before {
  border-bottom-color: {{$settings->color_default}}!important;
}
.arrow::before {
  border-top-color: {{$settings->color_default}}!important;
}
.nav-profile li.active {
  border-bottom: 3px solid {{ auth()->check() && auth()->user()->dark_mode == 'on' ? '#fff' : $settings->color_default }} !important;
}
.button-avatar-upload {left: 0;}
input[type='file'] {overflow: hidden;}
.badge-free { top: 10px; right: 10px; background: rgb(0 0 0 / 65%); color: #fff; font-size: 12px;}

.btn-facebook, .btn-twitter, .btn-google {position: relative;}
.btn-facebook i  {
  position: absolute;
    left: 10px;
    bottom: 14px;
    width: 36px;
}

.btn-twitter i {
    position: absolute;
    left: 10px;
    bottom: 9px !important;
    bottom: 13px;
    width: 36px;
}

.btn-google img  {
  position: absolute;
    left: 18px;
    bottom: 12px;
    width: 18px;
}

.button-search {top: 0;}

@media (min-width: 768px) {
  .pace {display:none !important;}
}

@media (min-width: 992px) {
  .menuMobile {display:none !important;}
}

.pace{-webkit-pointer-events:none;pointer-events:none;-webkit-user-select:none;-moz-user-select:none;user-select:none}
.pace-inactive{display:none}
.pace .pace-progress{background:{{$settings->color_default}};position:fixed;z-index:2000;top:0;right:100%;width:100%;height:3px}

.menuMobile {
  position: fixed;
  bottom: 0;
  left: 0;
  z-index: 1040;
  @if (auth()->check() && auth()->user()->dark_mode == 'off')
    background-color: {{ $settings->navbar_background_color }} !important;
  @endif

}
.btn-mobile {border-radius: 25px;
  @if (auth()->check() && auth()->user()->dark_mode == 'off')
  color: {{$settings->navbar_text_color}} !important;
  @endif
}
.btn-mobile:hover {
    background-color: rgb(243 243 243 / 26%);
    text-decoration: none !important;
    -webkit-transition: all 200ms linear;
    -moz-transition: all 200ms linear;
    -o-transition: all 200ms linear;
    -ms-transition: all 200ms linear;
    transition: all 200ms linear;
}

@media (max-width: 991px) {
  .navbar .navbar-collapse {
    width: 300px !important;
    box-shadow: 5px 0px 8px #000;
  }

  .card-profile {width: 100% !important; text-align: center;}
  .section-msg {padding: 0 !important;}
  .text-center-sm { text-align: center !important;}

  #navbarUserHome { position: initial !important;}

  .notify {
    top: 5px !important;
    right: 5px !important;
  }

  @auth
  .margin-auto {
      margin: auto!important;
  }
  @endauth
}
.sidebar-overlay #mobileMenuOverlay {
    position: fixed;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 101;
    -webkit-transition: all .9s;
    -moz-transition: all .8s;
    -ms-transition: all .8s;
    -o-transition: all .8s;
    transition: all .8s;
    transition-delay: .35s;
    left: 0;
}
.noti_notifications, .noti_msg {display: none;}

.link-menu-mobile {border-radius: 6px;}
.link-menu-mobile:hover:not(.balance) {
  background: rgb(242 242 242 / 40%);
}
a.link-border {text-decoration: none;}
@media (max-width: 479px) {
  .card-border-0 {
    border-right: 0 !important;
    border-left: 0 !important;
    border-radius: 0 !important;
  }
  .card.rounded-large {
    border-radius: 0 !important;
  }
  .wrap-post {padding: 0 !important;}
}

@media (min-width: 576px) {
  .modal-login {
      max-width: 415px;
  }
}
.toggleComments { cursor: pointer;}
.blocked {left: 0; top: 0;}
.card-settings > .list-group-flush>.list-group-item {border-width: 0 0 0px !important;}
.btn-active-hover {background-color: #f3f3f3;}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.container-msg {position: relative; overflow: auto; overflow-x: hidden; flex: 2; -webkit-box-flex: 2;}
.section-msg {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-flow: column;
    flex-flow: column;
    min-width: 0;
    width: 100%;
}
.container-media-msg {max-width: 100%;max-height: 100%;}
.container-media-img {max-width: 100%;}
.rounded-top-right-0 {border-top-right-radius: 0 !important;}
.rounded-top-left-0{border-top-left-radius: 0 !important;}
.custom-rounded {border-radius: 10px;}
.card-profile {width: 75%;}
.fancybox-button {background: none !important;}
.modal-open .modal,
.sweet-overlay {
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
}
.pulse-btn:active > i,
.pulse-btn:active > svg {
  animation-duration: 600ms;
  animation-name: pulse-animation;
  animation-timing-function: ease-in;
  -webkit-transition: all .1s;
    transition: all .1s;
}
@keyframes pulse-animation{
  0% { transform:scale(1.2); }
  100% { transform:scale(0); }
}
.post-image {
  max-height: 600px;
  object-fit: cover;
  object-position: 100% center;
}
@media (max-width: 600px) {
  .post-image {
      max-height: 90vw;
  }
}
.swiper-container {
  width: 100%;
}
.font-14 {
  font-size: 14px;
}
.card-user-profile {
  border-radius: .50rem!important;
}
.card-user-profile > .card-cover {
  border-top-left-radius: .50rem!important;
  border-top-right-radius: .50rem!important;
}
.btn-arrow-expand[aria-expanded='true'] > i.fa-chevron-down:before {
    transform: rotate(180deg);
    content: "\f077";
}

.btn-menu-expand[aria-expanded='true'] > i.fa-bars:before {
    content: "\f00d";
}
.wrapper-msg-right {
  float: initial;
  margin-right: auto;
  max-width: 500px;
}
.wrapper-msg-left {
  float: initial;
  margin-left: auto;
  max-width: 500px;
}
.post-img-grid {
  position: absolute;
  display: block;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
}
@keyframes placeHolderShimmer {
  0% {
    background-position: -150px 0;
  }
  100% {
    background-position: 150px 0;
  }
}
.media-wrapper {
  background-color: var(--color-media-wrapper);
  animation-name: pulse;
  animation-duration: 2s;
  animation-iteration-count: infinite;
  position: relative;
}

@keyframes pulse {
  0% {
    background-color: var(--color-media-wrapper);
  }
  50% {
    background-color: var(--color-pulse-media-wrapper);
  }
  100 {
    background-color: var(--color-media-wrapper);
  }
}
/* ----- MEDIA GRID 1 ------- */
.media-grid-1 .media-wrapper {
  position: relative;
  padding-top: 60%;
  width: 100%;
  overflow: hidden;
  background-size: cover;
  background-position: center;
  cursor: pointer;
  border-radius: 6px;
  -webkit-border-radius: 6px;
  -o-border-radius: 6px;
  -ms-border-radius: 6px;
  display: block;
}

/* ----- MEDIA GRID 2 ------- */
.media-grid-2 {
  position: relative;
  width: 100%;
  display: flex;
  display: -webkit-flex;
}

.media-grid-2 .media-wrapper {
  position: relative;
  padding-top: 45%;
  width: 50%;
  overflow: hidden;
  background-size: cover;
  background-position: center;
  cursor: pointer;
}
.media-grid-2 .media-wrapper:nth-child(1),
.media-grid-3 .media-wrapper:nth-child(1) {
  border-bottom-left-radius: 6px;
  -webkit-border-bottom-left-radius: 6px;
  -o-border-bottom-left-radius: 6px;
  -ms-border-bottom-left-radius: 6px;
  border-top-left-radius: 6px;
  -webkit-border-top-left-radius: 6px;
  -o-border-top-left-radius: 6px;
  -ms-border-top-left-radius: 6px;
}

.media-grid-2 .media-wrapper:nth-child(2) {
  border-bottom-right-radius: 6px;
  -webkit-border-bottom-right-radius: 6px;
  -o-border-bottom-right-radius: 6px;
  -ms-border-bottom-right-radius: 6px;
  border-top-right-radius: 6px;
  -webkit-border-top-right-radius: 6px;
  -o-border-top-right-radius: 6px;
  -ms-border-top-right-radius: 6px;
}

.media-grid-2 .media-wrapper:nth-child(2) {
  margin-left: 3px;
}
.media-grid-1,
.media-grid-3,
.media-grid-4,
.media-grid-5 {
  position: relative;
  width: 100%;
  display: table;
  overflow: hidden;
}
/* ----- MEDIA GRID 3 ------- */
.media-grid-3 .media-wrapper:nth-child(1) {
  position: relative;
  padding-top: 70.6%;
  width: calc(50% / 1 - 0px);
  overflow: hidden;
  background-size: cover;
  background-position: center;
  cursor: pointer;
  float: left;
}
.media-grid-3 .media-wrapper:nth-child(2) {
  position: relative;
  padding-top: 35%;
  width: calc(100% / 2 - 3px);
  overflow: hidden;
  background-size: cover;
  background-position: center;
  cursor: pointer;
  float: left;
  margin-left: 3px;
  margin-bottom: 3px;
  border-top-right-radius: 6px;
  -webkit-border-top-right-radius: 6px;
  -o-border-top-right-radius: 6px;
  -ms-border-top-right-radius: 6px;
}
.media-grid-3 .media-wrapper:nth-child(3) {
  position: relative;
  padding-top: 35%;
  width: calc(100% / 2 - 3px);
  overflow: hidden;
  background-size: cover;
  background-position: center;
  cursor: pointer;
  float: left;
  margin-left: 3px;
  border-bottom-right-radius: 6px;
  -webkit-border-bottom-right-radius: 6px;
  -o-border-bottom-right-radius: 6px;
  -ms-border-bottom-right-radius: 6px;
}

/* ----- MEDIA GRID 4/5 OR MORE ------- */
.media-grid-4 .media-wrapper:nth-child(1) {
  position: relative;
  padding-top: 50%;
  width: calc(100% / 1 - 0px);
  overflow: hidden;
  background-size: cover;
  background-position: center;
  cursor: pointer;
  float: left;
  margin-bottom: 3px;
  border-top-right-radius: 6px;
  -webkit-border-top-right-radius: 6px;
  -o-border-top-right-radius: 6px;
  -ms-border-top-right-radius: 6px;
  border-top-left-radius: 6px;
  -webkit-border-top-left-radius: 6px;
  -o-border-top-left-radius: 6px;
  -ms-border-top-left-radius: 6px;
}
.media-grid-5 .media-wrapper:nth-child(1) {
  position: relative;
  padding-top: 45%;
  width: calc(100% / 2 - 3px);
  float: left;
  overflow: hidden;
  background-size: cover;
  background-position: center;
  cursor: pointer;
  margin-bottom: 3px;
  margin-right: 3px;
}
.media-grid-5 .media-wrapper:nth-child(2) {
  position: relative;
  padding-top: 45%;
  width: calc(100% / 2 - 0px);
  float: left;
  overflow: hidden;
  background-size: cover;
  background-position: center;
  cursor: pointer;
  margin-bottom: 3px;

}
.media-grid-4 .media-wrapper:nth-child(2),
.media-grid-5 .media-wrapper:nth-child(3) {
  position: relative;
  padding-top: 30.3%;
  width: calc(100% / 3 - 0px);
  overflow: hidden;
  background-size: cover;
  background-position: center;
  cursor: pointer;
  float: left;
  border-bottom-left-radius: 6px;
  -webkit-border-bottom-left-radius: 6px;
  -o-border-bottom-left-radius: 6px;
  -ms-border-bottom-left-radius: 6px;
}
.media-grid-4 .media-wrapper:nth-child(3),
.media-grid-5 .media-wrapper:nth-child(4) {
  position: relative;
  padding-top: 30.3%;
  width: calc(100% / 3 - 3px);
  overflow: hidden;
  background-size: cover;
  background-position: center;
  cursor: pointer;
  float: left;
  margin-left: 3px;
}
.media-grid-4 .media-wrapper:nth-child(4),
.media-grid-5 .media-wrapper:nth-child(5) {
  position: relative;
  padding-top: 30.3%;
  width: calc(100% / 3 - 3px);
  overflow: hidden;
  background-size: cover;
  background-position: center;
  cursor: pointer;
  float: left;
  margin-left: 3px;
  border-bottom-right-radius: 6px;
  -webkit-border-bottom-right-radius: 6px;
  -o-border-bottom-right-radius: 6px;
  -ms-border-bottom-right-radius: 6px;
}
.media-grid-4 .media-wrapper:nth-child(1),
.media-grid-4 .media-wrapper:nth-child(2),
.media-grid-4 .media-wrapper:nth-child(3),
.media-grid-4 .media-wrapper:nth-child(4) > img,
.media-grid-4 .media-wrapper:nth-child(5) > img {
  z-index: 2;
}
.wrapper-media-music {
    width: 100%;
    max-width: 500px;
    height: auto;
    border: 1px solid #DDD;
    border-radius: 6px;
}
.progress-upload-cover {
  background-color: {{$settings->color_default}} !important;
}
.more-media {
  display: block;
  background: rgba(0,0,0,.3);
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9;
}
.more-media h2 {
  line-height: 1.8;
  text-align: center;
  position: absolute;
  left: 0;
  width: 100%;
  top: 50%;
  margin-top: -30px;
  color: #fff;
  z-index: 5;
}
.container-post-media {
  position: relative;
  width: 100%;
  display: flex;
  display: -webkit-flex;
  overflow: hidden;
}
.button-play {
  cursor: pointer;
  margin: auto auto;
  width: 48px;
  height: 48px;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 3;
  font-size: 28px;
  line-height: 48px;
  background-color: {{$settings->color_default}}!important;
  border-radius: 100%;
  text-align: center;
  opacity: 0.9;
}
.wrapper-msg-inbox {
  overflow: auto;
  overflow-x: hidden;
}
@media (max-width: 991px) {
  .wrapper-msg-inbox {
    padding-top: 78px !important;
    padding-bottom: 60px !important;
  }
}
.wrapper-msg-inbox::-webkit-scrollbar {
    width: 5px;
    height: 8px;
    -webkit-transition: all 0.2s ease;
  	        transition: all 0.2s ease;
}
.wrapper-msg-inbox::-webkit-scrollbar-track {
    background-color: @if (auth()->check() && auth()->user()->dark_mode == 'on') #313131 @else #ebebeb @endif;
    border-radius: 6px;
}
.wrapper-msg-inbox::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 6px;
}
.wrapper-msg-inbox {
  scrollbar-color: #ccc @if (auth()->check() && auth()->user()->dark_mode == 'on') #313131 @else #ebebeb @endif;
  scrollbar-width: 5px;
}
.msg-inbox {
  border: 0;
}
@media (min-width: 1280px) {
  .container-lg-3 {
    max-width: 1300px;
  }
}
.menu-left-home li > a {
  padding: 5px 12px;
  display: block;
  font-size: 19px;
  text-decoration: none;
  margin-bottom: 8px;
  border-radius: {{ $settings->button_style == 'rounded' ? '20px' : '4px' }};
  color: #8a96a3;
  -webkit-transition: all 200ms linear;
  -moz-transition: all 200ms linear;
  -o-transition: all 200ms linear;
  -ms-transition: all 200ms linear;
  transition: all 200ms linear;
}
.menu-left-home li > a:hover,
.menu-left-home li > a.active {
  background-color: {{$settings->color_default}};
  color: white;
}
.sticky-top {
    top: 90px;
}
.btn-category {
  width: 100%;
  text-align: left;
}
.category-filter {
  padding: 10px 15px;
  display: block;
  font-weight: bold;
}
.text-red {
  color: #F00;
}
.text-orange {
  color: #ff3507;
}
.img-vertical-lg {
  padding-top: 80% !important;
  max-width: 300px;
}
.wrapper-msg-left .img-vertical-lg {
  float:right;
}
.wrapper-msg-right .img-vertical-lg {
  float:left;
}
.button-white-sm {
  padding: 4px 15px;
  border: 1px solid #ccc;
  border-radius: 20px;
  text-align: center;
  font-size: 14px;
  color: @if (auth()->check() && auth()->user()->dark_mode == 'on') #fff @else #333 @endif;
  display: inline-block;
  vertical-align: middle;
}
a:hover.button-white-sm {
  text-decoration: none;
  border-color: {{$settings->color_default}};
  background-color: {{$settings->color_default}};
  color: white;
  -webkit-transition: all 200ms linear;
  -moz-transition: all 200ms linear;
  -o-transition: all 200ms linear;
  -ms-transition: all 200ms linear;
  transition: all 200ms linear;
}
.msg-inbox .active .verified {
  color: #FFF !important;
}
select[multiple] {
  visibility: hidden;
}
.select2-container
.select2-selection--multiple {
  min-height: 47px !important;
}

.select2-container--default
.select2-selection--multiple {
  border-left-width: 0 !important;
  border-bottom-left-radius: 0 !important;
  border-top-left-radius: 0 !important;

  background-color: @if (auth()->check() && auth()->user()->dark_mode == 'on') #303030 !important @else #fff !important @endif;
  border-color: @if (auth()->check() && auth()->user()->dark_mode == 'on') #222  !important @else #cad1d7 !important @endif;
}
@if (auth()->check() && auth()->user()->dark_mode == 'on')
.custom-control-label:not(.switch)::before {
background-color: #333 !important;
border-color: #adb5bd !important;
}
.ui-widget-content {
  background: #303030;
}
@endif

.select2-hidden-accessible {
  position: absolute !important;
}
.select2-container--default
.select2-selection--multiple
.select2-selection__choice {
  background-color: var(--plyr-color-main) !important;
  border: 1px solid var(--plyr-color-main) !important;
  color: #fff !important;
  padding: 4px 10px !important;
}
.select2-results__option {
  color: #333;
}
.select2-container
.select2-search--inline
.select2-search__field {
  margin-top: 10px !important;
}
.announcements a {
  text-decoration: none;
  border-bottom: 1px solid;
  color: #fff;
}
.unread-chat {
  background-color: #f8f9fa;
}
.glightbox-open {
	height: auto !important;
}
.txt-black {color: #241e12;}
.p-bottom-8 {padding-bottom: 8px;}
.pinned-post {border-color:{{$settings->color_default}} !important;}
.post-pending {
  border-color:#ff9800 !important;
}
.table-striped tbody tr:nth-of-type(odd) {background-color: rgba(0,0,0,.03);}

@auth
.icon-dashboard {
  padding: 12px 11px;
  background-color: @if (auth()->user()->dark_mode == 'on') #414141 !important; @else {{$settings->color_default}}2b; @endif
  border-radius: 35%;
  color: @if (auth()->user()->dark_mode == 'on') #FFF !important; @else {{$settings->color_default}} !important; @endif;
}
.icon-dashboard-2 {
    background-color: {{ auth()->user()->dark_mode == 'on'? '#414141' : $settings->color_default.'2e' }};
    border-radius: 50%;
    color: {{ auth()->user()->dark_mode == 'on'? '#fff' : $settings->color_default }} !important;
    width: 3rem!important;
    height: 3rem!important;
    align-items: center!important;
    justify-content: center!important;
    flex-shrink: 0!important;
    display: inline-flex!important;
    font-size: 24px;
}
.icon-notify {
  color: @if (auth()->user()->dark_mode == 'on') #FFF !important @else {{$settings->color_default}} @endif;
  width: 60px;
  height: 60px;
  font-size: 55px;
  line-height: 60px;
}
.btn-blocked {
    position: absolute;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.3);
    z-index: 99;
    top: 0;
    left: 0;
    border-radius: inherit;
}

.icon-sm-radio {
  padding: 7px 8px;
  border-radius: 35%;
  background-color: @if (auth()->user()->dark_mode == 'on') #414141;  @else {{$settings->color_default}}2b; @endif
  color: @if (auth()->user()->dark_mode == 'on') #FFF !important; @else {{$settings->color_default}} !important; @endif
}
@endauth

@media (max-width: 991px) {
  .w-100-mobile {
    width: 100% !important;
  }
}
.button-like-live {
  font-size: 25px;
}
@media (max-width: 767px) {

  .wrapper-live-chat  {
    background-color: transparent !important;
    position: absolute !important;
    padding: 0 !important;
    z-index: 200 !important;
    max-height: 250px !important;
    bottom: 0;
    left: 0;
    -webkit-mask-image: linear-gradient(transparent 0%, rgba(50, 50, 50, 1.0) 20%);
  }
  .wrapper-live-chat > .card,
  #commentLive {
    background-color: transparent !important;
    color: #FFF;
  }
  .titleChat {
    display: none !important;
  }
  #allComments {
    padding-top: 20px !important;
  }
  .live-blocked {
    background-color: rgba(255, 255, 255, 0.1) !important;
  }
  li.chatlist {
    color: #FFF !important;
    font-size: 14px;
    text-shadow: 0 1px 2px #000;
  }
  .wrapper-live-chat .content {
    padding-bottom: 0 !important;
    padding-top: 0 !important;
  }
  .wrapper-live-chat .card-footer {
    border: 0 !important;
  }
  .buttons-live {
    color: #FFF !important;
  }
  .buttons-live:hover {
    background-color: transparent !important;
  }
  #commentLive::placeholder {
    color: #FFF !important;
    opacity: 1;
  }
  #commentLive::-moz-placeholder {
    color: #FFF !important;
    opacity: 1;
  }
  #commentLive::-ms-input-placeholder {
    color: #FFF !important;
    opacity: 1;
  }
  #commentLive:-ms-input-placeholder {
    color: #FFF !important;
    opacity: 1;
  }
  #commentLive::-webkit-input-placeholder {
    color: #FFF !important;
    opacity: 1;
  }
  input#commentLive {
    border: 1px solid #FFF !important;
    border-radius: 60px;
  }
  .offline-live { display: none;}
}/* End max-width 767 */

.tipped-live {
  font-size: 14px;
  text-shadow: 0 1px 4px #4b4b4b;
}
.live_offline::before {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  backdrop-filter: blur(15px);
  -webkit-backdrop-filter: blur(15px);
  content: "";
  z-index: 1;
  position: absolute;
}
.live_offline::after {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  content: "";
  z-index: 1;
  position: absolute;
  background-color: rgba(50, 50, 50, 0.8);
}
.text-content-live {
  position: relative;
  color: #FFF;
  z-index: 2 !important;
}
#full-screen-video {
  position: relative;
  width: 100%;
  height: 100%;
}
.liveContainer {
  background-color: #000;
}
.live-top-menu {
  width: 100%;
  padding: 1rem!important;
  position: absolute;
  top: 0;
  right: 0;
  z-index: 200;
}
@keyframes pulse-live {
  0% {
    background-color: #ff0000;
  }
  50% {
    background-color: #ff0000b0;
  }
  100 {
    background-color: #ff0000;
  }
}
.live {
  color: #fff;
  border-color: #ff0000;
  background-color: #ff0000;
  padding: 5px 15px;
  border-radius: 4px;
  display: inline-block;
  vertical-align: text-top;
  font-weight: bold;
  animation-name: pulse-live;
  animation-duration: 2s;
  animation-iteration-count: infinite;
}
.live-views {
  color: #fff;
  border-color: #000000;
  background-color: #0000007a;
  padding: 5px 15px;
  border-radius: 4px;
  display: inline-block;
  vertical-align: text-top;
  font-weight: bold;
}
.close-live,
.exit-live,
.live-options {
  color: #FFF;
  font-size: 22px;
  vertical-align: bottom;
  cursor: pointer;
}
.text-shadow-sm {
  text-shadow: 0 1px 0px #000;
}
.div-flex {
  flex: 1 1 auto;
  position: relative;
  min-height: 12px;
}
.chat-msg {
  flex: 1 1 auto;
  display: flex;
  flex-direction: column;
}
.menu-options-live a {
  cursor: pointer;
}
.avatar-wrap-live {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  margin: 0 auto;
  background-color: #FFF;
  margin-bottom: 15px;
}
.video-poster-html {
  position:absolute;
  right: 0;
  top: 0;
  min-width: 100%;
  max-width: 100%;
  height: 100%;
  width: auto;
  z-index: 2;
  margin: 0 auto;
  object-fit: cover;
}
.wrapper-live {
  position: relative;
  max-width: 100px;
  margin: auto;
}
@keyframes pulseLive {
  0% {
    opacity: 0.1;
    transform: scale(1.05);
  }
  50% {
    opacity: 1;
    transform: scale(1.15);
  }
  100% {
    opacity: 0.1;
    transform: scale(1.3);
  }
}
.live-pulse {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  position: absolute;
  border: 3px solid #ff0000;
  z-index: 1;
  border-radius: 50%;
}
.live-pulse::after,
.live-pulse::before {
  width: 100%;
  border: 2px solid #ff0000 !important;
  height: 100%;
  content: "";
  display: block;
  z-index: 2;
  position: absolute;
  border-radius: 50%;
  animation-name: pulseLive;
  animation-duration: 1.6s;
  animation-iteration-count: infinite;
  animation-timing-function: ease-in-out;
}
.live-pulse::after {
  animation-delay: .5s;
}
.live-span {
  width: 39px;
  left: 0;
  right: 0;
  bottom: -10px;
  height: 17px;
  border-radius: 3px;
  text-transform: uppercase;
  justify-content: center;
  background-color: #ff0000;
  margin: auto;
  display: flex;
  z-index: 2;
  position: absolute;
  font-size: 8px;
  text-align: center;
  align-items: center;
  font-weight: 900;
  color: #FFF;
}
.button-like-live .bi-heart-fill {
  color: #F00 !important;
}
.avatar-live {
  border: 2px solid #f00;
}
.liveLink {cursor: pointer;}
.icon-wrap {
  position: absolute;
  top: -30px;
  right: 10px;
  z-index: 0;
  font-size: 115px;
  color: rgba(0,0,0,0.10);
  transform: rotate(20deg);
}
.inner-wrap {
  position: relative;
  z-index: 1;
}
.fee-wrap {
  padding: 5px 10px;
  border: 1px dashed #ccc;
  border-radius: 6px;
}
.btn-arrow-expand-bi[aria-expanded='true'] .bi-chevron-down::before {
  transform: rotate(180deg);
}
.transition-icon::before {
  -moz-transition: all 0.3s ease;
  -webkit-transition: all 0.3s ease;
  transition: all 0.3s ease;
}
.limitLiveStreaming {
  position: absolute;
  top: 45px;
  left: 65px;
}
.subscriptionDiscount {
  font-size: 14px;
  padding: 2px 20px;
  border: 1px dashed;
  border-radius: 50px;
  margin-top: 5px;
  margin-bottom: 5px;
  display: inline-block;
}
.border-dashed-radius {
  border: 1px dashed #a7a7a7;
  border-radius: 10px;
}
.list-taxes:last-child {
  border-bottom: 0 !important;
  border-radius: inherit;
}
.search-bar {
  border-radius: 60px;
  padding: 12px 20px !important;
  height: 40px !important;
  background-color: #f6f6f6;
  border: 1px solid transparent !important;
}
.search-bar:focus {
  border: 1px solid #ced4da !important;
  -moz-transition: all 0.3s ease;
  -webkit-transition: all 0.3s ease;
  transition: all 0.3s ease;
}
@auth
.preview-shop .fileuploader {
  display: block;
  background: @if (auth()->user()->dark_mode == 'on') #414141 !important; @else #fafbfd !important; @endif;
}
@if (auth()->user()->dark_mode == 'on')
.file-shop .fileuploader-input-caption {
  background: #414141 !important;
  border: #414141 !important;
}
@endif
.file-shop .fileuploader {
  display: block;
}
@endauth

.count-previews {
  position: absolute;
  right: 10px;
  top: 10px;
  padding: 8px 6px;
  background: #0000009e;
  border-radius: 6px;
  color: #fff;
  font-size: 12px;
}
a.link-shop,
a.choose-type-sale {
  color: inherit;
  text-decoration: none;
}
a:hover.choose-type-sale {
  border-color: {{$settings->color_default}};
}
.text-truncate-2 {
  word-wrap: break-word;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  white-space: pre-wrap;
  white-space: -moz-pre-wrap;
  white-space: -pre-wrap;
  white-space: -o-pre-wrap;
  word-break: break-word;
  text-align: left;
  display: -webkit-box;
  line-height: normal;
  height: auto;
  overflow: hidden;
  text-overflow: ellipsis;
}
.price-shop {
  position: absolute;
  right: 10px;
  bottom: 10px;
  padding: 8px 6px;
  background: {{$settings->color_default}};
  border-radius: 6px;
  color: #fff;
  font-size: 18px;
  font-weight: bold;
}
.rounded-large {
  border-radius: 15px!important;
}
.shadow-large {
    box-shadow: 0 8px 30px rgba(0,0,0,.05)!important;
}
.buttons-mobile-nav {
  position: absolute;
  right: 10px;
  top: 10px;
}
.btn-mobile-nav {
  @if (auth()->check() && auth()->user()->dark_mode == 'off')
  color: {{$settings->navbar_text_color}} !important;
  @endif
}
.btn-mobile-nav:hover {
  text-decoration: none !important;
}

.modal-content,
.modal-content > .modal-body > .card {
  border-radius: 0.8rem;
}

.btn-arrow::after {
    font-family: "bootstrap-icons";
    display: inline-block;
    padding-left: 5px;
    content: "\f138";
    transition: transform 0.3s ease-out;
    vertical-align: middle;

}
.btn-arrow-sm::after {
  font-size: 13px;
}
.btn-arrow:hover::after {
    transform: translateX(4px);
}
.btn-search {
    color: #c3c3c3;
    background: none;
    position: absolute;
    left: 0;
    outline: none;
    border: none;
    width: 50px;
    text-align: center;
    bottom: 20%;
}
.custom-select {
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
}
.ui-widget-content {
  padding: 10px;
  border: 0 !important;
  border-bottom-left-radius: 6px;
  border-bottom-right-radius: 6px;
  box-shadow: rgb(101 119 134 / 20%) 0px 0px 15px, rgb(101 119 134 / 15%) 0px 0px 3px 1px;
}
.ui-state-active,
.ui-widget-content
.ui-state-active {
    width: 100%;
    display: block;
    border: 0 !important; 
    background: {{ auth()->check() && auth()->user()->dark_mode == 'on' ? '#222' : '#efefef'}};
    color: #333;
    text-decoration: none;
    margin: 0;
    border-radius: 6px;
  }
.ui-menu .ui-menu-item-wrapper {
  width: 100%;
  display: block;
}
.ui-menu.ui-menu-item:not(:last-child) {
  margin-bottom: 10px;
}
.ui-widget {
  font-size: 15px;
}
.btn-sm-custom {
  font-size: .875rem;
  line-height: 1.5;
  padding: 0.25rem 0.5rem;
}
.dropdown-item {
  padding: 0.3rem 1.5rem;
}
.triggerEmoji {
  font-size: 20px;
  position: absolute;
  right: 0;
  top: 40%;
  cursor: pointer;
}
.triggerEmojiPost {
  font-size: 20px;
  position: absolute;
  right: 0;
  top: 0px;
  cursor: pointer;
}
.emoji {
  font-size: 26px;
  line-height: 32px;
  padding: 5px 8px;
  display: block;
  cursor: pointer;
  margin-bottom: 2px;
}
.dropdown-emoji {
  width: 350px;
  max-width: 350px;
  max-height: 300px;
  overflow: auto scroll;
}
.type-item {
  position: absolute;
  top: 10px;
  left: 10px;
  z-index: 2;
  background-color: #0000008c;
  font-size: 13px;
  color: #fff;
}
.font-13 {
  font-size: 13px;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
    height: 8px;
    -webkit-transition: all 0.2s ease;
  	        transition: all 0.2s ease;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background-color: @if (auth()->check() && auth()->user()->dark_mode == 'on') #313131 @else #ebebeb @endif;
    border-radius: 6px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 6px;
}
.custom-scrollbar {
  scrollbar-color: #ccc @if (auth()->check() && auth()->user()->dark_mode == 'on') #313131 @else #ebebeb @endif;
  scrollbar-width: 5px;
}
.icon--dashboard {
    color: rgb(0 0 0 / {{ auth()->check() && auth()->user()->dark_mode == 'on' ? '5%' : '2%' }}) !important;
    transform: none !important;
}
.text-revenue {
  position: relative;
  z-index: 10;
}
.quality-video {
  padding: 2px 4px;
  background-color: #fff;
  color: #000;
  border-radius: 3px;
  margin: 0 5px;
  font-weight: bold;
}
.line-replies {
  align-items: stretch;
  border: 0;
  border-bottom: 1px solid {{$settings->color_default}};
  box-sizing: border-box;
  display: inline-block;
  flex-direction: column;
  flex-shrink: 0;
  font: inherit;
  font-size: 100%;
  height: 0;
  margin: 0;
  margin-right: 16px;
  padding: 0;
  position: relative;
  vertical-align: middle;
  width: 24px;
}
.mr-14px {
    margin-right: 14px;
}
.dot-item:not(:last-child):after {
   font-family: "bootstrap-icons";
    content: "\F309";
    margin-left: 6px;
    color: inherit;
    vertical-align: middle;
}
.add-story {
  max-height: 160px;
  max-width: 110px;
  width: 25vw;
  border-radius: 5px;
  display: inline-block;
  margin: 0 6px;
  vertical-align: top;
}
.item-add-story {
  text-decoration: none;
  text-align: left;
  color: #fff;
  position: relative;
  max-height: 160px;
  display: block;
}
.add-story-preview {
  display: block;
  box-sizing: border-box;
  font-size: 0;
  max-height: 160px;
  height: 48vw;
  overflow: hidden;
  transition: transform .2s;
}
.add-story-preview img {
  display: block;
  box-sizing: border-box;
  height: 100%;
  width: 100%;
  background-size: cover;
  background-position: 50%;
  object-fit: cover;
  border-radius: 5px;
  position: absolute;
}
.item-add-story > .info {
  top: auto;
  height: auto;
  box-sizing: border-box;
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  padding: 6px;
  font-weight: 700;
  font-size: 12px;
  text-shadow: 1px 1px 1px rgb(0 0 0 / 35%), 1px 0 1px rgb(0 0 0 / 35%);
  display: inline-block;
  margin-top: 0.5em;
  line-height: 1.2em;
  width: 100%;
  overflow: hidden;
  text-overflow: ellipsis;
  border-bottom-left-radius: 5px;
  border-bottom-right-radius: 5px;
  z-index: 10;
}
.item-add-story > .info > .name {
  font-weight: 500;
}
.delete-history {
  position: absolute;
  top: 5px;
  right: 5px;
  padding: 2px 6px;
  background-color: #00000096!important;
  color: #fff;
  z-index: 2;
  border-radius: 6px;
  z-index: 10;
}
.bg-dark-transparent {
  background-color: #00000096!important;
}
.storyBackgrounds, .fontColor {
  width: 30px;
  height: 30px;
  object-fit: cover;
  border-radius: 50px;
  cursor: pointer;
  display: inline-block;
}
.storyBackgrounds.active,
.storyBg:first-child,
.fontColor.active {
  border: 1px solid #fff;
  box-shadow: 0 0 0 3px {{$settings->color_default}};
}
.fontColor-white {background-color: #fff;}
.fontColor-black {background-color: #000;}
.bg-current {
  border-radius: 10px;
  height: 700px;
}
.bg-inside {
  position: relative;
  overflow: auto;
  overflow-x: hidden;
  flex: 2;
  -webkit-box-flex: 2;
  border-radius: 10px;
  max-width: 400px;
  height: 650px;
}
.text-story {
  font-size: 24px;
  font-family: Arial;
  word-break: break-word;
  font-weight: bold;
  color: #fff;
}
.modalStoryViews {
  max-height: 400px;
}
.modal-text-story {
  color: #fff;
  position: absolute;
  top: 50%;
  left: 50%;
  z-index: 100;
  transform: translate(-50%, -50%);
  font-size: 24px;
  font-family: Arial;
  word-break: break-word;
  white-space: break-spaces;
  font-weight: bold;
  width: 45vh;
  text-align: center;
}
.text-story-preview {
    color: #fff;
    position: absolute;
    top: 50%;
    left: 50%;
    z-index: 100;
    transform: translate(-50%, -50%);
    font-size: 5px;
    font-family: Arial;
    word-break: break-word;
    white-space: break-spaces;
    font-weight: bold;
    width: 10vh;
    text-align: center;
}
.profilePhoto, .info>.name {cursor: pointer;}
.profilePhoto {border-radius: 50%;}
.bg-black {background-color: #18191a!important;}
.border-2 {border: 2px solid #fff!important;}
.verified-story:after {
  font-family: "bootstrap-icons";
  display: inline-block;
  padding-left: 5px;
  content: "\F4B6";
  vertical-align: middle;
}
.grecaptcha-badge {
  visibility: hidden;
}
.btn-post {
  width: 48px;
  height: 48px;
  padding: 0;
-webkit-transition: all 200ms linear;
  -moz-transition: all 200ms linear;
  -o-transition: all 200ms linear;
  -ms-transition: all 200ms linear;
  transition: all 200ms linear;
}
.btn-post:hover {
  background-color: #f3f3f3;
}
@media (max-width: 991px) {
  .btn-post {
    width: 40px;
    height: 40px;
  }
}
.icons-live {
  padding: 10px 12px;
-webkit-transition: all 200ms linear;
  -moz-transition: all 200ms linear;
  -o-transition: all 200ms linear;
  -ms-transition: all 200ms linear;
  transition: all 200ms linear;
}
.icons-live:hover {
  background-color: #f3f3f3;
}
.thumbnails-shop {
display: flex;
margin: 1rem auto 0;
padding: 0;
justify-content: center;
}


.thumbnail-shop {
width: 70px;
height: 70px;
overflow: hidden;
list-style: none;
margin: 0 0.2rem;
cursor: pointer;
opacity: 0.3;
}

.thumbnail-shop.is-active {
  opacity: 1;
}

.thumbnail-shop img {
width: 100%;
height: auto;
}
.buttonDisabled {
  opacity: .3;
  cursor: default;
}
.buttonDisabled:active > i {
  animation: none !important;
}
.container-full-w {
  max-width: 100% !important;
}
.font-12 {
  font-size: 12px;
}
.b-radio-custom {
  border-radius: 0.8rem !important;
}
@endif
</style>
