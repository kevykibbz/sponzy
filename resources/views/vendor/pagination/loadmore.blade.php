@if ($paginator->hasMorePages())
<a href="javascript:void(0)" data-url="{{ $paginator->nextPageUrl() }}" rel="next" class="btn btn-primary btn-sm text-center my-2 w-100 paginatorMsg">
       	 {{trans('general.loadmore')}} <i class="far fa-arrow-alt-circle-down"></i>
       	 	</a>
       	 	@endif
