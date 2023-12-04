<div class="container">
  <div class="row">
		@foreach (Helper::emojis() as $emoji)
			<div class="col-3">
	      <span class="emoji" data-emoji="{{$emoji}}">{{$emoji}}</span>
	    </div>
		@endforeach
  </div>
</div>
