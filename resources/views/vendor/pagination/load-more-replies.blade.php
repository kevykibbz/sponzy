@if ($paginator->hasMorePages())
    <a href="javascript:void(0)" class="loadMoreComments" data-url="{{ $paginator->nextPageUrl() }}">— {{ trans('general.load_comments') }} (<span class="counter">{{$paginator->total() - $paginator->lastItem()}}</span>)</a>
@endif
