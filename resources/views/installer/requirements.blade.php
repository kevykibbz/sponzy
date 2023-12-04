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
        <div class="jumbotron m-0 bg-primary" style="padding: 40px 0">
          <div class="container pt-lg-md">
            <div class="row justify-content-center">
              <div class="col-lg-5">
                <div class="card bg-light shadow border-0">

                <div class="card-header bg-white py-4">
                  <h4 class="text-center mb-0 font-weight-bold">
                    Welcome to Installer
                  </h4>
                  <small class="btn-block text-center mt-2">Server Requirements</small>
                </div>

                  <div class="card-body px-lg-5 py-lg-5">

                    <div class="card shadow-sm">
                  			<div class="list-group list-group-sm list-group-flush">

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>PHP Version: {{ phpversion() }}
                                    <small class="w-100 d-block">Version required: {{ $minVersionPHP }}</small>
                                  </span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $versionPHP ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

									<div class="list-group-item d-flex justify-content-between">
										<div>
												<span>Ctype</span>
										</div>
										<div>
												<i class="fas fa-{{ $Ctype ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
										</div>
								</div><!--- ./ list-group-item -->

								<div class="list-group-item d-flex justify-content-between">
									<div>
											<span>cURL</span>
									</div>
									<div>
											<i class="fas fa-{{ $curl ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
									</div>
							</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>DOM</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $dom ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>Fileinfo</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $Fileinfo ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

									<div class="list-group-item d-flex justify-content-between">
										<div>
												<span>Filter</span>
										</div>
										<div>
												<i class="fas fa-{{ $filter ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
										</div>
								</div><!--- ./ list-group-item -->

								<div class="list-group-item d-flex justify-content-between">
									<div>
											<span>Hash</span>
									</div>
									<div>
											<i class="fas fa-{{ $hash ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
									</div>
							</div><!--- ./ list-group-item -->

							<div class="list-group-item d-flex justify-content-between">
								<div>
										<span>Mbstring</span>
								</div>
								<div>
										<i class="fas fa-{{ $mbstring ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
								</div>
						</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>Openssl</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $openssl ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

									<div class="list-group-item d-flex justify-content-between">
										<div>
												<span>PCRE</span>
										</div>
										<div>
												<i class="fas fa-{{ $pcre ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
										</div>
								</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>PDO</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $pdo ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

									<div class="list-group-item d-flex justify-content-between">
										<div>
												<span>Session</span>
										</div>
										<div>
												<i class="fas fa-{{ $session ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
										</div>
								</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>Tokenizer</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $tokenizer ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>XML</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $xml ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>GD</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $gd ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>Exif</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $exif ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                          <div class="list-group-item d-flex justify-content-between">
                							<div>
                									<span>Allow_url_fopen</span>
                							</div>
                							<div>
                									<i class="fas fa-{{ $allow_url_fopen ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                							</div>
                					</div><!--- ./ list-group-item -->

                        </div>
                      </div>

                      @if ($versionPHP
                          && $dom
                          && $Ctype
                          && $Fileinfo
                          && $openssl
                          && $pdo
                          && $mbstring
                          && $tokenizer
                          && $hash
                          && $xml
                          && $curl
                          && $gd
                          && $exif
						  && $session
						  && $filter
                          && $allow_url_fopen
						  && $pcre
                          )
                          <a href="{{ url('install/script/database') }}" class="btn btn-primary my-4 w-100">Setup Database and App <i class="fa fa-long-arrow-alt-right ml-1"></i></a>
                        @else
                          <div class="alert alert-danger mt-3" role="alert">
                            <i class="fa fa-exclamation-triangle"></i> You must meet all the requirements to be able to install, enable or install the extensions marked in red or update your PHP version
                          </div>
                      @endif


                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
  </body>
</html>
