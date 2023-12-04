@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.storage') }}</span>
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

					 <form method="POST" action="{{ url('panel/admin/storage') }}" enctype="multipart/form-data">
						 @csrf

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">App URL</label>
		          <div class="col-sm-10">
		            <input  value="{{ config('app.url') }}" name="APP_URL" type="text" class="form-control @error('APP_URL') is-invalid @endif">
								<small class="d-block mt-1">{{__('admin.notice_app_url')}} <strong>{{url('/')}}</strong></small>
		          </div>
		        </div>

		        <div class="row mb-5">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{__('admin.disk')}}</label>
		          <div class="col-sm-10">
		            <select name="FILESYSTEM_DRIVER" class="form-select">
									 <option @if (config('filesystems.default') == 'default') selected @endif value="default">{{__('admin.disk_local')}}</option>
	 								 <option @if (config('filesystems.default') == 's3') selected @endif value="s3">Amazon S3</option>
	 								 <option @if (config('filesystems.default') == 'dospace') selected @endif value="dospace">DigitalOcean</option>
	 								 <option @if (config('filesystems.default') == 'wasabi') selected @endif value="wasabi">Wasabi</option>
										 <option @if (config('filesystems.default') == 'backblaze') selected @endif value="backblaze">Backblaze B2</option>
									 <option @if (config('filesystems.default') == 'vultr') selected @endif value="vultr">Vultr</option>
		           </select>
		          </div>
		        </div>

						<hr />

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Amazon Key</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.s3.key') }}" name="AWS_ACCESS_KEY_ID" type="text" class="form-control @error('AWS_ACCESS_KEY_ID') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Amazon Secret</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.s3.secret') }}" name="AWS_SECRET_ACCESS_KEY" type="text" class="form-control @error('AWS_SECRET_ACCESS_KEY') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Amazon Region</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.s3.region') }}" name="AWS_DEFAULT_REGION" type="text" class="form-control @error('AWS_DEFAULT_REGION') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Amazon Bucket</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.s3.bucket') }}" name="AWS_BUCKET" type="text" class="form-control @error('AWS_BUCKET') is-invalid @endif">
		          </div>
		        </div>

						<hr />

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">DigitalOcean Key</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.dospace.key') }}" name="DOS_ACCESS_KEY_ID" type="text" class="form-control @error('DOS_ACCESS_KEY_ID') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">DigitalOcean Secret</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.dospace.secret') }}" name="DOS_SECRET_ACCESS_KEY" type="text" class="form-control @error('DOS_SECRET_ACCESS_KEY') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">DigitalOcean Region</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.dospace.region') }}" name="DOS_DEFAULT_REGION" type="text" class="form-control @error('DOS_DEFAULT_REGION') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">DigitalOcean Bucket</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.dospace.bucket') }}" name="DOS_BUCKET" type="text" class="form-control @error('DOS_BUCKET') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
 						<div class="col-sm-10 offset-sm-2">
 							<div class="form-check">
 								<input class="form-check-input check" name="DOS_CDN" value="1" type="checkbox" id="DOS_CDN" @if (env('DOS_CDN')) checked="checked" @endif>
 								<label class="form-check-label" for="DOS_CDN">
 									{{ __('general.enabled') }}/{{ __('admin.disabled') }} DigitalOcean CDN
 								</label>
 							</div>
 						</div>
 					</div>

						<hr />

				<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Wasabi Key</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.wasabi.key') }}" name="WAS_ACCESS_KEY_ID" type="text" class="form-control @error('WAS_ACCESS_KEY_ID') is-invalid @endif">
								<small class="d-block mt-1"><strong>Important:</strong> Wasabi in trial mode does not allow public files, you must send an email to <strong>support@wasabi.com</strong> to enable public files, or avoid trial mode.</small>
		          </div>
		        </div>

				<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Wasabi Secret</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.wasabi.secret') }}" name="WAS_SECRET_ACCESS_KEY" type="text" class="form-control @error('WAS_SECRET_ACCESS_KEY') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Wasabi Region</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.wasabi.region') }}" name="WAS_DEFAULT_REGION" type="text" class="form-control @error('WAS_DEFAULT_REGION') is-invalid @endif">
		          </div>
		        </div>

				<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Wasabi Bucket</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.wasabi.bucket') }}" name="WAS_BUCKET" type="text" class="form-control @error('WAS_BUCKET') is-invalid @endif">
		          </div>
		        </div>

						<hr />

				<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Backblaze Account ID</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.backblaze.accountId') }}" name="BACKBLAZE_ACCOUNT_ID" type="text" class="form-control @error('BACKBLAZE_ACCOUNT_ID') is-invalid @endif">
		          </div>
		        </div>

				<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Backblaze Master Application Key</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.backblaze.applicationKey') }}" name="BACKBLAZE_APP_KEY" type="text" class="form-control @error('BACKBLAZE_APP_KEY') is-invalid @endif">
		          </div>
		        </div>

				<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Backblaze Bucket Name</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.backblaze.bucketName') }}" name="BACKBLAZE_BUCKET" type="text" class="form-control @error('BACKBLAZE_BUCKET') is-invalid @endif">
		          </div>
		        </div>

				<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Backblaze Bucket ID</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.backblaze.bucketId') }}" name="BACKBLAZE_BUCKET_ID" type="text" class="form-control @error('BACKBLAZE_BUCKET_ID') is-invalid @endif">
		          </div>
		        </div>

				<div class="row mb-3">
					<label class="col-sm-2 col-form-label text-lg-end">Backblaze Bucket Region</label>
					<div class="col-sm-10">
					  <input value="{{ env('BACKBLAZE_BUCKET_REGION') }}" name="BACKBLAZE_BUCKET_REGION" type="text" class="form-control @error('BACKBLAZE_BUCKET_REGION') is-invalid @endif" placeholder="s3.us-west-000.backblazeb2.com">
					</div>
				  </div>
						<hr />

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Vultr Key</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.vultr.key') }}" name="VULTR_ACCESS_KEY" type="text" class="form-control @error('VULTR_ACCESS_KEY') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Vultr Secret</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.vultr.secret') }}" name="VULTR_SECRET_KEY" type="text" class="form-control @error('VULTR_SECRET_KEY') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Vultr Region</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.vultr.region') }}" name="VULTR_REGION" type="text" class="form-control @error('VULTR_REGION') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Vultr Bucket</label>
		          <div class="col-sm-10">
		            <input value="{{ config('filesystems.disks.vultr.bucket') }}" name="VULTR_BUCKET" type="text" class="form-control @error('VULTR_BUCKET') is-invalid @endif">
		          </div>
		        </div>

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
