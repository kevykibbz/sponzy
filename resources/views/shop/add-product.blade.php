@extends('layouts.app')

@section('title') {{ __('general.add_product') }} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-12 py-5">
          <h2 class="mb-0 font-montserrat">
            {{ __('general.add_product') }}
          </h2>
          <p class="lead text-muted mt-0">
            {{ __('general.digital_products_desc') }}
        </p>
        </div>
      </div>
      <div class="row justify-content-center">

        <div class="col-lg-7">
            <form action="{{ url()->current() }}" method="post" enctype="multipart/form-data" id="shopProductForm">
              @csrf

              <div class="form-group preview-shop">
                <label for="preview">{{ __('general.preview') }} <small>(JPG, PNG)</small></label>
                <input type="file" name="preview" id="preview" accept="image/*">
              </div>

              <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="{{ __('admin.name') }}">
              </div>

                <div class="form-group">
                  <input type="text" class="form-control isNumber" name="price" autocomplete="off" placeholder="{{ __('general.price') }}">
                </div>

                <div class="form-group">
                  <input type="text" class="form-control" name="tags" placeholder="{{ __('general.tags') }} ({{ __('general.separate_with_comma') }})">
                </div>

                <div class="form-group">
                  <select name="category" class="form-control custom-select">
                    <option disabled value="" selected>{{ __('general.category') }}</option>
                    @foreach (App\Models\ShopCategories::orderBy('name')->get() as $category)
                      <option value="{{ $category->id }}">
                        {{ Lang::has('shop-categories.' . $category->slug) ? __('shop-categories.' . $category->slug) : $category->name }}
                      </option>
                    @endforeach
                  </select>
                </div>

              <div class="form-group">
                <textarea class="form-control textareaAutoSize" name="description" placeholder="{{ __('general.description') }}" rows="3"></textarea>
              </div>

              <div class="form-group file-shop mb-4">
                <label for="file">
                  {{ __('general.file') }} <small>(JPG, PNG, GIF, MP4, MOV, MP3, PDF, ZIP)</small>
                  <small class="d-block w-100">{{ __('general.file_to_downloaded') }}</small>
                </label>
                <input type="file" name="file" id="file" accept="image/*,application/pdf,application/x-zip-compressed,video/x-mpeg2,video/quicktime,video/mp4,video/x-m4v,audio/x-mpeg">
              </div>

              <!-- Alert -->
            <div class="alert alert-danger my-3 display-none" id="errorShopProduct">
               <ul class="list-unstyled m-0" id="showErrorsShopProduct"><li></li></ul>
             </div><!-- Alert -->

              <button class="btn btn-1 btn-primary btn-block" id="shopProductBtn" type="submit"><i></i> {{ __('users.create') }}</button>
            </form>
        </div><!-- end col-md-12 -->
      </div>
    </div>
  </section>
@endsection

@section('javascript')
  <script src="{{ asset('public/js/fileuploader/fileuploader-shop-preview.js') }}"></script>
  <script src="{{ asset('public/js/fileuploader/fileuploader-shop-file.js') }}"></script>
  <script src="{{ asset('public/js/shop.js') }}"></script>
@endsection
