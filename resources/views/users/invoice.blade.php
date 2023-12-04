<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <title>{{__('general.invoice')}} #{{str_pad($data->id, 4, "0", STR_PAD_LEFT)}}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    @include('includes.css_admin')

    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <link rel="shortcut icon" href="{{ url('public/img', $settings->favicon) }}" />
  </head>

  <body class="bg-light">
    <div class="wrapper">
  <!-- Main content -->
  <section class="invoice p-4 bg-white">
    <!-- title row -->
    <div class="row">
      <div class="col-12">
        <h2 class="border-bottom pb-3">
          <img src="{{ url('public/img', $settings->logo_2)}}" width="110">
          <small class="float-end date-invoice mt-3">{{__('admin.date')}}: {{Helper::formatDate($data->created_at)}}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info mb-3">
      <div class="col-sm-4 invoice-col">
        {{__('general.from')}}
        <address>
          @if ($settings->company)
            <span class="w-100 d-block mb-1 fw-bold">{{$settings->company}}</span>
          @endif

          @if ($settings->address)
            <span class="w-100 d-block mb-1">{{$settings->address}}</span>
          @endif

          @if ($settings->city || $settings->zip)
            <span class="w-100 d-block mb-1">{{$settings->city}} {{$settings->zip}}</span>
          @endif

          @if ($settings->country)
            <span class="w-100 d-block mb-1">{{$settings->country}}</span>
          @endif

          <span class="w-100 d-block mb-1">{{__('auth.email')}}: {{$settings->email_admin}}</span>

          @if ($settings->vat)
            {{__('general.vat')}}: {{$settings->vat}}
          @endif
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        {{__('general.to')}}
        <address>
          @if (isset($data->user()->username))
          <span class="w-100 d-block mb-1 fw-bold">
            {{$data->user()->name}} {{$data->user()->company != '' ? '- '.$data->user()->company : null }}
          </span>

          @if ($data->user()->address)
            <span class="w-100 d-block mb-1">{{$data->user()->address}}</span>
          @endif

          @if ($data->user()->city || $data->user()->zip)
            <span class="w-100 d-block mb-1">{{$data->user()->city}}, {{$data->user()->zip}}</span>
          @endif

          @if (isset($data->user()->country()->country_name))
            <span class="w-100 d-block mb-1">{{$data->user()->country()->country_name}}</span>
          @endif

          {{__('auth.email')}}: {{$data->user()->email}}

          @else 
          {{ __('general.no_available') }}
          @endif
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>{{__('general.invoice')}} #{{str_pad($data->id, 4, "0", STR_PAD_LEFT)}}</b><br>
        <b>{{__('general.payment_due')}}</b> {{Helper::formatDate($data->created_at)}}<br>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-borderless table-striped">
          <thead>
          <tr>
            <th>{{__('general.qty')}}</th>
            <th class="text-center">{{__('general.description')}}</th>
            <th class="text-end">{{__('general.subtotal')}}</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>1</td>

            @if ($data->type == 'subscription')
              <td class="text-center">{{__('general.subscription_for').$creator}}</td>
            @elseif ($data->type == 'ppv')
              <td class="text-center">{{__('general.ppv').$creator}}</td>
            @elseif ($data->type == 'purchase')
              <td class="text-center">{{__('general.purchase_item').$creator}}</td>
            @elseif ($data->type == 'live_streaming_private')
              <td class="text-center">{{__('general.live_streaming_private').$creator}}</td>
            @else
              <td class="text-center">{{__('general.single_payment').' ('.__('general.tip').')'.$creator}}</td>
            @endif

            <td class="text-end">{{Helper::amountFormatDecimal($data->amount)}} {{ $settings->currency_code }}</td>
          </tr>
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- /.col -->
      <div class="col-4 col-lg-6"></div>
      <!-- /.col -->
      <div class="col-8 col-lg-6">
        <div class="table-responsive">
          <table class="table">
            <tr class="border-bottom">
              <th class="w-50 text-end">{{__('general.subtotal')}}:</th>
              <td class="text-end">{{Helper::amountFormatDecimal($data->amount)}} {{ $settings->currency_code }}</td>
            </tr>

              @foreach($taxes as $tax)
                <tr class="border-bottom">
                  <th class="w-50 text-end">{{ $tax->name }} {{ $tax->percentage }}%:</th>
                  <td class="text-end">{{Helper::amountFormatDecimal(Helper::calculatePercentage($data->amount, $tax->percentage))}} {{ $settings->currency_code }}</td>
                </tr>
              @endforeach

            <tr class="h5 text-end">
              <th class="text-end">{{__('general.total')}}:</th>
              <td><strong>{{Helper::amountFormatDecimal($total)}} {{ $settings->currency_code }}</strong></td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    <div class="row no-print">
        <div class="col-12">
          <a href="javascript:void(0);" onclick="window.print();" class="btn btn-light border"><i class="fa fa-print"></i> {{__('general.print')}}</a>
        </div>
      </div>
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
  </body>
</html>
