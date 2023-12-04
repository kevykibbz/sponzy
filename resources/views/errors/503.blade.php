<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>{{__('admin.maintenance_mode')}}</title>
  <link href="{{ asset('public/css/core.min.css') }}" rel="stylesheet">
  <link href="{{ asset('public/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('public/css/styles.css') }}" rel="stylesheet">
  <link rel="shortcut icon" href="{{ url('public/img', config('settings.favicon')) }}" />
</head>

<body>
  <div class="wrap-center">
    <div class="container">
      <div class="row">
        <div class="col-md-12 error-page text-center parallax-fade-top" style="top: 0px; opacity: 1;">
          <h1 class="vivify popIn delay-1000 mb-lg-4 text-break">{{__('error.sorry')}}</h1>
          <p class="mt-3 mb-5 vivify popIn delay-1000 text-break">{{__('admin.msg_maintenance_mode')}}</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>