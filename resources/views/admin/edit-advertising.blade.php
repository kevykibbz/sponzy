@extends('admin.layout')

@section('content')
<h5 class="mb-4 fw-light">
  <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
  <i class="bi-chevron-right me-1 fs-6"></i>
  <a class="text-reset" href="{{ url('panel/admin/advertising') }}">{{ __('general.advertising') }}</a>
  <i class="bi-chevron-right me-1 fs-6"></i>
  <span class="text-muted">{{ __('admin.edit') }}</span>
</h5>

<div class="content">
  <div class="row">

    <div class="col-lg-12">

      @include('errors.errors-forms')

      <div class="card shadow-custom border-0">
        <div class="card-body p-lg-5">

          <form method="post" action="{{ route('advertising.update', ['id' => $ad->id]) }}"
            enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.title') }}</label>
              <div class="col-sm-10">
                <input value="{{ $ad->title }}" name="title" required type="text" class="form-control">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.description') }}</label>
              <div class="col-sm-10">
                <input value="{{ $ad->description }}" name="description" required type="text" class="form-control">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.url_ad') }}</label>
              <div class="col-sm-10">
                <input value="{{ $ad->url }}" name="url" required type="text" class="form-control">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.expiry') }}</label>
              <div class="col-sm-10">
                <select name="expired_at" class="form-select">
                  @for ($i = 1; $i <= 12; ++$i) <option value="{{ $i }}">
                    {{$i}} {{ trans_choice('general.months', $i) }}
                    </option>
                    @endfor
                </select>

                <div class="text-muted D-block mt-1">
                  <div class="form-check form-switch form-switch-sm">
                    <input type="checkbox" class="form-check-input" name="updateExpirationDate" value="1"
                      id="customSwitch1">
                    <label class="custom-control-label switch" for="customSwitch1">{{
                      __('general.update_expiration_date') }}</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ __('general.image') }}</label>
              <div class="col-lg-5 col-sm-10">
                <div class="input-group mb-1">
                  <input name="image" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">(JPG, PNG) 400x400</small>
              </div>
            </div>

            <fieldset class="row mb-3">
              <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('admin.status') }}</legend>
              <div class="col-sm-10">
                <div class="form-check form-switch form-switch-md">
                  <input class="form-check-input" type="checkbox" @disabled($ad->status->value === 2) name="status" @checked($ad->status->value) value="1"
                  role="switch">
                </div>
              </div>
            </fieldset><!-- end row -->

            <div class="row mb-3">
              <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-dark mt-3 px-5">{{ __('admin.save') }}</button>
              </div>
            </div>

          </form>

        </div><!-- card-body -->
      </div><!-- card  -->
    </div><!-- col-lg-12 -->

  </div><!-- end row -->
</div><!-- end content -->
@endsection