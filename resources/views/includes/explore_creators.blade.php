<div class="mb-2">
  <h6 class="mb-3 text-muted font-weight-light filter-explorer">
    {{__('general.explore_creators')}}

@auth
  @if ($users->count() > 2)
    <a href="javascript:void(0);" class="float-right h5 text-decoration-none refresh_creators refresh-btn mr-2">
      <i class="feather icon-refresh-cw"></i>
    </a>

    <a href="javascript:void(0);" class="float-right h5 text-decoration-none refresh_creators toggleFindFreeCreators btn-tooltip mr-3" data-toggle="tooltip" data-placement="top" title="{{ __('general.show_only_free') }}">
      <i class="feather icon-tag"></i>
    </a>
    @endif

  @else
    <a href="{{url('creators')}}" class="float-right">{{ __('general.view_all') }} <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small></a>
@endauth
  </h6>

  <ul class="list-group">
    <div class="containerRefreshCreators">
      @include('includes.listing-explore-creators')
    </div><!-- containerRefreshCreators -->
  </ul>
</div><!-- d-lg-none -->
