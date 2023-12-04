@extends('layouts.app')

@section('title') {{__('general.live_streaming_private_requests')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="bi-box-arrow-in-down mr-2"></i> {{__('general.live_streaming_private_requests')}}</h2>
          <p class="lead text-muted mt-0">{{__('general.subtitle_live_streaming_private_requests')}}</p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          @if ($lives->count() != 0)
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
                    <th class="active">{{__('general.buyer')}}</th>
                    <th class="active text-capitalize">{{__('general.minutes')}}</th>
                    <th class="active">{{__('general.price')}}</th>
                    <th class="active">{{__('admin.status')}}</th>
                    <th class="active">{{__('admin.date')}}</th>
                  <th scope="col">{{__('admin.actions')}}</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($lives as $live)
                  <tr>
                    <td>
                        @if (! isset($live->user->username))
                        {{ __('general.no_available') }}
                        @else
                        <a href="{{ url($live->user->username) }}" target="_blank">
                        {{ $live->user->name }} <i class="bi-box-arrow-up-right"></i> 
                        
                            @if (!$live->status->value)
                                <span class="badge badge-pill badge-{{ Helper::isOnline($live->user->id) ? 'success' : 'danger' }}">
                                    {{ Helper::isOnline($live->user->id) ? __('general.online') : __('general.offline')  }}
                                </span>
                                </a>
                            @endif
                        @endif
                    </td>
                    <td>
                        {{ $live->minutes }}
                    </td>
                    <td>{{ Helper::amountFormatDecimal($live->transaction->amount) }}</td>
                    <td>
                        <span class="badge badge-pill badge-{{ $live->status->label()}} text-uppercase">
                            {{ $live->status->locale()}}
                        </span>
                    </td>
                    <td>{{Helper::formatDate($live->created_at)}}</td>

                    <td>
                        <div class="d-flex">
                          @if (!$live->status->value)
                            <form class="d-inline-block" method="post" action="{{ route('live.accept', ['live' => $live->id]) }}">
                              @csrf
                              <button @disabled(!Helper::isOnline($live->user->id)) type="submit" title="{{ !Helper::isOnline($live->user->id) ? __('general.user_online_accept_request') : __('general.accept_request') }}" class="mr-2 btn btn-success btn-sm-custom actionAcceptLive">
                                <i class="bi-check2"></i>
                              </button>
                            </form>
  
                            <form class="d-inline-block" method="post" action="{{ route('live.reject', ['live' => $live->id]) }}">
                              @csrf
                              <button title="{{ __('general.reject_request') }}" class="btn btn-danger btn-sm-custom actionAcceptReject rejectLiveRequest" type="button">
                                <i class="bi-x-lg"></i>
                              </button>
                            </form>
                            </div>

                            @else
                            {{ __('general.not_applicable') }}
                          @endif
                      </td>

                </tr>
                @endforeach

              </tbody>
            </table>
          </div>
          </div><!-- card -->
          <small class="w-100 d-block mt-2">{{ __('general.info_live_streaming_private_requests') }}</small>

            @if ($lives->hasPages())
              <div class="mt-2">
                {{ $lives->onEachSide(0)->links() }}
            </div>
    		@endif

        @else
          <div class="my-5 text-center">
            <span class="btn-block mb-3">
              <i class="bi-box-arrow-in-down ico-no-result"></i>
            </span>
            <h4 class="font-weight-light">{{__('general.no_results_found')}}</h4>
          </div>
        @endif

        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection
