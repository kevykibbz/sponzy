<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Installer</title>
    <link href="{{ asset('public/css/core.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/styles.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ url('public/img/favicon.png') }}" />
  </head>
  <body class="bg-primary">
  		<main role="main">
        <div class="jumbotron home m-0 bg-primary" style="padding: 40px 0">
          <div class="container pt-lg-md">
            <div class="row justify-content-center">
              <div class="col-lg-5">
                <div class="card bg-light shadow border-0">

                <div class="card-header bg-white py-4">
                  <h4 class="text-center mb-0 font-weight-bold">
                    Database and App
                  </h4>
                  <small class="btn-block text-center mt-2">Setup Database and App</small>
                </div>

                  <div class="card-body px-lg-5 py-lg-5">

                    @if (session('notification'))
                            <div class="alert alert-success">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          			<span aria-hidden="true">×</span>
                          			</button>

                              {{ session('notification') }}
                            </div>
                          @endif

                    @include('errors.errors-forms')

                    <form method="POST" action="{{ url('install/script/database') }}">
                        @csrf

                    <div class="row">

                      <small class="w-100 d-block mb-2">-- Database</small>

                      <div class="col-md-6">
                      <div class="form-group mb-3">
                        <div class="input-group input-group-alternative">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-database"></i></span>
                          </div>
                          <input class="form-control" required value="{{old('database')}}" placeholder="Database" name="database" type="text">
                        </div>
                      </div>
                      </div>

                      <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group input-group-alternative">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                          </div>
                          <input name="username" required type="text" value="{{old('username')}}" class="form-control" placeholder="Username">
                        </div>
                      </div>
                      </div>

                      </div><!-- Row -->

                      <div class="form-group">
                        <div class="input-group input-group-alternative">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-database"></i></span>
                          </div>
                          <input name="host" required type="text" class="form-control" value="{{old('host')}}" placeholder="Host">
                        </div>
                        <small class="text-muted btn-block mb-4">For example: <em>127.0.0.1</em> or <em>localhost</em></small>
                      </div>

                      <div class="form-group">
                        <div class="input-group input-group-alternative">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-key"></i></span>
                          </div>
                          <input name="password" required type="password" class="form-control" placeholder="Password">
                        </div>
                      </div>

                      <small class="w-100 d-block mb-2">-- App</small>

                      <div class="form-group">
                        <div class="input-group input-group-alternative">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-cogs"></i></span>
                          </div>
                          <input name="app_name" required type="text" value="{{old('app_name')}}" class="form-control" placeholder="App Name">
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="input-group input-group-alternative">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-link"></i></span>
                          </div>
                          <input name="app_url" required type="text" value="{{old('app_url')}}" class="form-control" placeholder="App URL">
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="input-group input-group-alternative">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                          </div>
                          <input name="email_admin" required type="email" value="{{old('email_admin')}}" class="form-control" placeholder="Email Admin">
                        </div>
                        <small class="text-muted btn-block mb-4">For Example: <em>no-reply@yoursite.com</em></small>
                      </div>

                      <div class="text-center">
                        <button type="submit" onClick="this.form.submit(); this.disabled=true; this.innerText='Installing...'; " class="btn btn-primary my-4 w-100">Install</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
  </body>
  <script src="{{ asset('public/js/core.min.js') }}"></script>
  <script src="{{ asset('public/js/bootstrap.bundle.min.js') }}"></script>
</html>
