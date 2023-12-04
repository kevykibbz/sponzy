(function($) {
"use strict";


const lightbox = GLightbox({
    touchNavigation: true,
    loop: false,
    closeEffect: 'fade'
});

$(function () {
  $('.showTooltip').tooltip()
});

jQuery.fn.reset = function () {
	$(this).each (function() { this.reset(); });
}

function scrollElement(element) {
	var offset = $(element).offset().top;
	$('html, body').animate({scrollTop:offset}, 500);
};

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

//<-------- * TRIM * ----------->
function trim (string) {
	return string.replace(/^\s+/g,'').replace(/\s+$/g,'')
}

$(".toggle-menu, .overlay").on('click', function() {
	$('.overlay').toggleClass('open');
});

$('.isNumber').keypress(function (event) {
			return isNumber(event, this)
});

function isNumber(evt, element) {
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (
			(charCode != 46 || $(element).val().indexOf('.') != -1) &&
			(charCode < 48 || charCode > 57))
			return false;
			return true;
}

$(document).ready(function() {
  $(".onlyNumber").keydown(function (e) {
      // Allow: backspace, delete, tab, escape, enter and .
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
           // Allow: Ctrl+A, Command+A
          (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
           // Allow: home, end, left, right, down, up
          (e.keyCode >= 35 && e.keyCode <= 40)) {
               // let it happen, don't do anything
               return;
      }
      // Ensure that it is a number and stop the keypress
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
          e.preventDefault();
      }
  });
});

$('.select option[value="'+timezone+'"]').attr('selected', 'selected');

$(".select").select2({
    theme: "bootstrap-5",
});

// Delete Post, Categories, Members, Languages, etc...
$(".actionDelete").on('click', function(e) {
   	e.preventDefault();

   	var element = $(this);
    var form    = $(element).parents('form');

	element.blur();

	swal(
		{   title: delete_confirm,
		  type: "warning",
		  showLoaderOnConfirm: true,
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		   confirmButtonText: yes_confirm,
		   cancelButtonText: cancel_confirm,
		    closeOnConfirm: false,
		    },
		    function(isConfirm){
		    	 if (isConfirm) {
		    	 	form.submit();
		    	 	}
          });
		 });

   $('.filter').on('change', function() {
 		window.location.href = $(this).val();
 	});

  // Email Driver
	 $('#emailDriver').on('change', function() {

	     if ($(this).val() == 'mailgun') {
	 			$('#mailgun').slideDown();
	 		} else {
	 				$('#mailgun').slideUp();
	 		}

	     if ($(this).val() == 'ses') {
	 			$('#ses').slideDown();
	 		} else {
	 			$('#ses').slideUp();
	 		}
	 });

     // Refund
		 $(".actionRefund").on('click', function(e) {
		    	e.preventDefault();

		  var element = $(this);
		 	var form    = $(element).parents('form');

		 	element.blur();

		 	swal(
		 		{   title: delete_confirm,
		 		  type: "error",
		 		  showLoaderOnConfirm: true,
		 		  showCancelButton: true,
		 		  confirmButtonColor: "#DD6B55",
		 		   confirmButtonText: yes_confirm_refund,
		 		   cancelButtonText: cancel_confirm,
		 		    closeOnConfirm: false,
		 		    },
		 		    function(isConfirm){
		 		    	 if (isConfirm) {
		 		    	 	form.submit();
		 		    	 	}
		 		    });
		 		});

        $(document).on('change','#sort',function(){
        	 	$('#formSort').submit();
        	 });

           // Approve post
				 $(".actionApprovePost").on('click', function(e) {
				    	e.preventDefault();

				   var element = $(this);
					 var url     = element.attr('href');
					 var form    = $(element).parents('form');

				 	element.blur();

				 	swal(
				 		{   title: delete_confirm,
				 		  type: "warning",
				       text: approve_confirm_post,
				 		  showLoaderOnConfirm: true,
				 		  showCancelButton: true,
				 		  confirmButtonColor: "#52bb03",
				 		   confirmButtonText: yes_confirm_approve_post,
				 		   cancelButtonText: cancel_confirm,
				 		    closeOnConfirm: false,
				 		    },
				 		    function(isConfirm){
				 		    	 if (isConfirm) {
				 		    	 	form.submit();
				 		    	 	}
				 		    	});
				 		 });

             // Delete post
						 $(".actionDeletePost").on('click', function(e) {
						    	e.preventDefault();

						  var element = $(this);
						 	var form    = $(element).parents('form');

						 	element.blur();

						 	swal(
						 		{   title: delete_confirm,
						 		  type: "warning",
						      text: delete_confirm_post,
						 		  showLoaderOnConfirm: true,
						 		  showCancelButton: true,
						 		  confirmButtonColor: "#DD6B55",
						 		   confirmButtonText: yes_confirm_reject_post,
						 		   cancelButtonText: cancel_confirm,
						 		    closeOnConfirm: false,
						 		    },
						 		    function(isConfirm){
						 		    	 if (isConfirm) {
						 		    	 	form.submit();
						 		    	 	}
						 		    });
						 		});

                // Cancel Payment
	 $(".cancel_payment").on('click', function(e) {
	    	e.preventDefault();

	     var element = $(this);
	     var form    = $(element).parents('form');

	 	element.blur();

	 	swal(
	 		{   title: delete_confirm,
	 		  type: "warning",
	       text: cancel_payment,
	 		  showLoaderOnConfirm: true,
	 		  showCancelButton: true,
	 		  confirmButtonColor: "#DD6B55",
	 		   confirmButtonText: yes_cancel_payment,
	 		   cancelButtonText: cancel_confirm,
	 		    closeOnConfirm: false,
	 		    },
	 		    function(isConfirm){
	 		    	 if (isConfirm) {
	 		    	 	form.submit();
	 		    	 	}
	 		    	 });
	 		 });

       // Approve verification request
			 $(".actionApprove").on('click', function(e) {
			    	e.preventDefault();

			   var element = $(this);
				 var url     = element.attr('href');
				 var form    = $(element).parents('form');

			 	element.blur();

			 	swal(
			 		{   title: delete_confirm,
			 		  type: "warning",
			       text: approve_confirm_verification,
			 		  showLoaderOnConfirm: true,
			 		  showCancelButton: true,
			 		  confirmButtonColor: "#52bb03",
			 		   confirmButtonText: yes_confirm_approve_verification,
			 		   cancelButtonText: cancel_confirm,
			 		    closeOnConfirm: false,
			 		    },
			 		    function(isConfirm){
			 		    	 if (isConfirm) {
			 		    	 	form.submit();
			 		    	 	}
			 		    	});
			 		 });

					 // Delete verification request
					 $(".actionDeleteVerification").on('click', function(e) {
					    	e.preventDefault();

					  var element = $(this);
					 	var url     = element.attr('href');
					 	var form    = $(element).parents('form');

					 	element.blur();

					 	swal(
					 		{   title: delete_confirm,
					 		  type: "warning",
					       text: delete_confirm_verification,
					 		  showLoaderOnConfirm: true,
					 		  showCancelButton: true,
					 		  confirmButtonColor: "#DD6B55",
					 		   confirmButtonText: yes_confirm_verification,
					 		   cancelButtonText: cancel_confirm,
					 		    closeOnConfirm: false,
					 		    },
					 		    function(isConfirm){
					 		    	 if (isConfirm) {
					 		    	 	form.submit();
					 		    	 	}
					 		    });
					 		});

              // login as User...
		$(".loginAsUser").on('click', function(e) {
		   	e.preventDefault();

		   	var element = $(this);
		    var form    = $(element).parents('form');

			element.blur();

			swal({
					title: delete_confirm,
					text: login_as_user_warning,
				  type: "warning",
				  showLoaderOnConfirm: true,
				  showCancelButton: true,
				  confirmButtonColor: "#52bb03",
				   confirmButtonText: yes,
				   cancelButtonText: cancel_confirm,
				    closeOnConfirm: false,
				    },
				    function(isConfirm){
				    	 if (isConfirm) {
				    	 	form.submit();
				    	 	}
		          });
				 });

         // Delete Blog
		 $(".actionDeleteBlog").on('click', function(e) {
		    	e.preventDefault();

		    var element = $(this);
				var form    = $(element).parents('form');

		 	element.blur();

		 	swal(
		 		{   title: delete_confirm,
		 		  type: "warning",
		 		  showLoaderOnConfirm: true,
		 		  showCancelButton: true,
		 		  confirmButtonColor: "#DD6B55",
		 		   confirmButtonText: yes_confirm,
		 		   cancelButtonText: cancel_confirm,
		 		    closeOnConfirm: false,
		 		    },
		 		    function(isConfirm){
		 		    	 if (isConfirm) {
		 		    	 	form.submit();
		 		    	 	}
		 		    });
		 		 });

			$("#testSMTP").on('click', function(e) {
					e.preventDefault();
					$('#formTestSMTP').submit();
				});

		// trigger click select photo add bg stories
		$(document).on('click','#btnFileAddBg',function () {
			var _this = $(this);
				$("#fileAddBg").trigger('click');
				   _this.blur();
		   });

		   $("#fileAddBg").on('change', function(e) {
			e.preventDefault();
			$('#btnFileAddBg').find('i').removeClass('bi-plus-lg').addClass('spinner-border spinner-border-sm align-middle me-1');
			$('#formAddBg').submit();
		});

})(jQuery);
