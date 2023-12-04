//<--------- waiting -------//>
(function($) {
	"use strict";
$.fn.waiting = function(p_delay) {
	var $_this = this.first();
	var _return = $.Deferred();
	var _handle = null;

	if ($_this.data('waiting') != undefined) {
		$_this.data('waiting').rejectWith( $_this );
		$_this.removeData('waiting');
	}
	$_this.data('waiting', _return);
	_handle = setTimeout(function(){
		_return.resolveWith( $_this );
	}, p_delay );
	_return.fail(function(){
		clearTimeout(_handle);
	});
	return _return.promise();
};
})(jQuery);


(function($) {
"use strict";

// Init autosize
autosize($('.textareaAutoSize'));
// Init Plyr
const players = Plyr.setup('.js-player', {ratio: '4:3'});

// Owl Carousel
$('.owl-carousel').owlCarousel({
  margin:10,
  items : user_count_carousel,
  responsive: {
    0:{
          items:1
      },
      600:{
          items:2
      },
      1000:{
          items:3
      }
  }
});

// Check boxButton
$('.checkboxButton').on('click', function(){
  $amount = $(this).attr('data-amount');
  $('#onlyNumber').val($amount);
});

$.fn.modal.Constructor.prototype._enforceFocus = function() {};

// Copy Link
$('#btn_copy_url').on('click', function(){
	copyToClipboard('#copy_link', this);
});

function copyToClipboard(element,btn) {
    var $temp = $('<input>');
    $("body").append($temp);
    $temp.val($(element).val()).select();
		$(element).select().focus();
    document.execCommand("copy");
		$(btn).html('<i class="fa fa-check"></i> <span class="btn-block mt-3">'+copied+'</span>');
    setTimeout(function(){$(btn).html('<i class="fas fa-link"></i> <span class="btn-block mt-3">'+copy_link+'</span>'); }, 1000);
    $temp.remove();
    }
	// End Copy Link

// Button Delete Account
$('#buttonDeleteAccount').on('click', function() {
	if (this.form.checkValidity()) {
		$(this).attr({'disabled' : 'true'}).html(please_wait);
  		$('#formSend').submit();
	}
});

// Allowed only numbers
$("#onlyNumber, .onlyNumber").keydown(function (e) {
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
	});// End Allowed only numbers

//<----- Notifications
function Notifications() {
	 console.time('cache');

	 $.get(URL_BASE+"/ajax/notifications", function(data) {
		if (data) {

		 if(data.message == 'Unauthenticated.') {
			 window.location.reload();
		 }

		 if(data.notifications == 0) {
				$('title').html(_title);
		 }

		 //* Messages */
		if(data.messages != 0 ) {
			var totalMsg = data.messages;
			$('.noti_msg').removeClass('d-none').html(data.messages).fadeIn();
		} else {
			$('.noti_msg').removeClass('d-block').addClass('d-none').html('');

			if(data.notifications == 0) {
				 $('title').html(_title);
			}
		}

			//* Notifications */
			if(data.notifications != 0) {
				var totalNoty = data.notifications;
				$('.noti_notifications').removeClass('d-none').html(data.notifications).fadeIn();
			} else {
				$('.noti_notifications').removeClass('d-block').addClass('d-none').html('');
			}

			//* Error */
			if (data.error) {
				window.location.reload();
			}

			var totalGlobal = parseInt(totalMsg) + parseInt(totalNoty);

			if(data.notifications == 0 && data.messages == 0) {
				$('.notify').removeClass('d-block').addClass('d-none');
			}

		 if( data.notifications != 0 && data.messages != 0 ) {
				$('title').html( "("+ totalGlobal + ") " + _title );
			} else if( data.notifications != 0 && data.messages == 0 ) {
				$('title').html( "("+ data.notifications + ") " + _title );
			} else if( data.notifications == 0 && data.messages != 0 ) {
				$('title').html( "("+ data.messages + ") " + _title );
			}

		}//<-- DATA

		},'json').fail(function(jqXHR, ajaxOptions, thrownError)
		{
			if (thrownError == 'Unauthorized') {
				window.location.reload();
			}
		});

		console.timeEnd('cache');
}

// Initiator notifications
if (session_status == 'on') {
	setInterval(Notifications, 5000);
}
//End Notifications

jQuery.fn.reset = function () {
	$(this).each (function() { this.reset(); });
}
// Scroll element function
function scrollElement(element) {
	var offset = $(element).offset().top;
	$('html, body').animate({scrollTop:offset}, 500);
};

if (error_scrollelement == true) {
	scrollElement('#dangerAlert');
}

// Escape HTML
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

//<-------- * TRIM * ----------->
function trim(string) {
	return string.replace(/^\s+/g,'').replace(/\s+$/g,'')
}
// Bootstrap Tooltip
$(function () {
	$('[data-toggle="tooltip"]').tooltip()
})
// Bootstrap Popover
$(function () {
  $('[data-toggle="popover"]').popover()
})

$(function () {
    $('a[href="#search"]').on('click', function(event) {
        event.preventDefault();
        $('#search').addClass('open');
        $('#search > form > input[type="text"]').focus();
        $('body').css({overflow:'hidden'})
    });

    $('#search, #search button.close').on('click keyup', function(event) {
        if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
            $(this).removeClass('open');
            $('body').css({overflow:'auto'});
            $('#search > form > input[type="search"]').blur();
        }
    });

});
// Function truncate text on paragraph
function textTruncate(element, text) {
var descHeight = $(element).outerHeight();

 if( descHeight > 500 ) {
 	$(element).addClass('truncate').append('<span class="btn-block text-center color-default font-default readmoreBtn"><strong>'+text+'</strong></span>');
 }

 $(document).on('click','.readmoreBtn', function(){
 	$(element).removeClass('truncate');
 	$(this).remove();
	});
}//<<--- End

$(".navbar-toggler, .navbar-toggler-mobile").on('click', function() {
	$('.collapsing').toggleClass('show');
	$('body').toggleClass("sidebar-overlay overflow-hidden");
});

// Close menu mobile
$(".close-menu-mobile").on('click', function() {
$('body').removeClass("sidebar-overlay overflow-hidden");
});

// Bootstrap Toogle
function toggleNavbarMethod() {
     if ($(window).width() > 768) {
         $('.dropdown-Torner').on('mouseover', function(){
	        $(this).addClass('show');
			$(this).find('.dropdown-toggle').attr("aria-expanded","true");
	    }, function(){
	        $(this).removeClass('show');
			$(this).find('.dropdown-toggle').attr("aria-expanded","false");
	    });
		$('.btn-register-menu').removeClass('mobileButton');
     }
     else {
         $('.navbar .dropdown').off('mouseover').off('mouseout');
		 $('.btn-register-menu').removeClass('btn-light').addClass('btn-primary mobileButton');
     }
 }
 toggleNavbarMethod();
 $(window).resize(toggleNavbarMethod);

	$('.counterStats').counterUp({
		delay: 10,
		time: 1000
	});

	var logo = URL_BASE +'/public/img/' + $(".logo").attr('data-logo');
	var logo2 = URL_BASE +'/public/img/' + $(".logo").attr('data-logo-2');

	if ($(document).scrollTop() > $(".scroll").height()) {
		let buttonRegister = $('.btn-register-menu');
		$(".logo").attr('src', logo2);
		$('.navbar-toggler').removeClass('text-white');
		$('.search-bar').removeClass('border-0');
		$(".scroll").addClass('shadow-custom navbar_background_color p-nav-scroll link-scroll');

		if (!buttonRegister.hasClass('mobileButton')) {
			$('.btn-register-menu').removeClass('btn-light').addClass('btn-primary');
		}
	}

	/* Scroll Header */
	$(function () {
		$(document).scroll(function () {
			let $nav = $(".scroll");
			let buttonRegister = $('.btn-register-menu');
			$nav.toggleClass('shadow-custom navbar_background_color p-nav-scroll link-scroll', $(this).scrollTop() > $nav.height());

			if ($(this).scrollTop() > $nav.height()) {
				$(".logo").attr('src', logo2);
				$('.navbar-toggler').removeClass('text-white');
				$('.search-bar').removeClass('border-0');

				if (!buttonRegister.hasClass('mobileButton')) {
					$('.btn-register-menu').removeClass('btn-light').addClass('btn-primary');
				}
			}
			if ($(this).scrollTop() < $nav.height()) {
				$(".logo").attr('src', logo);
				$('.navbar-toggler').addClass('text-white');
				$('.search-bar').addClass('border-0');

				if (!buttonRegister.hasClass('mobileButton')) {
					$('.btn-register-menu').removeClass('btn-primary').addClass('btn-light');
				}
				
			}
		});
	});

$('#filter, .filter').on('change', function() {
	window.location.href = $(this).val();
});
jQuery(".timeAgo").timeago();

$(document).on('click','#avatar_file',function () {
		var _this = $(this);
	    $("#uploadAvatar").trigger('click');
	     _this.blur();
	});

	//<---------------- UPLOAD UPDATE/POST ----------->>>>
	$(document).on('click','#btnCreateUpdate',function(s) {

		s.preventDefault();
		var element = $(this);
		var $empty = element.attr('data-empty');
		var $error = element.attr('data-error');
		var $errorMsg = element.attr('data-msg-error');
		var description = $('#updateDescription').val();
		var postLength = $('#updateDescription').attr('data-post-length');
		var input = element.parents('form').find('input[name=price]');
		var inputTitle = element.parents('form').find('input[name=title]');

		element.attr({'disabled' : 'true'});
		element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');


		if (trim(description).length  == 0) {
			$('#updateDescription').focus();
			element.removeAttr('disabled');

			$('#showErrorsUdpate').html('<li><i class="fa fa-times-circle"></i> ' + $empty + '</li>');
			$('#errorUdpate').fadeIn(500);
			return false;
		}

		$('#progress, .blocked').show();

		(function() {

			var bar = $('.progress-bar');
			var percent = $('.percent');
			var percentVal = '0%';

			 $("#formUpdateCreate").ajaxForm({
			 dataType : 'json',
			 error: function(responseText, statusText, xhr, $form) {
				element.removeAttr('disabled');

				if(!xhr) {
					xhr = '- ' + $errorMsg;
				} else {
					xhr = '- ' + xhr;
				}

				$('.popout').removeClass('popout-success').addClass('popout-error').html($error+' '+xhr+'').fadeIn('500').delay('5000').fadeOut('500');
					 $('#progress, .blocked').hide();
					 bar.width(percentVal).removeClass('bg-success').addClass('bg-primary');
					 percent.html(percentVal).removeClass('text-success');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
			 },
			 beforeSend: function() {
	        bar.width(percentVal);
	        percent.html(percentVal);
	    },
	    uploadProgress: function(event, position, total, percentComplete) {
	        var percentVal = percentComplete + '%';
	        bar.width(percentVal);
	        percent.html(percentVal);

					if(percentComplete == 100) {
						bar.removeClass('bg-primary').addClass('bg-success');
						percent.addClass('text-success');
					}
	    },
			 success: function(result) {

			 //===== SUCCESS =====//
			 if (result.success != false && ! result.pending) {

				 $('#progress, .blocked').hide();
				 bar.width(percentVal).removeClass('bg-success').addClass('bg-primary');
				 percent.html(percentVal).removeClass('text-success');

				 $('#updateDescription').val('').css({height: '116px', transition: 'height .6s ease'});
				  $('#filePhoto').val('');
					$('#fileZip').val('');
					$('#inputScheduled').val('');
					$('#textPostPublish').html(publish);

					if (result.total == 1) {
						if ($('.advertising').length != 0) {
							$(result.data).hide().insertAfter('.advertising').fadeIn(500);
						} else {
							$(result.data).hide().prependTo('.grid-updates').fadeIn(500);
						}
						
					} else {
						$(result.data).hide().insertBefore('.card-updates:first').fadeIn(500);
					}
					

				 $(function () {
					 $('[data-toggle="tooltip"]').tooltip()
				 });

					jQuery(".timeAgo").timeago();
					$('.no-updates').remove();
					$('#removePhoto, #dateScheduleContainer').hide();
					$('#previewImage').html('');
					$('#maximum').html(postLength).css({color: '#666', fontWeight: 'normal'});

					$('#errorUdpate').fadeOut(500);
					$('#showErrorsUdpate').html('');
					element.addClass('e-none');
					element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

					const lightbox = GLightbox({
					    touchNavigation: true,
					    loop: false,
					    closeEffect: 'fade'
					});

					readMoreText();

					const players = Plyr.setup('.js-player', {ratio: '4:3'});

					if (input.hasClass('active')) {
			 		 input.val('');
			 		 $('#price').slideToggle(100);
			 		 input.removeClass('active');
			 		 $('#setPrice').removeClass('btn-active-hover');
			 		 input.blur();
			 	 }

				 if (inputTitle.hasClass('active')) {
					inputTitle.val('');
					$('#titlePost').slideToggle(100);
					inputTitle.removeClass('active');
					$('#setTitle').removeClass('btn-active-hover');
					inputTitle.blur();
				}

				 var api = $.fileuploader.getInstance($('input[name="photo[]"]'));
		 		api.reset();
		 		$('.fileuploader-thumbnails-input').show();

		 			 if ($('.fileuploader').hasClass('d-block')) {
		 				 $('.fileuploader').toggleClass('d-block');
		 			 }

				 } else if (result.success && result.pending) {

					 if (!result.encode && !result.schedule) {
						 // Success post pending
  					 $('#alertPostPending').fadeIn();
					 } if (! result.encode && result.schedule) {
						// Success post schedule
					  $('#alertPostSchedule').fadeIn();
					} else {
						 swal({
							 type: 'info',
							 title: video_on_way,
							 text: video_processed_info,
							 confirmButtonText: ok
			     		});
					 }


					 $('#progress, .blocked').hide();
					 bar.width(percentVal).removeClass('bg-success').addClass('bg-primary');
					 percent.html(percentVal).removeClass('text-success');

					 $('#updateDescription').val('').css({height: '116px', transition: 'height .6s ease'});
					  $('#filePhoto').val('');
						$('#fileZip').val('');
						$('#inputScheduled').val('');
						$('#textPostPublish').html(publish);

						$('#removePhoto, #dateScheduleContainer').hide();
						$('#previewImage').html('');
						$('#maximum').html(postLength).css({color: '#666', fontWeight: 'normal'});

						$('#errorUdpate').fadeOut(500);
						$('#showErrorsUdpate').html('');
						element.addClass('e-none');
						element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

						if (input.hasClass('active')) {
				 		 input.val('');
				 		 $('#price').slideToggle(100);
				 		 input.removeClass('active');
				 		 $('#setPrice').removeClass('btn-active-hover');
				 		 input.blur();
				 	 }

					 if (inputTitle.hasClass('active')) {
						inputTitle.val('');
						$('#titlePost').slideToggle(100);
						inputTitle.removeClass('active');
						$('#setTitle').removeClass('btn-active-hover');
						inputTitle.blur();
					}

					 var api = $.fileuploader.getInstance($('input[name="photo[]"]'));
			 		api.reset();
			 		$('.fileuploader-thumbnails-input').show();

			 			 if ($('.fileuploader').hasClass('d-block')) {
			 				 $('.fileuploader').toggleClass('d-block');
			 			 }

				 } else {
				$('#progress, .blocked').hide();
				bar.width(percentVal).removeClass('bg-success').addClass('bg-primary');
				percent.html(percentVal).removeClass('text-success');

				var error = '';
				var $key = '';

				for( $key in result.errors ) {
					error += '<li><i class="fa fa-times-circle"></i> ' + result.errors[$key] + '</li>';
				}

				$('#showErrorsUdpate').html(error);
				$('#errorUdpate').fadeIn(500);

				element.removeAttr('disabled');
				element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
			}
		}//<----- SUCCESS
	}).submit();
	})(); //<--- FUNCTION %
	});//<<<-------- * END FUNCTION CLICK UPDATE * ---->>>>

	/*========= Like ==============*/
	$(document).on('click','.likeButton',function(e) {
		var element     = $(this);
		var id          = element.attr("data-id");
		var data        = 'id=' + id;

		e.preventDefault();

		element.blur();

			 $.ajax({
			 	headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    		},
			   type: "POST",
			   url: URL_BASE+"/ajax/like",
				 dataType: 'json',
			   data: data,
			   success: function(result) {

			   	if (result.success) {
					element.parents('.card-updates').find('.countLikes').html(result.total);

						if (element.hasClass('active')) {
								element.removeClass('active');
								element.find('i').removeClass('fas fa-heart').addClass('far fa-heart');
								element.parents('.card-updates').find('.countLikes').html(result.total);

								if (is_likes) {
									element.parents('.card-updates').slideUp(400, function() {
										element.parents('.card-updates').remove();
									});
								}

							} else {
								element.addClass('active');
								element.find('i').removeClass('far fa-heart').addClass('fas fa-heart');
							}

			   	} else {
						window.location.reload();
						element.removeClass('likeButton');
						element.removeClass('active');

			   	}
			 }//<-- RESULT
		   }).fail(function(jqXHR, ajaxOptions, thrownError)
			 {
				 $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
			 });//<--- AJAX

	});//<----- LIKE

	//============= Comments
	$(document).on('keypress','.comments',function(e) {

		if (e.which == 13) {

		var element = $(this);
		var isReplyTo = element.parents('.container-comments ').find('.isReplyTo');
		var inputIsReply = element.parents('.container-comments ').find('.isReply');
		e.preventDefault();
		element.blur();

		element.parents('.card-footer').find('.blocked').show();

		$.ajax({
			 	headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    		},
			   type: "POST",
			   url: URL_BASE+"/comment/store",
			   dataType: 'json',
			   data: element.parents('.card-footer').find(".comments-form").serialize(),
			   success: function(result){

			   	if (result.success) {
			   		element.parents('.card-footer').find('.comments').val('');
			   		element.parents('.card-footer').find('.dangerAlertComments').fadeOut(1);
					
					if (result.isReply) {
						element.parents('.card-footer').find('.wrap-comments'+result.idComment).append(result.data);
						jQuery(".timeAgo").timeago();
					} else {
						element.parents('.card-footer').find('.container-media').append(result.data);
						jQuery(".timeAgo").timeago();
					}

					element.parents('.card-footer').find('.totalComments').html(result.total);
					element.parents('.card-footer').find('.blocked').hide();
					isReplyTo.slideUp(50);
					inputIsReply.val('');

			   	} else {
			   		var error = '';
					var $key  = '';

		            for ($key in result.errors) {
		            	error += '<li><i class="fa fa-times-circle"></i> ' + result.errors[$key] + '</li>';
		            }

					element.parents('.card-footer').find('.showErrorsComments').html(error);
					element.parents('.card-footer').find('.dangerAlertComments').fadeIn(500);
					element.parents('.card-footer').find('.blocked').hide();
				}
			 }//<-- RESULT
		   }).fail(function(jqXHR, ajaxOptions, thrownError)
			 {
				$('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
				 element.removeAttr('disabled');
			 });//<--- AJAX

		 }//e.which == 13
	});//<----- CLICK

	// Comment Btn Focus
	$(document).on('click','.comment-btn-focus',function(e){

		var element = $(this);

		$value = element.parents('.card-footer').find('.comments');
	      scrollElement($value);
				$value.focus();
	});//<----- CLICK

	//<----- DELETE REPLY
    $(document).on('click','.delete-replies', function(e){

		e.preventDefault();
 
		var element     = $(this);
		var id          = element.attr("data");
		element.blur();
 
	  $.ajaxSetup({
		 headers: {
			 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 }
	 });
 
	swal(
		{   title: delete_confirm,
			text: confirm_delete_comment,
			 type: "error",
			 showLoaderOnConfirm: true,
			 showCancelButton: true,
			 confirmButtonColor: "#DD6B55",
				confirmButtonText: yes_confirm,
				cancelButtonText: cancel_confirm,
				 closeOnConfirm: true,
				},
				function(isConfirm){
					   if (isConfirm) {
						$.post(URL_BASE+"/reply/delete/"+id, function(data) {
				if (data.success) {
					element.parents('.media').fadeOut( 400, function() {
						element.parents('.card-footer').find('.totalComments').html(data.total);
						element.parents('.media').remove();
					  });
				} else {
				  $('.popout').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
				}
						 }).fail(function(jqXHR, ajaxOptions, thrownError)
				   {
					   $('.popout').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
				   });
						}
					   });
	   });//<----- DELETE REPLY


	// Maximum length Update Edit Post
	$('.updateDescription').on('keyup', function() {

			var element = $(this).val();

			if (trim(element).length >= 1) {
				$(this).parents('form').find('.btnEditUpdate, .btnEditComment').removeAttr('disabled').removeClass('e-none');
				return false;
			} else {
				$(this).parents('form').find('.btnEditUpdate, .btnEditComment').attr({'disabled' : 'true'}).addClass('e-none');
				return false;
			}
		});

	// Maximum length Update
	$(document).on('keyup','#updateDescription', function(){
			var element = $(this).val();
			var maximum = $(this).attr('data-post-length');
			var button  = $('#btnCreateUpdate');

			if (trim(element).length >= 1 && trim(element).length <= maximum) {
				button.removeAttr('disabled').removeClass('e-none');
				return false;
			} else {
				button.attr({'disabled' : true}).addClass('e-none');
				return false;
			}
		});

		// Maximum length Post
		$('#updateDescription').on('keyup', function() {

	  var characterCount = $(this).val().length,
	      maximum = $('#maximum'),
	      theCount = $('#the-count'),
				postLength = $(this).attr('data-post-length');

	  maximum.text(postLength-characterCount);

	  if (characterCount >= postLength) {
	    maximum.css('color', '#ff0000');
	    maximum.css('font-weight','bold');
	  } else {
	    maximum.css('color', '#666');
	    maximum.css('font-weight','normal');
	  }
	});

	// Copy Link
	var clip = new ClipboardJS('.copy-url');

	clip.on("success", function() {
	  $('.popout').removeClass('popout-error').addClass('popout-success').html('<i class="fa fa-check mr-1"></i> '+copiedSuccess).slideDown('200').delay('3000').slideUp('50');
	});

 $(window).on('scroll', function () {
 				if ($(document).height() - $(this).height() - 100 < $(this).scrollTop()) {
 					 $('#paginator').trigger('click');
 				}
 		}).scroll();

	$(document).ready(function(){
	    $(".js-player").removeClass('invisible');
	});

$('#sendMessageUser').on('click', function() {
	window.location.href = $(this).attr('data-url');
});

// Cookies
$(document).ready(function() {
	if (Cookies.get('cookiePolicy'));
	else {
		$('.showBanner').fadeIn();
			$("#close-banner").on('click', function() {
					$(".showBanner").slideUp(50);
					Cookies.set('cookiePolicy', true, { expires: 365 });
				});
			}
		});

		// Cookie Alert Adult
		if (alert_adult) {
		$(document).ready(function() {
			if (Cookies.get('alertAdult'));
		 else {
			 $('#alertAdult').modal({
				    backdrop: 'static',
				    keyboard: false,
						show: true
				});

				 $("#btnAlertAdult").on('click', function() {
					 $('#alertAdult').modal('hide');
						 Cookies.set('alertAdult', true, { expires: 365 });
					 });
		 }

	 		});
		}

		// Cookies Announcements
		$(document).ready(function() {
			if (Cookies.get('announcementsAlert'+announcement_cookie));
			else {
				$('.announcements').show();
					$("#closeAnnouncements").on('click', function() {
							$(".announcements").slideUp(50);
							Cookies.set('announcementsAlert'+announcement_cookie, true, { expires: 365 });
						});
					}
				});

		//<----- DELETE COMMENT
		$(document).on('click','.delete-comment', function(e) {

		 e.preventDefault();

		 var element      = $(this);
	 	 var id           = element.attr("data");
		 var type         = element.attr("data-type");
		 var input        = element.parents('.container-comments ').find('.inputComment');
		 var inputIsReply = element.parents('.container-comments ').find('.isReply');
		 var isReplyTo    = element.parents('.container-comments ').find('.isReplyTo');
		 
		 element.blur();

	   $.ajaxSetup({
	      headers: {
	          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	      }
	  });

		swal(
			{
			title: delete_confirm,
				text: confirm_delete_comment,
				type: "error",
				showLoaderOnConfirm: true,
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
					confirmButtonText: yes_confirm,
					cancelButtonText: cancel_confirm,
					closeOnConfirm: true,
					},
					function(isConfirm) {
							if (isConfirm) {
							$.post(URL_BASE+"/ajax/delete-comment/"+id, function(data) {
						if (data.success) {
						element.parents('.wrapComments').fadeOut( 400, function() {
							element.parents('.card-footer').find('.totalComments').html(data.total);
							element.parents('.wrapComments').remove();

							if (inputIsReply.val() === id && type === 'isComment') {
								input.val('');
								isReplyTo.slideUp(50);
								inputIsReply.val('');
							 }
						});
					} else {
					$('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
					}
				}).fail(function(jqXHR, ajaxOptions, thrownError)
					{
						$('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
					});
				}
			});
	    });//<----- End DELETE COMMENT

// Delete Update (Post)
$(document).on('click','.actionDelete', function(e) {

   e.preventDefault();

   var element = $(this);
   var form    = $(element).parents('form');
   element.blur();

 swal(
   {   title: delete_confirm,
    text: confirm_delete_update,
     type: "error",
     showLoaderOnConfirm: true,
     showCancelButton: true,
     confirmButtonColor: "#DD6B55",
      confirmButtonText: yes_confirm,
      cancelButtonText: cancel_confirm,
       closeOnConfirm: true,
       },
       function(isConfirm){

				 if (isConfirm) {
					 (function() {
			        form.ajaxForm({
			        dataType : 'json',
			        success:  function(response) {
			          if (response.success) {

									// Determine the location of the post
									if (response.inPostDetail) {
										window.location.href = response.url_return;
									} else {
										element.parents('.card-updates').slideUp(150, function() {
											element.parents('.card-updates').remove();
										});
									}
			          } else {
									swal({
											type: 'info',
											title: error_oops,
											text: response.message,
										});
			          }
			        },
			        error: function(responseText, statusText, xhr, $form) {
			             // error
			             swal({
			                 type: 'error',
			                 title: error_oops,
			                 text: ''+error_occurred+' ('+xhr+')',
			               });
			         }
			       }).submit();
			     })(); //<--- FUNCTION %
				 } // isConfirm
        });
    });// End Delete Update (Post)

		//<<==================== PAGINATOR COMMENTS
	  $(document).on('click','.loadMoreComments', function(e) {

	    e.preventDefault();

	  var container = $(this).parents('.container-media');
	  var allElements = $(container).find('div.isCommentWrap').length;
	  var post = $(this).parents('.wrap-container').attr('data-id');
	  var wrapContainer = $(this).parents('.wrap-container');

	  wrapContainer.html('<a href="javascript:void(0)"><span class="line-replies"></span>'+loading+'</a>');

	  $.ajax({
	    url: URL_BASE+'/loadmore/comments?post=' + post + '&skip=' + allElements,
			dataType: 'json'
	  }).done(function(data) {

	    if (data.comments) {

	      wrapContainer.html('');

	      $(data.comments).hide().insertAfter(wrapContainer).fadeIn(500);
	      wrapContainer.remove();

	      jQuery(".timeAgo").timeago();
	      readMoreText();

	    } else {
	      $('.popout').addClass('popout-error').html(error_reload_page).slideDown('500').delay('5000').slideUp('500');
	    }
	    //<**** - Tooltip
	  }).fail(function(jqXHR, ajaxOptions, thrownError)
	  {
	    $('.popout').addClass('popout-error').html(error_reload_page).slideDown('500').delay('5000').slideUp('500');
	  });//<--- AJAX
	});
	//<<==================== END PAGINATOR COMMENTS

	//<<==================== PAGINATOR UPDATES
	$(document).on('click','#updatesPaginator .loadPaginator', function(e) {

	    e.preventDefault();
	    $(this).remove();
	    $('<div class="card mb-3 pb-4 loadMoreSpin rounded-large shadow-large"> <div class="card-body"> <div class="media"> <span class="rounded-circle mr-3"> <span class="item-loading position-relative loading-avatar"></span> </span> <div class="media-body"> <h5 class="mb-0 item-loading position-relative loading-name"></h5> <small class="text-muted item-loading position-relative loading-time"></small> </div> </div> </div> <div class="card-body pt-0 pb-3"> <p class="mb-1 item-loading position-relative loading-text-1"></p> <p class="mb-1 item-loading position-relative loading-text-2"></p> <p class="mb-0 item-loading position-relative loading-text-3"></p> </div> </div>').appendTo( "#updatesPaginator");

	    var allElements = $('div.card-updates').length;

			var q = query != '' ? 'q='+query+'&' : '';
			var filter = sortBy != '' ? 'sort='+sortBy+'&' : ''

			if (is_profile == true) {
				var url_pagination = URL_BASE+'/ajax/updates?'+filter+'id='+profile_id+sort_post_by_type_media+'&skip=' + allElements;
			} else if (is_bookmarks == true) {
				var url_pagination = URL_BASE+'/ajax/user/bookmarks?skip=' + allElements;
			} else if (is_likes == true) {
				var url_pagination = URL_BASE+'/ajax/user/likes?skip=' + allElements;
			} else if (is_purchases == true) {
				var url_pagination = URL_BASE+'/ajax/user/purchases?skip=' + allElements;
			} else if (is_explore == true) {
				var url_pagination = URL_BASE+'/ajax/explore?'+filter+''+q+'skip=' + allElements;
			} else {
				var url_pagination = URL_BASE+'/ajax/user/updates?skip=' + allElements;
			}

	    $.ajax({
	      url: url_pagination
	    }).done(function(data){
	      if(data) {
	        $('.loadMoreSpin').remove();

	        $(data).appendTo("#updatesPaginator");

	        $(function () {
	 				 $('[data-toggle="tooltip"]').tooltip()
	 			 });

	        jQuery(".timeAgo").timeago();
			
	        readMoreText();

					$( '.content-locked > a' ).on('mouseover', function() {
			     $( this ).parents('.content-locked').find('.ico-no-result').addClass('icon-unlock').removeClass('icon-lock');
			   })
			   .on('mouseout', function() {
			     $( this ).parents('.content-locked').find('.ico-no-result').removeClass('icon-unlock').addClass('icon-lock');
			   });

	        const players = Plyr.setup('.js-player', {ratio: '4:3'});

					const lightbox = GLightbox({
					    touchNavigation: true,
					    loop: false,
					    closeEffect: 'fade'
					});

	      } else {
	        $('.popout').html(error_occurred).slideDown('500').delay('2500').slideUp('500');
	      }
	      //<**** - Tooltip
	    }).fail(function(jqXHR, ajaxOptions, thrownError)
	    {
	      $('.popout').html(error_reload_page).slideDown('500').delay('2500').slideUp('500');
	    });//<--- AJAX
	  });
	  //<<==================== END PAGINATOR UPDATES

		//============ Content Locked
		$('#contentLocked').on('click', function() {

		  if ($(this).hasClass('unlock')) {
		     $('#customCheckLocked').trigger('click');
		     $(this).html('<i class="feather icon-lock f-size-20 align-bottom"></i>').removeClass('unlock');
		  } else {
		     $('#customCheckLocked').trigger('click')
		     $(this).html('<i class="feather icon-unlock f-size-20 align-bottom"></i>').addClass('unlock');
		  }
		});

		//============ Content Locked Edit Post
		$(document).on('click','.contentLocked', function() {
		  if ($(this).hasClass('unlock')) {
				$(this).parents('form').find('.customCheckLocked').trigger('click');

		     $(this).html('<i class="feather icon-lock" style="font-size:25px;"></i>').removeClass('unlock');
		  } else {
		     $(this).parents('form').find('.customCheckLocked').trigger('click');

		     $(this).html('<i class="feather icon-unlock" style="font-size:25px;"></i>').addClass('unlock');
		  }
		});

		//============ Remove photo/file on upload post
		$('#removePhoto').on('click', function() {
	  	 	$('#filePhoto').val('');
				$('#fileZip').val('');
				$('#zipFile').val('');
	  	 	$('#previewImage').html('');
	  	 	$(this).hide();
	  	 });

			 //============ Upload Preview Media
			 $("#filePhotoAAAAAAAAAAAA").on('change', function(){

		     $('#previewImage').html('');
		       $('#removePhoto').hide();
					 $('#fileZip').val('');

		   	var loaded = false;
		   	if(window.File && window.FileReader && window.FileList && window.Blob) {
		        //check empty input filed
		   		if($(this).val()) {
		   			var oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image|video\/mp4|video\/quicktime|audio\/mpeg)$/i;
		   			if($(this)[0].files.length === 0){return}

		   			var oFile = $(this)[0].files[0];
		   			var fsize = $(this)[0].files[0].size; //get file size
		   			var ftype = $(this)[0].files[0].type; // get file type

		   			if(!rFilter.test(oFile.type)) {
		   				$('#filePhoto').val('');
		           swal({
		       			title: error_oops,
		       			text: formats_available,
		       			type: "error",
		       			confirmButtonText: ok
		       			});
		   				return false;
		   			}

		   			var allowed_file_size = file_size_allowed;

		   			if(fsize>allowed_file_size) {
		   				$('#filePhoto').val('');
		           swal({
		       			title: error_oops,
		       			text: max_size_id,
		       			type: "error",
		       			confirmButtonText: ok
		       			});
		   				return false;
		   			}

		         if(ftype == 'video/mp4' || ftype == 'video/quicktime') {
		           // Extension
		           if(ftype == 'video/mp4') {
		             var $extension = '.mp4';
		           } else {
		             var $extension = '.mov';
		           }
		           if(oFile.name.length > 30) {
		             var $fileName = oFile.name.substring(0, 30) + "(...)" + $extension;
		           } else {
		             var $fileName = oFile.name;
		           }

		           $('#previewImage').html('<i class="fa fa-play-circle text-info mr-1"></i> '+$fileName);
		           $('#removePhoto').show();
		         }

		         if(ftype == 'audio/mpeg') {
		           if(oFile.name.length > 30) {
		             var $fileName = oFile.name.substring(0, 30) + "(...)" + ".mp3";
		           } else {
		             var $fileName = oFile.name;
		           }

		           $('#previewImage').html('<i class="fa fa-music text-info mr-1"></i> '+$fileName);
		           $('#removePhoto').show();
		         }

		   			oFReader.onload = function(e) {

		   				var image = new Image();
		   			    image.src = oFReader.result;

		   				image.onload = function() {

		   			    	if(image.width < 20) {
		   			    		$('#filePhoto').val('');
		                 swal({
		             			title: error_oops,
		             			text: error_width_min,
		             			type: "error",
		             			confirmButtonText: ok
		             			});
		   			    		return false;
		   			    	}

		               if(image.height > image.width) {
		                 var $imageWidth = 40;
		               } else {
		                 var $imageWidth = 65;
		               }

		   			    	$('#previewImage').html('<img src="'+e.target.result+'" class="rounded" width="'+$imageWidth+'" />');
		               $('#removePhoto').show();
		   			    	var _filname =  oFile.name;
		   					  var fileName = _filname.substr(0, _filname.lastIndexOf('.'));
		   			    };// <<--- image.onload
		           }
		           oFReader.readAsDataURL($(this)[0].files[0]);
		   		}
		   	}
		   });
		   //============ Upload Preview Media

			 // Story length
		   $('#story').on('keyup', function() {
		   var characterCount = $(this).val().length,
		       current = $('#current'),
		       maximum = $('#maximum'),
		       theCount = $('#the-count');

		   current.text(characterCount);

		   if (characterCount >= story_length) {
		     current.css('color', '#ff0000');
		     current.css('font-weight','bold');
		   } else {
		     current.css('color', '#666');
		     current.css('font-weight','normal');
		   }
		 });// End Story length

			 // Save changes form edit my page
		   $('#saveChanges').on('click', function(){
		     $(this).attr({'disabled' : 'true'}).html(please_wait);
		   $('#formEditPage').submit();
		  });

			// Maximum length Message
			$('#message').on('keyup', function() {

			    var element = $(this).val();

			    if (trim(element).length >= 1) {
			      $('#button-reply-msg').removeAttr('disabled').removeClass('e-none');
			      return false;
			    } else {
			      $('#button-reply-msg').attr({'disabled' : 'true'}).addClass('e-none');
			      return false;
			    }
			  });

		//==== Search Creator
		$('#searchCreator').on('keyup',function(e) {
		  e.preventDefault();
		    e.stopPropagation();

		  var $string = $(this).val();

		  if (trim($string).length < 2) {
		    return false;
		  } else if (e.which == 16
		    || e.which == 17
		    || e.which == 18
		    || e.which == 20
		    || e.which == 32
		    || e.which == 37
		    || e.which == 38
		    || e.which == 39
		    || e.which == 40
		    ) {
		    return false;
		  }

		  $('#spinner').show();
		  $('#containerUsers').html('');

		  $(this).waiting(500).done(function() {

		  $.ajax({
		    type : 'get',
		    url : URL_BASE+'/messages/search/creator',
		    data: {'user':trim($string)},
		    success:function(data) {
		      if (data) {
		        $('#spinner').hide();
		        $('#containerUsers').html(data);
		      } else {
		        $('#containerUsers').html('<small class="text-center">'+ no_results_found +'</small>');
		        $('#spinner').hide();
		      }
		    }
		  }).fail(function(jqXHR, ajaxOptions, thrownError)
		  {
		    $('#containerUsers').html('');
		    $('#containerUsers').html(error_occurred);
		    $('#spinner').hide();
		  });//<--- AJAX

		  });//<----- * WAITING * ---->
		});//==== End Search Creator

	// Cancel subscription
	$(".cancelBtn").on('click', function(e) {
     	e.preventDefault();

     	var element = $(this);
			var expiration = element.attr('data-expiration');
      element.blur();

  	swal(
  		{   title: delete_confirm,
  		 text: expiration,
  		 type: "error",
  		 showLoaderOnConfirm: true,
  		 showCancelButton: true,
  		 confirmButtonColor: "#DD6B55",
  		 confirmButtonText: yes_confirm_cancel,
  		 cancelButtonText: cancel_confirm,
  		 closeOnConfirm: false,
     },
     function(isConfirm){
  		    	 if (isConfirm) {
  		    	 	$('.formCancel').submit();
  		    	 	}
  		    	 });
  		 });// End Cancel subscription

			 // Save Notifications Settings
			 $('#save').on('click', function(e) {

		     e.preventDefault();
		     var $element = $(this);
		     var $msgDefault = $element.attr('data-msg');
				 var msg_success = $element.attr('data-msg-success');

		     $element.attr({'disabled' : 'true'}).html('<i class="spinner-border spinner-border-sm align-middle mr-1"></i>');

		     (function() {
		        $("#form").ajaxForm({
		        dataType : 'json',
		        success:  function(response) {
		          if (response.success) {
		            $element.html($msgDefault).removeAttr('disabled');
								$('.popout').removeClass('popout-error').addClass('popout-success').html(msg_success).fadeIn('500').delay('3000').fadeOut('500');
		            $('#notifications').modal('hide');
		          }
		        },
		        error: function(responseText, statusText, xhr, $form) {
		             // error
		             swal({
		                 type: 'error',
		                 title: 'Oops...',
		                 text: ''+error_occurred+' ('+xhr+')',
		               });
		               $element.html($msgDefault).removeAttr('disabled');
		         }
		       }).submit();
		     })(); //<--- FUNCTION %
		   });// End Save Notifications Settings

	 // Delete notifications
	 $(document).on('click','.actionDeleteNotify', function(e){

        e.preventDefault();

        var element = $(this);
        var form    = $(element).parents('form');
        element.blur();

      swal(
        {   title: delete_confirm,
         text: confirm_delete_notifications,
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
                form.submit();
                }
               });
         });// End Delete notifications

				 // Send form Verification Account
				 $('#sendData').on('click', function(){
				 	$(this).attr({'disabled' : 'true'}).html(please_wait);
				 	$('#formVerify').submit();
				 });

				 // trigger click select photo verification account
				 $(document).on('click','.btnFilePhoto',function () {
				 	var _this = $(this);
				 		_this.parents('.previewImageVerification').find(".fileVerifiyAccount").trigger('click');
				 		 _this.blur();
				 });

				 //======= FILE Verify Account
			 $(".fileVerifiyAccount").on('change', function() {

				 var _this = $(this);

			 	_this.parents('.previewImageVerification').find('.previewImage').html('');

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
			 				_this.parents('.previewImageVerification').find('#fileVerifiyAccount').val('');
			         swal({
			     			title: error_oops,
			     			text: formats_available_verification,
			     			type: "error",
			     			confirmButtonText: ok
			     			});
			 				return false;
			 			}

			 			var allowed_file_size = file_size_allowed_verify_account;

			 			if (fsize>allowed_file_size) {
			 				$('.popout').addClass('popout-error').html(max_size_id_lang).fadeIn(500).delay(4000).fadeOut();
			         $(this).val('');
			 				return false;
			 			}

			 			_this.parents('.previewImageVerification').find('.previewImage').html('<i class="fas fa-image text-info mr-1"></i> <strong>' + oFile.name + '</strong>');
			 		}
			 	} else{
			 		alert('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.');
			 		return false;
			 	}
			 });
			 //======= END FILE Verify Account

	// Delete Withdrawals
	$('.saveChanges').on('click', function(){
    $(this).attr({'disabled' : 'true'}).html(please_wait);
    $('form.d-inline').submit();
  });

  $(".deleteW").on('click', function(e) {
     	e.preventDefault();

     	var element = $(this);
      element.blur();

  	swal(
  		{   title: delete_confirm,
  		 text: confirm_delete_withdrawal,
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
         $('.d-inline').submit();
       }
     });
   });// End Delete Withdrawals

	 // Pin to your profile
 	$(document).on('click','.pin-post',function(e) {
 		var element     = $(this);
 		var id          = element.attr("data-id");
 		var data        = 'id=' + id;

 		e.preventDefault();

 		element.blur();

 			 $.ajax({
 			 	headers: {
 	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 	    		},
 			   type: "POST",
 			   url: URL_BASE+"/pin/post",
 			   data: data,
 			   success: function(response) {

 			   	if (response.success != false) {

						if (response.status == 'pin') {

							$('.card-updates').removeClass('pinned-post');
							$('.pinned_post').hide();

							$('.pinned-current').parents('.card-updates').find('.pin-post').html('<i class="bi bi-pin mr-2"></i> '+pin_to_your_profile);
							$('.pinned-current').hide();
							element.html('<i class="bi bi-pin mr-2"></i> '+unpin_from_profile);
							element.parents('.card-updates').find('.pinned_post').addClass('pinned-current');

							element.parents('.card-updates').addClass('pinned-post', function() {
								element.parents('.card-updates').addClass('pinned-post');
							});

							element.parents('.card-updates').find('.pinned_post').fadeIn();
							$('.popout').removeClass('popout-error').addClass('popout-success').html('<i class="fa fa-check mr-1"></i> '+post_pinned_success).slideDown('200').delay('3000').slideUp('50');

						} else {
							element.parents('.card-updates').find('.pinned_post').removeClass('pinned-current');
							element.html('<i class="bi bi-pin mr-2"></i> '+pin_to_your_profile);

							element.parents('.card-updates').removeClass('pinned-post', function() {
								element.parents('.card-updates').removeClass('pinned-post');
							});

							element.parents('.card-updates').find('.pinned_post').fadeOut();
							$('.popout').removeClass('popout-error').addClass('popout-success').html('<i class="fa fa-check mr-1"></i> '+post_unpinned_success).slideDown('200').delay('3000').slideUp('50');

						}

						}//<-- response
				 }//<-- response
 		   }).fail(function(jqXHR, ajaxOptions, thrownError)
 			 {
 				 $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
 			 });//<--- AJAX

 	});//<----- End Pin to your profile

	/*========= Like ==============*/
	$(document).on('click','.btnBookmark',function(e) {
		var element     = $(this);
		var id          = element.attr("data-id");
		var data        = 'id=' + id;

		e.preventDefault();

		element.blur();

			 $.ajax({
			 	headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    		},
			   type: "POST",
			   url: URL_BASE+"/ajax/bookmark",
			   data: data,
			   success: function(result){

			   	if (result.success && result.type == 'added') {

						element.find('i').removeClass('far fa-bookmark').addClass('fas fa-bookmark');
						element.removeClass('text-muted').addClass('text-primary');
					} else {

							if (is_bookmarks == true) {
								element.parents('.card-updates').slideUp(400, function() {
									element.parents('.card-updates').remove();
								});
							}
							element.find('i').removeClass('fas fa-bookmark').addClass('far fa-bookmark');
							element.addClass('text-muted').removeClass('text-primary');
						}
			 }//<-- RESULT
		   }).fail(function(jqXHR, ajaxOptions, thrownError)
			 {
				 $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
			 });//<--- AJAX

	});//<----- LIKE

	// Categories Carousel
	$('.owl-carousel-category').owlCarousel({
    margin:10,
    autoWidth:true,
    items:10
	});

	// Show/Hide Password
	$(document).ready(function() {
    $("#showHidePassword .c-pointer").on('click', function() {
        if ($('#showHidePassword input').attr("type") == "text") {
            $('#showHidePassword input').attr('type', 'password');
            $('#showHidePassword .c-pointer > i').addClass( "icon-eye-off" );
            $('#showHidePassword .c-pointer > i').removeClass( "icon-eye" );
        } else if ($('#showHidePassword input').attr("type") == "password") {
            $('#showHidePassword input').attr('type', 'text');
            $('#showHidePassword .c-pointer > i').removeClass( "icon-eye-off" );
            $('#showHidePassword .c-pointer > i').addClass( "icon-eye" );
        }
    });
	});

	// Switch/Toggle Login and Register form
	$(document).on('click','#toggleLogin',function(e) {

	 	e.preventDefault();
		var element = $(this);
		var notAccount = element.attr('data-not-account');
		var alreadyAccount = element.attr('data-already-account');
		var textLogin = element.attr('data-text-login');
		var textRegister = element.attr('data-text-register');
		var urlLogin = $('#formLoginRegister').attr('data-url-login');
		var urlRegister = $('#formLoginRegister').attr('data-url-register');

		element.toggleClass('register');
		$('#errorLogin').hide();


		if (element.hasClass('register')) {
				element.html('<strong>'+alreadyAccount+'</strong>');
				$('#btnLoginRegister').html('<i></i> '+textRegister);
				$('#full_name, #email, #agree_gdpr').show();
				$('#username_email, #remember, #forgotPassword').hide();
				$('#formLoginRegister').attr('action', urlRegister);
				$('#loginRegisterContinue').html(register);
				$('.loginRegisterWith').html(sign_up_with);

			} else {
				element.html('<strong>'+notAccount+'</strong>');
				$('#btnLoginRegister').html('<i></i> '+textLogin);
				$('#full_name, #email, #agree_gdpr').hide();
				$('#username_email, #remember, #forgotPassword').show();
				$('#formLoginRegister').attr('action', urlLogin);
				$('#loginRegisterContinue').html(login_continue);
				$('.loginRegisterWith').html(login_with);
			}
	 });//<<<-------- * END FUNCTION CLICK * ---->>>>

	 // Reset form Login/Register
	 $('#loginFormModal').on('hidden.bs.modal', function (e) {
	   $('#errorLogin').hide();
	 	$('#formLoginRegister').trigger('reset');

		if ($('#toggleLogin').hasClass('register')) {
			$('#toggleLogin').trigger('click');
		}

	 });

	 $(document).on('click','.toggleRegister',function(e) {

	 		var element = $(this);
	 		var btnToggleLogin = $('#toggleLogin');

			if (! btnToggleLogin.hasClass('register')) {
				btnToggleLogin.trigger('click');
			}

		});//<<<-------- * END FUNCTION CLICK * ---->>>>

	 // Hover lock post
	 $( '.content-locked > a' ).on('mouseover', function() {
    $( this ).parents('.content-locked').find('.ico-no-result').addClass('icon-unlock').removeClass('icon-lock');
  })
  .on('mouseout', function() {
    $( this ).parents('.content-locked').find('.ico-no-result').removeClass('icon-unlock').addClass('icon-lock');
  });

	$('input[name=free_subscription]').on('change', function() {
    if (this.checked) {
      $('.subscriptionPrice').attr({'disabled' : 'true'});
			$('#alertDisablePaidSubscriptions').fadeIn();
			$('#alertDisableFreeSubscriptions').fadeOut();
    } else {
      $('.subscriptionPrice').removeAttr('disabled');
			$('#alertDisableFreeSubscriptions').fadeIn();
			$('#alertDisablePaidSubscriptions').fadeOut();
    }
  });

	//======= Upload File
$("#fileZip").on('change', function() {

 $('#previewImage').html('');
 $('#removePhoto').hide();
 $('#filePhoto').val('');

 var loaded = false;
 if(window.File && window.FileReader && window.FileList && window.Blob) {
		 //check empty input filed
	 if($(this).val()) {
			var oFReader = new FileReader(), rFilter = /^(?:application\/x-zip-compressed|application\/zip)$/i;
		 if($(this)[0].files.length === 0){return}

		 var oFile = $(this)[0].files[0];
		 var fsize = $(this)[0].files[0].size; //get file size
		 var ftype = $(this)[0].files[0].type; // get file type

			if(!rFilter.test(oFile.type)) {
			 $('#fileZip').val('');
				swal({
				 title: error_oops,
				 text: formats_available_upload_file,
				 type: "error",
				 confirmButtonText: ok
				 });
			 return false;
		 }

		 var allowed_file_size = file_size_allowed;

		 if(fsize>allowed_file_size){
			 swal({
				title: error_oops,
				text: max_size_id,
				type: "error",
				confirmButtonText: ok
				});
			return false;
		 }

		 $('#previewImage').html('<i class="fa fa-paperclip text-info"></i> <strong>' + oFile.name + '</strong>');
		 $('#removePhoto').show();

	 }
 } else{
	 alert('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.');
	 return false;
 }
});
//======= Upload File

// Edit my Page
$('#saveChangesEditPage').on('click', function(e) {

	e.preventDefault();
	var $element = $(this);
	var msg_success = $element.attr('data-msg-success');
	$element.attr({'disabled' : 'true'});

	$element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

	(function() {
		 $("#formEditPage").ajaxForm({
		 dataType : 'json',
		 success:  function(response) {
			 if (response.success) {
				 $('.popout').removeClass('popout-error').addClass('popout-success').html(msg_success).fadeIn('500').delay('5000').fadeOut('500');
				 $element.removeAttr('disabled');
				 $('.datepicker').attr({'disabled' : 'true'});
				 $element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
				 $('#errorUdpateEditPage').hide();
				 $('.url-user').attr('href', response.url);

				 if (response.locale) {
				 	window.location.reload();
				 }
			 } else {

				 scrollElement('.scrollError');
				 var error = '';
 				 var $key = '';

 				for( $key in response.errors ) {
 					error += '<li><i class="fa fa-times-circle"></i> ' + response.errors[$key] + '</li>';
 				}

 				$('#showErrorsUdpatePage').html(error);
 				$('#errorUdpateEditPage').fadeIn(500);

 				$element.removeAttr('disabled');
 				$element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
			 }
		 },
		 error: function(responseText, statusText, xhr, $form) {
					// error
					swal({
							type: 'error',
							title: 'Oops...',
							text: ''+error_occurred+' ('+xhr+')',
						});
						$element.removeAttr('disabled');
						$element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
			}
		}).submit();
	})(); //<--- FUNCTION %
});// End Edit my Page

//============ Submit Form Edit Post
$(document).on('click','.btnEditUpdate', function(s){

		s.preventDefault();
		var element = $(this);
		var form    = $(element).parents('form');
		element.attr({'disabled' : 'true'});
		element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

		form.find('.blocked').show();

		(function() {

			 form.ajaxForm({
			 dataType: 'json',
			 error: function(responseText, statusText, xhr, $form) {
				element.removeAttr('disabled');

				$('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred+' '+xhr+'').fadeIn('500').delay('5000').fadeOut('500');
					 form.find('.blocked').hide();
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
			 },
			 success: function(result) {

			 //===== SUCCESS =====//
			 if (result.success) {

				 $('.modalEditPost').modal('hide');
				 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
				 element.removeAttr('disabled');
				 form.find('.blocked').hide();

				 if (result.locked == 'yes') {
				 	element.parents('.card-updates').find('.type-post').html('<i class="feather icon-lock mr-1"></i> '+result.price+'').attr({'title' : locked_post});
				} else {
					element.parents('.card-updates').find('.type-post').html('<i class="iconmoon icon-WorldWide mr-1"></i>').attr({'title' : public_post});
				}

				 element.parents('.card-updates').find('.update-text').html(result.description);

				 form.find('.errorUdpate').hide();

				}//<-- e
			else {
				form.find('.blocked').hide();

				var error = '';
				var $key = '';

				for( $key in result.errors ) {
					error += '<li><i class="fa fa-times-circle"></i> ' + result.errors[$key] + '</li>';
				}

				form.find('.showErrorsUdpate').html(error);
				$('.showErrorsUdpate').html(error);
				form.find('.errorUdpate').fadeIn(500);

				element.removeAttr('disabled');
				element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
			}
		}//<----- SUCCESS
	}).submit();
	})(); //<--- FUNCTION %
});

// ====== Hover Subscribe for Free ============
    $(document).on('mouseenter', '.subscriptionActive' ,function(){

		$(this).html( '<i class="feather icon-user-x mr-1"></i> ' + cancel_subscription);
		$(this).addClass('btn-danger').removeClass('btn-success');
		 })

		$(document).on('mouseleave', '.subscriptionActive' ,function() {
		 	$(this).html( '<i class="feather icon-user-check mr-1"></i> ' + your_subscribed);
			$(this).removeClass('btn-danger').addClass('btn-success');
		 });

	 /*========= Subscribe for Free =============*/
	$(document).on('click',"#subscribeFree",function(){
	var element    = $(this);
	var id         = element.attr("data-id");
	var info       = 'id=' + id;

	element.addClass('disabled');
	element.find('i').removeClass('feather icon-user-plus mr-1');
	element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

		 $.ajax({
		 	headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		},
		   type: "POST",
		   url: URL_BASE+"/subscription/free",
		   dataType: 'json',
		   data: info,
		   success: function(result){

		   	if (result.success) {
			   	  window.location.reload();
			   	  element.blur();
		   	} else {
		   		$('.popout').removeClass('popout-success').addClass('popout-error').html(result.error).slideDown('500').delay('5000').slideUp('500');
					element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
					element.find('i').addClass('feather icon-user-plus mr-1');
					element.removeClass('disabled');
		   	}
		 }//<-- RESULT
	   }).fail(function(jqXHR, ajaxOptions, thrownError)
		 {
			 $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
			 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
			 element.find('i').addClass('feather icon-user-plus mr-1');
			 element.removeClass('disabled');
		 });//<--- AJAX
});//<----- CLICK

 // Block right click on images
 $('body').on('contextmenu', 'img', function(e) {
	 return false;
 });

// Block dragging on images
 $('body').on('dragstart', 'img', function(event) {
	 event.preventDefault();
 });

 //==== Search Creator Navbar
 $('#searchCreatorNavbar').on('keyup',function(e) {
	 e.preventDefault();
		 e.stopPropagation();

	 var $string = $(this).val();
	 var $url = URL_BASE+'/creators?q='+$string;

	 if (trim($string).length < 3) {
		 $('#dropdownCreators').removeClass('show');
		 return false;
	 } else if (e.which == 16
		 || e.which == 17
		 || e.which == 18
		 || e.which == 20
		 || e.which == 32
		 || e.which == 37
		 || e.which == 38
		 || e.which == 39
		 || e.which == 40
		 ) {
		 return false;
	 }


	 $('#triggerBtn').trigger('click');
	 $('#spinnerSearch').show();
	 $('#containerCreators').html('');
	 $('#viewAll').hide();

	 $(this).waiting(500).done(function() {

	 $.ajax({
		 type : 'get',
		 url : URL_BASE+'/search/creators',
		 data: {'user':trim($string)},
		 success:function(data) {
			 if (data) {
				 $('#spinnerSearch').hide();
				 $('#containerCreators').html(data);
				 $('#viewAll').show();
				 $('#viewAll > a').attr({'href' : $url});
			 } else {
				 $('#containerCreators').html('<span class="p-2 text-center w-100 d-block">'+ no_results +'</span>');
				 $('#spinnerSearch, #viewAll').hide();
			 }
		 }
	 }).fail(function(jqXHR, ajaxOptions, thrownError)
	 {
		 $('#containerCreators').html('');
		 $('#containerCreators').html('<span class="p-2 text-center w-100 d-block">'+error_occurred+'</span>');
		 $('#spinnerSearch, #viewAll').hide();
	 });//<--- AJAX

	 });//<----- * WAITING * ---->
 });//==== End Search Creator

 // Explore creators refresh
 $(document).on('click','.toggleFindFreeCreators',function () {
	$(this).toggleClass('findFreeCreators text-muted');

	if ($(this).hasClass('findFreeCreators')) {
		$('.toggleFindFreeCreators').attr('data-original-title', show_all);
	} else {
		$('.toggleFindFreeCreators').attr('data-original-title', show_only_free);
	}
});

$(document).on('click','.refresh_creators',function (e) {
	 e.preventDefault();

	 var element = $(this);
	 var btnRefresh = element.parents('.filter-explorer').find('.refresh-btn');
	 var btnFreeCreators = element.parents('.filter-explorer').find('.toggleFindFreeCreators');

	 var free = btnFreeCreators.hasClass('findFreeCreators') ? '?type=free' : '';
	 
	 element.removeClass('refresh_creators');
	 btnRefresh.addClass('fa-spin');

	 $.ajax({
		 headers: {
				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
		 type : 'post',
		 url : URL_BASE+'/refresh/creators'+free,
		 success:function(response) {
			 if (response) {
				 $('.containerRefreshCreators').html(response);
				 element.addClass('refresh_creators');
				 btnRefresh.removeClass('fa-spin');

			 } else {
				 $('.popout').removeClass('popout-success').addClass('popout-error').html(no_results_found ).slideDown('500').delay('4000').slideUp('500');
				 element.addClass('refresh_creators');
				 btnRefresh.removeClass('fa-spin');
				 btnFreeCreators.removeClass('findFreeCreators text-muted');
				 btnFreeCreators.attr('data-original-title', show_only_free);
			 }
		 }
	 }).fail(function(jqXHR, ajaxOptions, thrownError)
	 {
		 $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
		 element.addClass('refresh_creators');
		 btnRefresh.removeClass('fa-spin');
		 btnFreeCreators.removeClass('findFreeCreators text-muted');
		 btnFreeCreators.attr('data-original-title', show_only_free);
	 });//<--- AJAX
 });//==== End Search Creator

 // Send Reports
 $(document).on('click','.sendReport', function(e){
    e.preventDefault();

    var element = $(this);
    var form    = $(element).parents('form');
    element.blur();

		element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');
		element.attr({'disabled' : 'true'});

			(function() {
				 form.ajaxForm({
				 dataType : 'json',
				 success:  function(response) {
					 if (response.success) {
						 swal({
			    			title: thanks,
			    			text: response.text,
			    			type: "success",
			    			confirmButtonText: ok
			    			});

								$('.modalReport').modal('hide');
								element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
								element.removeAttr('disabled');
								form.trigger("reset");
					 } else {
						let errors = '';
						let $key = '';

						for ($key in response.errors) {
							errors += response.errors[$key];
						}

						 swal({
			    			title: error_oops,
			    			text: errors,
			    			type: "error",
			    			confirmButtonText: ok
			    			});
								element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
								element.removeAttr('disabled');
					 }
				 },
				 error: function(responseText, statusText, xhr, $form) {
							// error
							swal({
									type: 'error',
									title: 'Oops...',
									text: ''+error_occurred+' ('+xhr+')',
								});
								$('.modalReport').modal('hide');
								element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
								element.removeAttr('disabled');
								form.trigger("reset");
					}
				}).submit();
			})(); //<--- FUNCTION %
     });// End Send Reports

		 $('#mobileMenuOverlay').on('click', function() {
			$('body').removeClass("sidebar-overlay overflow-hidden");
		});

		// trigger click select photo verification account
		$(document).on('click','#btnFileFormW9',function () {
		 var _this = $(this);
			 $("#fileVerifiyAccountFormW9").trigger('click');
				_this.blur();
		});

		//======= FILE FORM W-9
	$("#fileVerifiyAccountFormW9").on('change', function() {

	 $('#previewImageFormW9').html('');

	 var loaded = false;
	 if(window.File && window.FileReader && window.FileList && window.Blob) {
			 //check empty input filed
		 if($(this).val()) {
				var oFReader = new FileReader(), rFilter = /^(?:application\/pdf)$/i;
			 if($(this)[0].files.length === 0){return}

			 var oFile = $(this)[0].files[0];
			 var fsize = $(this)[0].files[0].size; //get file size
			 var ftype = $(this)[0].files[0].type; // get file type

				if(!rFilter.test(oFile.type)) {
				 $('#fileVerifiyAccountFormW9').val('');
					swal({
					 title: error_oops,
					 text: formats_available_verification_form_w9,
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

			 $('#previewImageFormW9').html('<i class="fas fa-image text-info"></i> <strong>' + oFile.name + '</strong>');

		 }
	 } else{
		 alert('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.');
		 return false;
	 }
	});
	//======= END FILE FORM W-9

	// Toogle Comments
	$(document).on('click','.toggleComments',function () {
		$(this).parents('.card-footer').find('.container-comments').toggleClass('display-none');
	});

	// Hide Tooltip
	$(document).ready(function() {
		$(document).on('click','.btn-tooltip',function () {
				 $(this).blur();
          $('[data-toggle="tooltip"]').tooltip("hide");
       });

			 $('.btn-tooltip-form').on('click', function () {
				 $(this).blur();
          $('[data-toggle="tooltip"]').tooltip("hide");
					$('[data-tooltip="tooltip"]').tooltip("hide");
       });
   });

	 // Toogle Set Price
	 $(document).on('click','#setPrice',function () {
		 var input = $(this).parents('form').find('input[name=price]');

	 		$('#price').slideToggle(100);
			input.toggleClass('active');
			$(this).toggleClass('btn-active-hover');
			input.focus();

		if (! input.hasClass('active')) {
			input.val('');
		}
 	});

	// Toogle Set Price Edit Post
	$(document).on('click','.setPrice',function () {

		var input = $(this).parents('form').find('input[name=price]');

		 $(this).parents('form').find('.price').slideToggle(100);
		 input.toggleClass('active');
		 $(this).toggleClass('btn-active-hover');
		 input.focus();

	 if (! input.hasClass('active')) {
		 input.val('');
	 }
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



		// Likes Comments
		$(document).on('click','.likeComment',function () {

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
			});

			var element  = $(this);
			var commentId = element.data('id');
			var type = element.data('type'); 

			$.post(URL_BASE+"/comment/like",{ comment_id: commentId, typeComment: type }, function(data) {

				if (data.success == true) {
					if (data.type == 'like') {
						element.html('<i class="fas fa-heart text-red mr-1"></i> <span class="countCommentsLikes">'+data.count+'</span>');
						element.blur();

					} else if (data.type == 'unlike') {
						var $count = data.count == 0 ? '' : data.count;
						element.html('<i class="far fa-heart mr-1"></i> <span class="countCommentsLikes">'+$count+'</span>');

						element.blur();
					}
				} else {
					window.location.reload();
				}

				if (data.session_null) {
					window.location.reload();
				}

			},'json').fail(function() {
			    $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
			  });
		});

		// Hide Alert Post Pending
		$(document).on('click','#btnAlertPostPending',function () {
			$('#alertPostPending').hide();
		});

		// Hide Alert Post Schedule
		$(document).on('click','#btnAlertPostSchedule',function () {
			$('#alertPostSchedule').hide();
		});

		// Send code TwoFactorAuth
		$('#btn2fa').on('click', function(e) {

			e.preventDefault();
			var $element = $(this);

			$element.attr({'disabled' : 'true'}).find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

			(function() {
				 $("#formVerify2fa").ajaxForm({
				 dataType : 'json',
				 success:  function(response) {
					 if (response.success) {

						 if (response.isProfileTwoFA) {
						 	window.location.reload();
						} else {
							window.location.href = response.redirect;
						}

					 } else {
						 var error = '';
		 				var $key = '';

		 				for ($key in response.errors) {
		 					error += '<li><i class="fa fa-times-circle"></i> ' + response.errors[$key] + '</li>';
		 				}

		 				$('#showErrorsModal2fa').html(error);
		 				$('#errorModal2fa').fadeIn(500);

		 				$element.removeAttr('disabled');
		 				$element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
					 }
				 },
				 error: function(responseText, statusText, xhr, $form) {
							// error
							swal({
									type: 'error',
									title: 'Oops...',
									text: ''+error_occurred+' ('+xhr+')',
								});
								$element.removeAttr('disabled');
								$element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
					}
				}).submit();
			})(); //<--- FUNCTION %
		});// End

		// Resend code
	  $('.resend_code').on('click',function(e) {

	 	 e.preventDefault();

	 	 var element = $(this);
		 var btnTwoFa = $('#btn2fa');
	 	 element.removeClass('resend_code').addClass('text-decoration-none');

		 btnTwoFa.attr({'disabled' : 'true'});

		 $('#resendCode').addClass('text-muted').html(resending_code);

	 	 $.ajax({
	 		 headers: {
	 				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	 			 },
	 		 type : 'post',
	 		 url : URL_BASE+'/2fa/resend',
	 		 success:function(response) {
	 			 if (response.success) {

					 $('.popout').removeClass('popout-error').addClass('popout-success').html(response.text).slideDown('500').delay('5000').slideUp('500');
	 				 element.addClass('resend_code').removeClass('text-decoration-none');
	 				 btnTwoFa.removeAttr('disabled');
					 $('#resendCode').removeClass('text-muted').html(resend_code);
	 			 }
	 		 }
	 	 }).fail(function(jqXHR, ajaxOptions, thrownError)
	 	 {
	 		 $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
			 element.addClass('resend_code');
			 btnTwoFa.removeAttr('disabled');
	 	 });//<--- AJAX
	  });//==== End

		// Open modal Create Live Stream
		$('.btnCreateLive').on('click', function () {
			$(this).blur();
			 $('#liveStreamingForm').modal('show');
		});

		// Create Live Stream
		$('#liveBtn').on('click', function(e) {

			e.preventDefault();
			var $element = $(this);
			$element.attr({'disabled' : 'true'});

			$element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

			(function() {
				 $("#formSendLive").ajaxForm({
				 dataType : 'json',
				 success:  function(response) {
					 if (response.success) {
						window.location.href = response.url;

					 } else {

						 var error = '';
		 				 var $key  = '';

		 				for ($key in response.errors) {
		 					error += '<li><i class="fa fa-times-circle"></i> ' + response.errors[$key] + '</li>';
		 				}

						$('#errorLive').fadeIn(500);
		 				$('#showErrorsLive').html(error);

		 				$element.removeAttr('disabled');
		 				$element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
					 }
				 },
				 error: function(responseText, statusText, xhr, $form) {
							// error
							swal({
									type: 'error',
									title: 'Oops...',
									text: ''+error_occurred+' ('+xhr+')',
								});
								$element.removeAttr('disabled');
								$element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
					}
				}).submit();
			})(); //<--- FUNCTION %
		});// End Create Live

		// Click Go Join Live Streaming
		$(document).on('click', '.liveLink', function() {
			window.location.href = $(this).attr('data-url');
		});

		function errorCustom()
		{
			$.each(response.errors, function (key, value) {
            $("#" + key).next().html(value[0]);
            $("#" + key).next().removeClass('d-none');
        });
		}

		// Availability of Live Stream
		$('#Availability').on('change', function() {
			var text = $(this).find(':selected').attr('data-text');
			$('#descAvailability').html(text);

			if ($(this).val() == 'everyone_free') {
				$('.priceLive').attr({'disabled' : 'true'});
				$('#everyoneFreeAlert').slideDown('fast');
				$('#LimitLiveStreamingPaid').slideUp('fast');
			} else {
				$('.priceLive').removeAttr('disabled');
				$('#everyoneFreeAlert').slideUp('fast');
				$('#LimitLiveStreamingPaid').slideDown('fast');
			}
	 });

	$(document).on('click', '.actionAcceptReject', function (e) {

		e.preventDefault();

		var element = $(this);
		var form = $(element).parents('form');
		element.blur();

		if (element.hasClass('acceptOrder')) {
			var textModal = action_cannot_reversed;
			var typeAction = 'warning';
			var textModal = action_cannot_reversed;
			var btnAction = mark_as_delivered;
			var btnColor = "#8bc34a";
		} else if (element.hasClass('rejectOrder')) {
			var textModal = confirm_reject_order;
			var typeAction = 'error';
			var btnAction = reject_order;
			var btnColor = "#ff2900";
		} else if (element.hasClass('rejectLiveRequest')) {
			var textModal = confirm_reject_order;
			var typeAction = 'error';
			var btnAction = reject_request;
			var btnColor = "#ff2900";
		}

		swal(
			{
				title: delete_confirm,
				type: typeAction,
				text: textModal,
				showLoaderOnConfirm: true,
				showCancelButton: true,
				confirmButtonColor: btnColor,
				confirmButtonText: btnAction,
				cancelButtonText: cancel_confirm,
				closeOnConfirm: false,
			},
			function (isConfirm) {
				if (isConfirm) {
					(function () {
						form.ajaxForm({
							dataType: 'json',
							success: function (response) {
								if (response.success) {
									window.location.reload();
								} else {
									swal({
										type: 'error',
										title: error_oops,
										text: error_occurred,
									});
								}
							},
							error: function (responseText, statusText, xhr, $form) {
								// error
								swal({
									type: 'error',
									title: error_oops,
									text: '' + error_occurred + ' (' + xhr + ')',
								});
							}
						}).submit();
					})(); //<--- FUNCTION %
				} // isConfirm
			});
	});// End Reject Order Custom Content

/** TO DISABLE SCREEN CAPTURE **/
document.addEventListener('keyup', (e) => {
    if (e.key == 'PrintScreen') {
        navigator.clipboard.writeText('');
        return false;
    }
});

/** TO DISABLE PRINTS WHIT CTRL+P **/
document.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.key == 'p') {
        return false;
        e.cancelBubble = true;
        e.preventDefault();
        e.stopImmediatePropagation();
    }
});

$(document).on('click','.emoji',function(e) {
	var element = $(this);
    var smiley = element.attr('data-emoji');
	var input = element.parents('form').find('.emojiArea');
	input.val(input.focus().val()+""+smiley+"");
	input.trigger('keyup');
});

/*========= restrictUser ==============*/
$(document).on('click','#restrictUser',function(e) {
	var element = $(this);
	var id      = element.attr("data-user");

	e.preventDefault();

	element.blur();

	if (element.hasClass('removeRestriction')) {
		var alertText = false;
		var alertConfirmButtonText = remove_restriction;
	} else {
		var alertText = confirm_restrict;
		var alertConfirmButtonText = restrict;
	}

	swal(
		{   title: delete_confirm,
				 text: alertText,
				 type: "error",
				 showLoaderOnConfirm: true,
				 showCancelButton: true,
				 confirmButtonColor: "#ff2900",
				 confirmButtonText: alertConfirmButtonText,
				 cancelButtonText: cancel_confirm,
				 closeOnConfirm: false,
	 },
	 function(isConfirm){
					 if (isConfirm) {

						 $.ajax({
							headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								},
							 type: "POST",
							 url: URL_BASE+"/restrict/user/"+id,
							 dataType: 'json',
							 success: function(result) {

								if (result.success) {
									window.location.reload();
								} else {
									$('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');

								}
						 }//<-- RESULT
						 }).fail(function(jqXHR, ajaxOptions, thrownError)
						 {
							 $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
						 });//<--- AJAX

						}
				});

});//<----- restrictUser

// Toogle Set Title Post
$(document).on('click','#setTitle',function () {
	var input = $(this).parents('form').find('input[name=title]');

	 $('#titlePost').slideToggle(100);
	 input.toggleClass('active');
	 $(this).toggleClass('btn-active-hover');
	 input.focus();

 if (! input.hasClass('active')) {
	 input.val('');
 }
});

// Toogle Set Price Edit Post
$(document).on('click','.setTitle',function () {

	var input = $(this).parents('form').find('input[name=title]');

	 $(this).parents('form').find('.titlePost').slideToggle(100);
	 input.toggleClass('active');
	 $(this).toggleClass('btn-active-hover');
	 input.focus();

 if (! input.hasClass('active')) {
	 input.val('');
  }
});

$(document).on('click','.replyButton',function () {
	var element = $(this);
    var commentId = element.attr('data');
	var username = element.attr('data-username');
	var isReplyTo = element.parents('.container-comments ').find('.isReplyTo');
	var usernameReply = element.parents('.container-comments ').find('.username-reply');
	var input = element.parents('.container-comments ').find('.inputComment');
	var inputIsReply = element.parents('.container-comments ').find('.isReply');

	// Insert Username to input
	input.val('').val(input.focus().val()+""+username+" ");

	isReplyTo.slideDown(100);
	usernameReply.html('').html(username);

	// Insert ID comment to isReply
	inputIsReply.val('').val(commentId);
});

$(document).on('click','.cancelReply',function () {

	var element = $(this);
	var isReplyTo = element.parents('.container-comments ').find('.isReplyTo');
	var input = element.parents('.container-comments ').find('.inputComment');
	var inputIsReply = element.parents('.container-comments ').find('.isReply');

	isReplyTo.slideUp(50);
	input.val('');
	inputIsReply.val('');
});

//<<==================== PAGINATOR COMMENTS
$(document).on('click','.loadMoreReplies', function(e) {

	e.preventDefault();

  var container      = $(this).parents('.wrapComments');
  var allElements    = $(container).find('div.isCommentReply').length;
  var comment        = $(this).parents('.wrap-container').attr('data-id');
  var wrapContainer  = $(this).parents('.wrap-container');

  wrapContainer.html('<a href="javascript:void(0)"><span class="line-replies"></span>'+loading+'</a>');

  $.ajax({
	url: URL_BASE+'/replies/loadmore?id=' + comment + '&skip=' + allElements,
		dataType: 'json'
  }).done(function(data) {

	if (data.replies) {
	  wrapContainer.html('');

	  $(data.replies).hide().insertAfter(wrapContainer).fadeIn(500);
	  wrapContainer.remove();

	  jQuery(".timeAgo").timeago();
	  readMoreText();

	} else {
	  $('.popout').addClass('popout-error').html(error_reload_page).slideDown('500').delay('5000').slideUp('500');
	}
	//<**** - Tooltip
  }).fail(function(jqXHR, ajaxOptions, thrownError)
  {
	$('.popout').addClass('popout-error').html(error_reload_page).slideDown('500').delay('5000').slideUp('500');
  });//<--- AJAX
});
//<<==================== END PAGINATOR COMMENTS

//<<============= Edit Comments
$(document).on('click','.btnEditComment', function(s){
	s.preventDefault();
	var element = $(this);
	var form    = $(element).parents('form');
	element.attr({'disabled' : 'true'});
	element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

	form.find('.blocked').show();

	(function() {
		 form.ajaxForm({
		 dataType: 'json',
		 error: function(responseText, statusText, xhr, $form) {
			element.removeAttr('disabled');

			$('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred+' '+xhr+'').fadeIn('500').delay('5000').fadeOut('500');
				 form.find('.blocked').hide();
				 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
		 },
		 success: function(result) {
		 //===== SUCCESS =====//
		 if (result.success) {
			 $('.modalEditComment').modal('hide');
			 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
			 element.removeAttr('disabled');
			 form.find('.blocked').hide();
			 $(result.target).html(result.comment);
			 form.find('.errorComment').hide();
			}//<-- e
		else {
			form.find('.blocked').hide();

			var error = '';
			var $key = '';

			for( $key in result.errors ) {
				error += '<li><i class="fa fa-times-circle mr-1"></i> ' + result.errors[$key] + '</li>';
			}

			form.find('.showErrorsComment').html(error);
			$('.showErrorsComment').html(error);
			form.find('.errorComment').fadeIn(500);

			element.removeAttr('disabled');
			element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
		}
	}//<----- SUCCESS
}).submit();
})(); //<--- FUNCTION %
});

$(document).on('click', '.plyr__control, .plyr__video-wrapper', function(e) {
	var postId = $(this).parents('.views').attr('data');

	if (postId === undefined) {
		return false;
	}

	$(this).parents('.views').removeClass('views');

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$.post(URL_BASE+"/video/views/"+postId+"");
});

$(document).on('click', '.storyBackgrounds', function(e) {
	var element = $(this);
	var background = element.data('bg');
	var backgroundName = element.data('bg-name');

	$('.storyBackgrounds').removeClass('storyBg active');
	element.addClass('active');

	$('.inputBackground').val(backgroundName);
	$('.bg-inside').css({background: 'url("'+background+'") center center #6a6a6a', backgroundSize: 'cover' });
});

$(document).on('click', '.fontColor', function(e) {
	var element = $(this);
	var color = element.data('color');

	$('.fontColor').removeClass('active');
	element.addClass('active');

	$('.inputColor').val(color);
	$('.text-story').css({color: color });
});

$('#storyFont').on('change', function() {
	var element = $(this).val();
	$('.text-story').css({fontFamily: element });
});

$('.addTextStory').keyup(function(e){
    var _valueClean = $(this).val().replace(/<(?=\/?script)/ig, "&lt;");

    if( trim(_valueClean) != '' || trim(_valueClean) !=  0) {
    	$('.text-story').html( trim( escapeHtml(_valueClean) ) );
    }
});

$(document).on('click','.delete-history', function(e){

	e.preventDefault();

	var element     = $(this);
	var id          = element.data("id");
	element.blur();

  $.ajaxSetup({
	 headers: {
		 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	 }
 });

swal(
	{   title: delete_confirm,
		 type: "error",
		 showLoaderOnConfirm: true,
		 showCancelButton: true,
		 confirmButtonColor: "#DD6B55",
			confirmButtonText: yes_confirm,
			cancelButtonText: cancel_confirm,
			 closeOnConfirm: true,
			},
			function(isConfirm){
				   if (isConfirm) {
					$.post(URL_BASE+"/delete/story/"+id, function(data) {
			if (data.success) {
				window.location.reload();
			} else {
			  $('.popout').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
			}
					 }).fail(function(jqXHR, ajaxOptions, thrownError)
			   {
				   $('.popout').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
			   });
					}
				   });
   });//<----- DELETE REPLY

   // Story Views
   $(document).on('click','.getViews', function(e) {
	var element = $(this);
	var id      = element.data("id");
	var total   = element.data("total");

	if (total === 0) {
		$('#containerUsers').html('<small class="text-center">'+ no_one_seen_story_yet +'</small>');
		$('#spinner').hide();
		return false;
	  }

	$('#spinner').show();
	$('#containerUsers').html('');

	$.ajax({
	  type : 'get',
	  url : URL_BASE+'/story/views/'+id
	}).done(function(data) {
		if (data) {
		  $('#spinner').hide();
		  $('#containerUsers').html(data);
		  jQuery(".timeAgo").timeago();
		}
	  }).fail(function(jqXHR, ajaxOptions, thrownError)
	{
	  $('#containerUsers').html('');
	  $('#containerUsers').html(error_occurred);
	  $('#spinner').hide();
	});//<--- AJAX
  });//==== Story Views

  $('.submitForm').on('click', function() {
	let isValid = this.form.checkValidity();

	if (isValid) {
		this.disabled=true;
		$('<i class="spinner-border spinner-border-sm align-middle mr-1"></i>').prependTo(this);
		this.form.submit();
	}
  });

  $(document).on('click', '.deleteCover', function (e) {
	e.preventDefault();

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	swal(
		{
			title: delete_confirm,
			text: confirm_delete_image_cover,
			type: "error",
			showLoaderOnConfirm: true,
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: yes_confirm,
			cancelButtonText: cancel_confirm,
			closeOnConfirm: true,
		},
		function (isConfirm) {
			if (isConfirm) {
				$.post(URL_BASE + "/delete/cover", function (data) {
					if (data.success) {
						if (data.cover) {
							$('.jumbotron-cover-user').css({ background: 'url("' + data.cover + '") center center #505050', backgroundSize: 'cover' });
						} else {
							$('.jumbotron-cover-user').css({ padding: '125px 0', transition: 'padding .6s ease', background: '#505050'});
						}
						
					} else if (data.errors) {
						$('.popout').html(data.errors.error).slideDown('500').delay('5000').slideUp('500');
					} else if (data.error) {
						$('.popout').html(data.message).slideDown('500').delay('5000').slideUp('500');
					} else {
						$('.popout').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
					}
				}).fail(function (jqXHR, ajaxOptions, thrownError) {
					$('.popout').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
				});
			}
		});
});

// Open modal Schedule Post
$('.btnSchedulePost').on('click', function () {
	$(this).blur();
	 $('#modalSchedulePost').modal('show');
});

function getMonthName(monthNumber, dateSchedule) {
	const date = new Date(dateSchedule);
	date.setMonth(monthNumber);
  
	return date.toLocaleString(lang, {
	  month: 'short',
	});
  }

$(document).on('click', '#btnSubmitSchedule', function () {
	let isValid = this.form.checkValidity();
	const dateControl = document.querySelector('input[type="datetime-local"]');
	const date = new Date(dateControl.value);
	const currentDate = new Date();

	let day = date.getDate();
	let month = getMonthName(date.getMonth(), dateControl.value);
	let year  = date.getFullYear();
	let time = date.toLocaleTimeString();
	const dateSchedule = `${day} ${month}, ${year} ${at} ${time}`;

	if (isValid) {
		if (date < currentDate) {
			$('#scheduleAlert').show();
			return false;
		  }

		$('#inputScheduled').val(dateControl.value);
		$('#dateSchedule').html(dateSchedule);
		$('#dateScheduleContainer').show();
		$('#modalSchedulePost').modal('hide');
		$('#textPostPublish').html(schedule);
	}
});

//============ Delete Post Schedule
$('#deleteSchedule').on('click', function() {
	$('#inputScheduled').val('');
	$('#dateScheduleContainer').hide();
	$('#textPostPublish').html(publish);
});

$('#datetime').on('change', function() {
	let dateValue = $(this).val();
	let btnSubmit = $('#btnSubmitSchedule');
	const currentDate = new Date();
	const date = new Date(dateValue);

	if (date < currentDate) {
		$('#scheduleAlert').show();
		btnSubmit.attr({'disabled' : 'true'});
	  } else {
		$('#scheduleAlert').hide();
		btnSubmit.removeAttr('disabled');
	  }
});

$('.buttonActionSubmit').on('click', function() {
	if (this.form.checkValidity()) {
		$(this).attr({'disabled' : 'true'}).html(please_wait);
		this.form.submit();
	}
});

//<<============= Edit Comments
$(document).on('click', '.actionAcceptLive', function (s) {
	s.preventDefault();
	var element = $(this);
	var form = $(element).parents('form');
	element.attr({ 'disabled': 'true' });
	element.find('i').addClass('spinner-border spinner-border-sm align-middle');

	(function () {
		form.ajaxForm({
			dataType: 'json',
			error: function (responseText, statusText, xhr, $form) {
				element.removeAttr('disabled');

				$('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred + ' ' + xhr + '').fadeIn('500').delay('5000').fadeOut('500');
				element.find('i').removeClass('spinner-border spinner-border-sm align-middle');
			},
			success: function (response) {
				//===== SUCCESS =====//
				if (response.success) {
					// Redirect to Live Private
					window.location.href = response.url;
				}//<-- e
				else {
					var error = '';
					var $key = '';

					for ($key in response.errors) {
						error += response.errors[$key];
					}

					swal({
						type: 'error',
						title: error_oops,
						text: error,
					});

					element.removeAttr('disabled');
					element.find('i').removeClass('spinner-border spinner-border-sm align-middle');
				}
			}//<----- SUCCESS
		}).submit();
	})(); //<--- FUNCTION %
});

// Open modal Live Stream Private request
$('.requestLivePrivateModal').on('click', function () {
	$(this).blur();
	 $('#modalLivePrivateRequest').modal('show');
});
	
	function readMoreText() {
		$('.truncated').truncated();
	}

	readMoreText();

})(jQuery);
