<script src="{{ asset('public/js/core.min.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('public/js/jqueryTimeago_'.Lang::locale().'.js') }}"></script>
<script src="{{ asset('public/js/lazysizes.min.js') }}" async=""></script>
<script src="{{ asset('public/js/plyr/plyr.min.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/plyr/plyr.polyfilled.min.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/app-functions.js') }}?v={{$settings->version}}"></script>

@if (! request()->is('live/*'))
<script src="{{ asset('public/js/install-app.js') }}?v={{$settings->version}}"></script>
@endif

@auth
  <script src="{{ asset('public/js/fileuploader/jquery.fileuploader.min.js') }}"></script>
  <script src="{{ asset('public/js/fileuploader/fileuploader-post.js') }}?v={{$settings->version}}"></script>
  <script src="{{ asset('public/js/jquery-ui/jquery-ui.min.js') }}"></script>
  @if (request()->path() == '/' && auth()->user()->verified_id == 'yes' || request()->route()->named('profile') && request()->path() == auth()->user()->username  && auth()->user()->verified_id == 'yes')
  <script src="{{ asset('public/js/jquery-ui/mentions.js') }}"></script>
@endif

@if ($settings->story_status)
<script src="{{ asset('public/js/story/zuck.min.js') }}?v={{$settings->version}}"></script>
@endif

<script src="https://js.stripe.com/v3/"></script>
<script src='https://checkout.razorpay.com/v1/checkout.js'></script>
<script src='https://js.paystack.co/v1/inline.js'></script>
@if (request()->is('my/wallet'))
<script src="{{ asset('public/js/add-funds.js') }}?v={{$settings->version}}"></script>
@else
<script src="{{ asset('public/js/payment.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/payments-ppv.js') }}?v={{$settings->version}}"></script>
@endif
@endauth

@if ($settings->custom_js)
  <script type="text/javascript">
  {!! $settings->custom_js !!}
  </script>
@endif

<script type="text/javascript">
const lightbox = GLightbox({
    touchNavigation: true,
    loop: false,
    closeEffect: 'fade'
});

@auth
$('.btnMultipleUpload').on('click', function() {
  $('.fileuploader').toggleClass('d-block');
});
@endauth
</script>

@if (auth()->guest()
    && ! request()->is('password/reset')
    && ! request()->is('password/reset/*')
    && ! request()->is('contact')
    )
<script type="text/javascript">
	//<---------------- Login Register ----------->>>>
	onSubmitformLoginRegister = function() {
		  sendFormLoginRegister();
		}

	if (! captcha) {
	    $(document).on('click','#btnLoginRegister',function(s) {
 		 s.preventDefault();
		 sendFormLoginRegister();
 	 });//<<<-------- * END FUNCTION CLICK * ---->>>>
	}

	function sendFormLoginRegister() {
		var element = $(this);
		$('#btnLoginRegister').attr({'disabled' : 'true'});
		$('#btnLoginRegister').find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

		(function(){
			 $("#formLoginRegister").ajaxForm({
			 dataType : 'json',
			 success:  function(result) {

         if (result.actionRequired) {
           $('#modal2fa').modal({
    				    backdrop: 'static',
    				    keyboard: false,
    						show: true
    				});

            $('#loginFormModal').modal('hide');
           return false;
         }

				 // Success
				 if (result.success) {

           if (result.isModal && result.isLoginRegister) {
             window.location.reload();
           }

					 if (result.url_return && ! result.isModal) {
					 	window.location.href = result.url_return;
					 }

					 if (result.check_account) {
					 	$('#checkAccount').html(result.check_account).fadeIn(500);

						$('#btnLoginRegister').removeAttr('disabled');
						$('#btnLoginRegister').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
						$('#errorLogin').fadeOut(100);
						$("#formLoginRegister").reset();
					 }

				 }  else {

					 if (result.errors) {
						 var error = '';
						 var $key = '';

					for ($key in result.errors) {
							 error += '<li><i class="far fa-times-circle"></i> ' + result.errors[$key] + '</li>';
						 }

						 $('#showErrorsLogin').html(error);
						 $('#errorLogin').fadeIn(500);
						 $('#btnLoginRegister').removeAttr('disabled');
						 $('#btnLoginRegister').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

						 if (captcha) {
							grecaptcha.reset();
						 }
					 }
				 }
				},

				statusCode: {
						419: function() {
							window.location.reload();
						}
					},
				error: function(responseText, statusText, xhr, $form) {
						// error
						$('#btnLoginRegister').removeAttr('disabled');
						$('#btnLoginRegister').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
						swal({
								type: 'error',
								title: error_oops,
								text: error_occurred+' ('+xhr+')',
							});
							
						if (captcha) {
							grecaptcha.reset();
						 }
				}
			}).submit();
		})(); //<--- FUNCTION %
	}
</script>
@endif
