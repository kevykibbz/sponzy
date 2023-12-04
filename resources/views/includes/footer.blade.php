<!-- FOOTER -->
<div class="py-5 @auth d-none d-lg-block @endauth @if (auth()->check() && auth()->user()->dark_mode == 'off' || auth()->guest() ) footer_background_color footer_text_color @else bg-white @endif @if (auth()->check() && auth()->user()->dark_mode == 'off' && $settings->footer_background_color == '#ffffff' || auth()->guest() && $settings->footer_background_color == '#ffffff' ) border-top @endif">
<footer class="container">
  <div class="row">
    <div class="col-md-3">
      <a href="{{url('/')}}">
        @if (auth()->check() && auth()->user()->dark_mode == 'on')
          <img src="{{url('public/img', $settings->logo)}}" alt="{{$settings->title}}" class="max-w-125">
        @else
          <img src="{{url('public/img', $settings->logo_2)}}" alt="{{$settings->title}}" class="max-w-125">
      @endif
      </a>
      @if ($settings->facebook != ''
          || $settings->twitter != ''
          || $settings->instagram != ''
          || $settings->pinterest != ''
          || $settings->youtube != ''
          || $settings->github != ''
          || $settings->tiktok != ''
          || $settings->snapchat != ''
          || $settings->telegram != ''
          || $settings->reddit != ''
          || $settings->linkedin != ''
          || $settings->threads != ''
          )
      <div class="w-100">
        <span class="w-100">{{trans('general.keep_connect_with_us')}} {{trans('general.follow_us_social')}}</span>
        <ul class="list-inline list-social m-0">
          @if ($settings->twitter != '')
          <li class="list-inline-item"><a href="{{$settings->twitter}}" target="_blank" class="ico-social"><i class="bi-twitter-x"></i></a></li>
        @endif

        @if ($settings->facebook != '')
          <li class="list-inline-item"><a href="{{$settings->facebook}}" target="_blank" class="ico-social"><i class="fab fa-facebook"></i></a></li>
          @endif

          @if ($settings->instagram != '')
          <li class="list-inline-item"><a href="{{$settings->instagram}}" target="_blank" class="ico-social"><i class="fab fa-instagram"></i></a></li>
        @endif

          @if ($settings->pinterest != '')
          <li class="list-inline-item"><a href="{{$settings->pinterest}}" target="_blank" class="ico-social"><i class="fab fa-pinterest"></i></a></li>
          @endif

          @if ($settings->youtube != '')
          <li class="list-inline-item"><a href="{{$settings->youtube}}" target="_blank" class="ico-social"><i class="fab fa-youtube"></i></a></li>
          @endif

          @if ($settings->github != '')
          <li class="list-inline-item"><a href="{{$settings->github}}" target="_blank" class="ico-social"><i class="fab fa-github"></i></a></li>
          @endif

          @if ($settings->tiktok != '')
          <li class="list-inline-item"><a href="{{$settings->tiktok}}" target="_blank" class="ico-social"><i class="bi-tiktok"></i></a></li>
          @endif

          @if ($settings->snapchat != '')
          <li class="list-inline-item"><a href="{{$settings->snapchat}}" target="_blank" class="ico-social"><i class="bi-snapchat"></i></a></li>
          @endif

          @if ($settings->telegram != '')
          <li class="list-inline-item"><a href="{{$settings->telegram}}" target="_blank" class="ico-social"><i class="bi-telegram"></i></a></li>
          @endif

          @if ($settings->reddit != '')
          <li class="list-inline-item"><a href="{{$settings->reddit}}" target="_blank" class="ico-social"><i class="bi-reddit"></i></a></li>
          @endif

          @if ($settings->linkedin != '')
          <li class="list-inline-item"><a href="{{$settings->linkedin}}" target="_blank" class="ico-social"><i class="bi-linkedin"></i></a></li>
          @endif

          @if ($settings->threads != '')
          <li class="list-inline-item"><a href="{{$settings->threads}}" target="_blank" class="ico-social"><i class="bi-threads"></i></a></li>
          @endif
        </ul>
      </div>
    @endif

    <li>
      <div id="installContainer" class="display-none">
        <button class="btn btn-primary w-100 rounded-pill mb-4 mt-3" id="butInstall" type="button">
          <i class="bi-phone mr-1"></i> {{ __('general.install_web_app') }}
        </button>
      </div>
    </li>

    </div>
    <div class="col-md-3">
      <h6 class="text-uppercase">@lang('general.about')</h6>
      <ul class="list-unstyled">
        @foreach (Helper::pages() as $page)

      @if ($page->access == 'all')

          <li>
            <a class="link-footer" href="{{ url('/p', $page->slug) }}">
              {{ $page->title }}
            </a>
        </li>

      @elseif ($page->access == 'creators' && auth()->check() && auth()->user()->verified_id == 'yes')
          <li>
            <a class="link-footer" href="{{ url('/p', $page->slug) }}">
              {{ $page->title }}
            </a>
        </li>

      @elseif ($page->access == 'members' && auth()->check())
          <li>
            <a class="link-footer" href="{{ url('/p', $page->slug) }}">
              {{ $page->title }}
            </a>
        </li>
      @endif

    @endforeach

      @if (! $settings->disable_contact)
        <li><a class="link-footer" href="{{ url('contact') }}">{{ trans('general.contact') }}</a></li>
      @endif


        @if ($blogsCount != 0)
          <li><a class="link-footer" href="{{ url('blog') }}">{{ trans('general.blog') }}</a></li>
        @endif
      </ul>
    </div>
    @if ($categoriesCount != 0)
    <div class="col-md-3">
      <h6 class="text-uppercase">@lang('general.categories')</h6>
      <ul class="list-unstyled">
        @foreach ($categoriesFooter as $category)
        <li>
          <a class="link-footer" href="{{ url('category', $category->slug) }}">
            {{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}
          </a>
        </li>
        @endforeach

        @if ($categoriesCount > 6)
          <li><a class="link-footer btn-arrow" href="{{ url('creators') }}">{{ trans('general.explore') }}</a></li>
          @endif
      </ul>
    </div>
  @endif
    <div class="col-md-3">
      <h6 class="text-uppercase">@lang('general.links')</h6>
      <ul class="list-unstyled">
      @guest
        <li><a class="link-footer" href="{{$settings->home_style == 0 ? url('login') : url('/')}}">{{ trans('auth.login') }}</a></li><li>
          @if ($settings->registration_active == '1')
        <li><a class="link-footer" href="{{$settings->home_style == 0 ? url('signup') : url('/')}}">{{ trans('auth.sign_up') }}</a></li><li>
        @endif
        @else
          <li><a class="link-footer url-user" href="{{ url(auth()->User()->username) }}">{{ auth()->user()->verified_id == 'yes' ? trans('general.my_page') : trans('users.my_profile') }}</a></li><li>
          <li><a class="link-footer" href="{{ url('settings/page') }}">{{ auth()->user()->verified_id == 'yes' ? trans('general.edit_my_page') : trans('users.edit_profile')}}</a></li><li>
          <li><a class="link-footer" href="{{ url('my/subscriptions') }}">{{ trans('users.my_subscriptions') }}</a></li><li>
          <li><a class="link-footer" href="{{ url('logout') }}">{{ trans('users.logout') }}</a></li><li>
      @endguest

      @guest
        @if ($languages->count() > 1)
        <li class="dropdown mt-1">
          <a class="btn btn-outline-secondary rounded-pill mt-2 dropdown-toggle px-4 dropdown-toggle text-decoration-none" href="javascript:;" data-toggle="dropdown">
            <i class="feather icon-globe mr-1"></i>
            @foreach ($languages as $language)
              @if ($language->abbreviation == config('app.locale') ) {{ $language->name }}  @endif
            @endforeach
        </a>

        <div class="dropdown-menu">
          @foreach ($languages as $language)
            <a @if ($language->abbreviation != config('app.locale')) href="{{ url('change/lang', $language->abbreviation) }}" @endif class="dropdown-item mb-1 dropdown-lang @if( $language->abbreviation == config('app.locale') ) active text-white @endif">
            @if ($language->abbreviation == config('app.locale')) <i class="fa fa-check mr-1"></i> @endif {{ $language->name }}
            </a>
            @endforeach
        </div>
        </li>
      @endif
    @endguest

      </ul>
    </div>
  </div>
</footer>
</div>

<footer class="py-3 @if (auth()->check() && auth()->user()->dark_mode == 'off' || auth()->guest() ) footer_background_color @endif text-center">
  <div class="container">
    <div class="row">
    @auth
      <div class="d-lg-none d-block pb-5 mb-2">
        @include('includes.footer-tiny')
      </div>
    @endauth
      <div class="col-md-12 copyright @auth d-none d-lg-block @endauth">
        &copy; {{date('Y')}} {{$settings->title}}, {{__('emails.rights_reserved')}}

        @if ($settings->show_address_company_footer)
        <small class="ml-2">
          {{ $settings->company }} - {{ __('general.address') }}: {{ $settings->address }} {{ $settings->city }} {{ $settings->country }}
        </small>
        @endif
      </div>
    </div>
  </div>
</footer>
