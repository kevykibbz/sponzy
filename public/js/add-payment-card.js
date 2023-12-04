//<--------- Add Payment Card -------//>
(function($) {
	"use strict";

	const stripe = Stripe(key_stripe);
	const elements = stripe.elements();

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

	const cardElement = elements.create('card', {style: style, hidePostalCode: true});

	cardElement.mount('#card-element');
	const cardButton = document.getElementById('card-button');
	const clientSecret = cardButton.dataset.secret;

	// Handle real-time validation errors from the card Element.
	cardElement.addEventListener('change', function(event) {
		var displayError = document.getElementById('card-errors');

			if (event.error) {
				displayError.classList.remove('display-none');
				displayError.textContent = event.error.message;
				$('#card-button').removeAttr('disabled');
				$('#card-button').find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');
			} else {
				displayError.classList.add('display-none');
				displayError.textContent = '';
			}
	});

cardButton.addEventListener('click', async (e) => {

var button = $('#card-button');
var displayError = document.getElementById('card-errors');

button.attr({'disabled' : 'true'});
button.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

	const { setupIntent, error } = await stripe.confirmCardSetup(
			clientSecret, {
					payment_method: {
							card: cardElement,
							billing_details: { name: full_name_user }
					}
			}
	);

	if (error) {
			// Display "error.message" to the user.
			displayError.classList.remove('display-none');
			displayError.textContent = error.message;
			button.removeAttr('disabled');
			button.find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');
	} else {
			// The card has been verified successfully.
			displayError.classList.add('display-none');
			displayError.textContent = '';

			$.ajax({
			headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
			 type: "POST",
			 dataType: 'json',
			 url: URL_BASE+"/settings/payments/card",
			 data: {payment_method: setupIntent.payment_method},
			 success: function(response) {
					 if (response.success == false) {
							$('#success').addClass('display-none');
								button.removeAttr('disabled');
								button.find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');
								$('.popout').removeClass('popout-success').addClass('popout-error').html(payment_card_error).slideDown('500').delay('5000').slideUp('500');
								cardElement.clear();
					 } else {
							$('#success').removeClass('display-none');
								button.removeAttr('disabled');
								button.find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');
								cardElement.clear();
					 }
				}//<-- RESULT
		 }).fail(function(jqXHR, ajaxOptions, thrownError) {
			 $('.popout').removeClass('popout-success').addClass('popout-error').html(payment_card_error).slideDown('500').delay('5000').slideUp('500');
			 button.removeAttr('disabled');
			 button.find('i').removeClass('spinner-border spinner-border-sm align-baseline mr-1');
			 cardElement.clear();
		 });//<--- AJAX
	}
});

})(jQuery);
