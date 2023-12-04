@if (! request()->get('q') || request()->get('q') && strlen(request()->get('q')) <= 2)

  <button type="button" class="btn-menu-expand btn btn-primary btn-block mb-4 d-lg-none" type="button" data-toggle="collapse" data-target="#navbarFilters" aria-controls="navbarCollapse" aria-expanded="false">
      <i class="bi bi-filter-right mr-2"></i> {{trans('general.filter_by')}}
    </button>

<div class="navbar-collapse collapse d-lg-block" id="navbarFilters">
<div class="btn-block mb-3">
  <span>

    <span class="category-filter d-lg-block d-none">
    <i class="bi bi-filter-right mr-2"></i> {{trans('general.filter_by')}}
    </span>

    <a class="text-muted btn btn-sm bg-white border mb-2 e-none btn-category @if (request()->is('creators') || isset($isCategory) && request()->is('category/'.$slug.''))active-category @endif" href="{{ isset($isCategory) ? url('category', $slug) : url('creators')}}">
      <img src="{{url('public/img/popular.png')}}" class="mr-2" width="30" /> {{trans('general.popular')}}
    </a>

    <a class="text-muted btn btn-sm bg-white border mb-2 e-none btn-category @if (request()->is('creators/featured') || isset($isCategory) && request()->is('category/'.$slug.'/featured'))active-category @endif" href="{{ isset($isCategory) ? url('category/'.$slug.'','featured') : url('creators/featured')}}">
      <img src="{{url('public/img/featured.png')}}" class="mr-2" width="30" /> {{trans('general.featured_creators')}}
    </a>

    <a class="text-muted btn btn-sm bg-white border mb-2 e-none btn-category @if (request()->is('creators/more-active') || isset($isCategory) && request()->is('category/'.$slug.'/more-active'))active-category @endif" href="{{ isset($isCategory) ? url('category/'.$slug.'','more-active') : url('creators/more-active')}}">
      <img src="{{url('public/img/more-active.png')}}" class="mr-2" width="30" /> {{trans('general.more_active')}}
    </a>

    <a class="text-muted btn btn-sm bg-white border mb-2 e-none btn-category @if (request()->is('creators/new') || isset($isCategory) && request()->is('category/'.$slug.'/new'))active-category @endif" href="{{ isset($isCategory) ? url('category/'.$slug.'','new') : url('creators/new')}}">
      <img src="{{url('public/img/creators.png')}}" class="mr-2" width="30" />  {{trans('general.new_creators')}}
    </a>

    <a class="text-muted btn btn-sm bg-white border mb-2 e-none btn-category @if (request()->is('creators/free') || isset($isCategory) && request()->is('category/'.$slug.'/free'))active-category @endif" href="{{ isset($isCategory) ? url('category/'.$slug.'','free') : url('creators/free')}}">
      <img src="{{url('public/img/unlock.png')}}" class="mr-2" width="30" /> {{trans('general.free_subscription')}}
    </a>

    @if ($settings->search_creators_genders)
    <a class="text-muted btn btn-sm bg-white border mb-2 e-none btn-category" href="javascript:;" data-toggle="modal" data-target="#filterGendersAge">
      <img src="{{url('public/img/genders.png')}}" class="mr-2" width="30" /> {{trans('general.gender_age')}}
    </a>
  @endif

    @if ($settings->live_streaming_status == 'on')
      <a class="text-muted btn btn-sm bg-white border mb-2 e-none btn-category @if(request()->is('explore/creators/live'))active-category @endif" href="{{url('explore/creators/live')}}">
        <img src="{{url('public/img/live.png')}}" class="mr-2" width="30" /> {{trans('general.live')}}
      </a>
    @endif
  </span>
</div>
</div>

@if ($settings->search_creators_genders)
<div class="modal fade" id="filterGendersAge" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
  <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-body p-0">
        <div class="card bg-white shadow border-0">

          <div class="card-body px-lg-5 py-lg-5">

            <div class="mb-3">
              <h6 class="position-relative">{{trans('general.filter_by_gender_age')}}
                <small data-dismiss="modal" class="btn-cancel-msg"><i class="bi bi-x-lg"></i></small>
              </h6>
            </div>

            <form method="GET" action="{{ url()->current() }}">

              <div class="row">
                <div class="col-md-12">
                  <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-venus-mars"></i></span>
                  </div>
                  <select name="gender" class="form-control custom-select">
                    <option selected="selected" value="all">{{ __('general.all_genders') }}</option>
                    @foreach ($genders = explode(',', $settings->genders) as $gender)
                      <option @if (request('gender') == $gender) selected="selected" @endif value="{{$gender}}">{{ __('general.'.$gender) }}</option>
                    @endforeach
                    </select>
                    </div>
                  </div><!-- ./col-md-12 -->
              </div><!-- row -->

              <div class="row form-group mb-0">
                <div class="col-6">
                    <div class="input-group mb-4">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="bi-dash-lg"></i></span>
                      </div>
                      <input class="form-control" min="18" name="min_age" placeholder="{{trans('general.min_age')}}"  value="{{ request('min_age') }}" type="number">
                    </div>
                  </div><!-- ./col-md-6 -->

                  <div class="col-6">
                      <div class="input-group mb-4">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="bi-plus-lg"></i></span>
                        </div>
                        <input class="form-control" name="max_age" placeholder="{{trans('general.max_age')}}"  value="{{ request('max_age') }}" type="number">
                      </div>
                    </div><!-- ./col-md-6 -->
                  </div><!-- row -->

              <button type="submit" class="btn btn-primary btn-sm mt-3 w-100">
                {{trans('general.search')}}
              </button>

          </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div><!-- End Modal Filter by genders -->
@endif

@endif
