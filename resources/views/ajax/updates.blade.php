@if( $data->count() != 0 )
@foreach ( $data as $response )

	@include('includes.updates')

@endforeach
{{ $data->links('vendor.pagination.loadmore') }}
@endif
