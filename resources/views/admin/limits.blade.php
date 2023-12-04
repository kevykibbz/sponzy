@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.general_settings') }}</span>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.limits') }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			@if (session('success_message'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check2 me-1"></i>	{{ session('success_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
                </div>
              @endif

              @include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form method="POST" action="{{ url('panel/admin/settings/limits') }}" enctype="multipart/form-data">
             @csrf

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.auto_approve_post') }}</label>
		          <div class="col-sm-10">
		            <select name="auto_approve_post" class="form-select">
                  <option @if ($settings->auto_approve_post == 'on') selected="selected" @endif value="on">{{ __('general.yes') }}</option>
                  <option @if ($settings->auto_approve_post == 'off') selected="selected" @endif value="off">{{ __('general.no') }}</option>
		           </select>
		          </div>
		        </div><!-- end row -->

						<div class="row mb-3">
             <label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.file_size_allowed') }}</label>
             <div class="col-sm-10">
               <select name="file_size_allowed" class="form-select">
								 <option @if( $settings->file_size_allowed == 1024 ) selected="selected" @endif value="1024">1 MB</option>
								 <option @if( $settings->file_size_allowed == 2048 ) selected="selected" @endif value="2048">2 MB</option>
								 <option @if( $settings->file_size_allowed == 3072 ) selected="selected" @endif value="3072">3 MB</option>
								 <option @if( $settings->file_size_allowed == 4096 ) selected="selected" @endif value="4096">4 MB</option>
								 <option @if( $settings->file_size_allowed == 5120 ) selected="selected" @endif value="5120">5 MB</option>
								 <option @if( $settings->file_size_allowed == 10240 ) selected="selected" @endif value="10240">10 MB</option>
								 <option @if( $settings->file_size_allowed == 15360 ) selected="selected" @endif value="15360">15 MB</option>
								 <option @if( $settings->file_size_allowed == 20480 ) selected="selected" @endif value="20480">20 MB</option>
								 <option @if( $settings->file_size_allowed == 25600 ) selected="selected" @endif value="25600">25 MB</option>
								 <option @if( $settings->file_size_allowed == 30720 ) selected="selected" @endif value="30720">30 MB</option>
								 <option @if( $settings->file_size_allowed == 40960 ) selected="selected" @endif value="40960">40 MB</option>
								 <option @if( $settings->file_size_allowed == 51200 ) selected="selected" @endif value="51200">50 MB</option>
								 <option @if( $settings->file_size_allowed == 102400 ) selected="selected" @endif value="102400">100 MB</option>
								 <option @if( $settings->file_size_allowed == 153600 ) selected="selected" @endif value="153600">150 MB</option>
								 <option @if( $settings->file_size_allowed == 256000 ) selected="selected" @endif value="256000">250 MB</option>
								 <option @if( $settings->file_size_allowed == 307200 ) selected="selected" @endif value="307200">300 MB</option>
								 <option @if( $settings->file_size_allowed == 512000 ) selected="selected" @endif value="512000">500 MB</option>
								 <option @if( $settings->file_size_allowed == 716800 ) selected="selected" @endif value="716800">700 MB</option>
								 <option @if( $settings->file_size_allowed == 819200 ) selected="selected" @endif value="819200">800 MB</option>
								 <option @if( $settings->file_size_allowed == 1024000 ) selected="selected" @endif value="1024000">1 GB</option>
								 <option @if( $settings->file_size_allowed == 2048000 ) selected="selected" @endif value="2048000">2 GB</option>
								 <option @if( $settings->file_size_allowed == 3072000 ) selected="selected" @endif value="3072000">3 GB</option>
								 <option @if( $settings->file_size_allowed == 5120000 ) selected="selected" @endif value="5120000">5 GB</option>
              </select>

              <small class="d-block w-100">
                {{ __('admin.upload_max_filesize_info') }} <strong><?php echo str_replace('M', 'MB', ini_get('upload_max_filesize')) ?></strong>
              </small>
             </div>
           </div><!-- end row -->

					 <div class="row mb-3">
						<label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.file_size_allowed') }} ({{ __('general.verify_account') }})</label>
						<div class="col-sm-10">
							<select name="file_size_allowed_verify_account" class="form-select">
								<option @if( $settings->file_size_allowed_verify_account == 1024 ) selected="selected" @endif value="1024">1 MB</option>
								<option @if( $settings->file_size_allowed_verify_account == 2048 ) selected="selected" @endif value="2048">2 MB</option>
								<option @if( $settings->file_size_allowed_verify_account == 3072 ) selected="selected" @endif value="3072">3 MB</option>
								<option @if( $settings->file_size_allowed_verify_account == 4096 ) selected="selected" @endif value="4096">4 MB</option>
								<option @if( $settings->file_size_allowed_verify_account == 5120 ) selected="selected" @endif value="5120">5 MB</option>
								<option @if( $settings->file_size_allowed_verify_account == 10240 ) selected="selected" @endif value="10240">10 MB</option>
								<option @if( $settings->file_size_allowed_verify_account == 15360 ) selected="selected" @endif value="15360">15 MB</option>
								<option @if( $settings->file_size_allowed_verify_account == 20480 ) selected="selected" @endif value="20480">20 MB</option>
								<option @if( $settings->file_size_allowed_verify_account == 30720 ) selected="selected" @endif value="30720">30 MB</option>
						 </select>

						 <small class="d-block w-100">
							 {{ __('admin.upload_max_filesize_info') }} <strong><?php echo str_replace('M', 'MB', ini_get('upload_max_filesize')) ?></strong>
						 </small>
						</div>
					</div><!-- end row -->

					<div class="row mb-3">
					 <label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.post_length') }}</label>
					 <div class="col-sm-10">
						 <select name="update_length" class="form-select">
							 <option @if( $settings->update_length == 100 ) selected="selected" @endif value="100">100 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 150 ) selected="selected" @endif value="150">150 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 200 ) selected="selected" @endif value="200">200 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 250 ) selected="selected" @endif value="250">250 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 300 ) selected="selected" @endif value="300">300 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 400 ) selected="selected" @endif value="400">400 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 500 ) selected="selected" @endif value="500">500 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 700 ) selected="selected" @endif value="700">700 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 1000 ) selected="selected" @endif value="1000">1000 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 2000 ) selected="selected" @endif value="2000">2000 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 3000 ) selected="selected" @endif value="3000">3000 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 4000 ) selected="selected" @endif value="4000">4000 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 5000 ) selected="selected" @endif value="5000">5000 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 10000 ) selected="selected" @endif value="10000">10,000 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 20000 ) selected="selected" @endif value="20000">20,000 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 30000 ) selected="selected" @endif value="30000">30,000 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 40000 ) selected="selected" @endif value="40000">40,000 {{ trans('admin.characters') }}</option>
							 <option @if( $settings->update_length == 50000 ) selected="selected" @endif value="50000">50,000 {{ trans('admin.characters') }}</option>
						</select>

						<small class="d-block w-100">
							{{ trans('admin.post_length_info') }}
						</small>
					 </div>
				 </div><!-- end row -->

				 <div class="row mb-3">
					<label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.story_length') }}</label>
					<div class="col-sm-10">
						<select name="story_length" class="form-select">
							<option @if( $settings->story_length == 100 ) selected="selected" @endif value="100">100 {{ trans('admin.characters') }}</option>
							<option @if( $settings->story_length == 150 ) selected="selected" @endif value="150">150 {{ trans('admin.characters') }}</option>
							<option @if( $settings->story_length == 200 ) selected="selected" @endif value="200">200 {{ trans('admin.characters') }}</option>
							<option @if( $settings->story_length == 250 ) selected="selected" @endif value="250">250 {{ trans('admin.characters') }}</option>
							<option @if( $settings->story_length == 300 ) selected="selected" @endif value="300">300 {{ trans('admin.characters') }}</option>
							<option @if( $settings->story_length == 400 ) selected="selected" @endif value="400">400 {{ trans('admin.characters') }}</option>
							<option @if( $settings->story_length == 500 ) selected="selected" @endif value="500">500 {{ trans('admin.characters') }}</option>
							<option @if( $settings->story_length == 700 ) selected="selected" @endif value="700">700 {{ trans('admin.characters') }}</option>
							<option @if( $settings->story_length == 1000 ) selected="selected" @endif value="1000">1000 {{ trans('admin.characters') }}</option>
							<option @if( $settings->story_length == 2000 ) selected="selected" @endif value="2000">2000 {{ trans('admin.characters') }}</option>
							<option @if( $settings->story_length == 3000 ) selected="selected" @endif value="3000">3000 {{ trans('admin.characters') }}</option>
							<option @if( $settings->story_length == 4000 ) selected="selected" @endif value="4000">4000 {{ trans('admin.characters') }}</option>
							<option @if( $settings->story_length == 5000 ) selected="selected" @endif value="5000">5000 {{ trans('admin.characters') }}</option>
					 </select>

					 <small class="d-block w-100">
						 {{ trans('admin.story_length_info') }}
					 </small>
					</div>
				</div><!-- end row -->

				<div class="row mb-3">
					<label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.comment_length') }}</label>
					<div class="col-sm-10">
						<select name="comment_length" class="form-select">
							<option @if( $settings->comment_length == 100 ) selected="selected" @endif value="100">100 {{ trans('admin.characters') }}</option>
							<option @if( $settings->comment_length == 150 ) selected="selected" @endif value="150">150 {{ trans('admin.characters') }}</option>
							<option @if( $settings->comment_length == 200 ) selected="selected" @endif value="200">200 {{ trans('admin.characters') }}</option>
							<option @if( $settings->comment_length == 250 ) selected="selected" @endif value="250">250 {{ trans('admin.characters') }}</option>
							<option @if( $settings->comment_length == 300 ) selected="selected" @endif value="300">300 {{ trans('admin.characters') }}</option>
							<option @if( $settings->comment_length == 400 ) selected="selected" @endif value="400">400 {{ trans('admin.characters') }}</option>
							<option @if( $settings->comment_length == 500 ) selected="selected" @endif value="500">500 {{ trans('admin.characters') }}</option>
							<option @if( $settings->comment_length == 700 ) selected="selected" @endif value="700">700 {{ trans('admin.characters') }}</option>
							<option @if( $settings->comment_length == 1000 ) selected="selected" @endif value="1000">1000 {{ trans('admin.characters') }}</option>
							<option @if( $settings->comment_length == 2000 ) selected="selected" @endif value="2000">2000 {{ trans('admin.characters') }}</option>
							<option @if( $settings->comment_length == 3000 ) selected="selected" @endif value="3000">3000 {{ trans('admin.characters') }}</option>
							<option @if( $settings->comment_length == 4000 ) selected="selected" @endif value="4000">4000 {{ trans('admin.characters') }}</option>
							<option @if( $settings->comment_length == 5000 ) selected="selected" @endif value="5000">5000 {{ trans('admin.characters') }}</option>
					 </select>
					</div>
				</div><!-- end row -->

				<div class="row mb-3">
					<label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.number_posts_show') }}</label>
					<div class="col-sm-10">
						<select name="number_posts_show" class="form-select">
							<option @if( $settings->number_posts_show == 5 ) selected="selected" @endif value="5">5</option>
							<option @if( $settings->number_posts_show == 10 ) selected="selected" @endif value="10">10</option>
							<option @if( $settings->number_posts_show == 15 ) selected="selected" @endif value="15">15</option>
							<option @if( $settings->number_posts_show == 20 ) selected="selected" @endif value="20">20</option>
							<option @if( $settings->number_posts_show == 30 ) selected="selected" @endif value="30">30</option>
					 </select>
					</div>
				</div><!-- end row -->

				<div class="row mb-3">
					<label class="col-sm-2 col-form-labe text-lg-end">{{ __('admin.number_comments_show') }}</label>
					<div class="col-sm-10">
						<select name="number_comments_show" class="form-select">
							<option @if( $settings->number_comments_show == 1 ) selected="selected" @endif value="1">1</option>
							<option @if( $settings->number_comments_show == 2 ) selected="selected" @endif value="2">2</option>
							<option @if( $settings->number_comments_show == 3 ) selected="selected" @endif value="3">3</option>
							<option @if( $settings->number_comments_show == 4 ) selected="selected" @endif value="4">4</option>
							<option @if( $settings->number_comments_show == 5 ) selected="selected" @endif value="5">5</option>
							<option @if( $settings->number_comments_show == 10 ) selected="selected" @endif value="10">10</option>
							<option @if( $settings->number_comments_show == 15 ) selected="selected" @endif value="15">15</option>
							<option @if( $settings->number_comments_show == 20 ) selected="selected" @endif value="20">20</option>
							<option @if( $settings->number_comments_show == 30 ) selected="selected" @endif value="30">30</option>
					 </select>
					</div>
				</div><!-- end row -->

				<div class="row mb-3">
					<label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.maximum_files_post') }}</label>
					<div class="col-sm-10">
						<select name="maximum_files_post" class="form-select">
							<option @if( $settings->maximum_files_post == 1 ) selected="selected" @endif value="1">1</option>
							<option @if( $settings->maximum_files_post == 2 ) selected="selected" @endif value="2">2</option>
							<option @if( $settings->maximum_files_post == 3 ) selected="selected" @endif value="3">3</option>
							<option @if( $settings->maximum_files_post == 4 ) selected="selected" @endif value="4">4</option>
							<option @if( $settings->maximum_files_post == 5 ) selected="selected" @endif value="5">5</option>
							<option @if( $settings->maximum_files_post == 10 ) selected="selected" @endif value="10">10</option>
							<option @if( $settings->maximum_files_post == 15 ) selected="selected" @endif value="15">15</option>
							<option @if( $settings->maximum_files_post == 20 ) selected="selected" @endif value="20">20</option>
					 </select>
					</div>
				</div><!-- end row -->

				<div class="row mb-3">
					<label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.maximum_files_msg') }}</label>
					<div class="col-sm-10">
						<select name="maximum_files_msg" class="form-select">
							<option @if( $settings->maximum_files_msg == 1 ) selected="selected" @endif value="1">1</option>
							<option @if( $settings->maximum_files_msg == 2 ) selected="selected" @endif value="2">2</option>
							<option @if( $settings->maximum_files_msg == 3 ) selected="selected" @endif value="3">3</option>
							<option @if( $settings->maximum_files_msg == 4 ) selected="selected" @endif value="4">4</option>
							<option @if( $settings->maximum_files_msg == 5 ) selected="selected" @endif value="5">5</option>
							<option @if( $settings->maximum_files_msg == 10 ) selected="selected" @endif value="10">10</option>
							<option @if( $settings->maximum_files_msg == 15 ) selected="selected" @endif value="15">15</option>
							<option @if( $settings->maximum_files_msg == 20 ) selected="selected" @endif value="20">20</option>
					 </select>
					</div>
				</div><!-- end row -->

				<div class="row mb-3">
					<label class="col-sm-2 col-form-labe text-lg-end">{{ __('general.limit_categories') }}</label>
					<div class="col-sm-10">
						<select name="limit_categories" class="form-select">
							<option @if( $settings->limit_categories == 1 ) selected="selected" @endif value="1">1</option>
							<option @if( $settings->limit_categories == 2 ) selected="selected" @endif value="2">2</option>
							<option @if( $settings->limit_categories == 3 ) selected="selected" @endif value="3">3</option>
							<option @if( $settings->limit_categories == 4 ) selected="selected" @endif value="4">4</option>
							<option @if( $settings->limit_categories == 5 ) selected="selected" @endif value="5">5</option>
							<option @if( $settings->limit_categories == 10 ) selected="selected" @endif value="10">10</option>
							<option @if( $settings->limit_categories == 15 ) selected="selected" @endif value="15">15</option>
							<option @if( $settings->limit_categories == 20 ) selected="selected" @endif value="20">20</option>
					 </select>
					</div>
				</div><!-- end row -->

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
