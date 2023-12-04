//<--------- Start Payment -------//>
(function($) {
	"use strict";

  $('input[name=payment_gateway]').on('click', function() {
    if($(this).val() == 'Stripe') {
      $('#stripeContainer').slideDown();
    } else {
      $('#stripeContainer').slideUp();
    }
  });

	$('input[name=payment_gateway_tip]').on('click', function() {
    if($(this).val() == 'Stripe') {
      $('#stripeContainerTip').slideDown();
    } else {
      $('#stripeContainerTip').slideUp();
    }
  });

	$('input[name=payment_gateway]').on('click', function() {
    if($(this).val() == 'Paystack') {
      $('#paystackContainer').slideDown();
    } else {
      $('#paystackContainer').slideUp();
    }
  });

  $(document).ready(function() {

    //<---------------- Buy Subscription ----------->>>>
			$(document).on('click','.subscriptionBtn',function(s) {
				var isValid = this.form.checkValidity();

				if (isValid) {

				s.preventDefault();
				var element = $(this);
				element.attr({'disabled' : 'true'});
        var payment = $('input[name=payment_gateway]:checked').val();
        element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

				(function(){
					 $("#formSubscription").ajaxForm({
					 dataType : 'json',
					 success:  function(result) {

             // success
             if(result.success == true && result.insertBody) {

               $('#bodyContainer').html('');

              $(result.insertBody).appendTo("#bodyContainer");

              if (payment != 'PayPal' && payment != 'Stripe') {
                element.removeAttr('disabled');
                element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
              }

               $('#error').fadeOut();

             } else if(result.success == true && result.url) {
               window.location.href = result.url;
             } else {

               var error = '';
               var $key = '';

               for($key in result.errors) {
                 error += '<li><i class="far fa-times-circle"></i> ' + result.errors[$key] + '</li>';
               }

               $('#showErrors').html(error);
               $('#error').fadeIn(500);
               element.removeAttr('disabled');
               element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

             }

						},
            error: function(responseText, statusText, xhr, $form) {
                // error
                element.removeAttr('disabled');
                element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
                swal({
                    type: 'error',
                    title: error_oops,
                    text: error_occurred+' ('+xhr+')',
                  });
            }
					}).submit();
				})(); //<--- FUNCTION %
			  }// isValid
			});//<<<-------- * END FUNCTION CLICK * ---->>>>
	//============ End Payment =================//
 });// document ready

 //<---------------- Send Tip ----------->>>>
 if (stripeKey != '' && ! liveMode) {

 // Create a Stripe client.
 var stripe = Stripe(stripeKey);

 // Create an instance of Elements.
 var elements = stripe.elements();

 // Custom styling can be passed to options when creating an Element.
 // (Note that this demo uses a wider set of styles than the guide below.)
 var style = {
	 base: {
		 color: colorStripe,
		 fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
		 fontSmoothing: 'antialiased',
		 fontSize: '16px',
		 '::placeholder': {
			 color: '#aab7c4'
		 }
	 },
	 invalid: {
		 color: '#fa755a',
		 iconColor: '#fa755a'
	 }
 };

 // Create an instance of the card Element.
 var cardElement = elements.create('card', {style: style, hidePostalCode: true});

 // Add an instance of the card Element into the `card-element` <div>.
 cardElement.mount('#card-element');

 // Handle real-time validation errors from the card Element.
 cardElement.addEventListener('change', function(event) {
	 var displayError = document.getElementById('card-errors');
	 var payment = $('input[name=payment_gateway_tip]:checked').val();

	 if (payment == 'Stripe') {
		 if (event.error) {
			 displayError.classList.remove('display-none');
			 displayError.textContent = event.error.message;
			 $('#tipBtn').removeAttr('disabled');
			 $('#tipBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
		 } else {
			 displayError.classList.add('display-none');
			 displayError.textContent = '';
		 }
	 }

 });

 var cardholderName = document.getElementById('cardholder-name');
 var cardholderEmail = document.getElementById('cardholder-email');
 var cardButton = document.getElementById('tipBtn');

 cardButton.addEventListener('click', function(ev) {

	 var payment = $('input[name=payment_gateway_tip]:checked').val();

	 if (payment == 'Stripe') {

	 stripe.createPaymentMethod('card', cardElement, {
		 billing_details: {name: cardholderName.value, email: cardholderEmail.value}
	 }).then(function(result) {
		 if (result.error) {

			 if (result.error.type == 'invalid_request_error') {

					 if(result.error.code == 'parameter_invalid_empty') {
						 $('.popout').addClass('popout-error').html(error).fadeIn('500').delay('8000').fadeOut('500');
					 } else {
						 $('.popout').addClass('popout-error').html(result.error.message).fadeIn('500').delay('8000').fadeOut('500');
					 }
			 }
			 $('#tipBtn').removeAttr('disabled');
			 $('#tipBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

		 } else {

			 $('#tipBtn').attr({'disabled' : 'true'});
			 $('#tipBtn').find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

			 // Otherwise send paymentMethod.id to your server
			 $('input[name=payment_method_id]').remove();

			 var $input = $('<input id=payment_method_id type=hidden name=payment_method_id />').val(result.paymentMethod.id);
			 $('#formSendTip').append($input);

			 $.ajax({
			 headers: {
					 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				type: "POST",
				dataType: 'json',
				url: URL_BASE+"/send/tip",
				data: $('#formSendTip').serialize(),
				success: function(result) {
						handleServerResponse(result);

						if(result.success == false) {
							$('#tipBtn').removeAttr('disabled');
							$('#tipBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
						}
			}//<-- RESULT
			})

		 }//ELSE
	 });
 }//PAYMENT STRIPE
});

 function handleServerResponse(response) {
	 if (response.error) {
		 $('.popout').addClass('popout-error').html(response.error).fadeIn('500').delay('8000').fadeOut('500');
		 $('#tipBtn').removeAttr('disabled');
		 $('#tipBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

	 } else if (response.requires_action) {
		 // Use Stripe.js to handle required card action
		 stripe.handleCardAction(
			 response.payment_intent_client_secret
		 ).then(function(result) {
			 if (result.error) {
				 $('.popout').addClass('popout-error').html(error_payment_stripe_3d).fadeIn('500').delay('10000').fadeOut('500');
				 $('#tipBtn').removeAttr('disabled');
				 $('#tipBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

			 } else {
				 // The card action has been handled
				 // The PaymentIntent can be confirmed again on the server

				 var $input = $('<input type=hidden name=payment_intent_id />').val(result.paymentIntent.id);
				 $('#formSendTip').append($input);

				 $('input[name=payment_method_id]').remove();

				 $.ajax({
				 headers: {
						 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					type: "POST",
					dataType: 'json',
					url: URL_BASE+"/send/tip",
					data: $('#formSendTip').serialize(),
					success: function(result){

						if(result.success) {
							swal({
			 				 title: thanks,
			 				 text: tip_sent_success,
			 				 type: "success",
			 				 confirmButtonText: ok
			 				 });
			 				 $('#tipForm').modal('hide');
			 				 $('.InputElement').val('');
			 				 $('#tipBtn').removeAttr('disabled');
							 $('#formSendTip').trigger("reset");
			 				 $('#tipBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
			 				 cardElement.clear();
							 $('#errorTip').hide();
							 if (result.wallet) {
							 	$('.balanceWallet').html(result.wallet);
							 }

						} else {
							$('.popout').addClass('popout-error').html(result.error).fadeIn('500').delay('8000').fadeOut('500');
							$('#tipBtn').removeAttr('disabled');
							$('#tipBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
						}
				}//<-- RESULT
				})
			 }// ELSE
		 });
	 } else {
		 // Show success message
		 if (response.success) {
			 swal({
				 title: thanks,
				 text: tip_sent_success,
				 type: "success",
				 confirmButtonText: ok
				 });
				 $('#tipForm').modal('hide');
				 $('.InputElement').val('');
				 $('#formSendTip').trigger("reset");
				 $('#tipBtn').removeAttr('disabled');
				 $('#tipBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
				 cardElement.clear();
				 $('.balanceWallet').html(response.wallet);
		 }
	 }
 }
}
// Stripe Elements


//<---------------- Pay tip ----------->>>>
 $(document).on('click','.tipBtn',function(s) {

	var isValid = this.form.checkValidity();

	if (isValid) {
	 s.preventDefault();
	 var element = $(this);
	 var form = $(this).attr('data-form');
	 element.attr({'disabled' : 'true'});
	 var payment = $('input[name=payment_gateway_tip]:checked').val();
	 element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

	 (function(){
			$('#formSendTip').ajaxForm({
			dataType : 'json',
			success:  function(result) {

				// Wallet
				if (result.success == true && payment == 'wallet' || result.success && result.instantPayment) {
					swal({
	 				 title: thanks,
	 				 text: tip_sent_success,
	 				 type: "success",
	 				 confirmButtonText: ok
	 				 });
	 				 $('#tipForm').modal('hide');
	 				 $('.InputElement').val('');
	 				 $('#formSendTip').trigger("reset");
	 				 $('#tipBtn').removeAttr('disabled');
	 				 $('#tipBtn').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
					 	$('.balanceWallet').html(result.wallet);

				}

				// success
				if (result.success == true && result.insertBody) {

					$('#bodyContainer').html('');

				 $(result.insertBody).appendTo("#bodyContainer");

				 if (payment != 'PayPal' && payment != 'Stripe') {
					 element.removeAttr('disabled');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
				 }

					$('#errorTip').hide();

				} else if(result.success == true && result.url) {
					window.location.href = result.url;
				} else {

					if (result.errors) {

						var error = '';
						var $key = '';

						for($key in result.errors) {
							error += '<li><i class="far fa-times-circle"></i> ' + result.errors[$key] + '</li>';
						}

						$('#showErrorsTip').html(error);
						$('#errorTip').show();
						element.removeAttr('disabled');
						element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
					}
				}

			 },
			 error: function(responseText, statusText, xhr, $form) {
					 // error
					 element.removeAttr('disabled');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
					 swal({
							 type: 'error',
							 title: error_oops,
							 text: error_occurred+' ('+xhr+')',
						 });
			 }
		 }).submit();
	 })(); //<--- FUNCTION %
	 }// isValid
 });//<<<-------- * END FUNCTION CLICK * ---->>>>
//============ End Payment =================//

function toFixed(number, decimals) {
			var x = Math.pow(10, Number(decimals) + 1);
			return (Number(number) + (1 / x)).toFixed(decimals);
		}

$('#tipForm').on('show.bs.modal', function (event) {
  	var button = $(event.relatedTarget) // Button that triggered the modal
  	var coverUser = button.data('cover') // Extract info from data-* attributes
	var avatarUser = button.data('avatar') // Extract info from data-* attributes
	var fullNameUser = button.data('name') // Extract info from data-* attributes
	var userId = button.data('userid') // Extract info from data-* attributes

	if (coverUser != '') {
		var _coverUser = 'url("'+coverUser+'")';
	} else {
		var _coverUser = null;
	}

  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this);
  modal.find('.card-header').css({height: '100px', background: color_default+' '+_coverUser+' no-repeat center center', backgroundSize: 'cover' });
  modal.find('.avatar-modal').attr('src', avatarUser);
	modal.find('.userNameTip').html(fullNameUser);
	modal.find('.userIdInput').val(userId);

	var tipAmount = modal.find('.tipAmount');
	var minTipAmount = modal.find('.tipAmount').attr('data-min-tip');
	var maxTipAmount = modal.find('.tipAmount').attr('data-max-tip');
	var taxes = modal.find('li.isTaxable').length;


	$(tipAmount).on('keyup', function() {

		var totalTax = 0;

		var valueOriginal = $(this).val();
    var amount = parseFloat($(this).val());

		if (valueOriginal.length == 0
				|| valueOriginal == ''
				|| amount < minTipAmount
				|| amount > maxTipAmount
			) {
			// Reset
			for (var i = 1; i <= taxes; i++) {
				modal.find('.amount'+i).html('0');
				modal.find('.subtotalTip').html('0');
				modal.find('.totalTip').html('0');
			}
		} else {
			// Taxes
			for (var i = 1; i <= taxes; i++) {
				var percentage = modal.find('.percentageAppliedTax'+i).attr('data');
				var value = (amount * percentage / 100);
				modal.find('.amount'+i).html(toFixed(value, decimalZero));
				totalTax += value;
			}

			var totalTaxes = (Math.round(totalTax * 100) / 100).toFixed(2);
			modal.find('.subtotalTip').html(parseFloat(valueOriginal).toFixed(decimalZero));


			var totalTip = parseFloat((parseFloat(valueOriginal) + parseFloat(totalTaxes))).toFixed(decimalZero);
			modal.find('.totalTip').html(totalTip);
		}

	});

});// show.bs.modal

$('#tipForm').on('hidden.bs.modal', function (e) {
	var modal = $(this);
  $('#errorTip').hide();
	$('#formSendTip').trigger("reset");
	$('#card-errors').addClass('display-none');
	$('.InputElement').val('');
	$('#card-element').removeClass('StripeElement--invalid');

	var taxes = modal.find('li.isTaxable').length;

	for (var i = 1; i <= taxes; i++) {
		modal.find('.amount'+i).html('0');
		modal.find('.subtotalTip').html('0');
		modal.find('.totalTip').html('0');
	}
});

// Delete Card Stripe
$("#deleteCardStripe").on('click', function(e) {
		e.preventDefault();

		var element = $(this);
		element.blur();

	swal(
		{   title: delete_confirm,
		 type: "error",
		 showLoaderOnConfirm: true,
		 showCancelButton: true,
		 confirmButtonColor: "#DD6B55",
		 confirmButtonText: yes_confirm,
		 cancelButtonText: cancel_confirm,
		 closeOnConfirm: false,
	 },
	 function(isConfirm){
					 if (isConfirm) {
						$('#formDeleteCardStripe').submit();
						}
					 });
		 });// End Delete Card Stripe

		 // Delete Card Paystack
		 $("#deleteCardPaystack").on('click', function(e) {
		 		e.preventDefault();

		 		var element = $(this);
		 		element.blur();

		 	swal(
		 		{   title: delete_confirm,
		 		 type: "error",
		 		 showLoaderOnConfirm: true,
		 		 showCancelButton: true,
		 		 confirmButtonColor: "#DD6B55",
		 		 confirmButtonText: yes_confirm,
		 		 cancelButtonText: cancel_confirm,
		 		 closeOnConfirm: false,
		 	 },
		 	 function(isConfirm){
		 					 if (isConfirm) {
		 						$('#formDeleteCardPaystack').submit();
		 						}
		 					 });
		 		 });// End Delete Card Paystack

})(jQuery);
