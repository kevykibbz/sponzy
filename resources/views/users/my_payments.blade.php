@extends('layouts.app')

@section('title') {{trans('general.payments')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="bi bi-receipt mr-2"></i> {{trans('general.payments')}}</h2>
          @if (request()->is('my/payments'))
          <p class="lead text-muted mt-0">{{trans('general.my_payments_subtitle')}}</p>
        @else
          <p class="lead text-muted mt-0">{{trans('general.my_payments_received_subtitle')}}</p>
        @endif
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          @if ($transactions->count() != 0 && auth()->user()->verified_id == 'yes')

            <div class="btn-block mb-3 text-right">
              <span>
                {{trans('general.filter_by')}}

                <select class="ml-2 custom-select w-auto" id="filter">
                    <option @if (request()->is('my/payments')) selected @endif value="{{url('my/payments')}}">{{trans('general.payments_made')}}</option>
                    <option @if (request()->is('my/payments/received')) selected @endif value="{{url('my/payments/received')}}">{{trans('general.payments_received')}}</option>
                  </select>
              </span>
            </div>
          @endif

        @if ($transactions->count())
            @if (session('error_message'))
            <div class="alert alert-danger mb-3">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
              </button>

              <i class="fa fa-exclamation-triangle mr-2"></i> {{ trans('general.please_complete_all') }}
              <a href="{{ url('settings/page') }}#billing" class="text-white link-border">{{ trans('general.billing_information') }}</a>
            </div>
            @endif

        <div class="card shadow-sm">
          <div class="table-responsive">
            <table class="table table-striped m-0">
              <thead>
                <tr>
                  <th scope="col">ID</th>
                @if (request()->is('my/payments'))
                  <th scope="col">{{trans('general.paid_to')}}</th>
                  <th scope="col">{{trans('general.payment_gateway')}}</th>
                @endif
                  <th scope="col">{{trans('admin.date')}}</th>
                  <th scope="col">{{trans('admin.amount')}}</th>
                  <th scope="col">{{trans('admin.type')}}</th>
                  @if (request()->is('my/payments/received'))
                    <th scope="col">{{trans('general.paid_by')}}</th>
                    <th scope="col">{{trans('general.earnings')}}</th>
                  @endif
                  <th scope="col">{{trans('admin.status')}}</th>
                  @if (request()->is('my/payments'))
                  <th> {{trans('general.invoice')}}</th>
                @endif
                </tr>
              </thead>

              <tbody>

                @foreach ($transactions as $transaction)
                  <tr>
                    <td>{{ str_pad($transaction->id, 4, "0", STR_PAD_LEFT) }}</td>
                    @if (request()->is('my/payments'))
                    <td>{{ $transaction->subscribed()->username ?? trans('general.no_available')}}</td>
                    <td>{{ $transaction->payment_gateway }}</td>
                    @endif
                    <td>{{ Helper::formatDate($transaction->created_at) }}</td>
                    <td>{{ Helper::amountFormatDecimal($transaction->amount) }}</td>
                    <td>{{ __('general.'.$transaction->type) }}</td>
                    @if (request()->is('my/payments/received'))
                      <td>{{ $transaction->user()->username ?? trans('general.no_available') }}</td>
                    <td>
                      {{ Helper::amountFormatDecimal($transaction->earning_net_user) }}

                      @if ($transaction->percentage_applied)
                        <a tabindex="0" role="button" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="{{trans('general.percentage_applied')}} {{ $transaction->percentage_applied }} {{trans('general.platform')}} @if ($transaction->direct_payment) ({{ __('general.direct_payment') }}) @endif">
                          <i class="far fa-question-circle"></i>
                        </a>

                      @endif
                    </td>
                    @endif
                    <td>
                      @if ($transaction->approved == '1')
                        <span class="badge badge-pill badge-success text-uppercase">{{trans('general.success')}}</span>
                      @elseif ($transaction->approved == '2')
                        <span class="badge badge-pill badge-danger text-uppercase">{{trans('general.canceled')}}</span>
                        @if (request()->is('my/payments/received'))
                          <a tabindex="0" role="button" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="{{trans('general.payment_canceled')}}">
                        <i class="far fa-question-circle"></i>
                      </a>
                        @endif
                      @else
                        <span class="badge badge-pill badge-warning text-uppercase">{{trans('general.pending')}}</span>
                      @endif
                    </td>
                    @if (request()->is('my/payments'))
                    <td>
                      @if ($transaction->approved == '1')
                      <a href="{{url('payments/invoice', $transaction->id)}}" target="_blank"><i class="far fa-file-alt"></i> {{trans('general.invoice')}}</a>
                    </td>
                  @else
                    {{trans('general.no_available')}}
                      @endif
                    @endif
                  </tr>
                @endforeach

              </tbody>
            </table>
          </div>
          </div><!-- card -->

          @if ($transactions->hasPages())
  			    	{{ $transactions->onEachSide(0)->links() }}
  			    	@endif

        @else
          <div class="my-5 text-center">
            <span class="btn-block mb-3">
              <i class="bi bi-receipt ico-no-result"></i>
            </span>
            @if (request()->is('my/payments'))
            <h4 class="font-weight-light">{{trans('general.not_payment_made')}}</h4>
          @else
            <h4 class="font-weight-light">{{trans('general.not_payment_received')}}</h4>
          @endif
          </div>
        @endif

        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection
