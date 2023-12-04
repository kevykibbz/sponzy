//<--------- Start Payment -------//>
(function($) {
	"use strict";

  $('input[name=payment_gateway]').on('click', function() {
		if ($(this).val() == 'Bank') {
			$('#bankTransferBox').slideDown();
		} else {
				$('#bankTransferBox').slideUp();
		}

  });

//======= FILE Bank Transfer
$("#fileBankTransfer").on('change', function() {
 $('#previewImage').html('');

 var loaded = false;
 if(window.File && window.FileReader && window.FileList && window.Blob) {
		 //check empty input filed
	 if($(this).val()) {
			var oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image)$/i;
		 if($(this)[0].files.length === 0){return}

		 var oFile = $(this)[0].files[0];
		 var fsize = $(this)[0].files[0].size; //get file size
		 var ftype = $(this)[0].files[0].type; // get file type

			if(!rFilter.test(oFile.type)) {
			 $('#fileBankTransfer').val('');
				swal({
				 title: error_oops,
				 text: formats_available_images,
				 type: "error",
				 confirmButtonText: ok
				 });
			 return false;
		 }

		 var allowed_file_size = file_size_allowed_verify_account;

		 if(fsize>allowed_file_size){
			 $('.popout').addClass('popout-error').html(max_size_id_lang).fadeIn(500).delay(4000).fadeOut();
				$(this).val('');
			 return false;
		 }
		 $('#previewImage').html('<i class="fas fa-image text-info"></i> <strong>' + oFile.name + '</strong>');

	 }
 } else {
	 alert('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.');
	 return false;
 }
});
//======= END FILE Bank Transfer

//<---------------- Pay tip ----------->>>>
 $(document).on('click','#addFundsBtn',function(s) {
	var isValid = this.form.checkValidity();

	if (isValid) {

	 s.preventDefault();
	 var element = $(this);
	 var form = $(this).attr('data-form');
	 element.attr({'disabled' : 'true'});
	 var payment = $('input[name=payment_gateway]:checked').val();
	 element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

	 (function(){
			$('#formAddFunds').ajaxForm({
			dataType : 'json',
			success:  function(result) {

				// success
				if (result.success && result.instantPayment) {
						window.location.reload();
				}

				if (result.success == true && result.insertBody) {

					$('#bodyContainer').html('');

				 $(result.insertBody).appendTo("#bodyContainer");

				 if (payment != 'PayPal' && payment != 'Stripe') {
					 element.removeAttr('disabled');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
				 }

					$('#errorAddFunds').hide();

				} else if(result.success == true && result.status == 'pending') {

					swal({
					 title: thanks,
					 text: result.status_info,
					 type: "success",
					 confirmButtonText: ok
					 });

					 $('#formAddFunds').trigger("reset");
					 element.removeAttr('disabled');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
					 $('#previewImage').html('');
					 $('#handlingFee, #total, #total2').html('0');
					 $('#bankTransferBox').hide();

				} else if(result.success == true && result.url) {
					window.location.href = result.url;
				} else {

					if (result.errors) {

						var error = '';
						var $key = '';

						for($key in result.errors) {
							error += '<li><i class="far fa-times-circle"></i> ' + result.errors[$key] + '</li>';
						}

						$('#showErrorsFunds').html(error);
						$('#errorAddFunds').show();
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

})(jQuery);
