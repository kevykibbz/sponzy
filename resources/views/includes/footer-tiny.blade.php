<div class="card border-0 bg-transparent">
  <div class="card-body p-0">
    <small class="text-muted">
      &copy; {{date('Y')}} {{$settings->title}}, {{__('emails.rights_reserved')}}

      @if ($settings->show_address_company_footer)
          {{ $settings->company }} - {{ __('general.address') }}: {{ $settings->address }} {{ $settings->city }} {{ $settings->country }}
        @endif
    </small>

    <ul class="list-inline mb-0 small">

      @foreach (Helper::pages() as $page)
        @if ($page->access == 'all')

          <li class="list-inline-item">
            <a class="link-footer footer-tiny" href="{{ url('/p', $page->slug) }}">
              {{ $page->title }}
            </a>
          </li>

        @elseif ($page->access == 'creators' && auth()->check() && auth()->user()->verified_id == 'yes')
          <li class="list-inline-item">
            <a class="link-footer footer-tiny" href="{{ url('/p', $page->slug) }}">
              {{ $page->title }}
            </a>
          </li>

        @elseif ($page->access == 'members' && auth()->check())
          <li class="list-inline-item">
            <a class="link-footer footer-tiny" href="{{ url('/p', $page->slug) }}">
              {{ $page->title }}
            </a>
          </li>
        @endif

      @endforeach

      @if (! $settings->disable_contact)
        <li class="list-inline-item"><a class="link-footer footer-tiny" href="{{ url('contact') }}">{{ trans('general.contact') }}</a></li>
    @endif

      @if ($blogsCount != 0)
      <li class="list-inline-item"><a class="link-footer footer-tiny" href="{{ url('blog') }}">{{ trans('general.blog') }}</a></li>
    @endif

    @guest
      @if ($languages->count() > 1)
    <div class="btn-group dropup d-inline">
      <li class="list-inline-item">
        <a class="link-footer dropdown-toggle text-decoration-none footer-tiny" href="javascript:;" data-toggle="dropdown">
          <i class="feather icon-globe"></i>
          @foreach ($languages as $language)
            @if( $language->abbreviation == config('app.locale') ) {{ $language->name }}  @endif
          @endforeach
      </a>

      <div class="dropdown-menu">
        @foreach ($languages as $language)
          <a @if ($language->abbreviation != config('app.locale')) href="{{ url('change/lang', $language->abbreviation) }}" @endif class="dropdown-item mb-1 @if( $language->abbreviation == config('app.locale') ) active text-white @endif">
          @if ($language->abbreviation == config('app.locale')) <i class="fa fa-check mr-1"></i> @endif {{ $language->name }}
          </a>
          @endforeach
      </div>
      </li>
    </div><!-- dropup -->
      @endif
    @endguest

    <li class="list-inline-item">
      <div id="installContainer" class="display-none">
        <a class="link-footer footer-tiny" id="butInstall" href="javascript:void(0);">
          <i class="bi-phone"></i> {{ __('general.install_web_app') }}
        </a>
      </div>
    </li>
    </ul>
  </div>
</div>
