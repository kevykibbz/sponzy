@extends('admin.layout')

@section('content')
<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
    <i class="bi-chevron-right me-1 fs-6"></i>
    <span class="text-muted">{{ __('general.advertising') }}</span>

    <a href="{{ url('panel/admin/advertising/add') }}" class="btn btn-sm btn-dark float-lg-end mt-1 mt-lg-0">
        <i class="bi-plus-lg"></i> {{ __('general.add_new') }}
    </a>
</h5>

<div class="content">
    <div class="row">

        <div class="col-lg-12">

            @if (session('success_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check2 me-1"></i> {{ session('success_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @endif

            <div class="card shadow-custom border-0">
                <div class="card-body p-lg-4">

                    <div class="table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>

                                @if ($ads->count())
                                <tr>
                                    <th class="active">{{ __('admin.title') }}</th>
                                    <th class="active">{{ __('general.clicks') }}</th>
                                    <th class="active">{{ __('general.impressions') }}</th>
                                    <th class="active">{{ __('admin.date') }}</th>
                                    <th class="active">{{ __('general.expiry') }}</th>
                                    <th class="active">{{ __('admin.status') }}</th>
                                    <th class="active">{{ __('admin.actions') }}</th>
                                </tr>

                                @foreach ($ads as $ad)
                                <tr>
                                    <td>{{ str_limit($ad->title, 30) }}</td>
                                    <td>{{ $ad->clicks }}</td>
                                    <td>{{ $ad->impressions }}</td>
                                    <td>{{ Helper::formatDate($ad->created_at) }}</td>
                                    <td>{{ Helper::formatDate($ad->expired_at) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $ad->status->label()}}">
                                            {{ $ad->status->locale() }}
                                            </span>
                                        </td>
                                    <td>
                                        <a href="{{ url('panel/admin/advertising/edit', $ad->id) }}"
                                            class="btn btn-success rounded-pill btn-sm me-2">
                                            <i class="bi-pencil"></i>
                                        </a>

                                        <form method="POST"
                                            action="{{ route('advertising.destroy', ['ad' => $ad->id]) }}"
                                            accept-charset="UTF-8" class="d-inline-block align-top">
                                            @csrf
                                            <button class="btn btn-danger rounded-pill btn-sm actionDelete"
                                                type="button"><i class="bi-trash-fill"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr><!-- /.TR -->
                                @endforeach

                                @else
                                <h5 class="text-center p-5 text-muted fw-light m-0">{{ __('general.no_results_found')}}</h5>
                                @endif

                            </tbody>
                        </table>
                    </div><!-- /.box-body -->

                </div><!-- card-body -->
            </div><!-- card  -->
        </div><!-- col-lg-12 -->

    </div><!-- end row -->
</div><!-- end content -->
@endsection