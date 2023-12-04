@if (count($errors) > 0)
	<!-- Start Box Body -->
  <div class="box-body">
	<div class="alert alert-danger" id="dangerAlert">
		{{trans('auth.error_desc')}} <br><br>
		<ul class="list-unstyled">
			@foreach ($errors->all() as $error)
				<li><i class="far fa-times-circle"></i> {{$error}}</li>
			@endforeach
		</ul>
	</div>
</div><!-- /.box-body -->
@endif
