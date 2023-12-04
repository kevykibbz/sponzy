@extends('layouts.app')

@section('title') {{trans('general.withdrawals')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="bi bi-arrow-left-right mr-2"></i> {{trans('general.withdrawals')}}</h2>
          <p class="lead text-muted mt-0">{{trans('general.history_withdrawals')}}</p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          @include('errors.errors-forms')

        @if (auth()->user()->payment_gateway == '')
          <div class="alert alert-warning alert-dismissible" role="alert">
          <span class="alert-inner--text"><i class="far fa-credit-card mr-2"></i> {{trans('users.please_select_a')}}
            <a href="{{url('settings/payout/method')}}" class="text-white link-border">{{trans('users.payout_method')}}</a>
          </span>
        </div>
        @endif

            <div class="row">
              <div class="col-md-12">

                @php
                  $datePaid = Withdrawals::select('date')
                      ->where('user_id', auth()->user()->id)
                      ->where('status','pending')
                      ->orderBy('id','desc')
                      ->first();
                @endphp

                <div class="alert alert-primary overflow-hidden" role="alert">

                  <div class="inner-wrap">
                    <h4>
                      <small>{{trans('general.balance')}}:</small> {{Helper::amountFormatDecimal(auth()->user()->balance)}} <small>{{$settings->currency_code}}</small>
                    </h4>

                    <i class="fa fa-info-circle mr-2"></i>

                    @if (! $datePaid)
                      <span>
                        {{trans('general.amount_min_withdrawal')}} <strong>{{Helper::amountWithoutFormat($settings->amount_min_withdrawal)}} {{$settings->currency_code}}</strong>

                        @if ($settings->amount_max_withdrawal)
                         - ({{ __('general.maximum') }}) <strong>{{Helper::amountWithoutFormat($settings->amount_max_withdrawal)}} {{$settings->currency_code}}</strong>
                        @endif
                  @endif

                  @if ($datePaid)
                    @if (! $settings->specific_day_payment_withdrawals)
                      {{trans('users.date_paid')}} <strong>{{Helper::formatDate(Carbon\Carbon::parse($datePaid->date)->addWeekdays($settings->days_process_withdrawals))}}</strong>

                    @else
                      {{ trans('users.date_paid') }} <strong>{{ Helper::formatDate(Helper::paymentDateOfEachMonth($settings->specific_day_payment_withdrawals)) }}</strong>
                    @endif
                  @endif

                  <small class="btn-block">
                    @if (! $settings->specific_day_payment_withdrawals)
                      * {{ trans('general.payment_process_days', ['days' => $settings->days_process_withdrawals]) }}

                    @elseif (! $datePaid)
                      * {{ trans('users.date_paid') }} {{ Helper::formatDate(Helper::paymentDateOfEachMonth($settings->specific_day_payment_withdrawals)) }}
                    @endif
                  </small>

                  </span>
                  </div>

                <span class="icon-wrap"><i class="bi bi-arrow-left-right"></i></span>

              </div><!-- /alert -->

                <h5>

                  @if (auth()->user()->balance >= $settings->amount_min_withdrawal
                      && auth()->user()->payment_gateway != ''
                      && auth()->user()->withdrawals()
                      ->where('status','pending')
                      ->count() == 0)

                  {!! Form::open([
                   'method' => 'POST',
                   'url' => "settings/withdrawals",
                   'class' => 'd-inline'
                 ]) !!}

                 @if ($settings->type_withdrawals == 'custom')
                   <div class="form-group mt-3">
                     <div class="input-group mb-2">
                     <div class="input-group-prepend">
                       <span class="input-group-text">{{$settings->currency_symbol}}</span>
                     </div>
                         <input class="form-control form-control-lg isNumber" autocomplete="off" name="amount" placeholder="{{trans('admin.amount')}}" type="text">
                     </div>
                   </div><!-- End form-group -->
                 @endif

                  {!! Form::submit(trans('general.make_withdrawal'), ['class' => 'btn btn-1 btn-success mb-2 saveChanges']) !!}
                  {!! Form::close() !!}
                @else
                  <button class="btn btn-1 btn-success mb-2 disabled e-none">{{trans('general.make_withdrawal')}}</button>
                @endif

                </h5>

              </div><!-- col-md-12 -->
            </div>

          @if ($withdrawals->count() != 0)
          <div class="card shadow-sm">
          <div class="table-responsive">
            <table class="table table-striped m-0">
              <thead>
                <tr>
                  <th scope="col">{{trans('admin.amount')}}</th>
                  <th scope="col">{{trans('admin.method')}}</th>
                  <th scope="col">{{trans('admin.date')}}</th>
                  <th scope="col">{{trans('admin.status')}}</th>
                  <th scope="col">{{trans('admin.actions')}}</th>
                </tr>
              </thead>

              <tbody>

                @foreach ($withdrawals as $withdrawal)
                  <tr>
                    <td>{{Helper::amountFormatDecimal($withdrawal->amount)}}</td>
                    <td>{{$withdrawal->gateway == 'Bank' ? trans('general.bank') : $withdrawal->gateway}}</td>
                    <td>{{Helper::formatDate($withdrawal->date)}}
                    </td>
                    <td>@if ( $withdrawal->status == 'paid' )
                    <span class="badge badge-pill badge-success text-uppercase">{{trans('general.paid')}}</span>
                    @else
                    <span class="badge badge-pill badge-warning text-uppercase">{{trans('general.pending_to_pay')}}</span>
                    @endif
                  </td>
                    <td>

                  @if ($withdrawal->status != 'paid' && Carbon\Carbon::parse($withdrawal->estimated_payment)->shortAbsoluteDiffForHumans() <> '5d')
                      {!! Form::open([
                        'method' => 'POST',
                        'url' => "delete/withdrawal/$withdrawal->id",
                        'class' => 'd-inline'
                      ]) !!}

                      {!! Form::button(trans('general.delete'), ['class' => 'btn btn-danger btn-sm deleteW p-1 px-2']) !!}
                      {!! Form::close() !!}

                  @elseif ($withdrawal->status != 'paid' && Carbon\Carbon::parse($withdrawal->estimated_payment)->shortAbsoluteDiffForHumans() == '5d')

                    {{ trans('general.in_process') }}
                  @else

                  {{trans('general.paid')}} - {{Helper::formatDate($withdrawal->date_paid)}}

                  @endif
                  </td>
                </tr>
                @endforeach

              </tbody>
            </table>
          </div>
          </div><!-- card -->

          @if ($withdrawals->hasPages())
            {{ $withdrawals->links() }}
          @endif

        @endif
        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection
