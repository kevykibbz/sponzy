<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ auth()->user()->dark_mode == 'on' ? 'dark' : 'light' }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ url('public/img', $settings->favicon) }}" />

    <title>{{ __('admin.admin') }}</title>

    @include('includes.css_admin')

    <script type="text/javascript">
      var URL_BASE = "{{ url('/') }}";
      var url_file_upload = "{{route('upload.image', ['_token' => csrf_token()])}}";
      var delete_confirm = "{{__('general.delete_confirm')}}";
      var yes_confirm = "{{__('general.yes_confirm')}}";
      var yes = "{{__('general.yes')}}";
      var cancel_confirm = "{{__('general.cancel_confirm')}}";
      var timezone = "{{config('app.timezone')}}";
      var add_tag = "{{ __("general.add_tag") }}";
      var choose_image = '{{__('general.choose_image')}}';
      var formats_available = "{{ __('general.formats_available_verification_form_w9', ['formats' => 'JPG, PNG, GIF, SVG']) }}";
      var cancel_payment = "{!!__('general.confirm_cancel_payment')!!}";
      var yes_cancel_payment = "{{__('general.yes_cancel_payment')}}";
      var approve_confirm_verification = "{{__('admin.approve_confirm_verification')}}";
      var yes_confirm_approve_verification = "{{__('admin.yes_confirm_approve_verification')}}";
      var yes_confirm_verification = "{{__('admin.yes_confirm_verification')}}";
      var delete_confirm_verification = "{{__('admin.delete_confirm_verification')}}";
      var login_as_user_warning = "{{__('general.login_as_user_warning')}}";
      var yes_confirm_reject_post = "{{__('general.yes_confirm_reject_post')}}";
      var delete_confirm_post = "{{__('general.delete_confirm_post')}}";
      var yes_confirm_approve_post = "{{__('general.yes_confirm_approve_post')}}";
      var approve_confirm_post = "{{__('general.approve_confirm_post')}}";
      var yes_confirm_refund = "{{__('general.refund')}}";
     </script>

    <style>
     :root {
       --color-default: #000000;
    }
     </style>

    @yield('css')
  </head>
  <body>
  <div class="overlay" data-bs-toggle="offcanvas" data-bs-target="#sidebar-nav"></div>
  <div class="popout font-default"></div>

    <main>

      <div class="offcanvas offcanvas-start sidebar bg-dark text-white" tabindex="-1" id="sidebar-nav" data-bs-keyboard="false" data-bs-backdrop="false">
      <div class="offcanvas-header">
          <h5 class="offcanvas-title"><img src="{{ url('public/img', $settings->logo) }}" width="100" /></h5>
          <button type="button" class="btn-close btn-close-custom text-white toggle-menu d-lg-none" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="bi bi-x-lg"></i>
          </button>
      </div>
      <div class="offcanvas-body px-0 scrollbar">
          <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-start list-sidebar" id="menu">

              @if (auth()->user()->hasPermission('dashboard'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin') }}" class="nav-link text-truncate @if (request()->is('panel/admin')) active @endif">
                      <i class="bi-speedometer2 me-2"></i> {{ __('admin.dashboard') }}
                  </a>
              </li><!-- /end list -->
            @endif

              @if (auth()->user()->hasPermission('general'))
              <li class="nav-item">
                  <a href="#settings" data-bs-toggle="collapse" class="nav-link text-truncate dropdown-toggle @if (request()->is(['panel/admin/settings', 'panel/admin/settings/limits', 'panel/admin/video/encoding'])) active @endif" @if (request()->is(['panel/admin/settings', 'panel/admin/settings/limits', 'panel/admin/video/encoding'])) aria-expanded="true" @endif>
                      <i class="bi-gear me-2"></i> {{ __('admin.general_settings') }}
                  </a>
              </li><!-- /end list -->
            @endif

              <div class="collapse w-100 @if (request()->is(['panel/admin/settings', 'panel/admin/settings/limits', 'panel/admin/video/encoding'])) show @endif ps-3" id="settings">
                <li>
                <a class="nav-link text-truncate w-100 @if (request()->is('panel/admin/settings')) text-white @endif" href="{{ url('panel/admin/settings') }}">
                  <i class="bi-chevron-right fs-7 me-1"></i> {{ __('admin.general') }}
                  </a>
                </li>
                <li>
                <a class="nav-link text-truncate @if (request()->is('panel/admin/settings/limits')) text-white @endif" href="{{ url('panel/admin/settings/limits') }}">
                  <i class="bi-chevron-right fs-7 me-1"></i> {{ __('admin.limits') }}
                  </a>

                  <a class="nav-link text-truncate @if (request()->is('panel/admin/video/encoding')) text-white @endif" href="{{ url('panel/admin/video/encoding') }}">
                    <i class="bi-chevron-right fs-7 me-1"></i> {{ __('general.video_encoding') }}
                    </a>
                </li>
              </div><!-- /end collapse settings -->

              @if (auth()->user()->hasPermission('reports'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/reports') }}" class="nav-link text-truncate @if (request()->is('panel/admin/reports')) active @endif">
                      <i class="bi-flag me-2"></i> 

                      @if ($reports <> 0)
                        <span class="badge rounded-pill bg-warning text-dark me-1">{{ $reports }}</span>
                      @endif
                      
                      {{ __('admin.reports') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('withdrawals'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/withdrawals') }}" class="nav-link text-truncate @if (request()->is('panel/admin/withdrawals')) active @endif">
                      <i class="bi-bank me-2"></i>

                      @if ($withdrawalsPendingCount <> 0)
                        <span class="badge rounded-pill bg-warning text-dark me-1">{{ $withdrawalsPendingCount }}</span>
                      @endif

                      {{ __('general.withdrawals') }}
                  </a>
              </li><!-- /end list -->
              @endif
              
              @if (auth()->user()->hasPermission('verification_requests'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/verification/members') }}" class="nav-link text-truncate @if (request()->is('panel/admin/verification/members')) active @endif">
                      <i class="bi-person-badge me-2"></i>

                      @if ($verificationRequestsCount <> 0)
                        <span class="badge rounded-pill bg-warning text-dark me-1">{{ $verificationRequestsCount }}</span>
                      @endif

                      {{ __('admin.verification_requests') }}
                  </a>
              </li><!-- /end list -->
            @endif

            @if (auth()->user()->hasPermission('deposits'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/deposits') }}" class="nav-link text-truncate @if (request()->is('panel/admin/deposits')) active @endif">
                      <i class="bi-cash-stack me-2"></i>

                      @if ($depositsPendingCount <> 0)
                        <span class="badge rounded-pill bg-warning text-dark me-1">{{ $depositsPendingCount }}</span>
                      @endif

                      {{ __('general.deposits') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('posts'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/posts') }}" class="nav-link text-truncate @if (request()->is('panel/admin/posts')) active @endif">
                      <i class="bi-pencil-square me-2"></i>

                      @if ($updatesPendingCount <> 0)
                        <span class="badge rounded-pill bg-warning text-dark me-1">{{ $updatesPendingCount }}</span>
                      @endif

                      {{ __('general.posts') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('subscriptions'))
            <li class="nav-item">
                <a href="{{ url('panel/admin/subscriptions') }}" class="nav-link text-truncate @if (request()->is('panel/admin/subscriptions')) active @endif">
                    <i class="bi-arrow-repeat me-2"></i> {{ __('admin.subscriptions') }}
                </a>
            </li><!-- /end list -->
            @endif

              @if (auth()->user()->hasPermission('transactions'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/transactions') }}" class="nav-link text-truncate @if (request()->is('panel/admin/transactions')) active @endif">
                      <i class="bi-receipt me-2"></i> {{ __('admin.transactions') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('members'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/members') }}" class="nav-link text-truncate @if (request()->is('panel/admin/members')) active @endif">
                      <i class="bi-people me-2"></i> {{ __('admin.members') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('advertising'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/advertising') }}" class="nav-link text-truncate @if (request()->is('panel/admin/advertising')) active @endif">
                      <i class="bi-badge-ad me-2"></i> {{ __('general.advertising') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('comments_replies'))
              <li class="nav-item">
                  <a href="#comments_replies" data-bs-toggle="collapse" class="nav-link text-truncate dropdown-toggle @if (request()->is(['panel/admin/comments', 'panel/admin/replies'])) active @endif" @if (request()->is(['panel/admin/comments', 'panel/admin/replies'])) aria-expanded="true" @endif>
                      <i class="bi-chat me-2"></i> {{ __('general.comments_replies') }}
                  </a>
              </li><!-- /end list -->
            @endif

              <div class="collapse w-100 @if (request()->is(['panel/admin/comments', 'panel/admin/replies'])) show @endif ps-3" id="comments_replies">
                <li>
                <a class="nav-link text-truncate w-100 @if (request()->is('panel/admin/comments')) text-white @endif" href="{{ url('panel/admin/comments') }}">
                  <i class="bi-chevron-right fs-7 me-1"></i> {{ __('general.comments') }}
                  </a>
                </li>
                <li>
                <a class="nav-link text-truncate @if (request()->is('panel/admin/replies')) text-white @endif" href="{{ url('panel/admin/replies') }}">
                  <i class="bi-chevron-right fs-7 me-1"></i> {{ __('general.replies') }}
                  </a>
                </li>
              </div><!-- /end collapse settings -->


              @if (auth()->user()->hasPermission('messages'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/messages') }}" class="nav-link text-truncate @if (request()->is('panel/admin/messages')) active @endif">
                      <i class="bi-send me-2"></i> {{ __('general.messages') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('announcements'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/announcements') }}" class="nav-link text-truncate @if (request()->is('panel/admin/announcements')) active @endif">
                      <i class="bi-megaphone me-2"></i> {{ __('general.announcements') }}
                  </a>
              </li><!-- /end list -->
            @endif

              @if (auth()->user()->hasPermission('maintenance'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/maintenance/mode') }}" class="nav-link text-truncate @if (request()->is('panel/admin/maintenance/mode')) active @endif">
                      <i class="bi bi-tools me-2"></i> {{ __('admin.maintenance_mode') }}
                  </a>
              </li><!-- /end list -->
            @endif

            @if (auth()->user()->hasPermission('billing'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/billing') }}" class="nav-link text-truncate @if (request()->is('panel/admin/billing')) active @endif">
                      <i class="bi-receipt-cutoff me-2"></i> {{ __('general.billing_information') }}
                  </a>
              </li><!-- /end list -->
            @endif

                @if (auth()->user()->hasPermission('tax'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/tax-rates') }}" class="nav-link text-truncate @if (request()->is('panel/admin/tax-rates')) active @endif">
                      <i class="bi-receipt me-2"></i> {{ __('general.tax_rates') }}
                  </a>
              </li><!-- /end list -->
            @endif

            @if (auth()->user()->hasPermission('countries_states'))
              <li class="nav-item">
                  <a href="#countriesStates" data-bs-toggle="collapse"  class="nav-link text-truncate dropdown-toggle @if (request()->is('panel/admin/countries') || request()->is('panel/admin/states')) active @endif" @if (request()->is('panel/admin/countries') || request()->is('panel/admin/states')) aria-expanded="true" @endif>
                      <i class="bi-globe me-2"></i> {{ __('general.countries_states') }}
                  </a>
              </li><!-- /end list -->
              @endif

              <div class="collapse w-100 @if (request()->is('panel/admin/countries') || request()->is('panel/admin/states')) show @endif ps-3" id="countriesStates">
                <li class="nav-item">
                    <a href="{{ url('panel/admin/countries') }}" class="nav-link text-truncate w-100 @if (request()->is('panel/admin/countries')) text-white @endif">
                        <i class="bi-chevron-right fs-7 me-1"></i> {{ __('general.countries') }}
                    </a>
                </li><!-- /end list -->
                <li class="nav-item">
                    <a href="{{ url('panel/admin/states') }}" class="nav-link text-truncate w-100 @if (request()->is('panel/admin/states')) text-white @endif">
                        <i class="bi-chevron-right fs-7 me-1"></i> {{ __('general.states') }}
                    </a>
                </li><!-- /end list -->
              </div><!-- /end collapse settings -->

              @if (auth()->user()->hasPermission('email'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/settings/email') }}" class="nav-link text-truncate @if (request()->is('panel/admin/settings/email')) active @endif">
                      <i class="bi-at me-2"></i> {{ __('admin.email_settings') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('live_streaming'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/live-streaming') }}" class="nav-link text-truncate @if (request()->is('panel/admin/live-streaming')) active @endif">
                      <i class="bi-camera-video me-2"></i> {{ __('general.live_streaming') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('live_streaming_private_requests'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/live-streaming-private-requests') }}" title="{{ __('general.live_streaming_private_requests') }}" class="nav-link text-truncate @if (request()->is('panel/admin/live-streaming-private-requests')) active @endif">
                      <i class="bi-person-video3 me-2"></i> {{ __('general.live_streaming_private_requests') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('push_notifications'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/push-notifications') }}" class="nav-link text-truncate @if (request()->is('panel/admin/push-notifications')) active @endif">
                      <i class="bi-app-indicator me-2"></i> {{ __('general.push_notifications') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('stories'))
              <li class="nav-item">
                  <a href="#stories" data-bs-toggle="collapse" class="nav-link text-truncate dropdown-toggle @if (request()->is('panel/admin/stories/settings') || request()->is('panel/admin/stories/posts') || request()->is('panel/admin/stories/backgrounds')) active @endif" @if (request()->is('panel/admin/stories/settings') || request()->is('panel/admin/stories/posts') || request()->is('panel/admin/stories/backgrounds') || request()->is('panel/admin/stories/fonts')) aria-expanded="true" @endif>
                      <i class="bi-clock-history me-2"></i> {{ __('general.stories') }}
                  </a>
              </li><!-- /end list -->
              @endif

              <div class="collapse w-100 @if (request()->is('panel/admin/stories/settings') || request()->is('panel/admin/stories/posts') || request()->is('panel/admin/stories/backgrounds') || request()->is('panel/admin/stories/fonts')) show @endif ps-3" id="stories">
                <li class="nav-item">
                    <a href="{{ url('panel/admin/stories/settings') }}" class="nav-link text-truncate w-100 @if (request()->is('panel/admin/stories/settings')) text-white @endif">
                        <i class="bi-chevron-right fs-7 me-1"></i> {{ __('general.settings') }}
                    </a>
                </li><!-- /end list -->
                <li class="nav-item">
                    <a href="{{ url('panel/admin/stories/posts') }}" class="nav-link text-truncate w-100 @if (request()->is('panel/admin/stories/posts')) text-white @endif">
                        <i class="bi-chevron-right fs-7 me-1"></i> {{ __('general.posts') }}
                    </a>
                </li><!-- /end list -->
                <li class="nav-item">
                    <a href="{{ url('panel/admin/stories/backgrounds') }}" class="nav-link text-truncate w-100 @if (request()->is('panel/admin/stories/backgrounds')) text-white @endif">
                        <i class="bi-chevron-right fs-7 me-1"></i> {{ __('general.backgrounds') }}
                    </a>
                </li><!-- /end list -->
                <li class="nav-item">
                    <a href="{{ url('panel/admin/stories/fonts') }}" class="nav-link text-truncate w-100 @if (request()->is('panel/admin/stories/fonts')) text-white @endif">
                        <i class="bi-chevron-right fs-7 me-1"></i> {{ __('general.google_fonts') }}
                    </a>
                </li><!-- /end list -->
              </div><!-- /end collapse settings -->

              @if (auth()->user()->hasPermission('shop'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/shop') }}" class="nav-link text-truncate @if (request()->is('panel/admin/shop')) active @endif">
                      <i class="bi-shop-window me-2"></i> {{ __('general.shop') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('products'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/products') }}" class="nav-link text-truncate @if (request()->is('panel/admin/products')) active @endif">
                      <i class="bi-tag me-2"></i> {{ __('general.products') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('shop_categories'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/shop-categories') }}" class="nav-link text-truncate @if (request()->is('panel/admin/shop-categories')) active @endif">
                      <i class="bi-list-ul me-2"></i> {{ __('general.shop_categories') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('sales'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/sales') }}" class="nav-link text-truncate @if (request()->is('panel/admin/sales')) active @endif">
                      <i class="bi-cart me-2"></i> {{ __('general.sales') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('storage'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/storage') }}" class="nav-link text-truncate @if (request()->is('panel/admin/storage')) active @endif">
                      <i class="bi-server me-2"></i> {{ __('admin.storage') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('theme'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/theme') }}" class="nav-link text-truncate @if (request()->is('panel/admin/theme')) active @endif">
                      <i class="bi-brush me-2"></i> {{ __('admin.theme') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('custom_css_js'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/custom-css-js') }}" class="nav-link text-truncate @if (request()->is('panel/admin/custom-css-js')) active @endif">
                      <i class="bi-code-slash me-2"></i> {{ __('general.custom_css_js') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('referrals'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/referrals') }}" class="nav-link text-truncate @if (request()->is('panel/admin/referrals')) active @endif">
                      <i class="bi-person-plus me-2"></i> {{ __('general.referrals') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('languages'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/languages') }}" class="nav-link text-truncate @if (request()->is('panel/admin/languages')) active @endif">
                      <i class="bi-translate me-2"></i> {{ __('admin.languages') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('categories'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/categories') }}" class="nav-link text-truncate @if (request()->is('panel/admin/categories')) active @endif">
                      <i class="bi-list-stars me-2"></i> {{ __('admin.categories') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('pages'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/pages') }}" class="nav-link text-truncate @if (request()->is('panel/admin/pages')) active @endif">
                      <i class="bi-file-earmark-text me-2"></i> {{ __('admin.pages') }}
                  </a>
              </li><!-- /end list -->
                @endif

                @if (auth()->user()->hasPermission('blog'))
                <li class="nav-item">
                    <a href="{{ url('panel/admin/blog') }}" class="nav-link text-truncate @if (request()->is('panel/admin/blog')) active @endif">
                        <i class="bi-pencil me-2"></i> {{ __('general.blog') }}
                    </a>
                </li><!-- /end list -->
                  @endif

                @if (auth()->user()->hasPermission('payments'))
              <li class="nav-item">
                  <a href="#payments" data-bs-toggle="collapse" class="nav-link text-truncate dropdown-toggle @if (request()->is('panel/admin/payments') || request()->is('panel/admin/payments/*')) active @endif" @if (request()->is('panel/admin/payments') || request()->is('panel/admin/payments/*')) aria-expanded="true" @endif>
                      <i class="bi-credit-card me-2"></i> {{ __('admin.payment_settings') }}
                  </a>
              </li><!-- /end list -->

              <div class="collapse w-100 ps-3 @if (request()->is('panel/admin/payments') || request()->is('panel/admin/payments/*')) show @endif" id="payments">
                <li>
                <a class="nav-link text-truncate @if (request()->is('panel/admin/payments')) text-white @endif" href="{{ url('panel/admin/payments') }}">
                  <i class="bi-chevron-right fs-7 me-1"></i> {{ __('admin.general') }}
                  </a>
                </li>

                @foreach ($paymentsGateways as $key)
                <li>
                <a class="nav-link text-truncate @if (request()->is('panel/admin/payments/'.$key->id.'')) text-white @endif" href="{{ url('panel/admin/payments', $key->id) }}">
                  <i class="bi-chevron-right fs-7 me-1"></i> {{ $key->type == 'bank' ? __('general.bank_transfer') : $key->name }}
                  </a>
                </li>
              @endforeach
              </div><!-- /end collapse settings -->
              @endif

              @if (auth()->user()->hasPermission('profiles_social'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/profiles-social') }}" class="nav-link text-truncate @if (request()->is('panel/admin/profiles-social')) active @endif">
                      <i class="bi-share me-2"></i> {{ __('admin.profiles_social') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('social_login'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/social-login') }}" class="nav-link text-truncate @if (request()->is('panel/admin/social-login')) active @endif">
                      <i class="bi-facebook me-2"></i> {{ __('admin.social_login') }}
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('google'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/google') }}" class="nav-link text-truncate @if (request()->is('panel/admin/google')) active @endif">
                      <i class="bi-google me-2"></i> Google
                  </a>
              </li><!-- /end list -->
              @endif

              @if (auth()->user()->hasPermission('pwa'))
              <li class="nav-item">
                  <a href="{{ url('panel/admin/pwa') }}" class="nav-link text-truncate @if (request()->is('panel/admin/pwa')) active @endif">
                      <i class="bi-phone me-2"></i> PWA
                  </a>
              </li><!-- /end list -->
              @endif

          </ul>
      </div>
  </div>

  <header class="py-3 mb-3 shadow-custom">

    <div class="container-fluid d-grid gap-3 px-4 justify-content-end position-relative">

      <div class="d-flex align-items-center">

        <a class="text-dark ms-2 animate-up-2 me-4" href="{{ url('/') }}">
        {{ __('admin.view_site') }} <i class="bi-arrow-up-right"></i>
        </a>

        <div class="flex-shrink-0 dropdown">
          <a href="#" class="d-block link-dark text-decoration-none" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
           <img src="{{ Helper::getFile(config('path.avatar').auth()->user()->avatar) }}" width="32" height="32" class="rounded-circle">
          </a>
          <ul class="dropdown-menu dropdown-menu-macos arrow-dm" aria-labelledby="dropdownUser2">
            <a class="dropdown-item" href="{{ url(auth()->user()->username) }}">
              <i class="bi-person me-2"></i> {{ __('users.my_profile') }}
              </a>

              <a class="dropdown-item" href="{{ url('settings/page') }}">
                <i class="bi-pencil me-2"></i> {{ __('general.edit_my_page') }}
                </a>

                <hr class="dropdown-divider"></hr>

                <a class="dropdown-item" href="{{ url('logout') }}">
                  <i class="bi-box-arrow-in-right me-2"></i> {{ __('users.logout') }}
                  </a>
          </ul>
        </div>

        <a class="ms-4 toggle-menu d-block d-lg-none text-dark fs-3 position-absolute start-0" data-bs-toggle="offcanvas" data-bs-target="#sidebar-nav" href="#">
            <i class="bi-list"></i>
            </a>
      </div>
    </div>
  </header>

  <div class="container-fluid">
      <div class="row">
          <div class="col min-vh-100 admin-container p-4">
              @yield('content')
          </div>
      </div>
  </div>

  <footer class="admin-footer px-4 py-3 shadow-custom">
    &copy; {{ $settings->title }} v{{$settings->version}} - {{ date('Y') }}
  </footer>

</main>

    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{ asset('public/js/core.min.js') }}?v={{$settings->version}}"></script>
    <script src="{{ asset('public/admin/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{ asset('public/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/admin/admin-functions.js') }}?v={{$settings->version}}"></script>

    @yield('javascript')

    @if (session('success_update'))
      <script type="text/javascript">
          swal({
            title: "{{ session('success_update') }}",
            type: "success",
            confirmButtonText: "{{ __('users.ok') }}"
            });
        </script>
    	 @endif

		 @if (session('unauthorized'))
       <script type="text/javascript">
    		swal({
    			title: "{{ __('general.error_oops') }}",
    			text: "{{ session('unauthorized') }}",
    			type: "error",
    			confirmButtonText: "{{ __('users.ok') }}"
    			});
          </script>
   		 @endif
     </body>
</html>
