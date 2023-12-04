@if ($users->hasMorePages())
  <div class="btn-block text-center d-none">
    {{ $users->appends([
      'q' => request('q'),
      'gender' => request('gender'),
      'min_age' => request('min_age'),
      'max_age' => request('max_age')
      ])->links('vendor.pagination.loadmore') }}
  </div>
  @endif