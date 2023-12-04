@extends('layouts.app')

@section('title') {{trans('general.restricted_users')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="feather icon-slash mr-2"></i> {{trans('general.restricted_users')}}</h2>
          <p class="lead text-muted mt-0">{{trans('general.info_restricted_users')}}</p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          @if ($restrictions->count() != 0)

            @if (session('message'))
            <div class="alert alert-success mb-3">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
              </button>
              <i class="fa fa-check mr-1"></i> {{ session('message') }}
            </div>
            @endif

            @if (session('error_message'))
            <div class="alert alert-danger mb-3">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
              </button>
              <i class="fa fa-check mr-1"></i> {{ session('error_message') }}
            </div>
            @endif

          <div class="card shadow-sm">
          <div class="table-responsive">
            <table class="table table-striped m-0">
              <thead>
                <tr>
                  <th scope="col">{{trans('general.user')}}</th>
                  <th scope="col">{{trans('admin.date')}}</th>
                  <th scope="col">{{trans('admin.actions')}}</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($restrictions as $restriction)
                  <tr>
                      <td>
                        @if (! isset($restriction->userRestricted()->username))
                          {{ trans('general.no_available') }}
                        @else
                        <a href="{{ url($restriction->userRestricted()->username) }}">
                          <img src="{{Helper::getFile(config('path.avatar').$restriction->userRestricted()->avatar)}}" width="40" height="40" class="rounded-circle mr-2">

                          {{ '@'.$restriction->userRestricted()->username }}
                        </a>
                      @endif
                      </td>
                    <td>{{Helper::formatDate($restriction->created_at)}}</td>

                    <td>
                      <button title="" class="btn btn-danger btn-sm-custom removeRestriction" type="button" data-user="{{$restriction->userRestricted()->id}}" id="restrictUser">
                        <i class="bi-trash"></i> {{ __('general.remove_restriction') }}
                      </button>
                    </td>
                  </tr>
                @endforeach

              </tbody>
            </table>
          </div>
          </div><!-- card -->

            @if ($restrictions->hasPages())
              <div class="mt-2">
    			    	{{ $restrictions->onEachSide(0)->links() }}
                </div>
    			    	@endif

        @else
          <div class="my-5 text-center">
            <span class="btn-block mb-3">
              <i class="feather icon-slash ico-no-result"></i>
            </span>
            <h4 class="font-weight-light">{{trans('general.no_results_found')}}</h4>
          </div>
        @endif

        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection
