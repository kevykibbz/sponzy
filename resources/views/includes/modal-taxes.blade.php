<ul class="list-group list-group-flush border-dashed-radius">

	<li class="list-group-item py-1 list-taxes">
    <div class="row">
      <div class="col">
        <small>{{trans('general.subtotal')}}:</small>
      </div>
      <div class="col-auto">
        <small class="subtotal font-weight-bold">
        {{ Helper::symbolPositionLeft() }}<span class="subtotalTip">0</span>{{ Helper::symbolPositionRight() }}
        </small>
      </div>
    </div>
  </li>
  
	@foreach (auth()->user()->isTaxable() as $tax)
		<li class="list-group-item py-1 list-taxes isTaxable">
	    <div class="row">
	      <div class="col">
	        <small>{{ $tax->name }} {{ $tax->percentage }}%:</small>
	      </div>
	      <div class="col-auto percentageAppliedTax{{$loop->iteration}}" data="{{ $tax->percentage }}">
	        <small class="font-weight-bold">
	        {{ Helper::symbolPositionLeft() }}<span class="amount{{$loop->iteration}}">0</span>{{ Helper::symbolPositionRight() }}
	        </small>
	      </div>
	    </div>
	  </li>
	@endforeach

	<li class="list-group-item py-1 list-taxes">
    <div class="row">
      <div class="col">
        <small class="font-weight-bold">{{trans('general.total')}}:</small>
      </div>
      <div class="col-auto">
        <small class="totalPPV font-weight-bold">
        {{ Helper::symbolPositionLeft() }}<span class="totalTip">0</span>{{ Helper::symbolPositionRight() }}
        </small>
      </div>
    </div>
  </li>

</ul>
