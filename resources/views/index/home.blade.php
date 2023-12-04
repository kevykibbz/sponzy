@extends('layouts.app')

@section('content')
  <!-- jumbotron -->
  <div class="jumbotron homepage m-0 bg-gradient">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 second">
          <h1 class="display-4 pt-5 mb-3 text-white text-center-sm">{{__('general.welcome_title')}}</h1>
          <p class="text-white text-center-sm">{{__('general.subtitle_welcome')}}</p>
          <p>
            <a href="{{url('creators')}}" class="btn btn-lg btn-main btn-outline-light btn-w-mb px-4 mr-2" role="button">{{__('general.explore')}}</a>

            <a class="btn btn-lg btn-main btn-light btn-w px-4 toggleRegister btn-arrow" href="{{ $settings->registration_active == '1' ? url('signup') : url('login')}}">
              {{__('general.getting_started')}}</a>
          </p>
        </div>
        <div class="col-lg-8 first">
          <img src="{{url('public/img', $settings->home_index)}}" class="img-center img-fluid">
        </div>
      </div>
    </div>
  </div>
  <!-- ./ jumbotron -->

  <div class="section py-5 py-large">
    <div class="container">
        <div class="btn-block text-center mb-5">
          <h1 class="txt-black">{{__('general.header_box_2')}}</h1>
          <p>
            {{__('general.desc_box_2')}}
          </p>
          </div>

          <div class="row">
            <div class="col-lg-4">
              <div class="text-center">
                <img src="{{url('public/img', $settings->img_1)}}" class="img-center img-fluid" width="200">
                <h4 class="mt-1 txt-black">{{__('general.card_1')}}</h4>
                <p class="text-muted mt-1">{{__('general.desc_card_1')}}</p>
              </div>
          </div>

          <div class="col-lg-4">
            <div class="text-center">
              <img src="{{url('public/img', $settings->img_2)}}" class="img-center img-fluid" width="200">
              <h4 class="mt-1 txt-black">{{__('general.card_2')}}</h4>
              <p class="text-muted mt-1">{{__('general.desc_card_2')}}</p>
            </div>
        </div>

        <div class="col-lg-4">
          <div class="text-center">
            <img src="{{url('public/img', $settings->img_3)}}" class="img-center img-fluid" width="200">
            <h4 class="mt-1 txt-black">{{__('general.card_3')}}</h4>
            <p class="text-muted mt-1">{{__('general.desc_card_3')}}</p>
          </div>
      </div>

      </div>
    </div>
  </div>

  <!-- Create profile -->
  <div class="section py-5 py-large">
    <div class="container">
      <div class="row align-items-center">
      <div class="col-12 col-lg-7 text-center mb-3">
        <img src="{{url('public/img', $settings->img_4)}}" alt="User" class="img-fluid">
      </div>
      <div class="col-12 col-lg-5">
        <h1 class="m-0 card-profile txt-black">{{__('general.header_box_3')}}</h1>
        <div class="col-lg-9 col-xl-8 p-0">
          <p class="py-4 m-0 text-muted">{{__('general.desc_box_3')}}</p>
        </div>
        <a href="{{ $settings->registration_active == '1' ? url('signup') : url('login')}}" class="btn-arrow btn btn-lg btn-main btn-outline-primary btn-w px-4">
          {{__('general.getting_started')}}
        </a>
      </div>
    </div>
    </div><!-- End Container -->
  </div><!-- End Section -->

@if ($settings->widget_creators_featured == 'on')

    @if ($users->count() != 0)
    <!-- Users -->
    <div class="section py-5 py-large">
      <div class="container">
        <div class="btn-block text-center mb-5">
          <h1 class="txt-black">{{__('general.creators_featured')}}</h1>
          <p>
            {{__('general.desc_creators_featured')}}
          </p>
        </div>
        <div class="row">

          <div class="owl-carousel owl-theme">
            @foreach ($users as $response)
              @include('includes.listing-creators')
          @endforeach
          </div>

          @if ($usersTotal > $users->total())
          <div class="w-100 text-center mt-4 px-lg-0 px-3">
            <a href="{{url('creators')}}" class="btn-arrow btn btn-lg btn-main btn-outline-primary btn-w px-4">
              {{__('general.view_all_creators')}}
            </a>
          </div>
          @endif
        </div><!-- End Row -->
      </div><!-- End Container -->
    </div><!-- End Section -->
  @endif
@endif

  @if ($settings->show_counter == 'on')
  <!-- Counter -->
  <div class="section py-2 bg-gradient text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <div class="d-flex py-3 my-1 my-lg-0 justify-content-center">
            <span class="mr-3 display-4"><i class="bi bi-people align-baseline"></i></span>
            <div>
              <h3 class="mb-0">{!! Helper::formatNumbersStats($usersTotal) !!}</h3>
              <h5>{{__('general.creators')}}</h5>
            </div>
          </div>

        </div>
        <div class="col-md-4">
          <div class="d-flex py-3 my-1 my-lg-0 justify-content-center">
            <span class="mr-3 display-4"><i class="bi bi-images align-baseline"></i></span>
            <div>
              <h3 class="mb-0">{!! Helper::formatNumbersStats(Updates::count()) !!}</h3>
              <h5 class="font-weight-light">{{__('general.content_created')}}</h5>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="d-flex py-3 my-1 my-lg-0 justify-content-center">
            <span class="mr-3 display-4"><i class="bi bi-cash-coin align-baseline"></i></span>
            <div>
              <h3 class="mb-0">@if($settings->currency_position == 'left') {{ $settings->currency_symbol }}@endif{!! Helper::formatNumbersStats(Transactions::whereApproved('1')->sum('earning_net_user')) !!}@if($settings->currency_position == 'right'){{ $settings->currency_symbol }} @endif</h3>
              <h5 class="font-weight-light">{{__('general.earnings_of_creators')}}</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endif

@if ($settings->earnings_simulator == 'on')
<!-- Earnings simulator -->
<div class="section py-5 py-large">
  <div class="container mb-4">
    <div class="btn-block text-center">
      <h1 class="txt-black">{{__('general.earnings_simulator')}}</h1>
      <p>
        {{__('general.earnings_simulator_subtitle')}}
      </p>
    </div>
    <div class="row">
      <div class="col-md-6">
        <label for="rangeNumberFollowers" class="w-100">
          {{ __('general.number_followers') }}
          <i class="feather icon-facebook mr-1"></i>
          <i class="feather icon-twitter mr-1"></i>
          <i class="feather icon-instagram"></i>
          <span class="float-right">
            #<span id="numberFollowers">1000</span>
          </span>
        </label>
        <input type="range" class="custom-range" value="0" min="1000" max="1000000" id="rangeNumberFollowers" onInput="$('#numberFollowers').html($(this).val())">
      </div>

      <div class="col-md-6">
        <label for="rangeMonthlySubscription" class="w-100">{{ __('general.monthly_subscription_price') }}
          <span class="float-right">
            {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="monthlySubscription">{{ $settings->min_subscription_amount }}</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}
        </span>
        </label>
        <input type="range" class="custom-range" value="0" onInput="$('#monthlySubscription').html($(this).val())" min="{{ $settings->min_subscription_amount }}" max="{{ $settings->max_subscription_amount }}" id="rangeMonthlySubscription">
      </div>

      <div class="col-md-12 text-center mt-4">
        <h3 class="font-weight-light">{{__('general.earnings_simulator_subtitle_2')}}
          <span class="font-weight-bold"><span id="estimatedEarn"></span> <small>{{$settings->currency_code}}</small></span>
          {{ __('general.per_month') }}*</h3>
        <p class="mb-1">
          * {{__('general.earnings_simulator_subtitle_3')}}
        </p>
        @if ($settings->fee_commission != 0)
          <small class="w-100 d-block">* {{__('general.include_platform_fee', ['percentage' => $settings->fee_commission])}}</small>
        @endif
      </div>
    </div>
  </div>
</div>
@endif

    <div class="jumbotron m-0 text-white text-center bg-gradient">
      <div class="container position-relative">
        <h1>{{__('general.head_title_bottom')}}</h1>
        <p>{{__('general.head_title_bottom_desc')}}</p>
        <p>
          <a href="{{url('creators')}}" class="btn btn-lg btn-main btn-outline-light btn-w-mb px-4 mr-2" role="button">{{__('general.explore')}}</a>
          <a class="btn-arrow btn btn-lg btn-main btn-light btn-w px-4 toggleRegister" href="{{ $settings->registration_active == '1' ? url('signup') : url('login')}}" role="button">
          {{__('general.getting_started')}}
        </a>
        </p>
      </div>
    </div>

@endsection

@section('javascript')

  @if ($settings->earnings_simulator == 'on')
  <script type="text/javascript">

  function decimalFormat(nStr)
  {
    @if ($settings->decimal_format == 'dot')
     var $decimalDot = '.';
     var $decimalComma = ',';
     @else
     var $decimalDot = ',';
     var $decimalComma = '.';
     @endif

     @if ($settings->currency_position == 'left')
     var currency_symbol_left = '{{$settings->currency_symbol}}';
     var currency_symbol_right = '';
     @else
     var currency_symbol_right = '{{$settings->currency_symbol}}';
     var currency_symbol_left = '';
     @endif

      nStr += '';
      var x = nStr.split('.');
      var x1 = x[0];
      var x2 = x.length > 1 ? $decimalDot + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
          var x1 = x1.replace(rgx, '$1' + $decimalComma + '$2');
      }
      return currency_symbol_left + x1 + x2 + currency_symbol_right;
    }

    function earnAvg() {
      var fee = {{ $settings->fee_commission }};
      @if($settings->currency_code == 'JPY')
       $decimal = 0;
      @else
       $decimal = 2;
      @endif

      var monthlySubscription = parseFloat($('#rangeMonthlySubscription').val());
      var numberFollowers = parseFloat($('#rangeNumberFollowers').val());

      var estimatedFollowers = (numberFollowers * 5 / 100)
      var followersAndPrice = (estimatedFollowers * monthlySubscription);
      var percentageAvgFollowers = (followersAndPrice * fee / 100);
      var earnAvg = followersAndPrice - percentageAvgFollowers;

      return decimalFormat(earnAvg.toFixed($decimal));
    }
   $('#estimatedEarn').html(earnAvg());

   $("#rangeNumberFollowers, #rangeMonthlySubscription").on('change', function() {

     $('#estimatedEarn').html(earnAvg());

   });
  </script>
@endif

@if (session('success_verify'))
  <script type="text/javascript">

	swal({
		title: "{{ __('general.welcome') }}",
		text: "{{ __('users.account_validated') }}",
		type: "success",
		confirmButtonText: "{{ __('users.ok') }}"
		});
    </script>
	 @endif

	 @if (session('error_verify'))
   <script type="text/javascript">
	swal({
		title: "{{ __('general.error_oops') }}",
		text: "{{ __('users.code_not_valid') }}",
		type: "error",
		confirmButtonText: "{{ __('users.ok') }}"
		});
    </script>
	 @endif

@endsection
