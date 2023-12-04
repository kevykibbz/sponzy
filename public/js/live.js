//<--------- Messages -------//>
(function ($) {
	"use strict";

	//<-------- * TRIM Space * ----------->
	function trim(string) {
		return string.replace(/^\s+/g, '').replace(/\s+$/g, '');
	}

	// Scroll to paginator chat
	let scr = $('#contentDIV')[0].scrollHeight;
	$('#contentDIV').animate({ scrollTop: scr }, 100);

	$(document).on('click', '#button-reply-msg', function (s) {

		s.preventDefault();

		let element = $(this);

	});//<<<-------- * END FUNCTION CLICK * ---->>>>

	//<----- Chat Live
	let request = false;

	function Chat() {
		let param = /^[0-9]+$/i;
		let lastID = $('li.chatlist:last').attr('data');
		let liveID = $('.live-data').attr('data');
		let creator = $('.live-data').attr('data-creator');

		if (!liveOnline) {
			return false;
		}

		if (!request) {
			request = true;
			//****** COUNT DATA
			request = $.ajax({
				method: "GET",
				url: URL_BASE + "/get/data/live",
				data: {
					last_id: lastID ? lastID : 0,
					live_id: liveID,
					creator: creator
				},
				complete: function () { request = false; }
			}).done(function (response) {

				if (response) {
					// Live end
					if (response.status == 'offline') {
						liveType == 'normal' ? window.location.reload() : window.location.href = URL_BASE;
						return false;
					}

					// Session Null
					if (response.session_null) {
						liveType == 'normal' ? window.location.reload() : window.location.href = URL_BASE;
					}

					// Comments
					if (response.total !== 0) {
						// Scroll to paginator chat
						let scr = $('#contentDIV')[0].scrollHeight;
						$('#contentDIV').animate({ scrollTop: scr }, 100);

						let total_data = response.comments.length;

						for (let i = 0; i < total_data; ++i) {
							$(response.comments[i]).hide().appendTo('#allComments').fadeIn(250);
						}
					} // response.total !== 0

					// Online users
					$('#liveViews').html(response.onlineUsers);

					// Likes
					if (response.likes !== 0) {
						$('#counterLiveLikes').html(response.likes);
					} else {
						$('#counterLiveLikes').html('');
					}

					if (response.time) {
						$('.limitLiveStreaming > span').html(response.time);
					}

					if (response.amountTips) {
						$('#earned').removeClass('display-none').addClass('d-block');
						$('#amountTip').html(response.amountTips);
					}
				}//<-- response

			}, 'json');

		}// End Request

	}//End Function TimeLine

	setInterval(Chat, 1500);

	// End Live Stream
	$(document).on('click', '#endLive', function (e) {
		e.preventDefault();
		let element = $(this);
		element.blur();

		swal(
			{
				title: delete_confirm,
				text: confirm_end_live,
				type: "error",
				showLoaderOnConfirm: true,
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: yes_confirm_end_live,
				cancelButtonText: cancel_confirm,
				closeOnConfirm: false,
			},
			function (isConfirm) {
				if (isConfirm) {
					(function () {
						$('#formEndLive').ajaxForm({
							dataType: 'json',
							success: function (response) {
								// Exit
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
	});// End live

	// Exit Live
	$(document).on('click', '#exitLive', function (e) {
		e.preventDefault();
		let element = $(this);
		element.blur();

		swal(
			{
				title: delete_confirm,
				text: confirm_exit_live,
				type: "error",
				showLoaderOnConfirm: true,
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: yes_confirm_exit_live,
				cancelButtonText: cancel_confirm,
				closeOnConfirm: false,
			},
			function (isConfirm) {
				if (isConfirm) {
					(function () {
						window.location.href = URL_BASE;
					})(); //<--- FUNCTION %
				} // isConfirm
			});
	});// Exit live

	//============= Comments
	$(document).on('keypress', '#commentLive', function (e) {
		if (e.which == 13) {
			e.preventDefault();

			let element = $(this);
			element.blur();

			$('.blocked').show();

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: "POST",
				url: URL_BASE + "/comment/live",
				dataType: 'json',
				data: $("#formSendCommentLive").serialize(),
				success: function (result) {

					if (result.success) {
						element.val('');
						$('.blocked').hide();
						$('#showErrorMsg').html('');
						$('#errorMsg').hide();
					} else {

						let error = '';
						let $key = '';

						for ($key in result.errors) {
							error += '<li><i class="fa fa-times-circle"></i> ' + result.errors[$key] + '</li>';
						}

						$('#showErrorMsg').html(error);
						$('#errorMsg').fadeIn(500);
						$('.blocked').hide();
					}
				}//<-- RESULT
			}).fail(function (jqXHR, ajaxOptions, thrownError) {
				$('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
				$('.blocked').hide();
			});//<--- AJAX

		}//e.which == 13
	});//<----- CLICK

	// Hide Top Menu y Chat
	$(document).on('click', '#full-screen-video', function (e) {

		if ($(window).width() <= 767) {
			$('.liveContainerFullScreen').toggleClass('controls-hidden');

			if ($('.liveContainerFullScreen').hasClass('controls-hidden')) {
				$(".live-top-menu").animate({ "top": "-80px" }, "fast");
				$(".wrapper-live-chat").animate({ "bottom": "-250px" }, "fast");

			} else {
				$(".live-top-menu").animate({ "top": "0" }, "slow");
				$(".wrapper-live-chat").animate({ "bottom": "0" }, "slow");
			}
		}
	});

	/*========= Like ==============*/
	$(document).on('click', '.button-like-live', function (e) {
		let element = $(this);
		let id = $('.liveContainerFullScreen').attr("data-id");
		let data = 'id=' + id;

		e.preventDefault();

		element.blur();

		if (!id) {
			return false;
		}

		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type: 'POST',
			url: URL_BASE + "/live/like",
			dataType: 'json',
			data: data,
			success: function (result) {

				if (result.success) {
					if (result.likes !== 0) {
						$('#counterLiveLikes').html(result.likes);
					} else {
						$('#counterLiveLikes').html('');
					}

					if (element.hasClass('active')) {
						element.removeClass('active');
						element.find('i').removeClass('bi-heart-fill').addClass('bi-heart');

						if (result.likes !== 0) {
							$('#counterLiveLikes').html(result.likes);
						} else {
							$('#counterLiveLikes').html('');
						}

					} else {
						element.addClass('active');
						element.find('i').removeClass('bi-heart').addClass('bi-heart-fill');
					}

				} else {
					liveType == 'normal' ? window.location.reload() : window.location.href = URL_BASE;
					element.removeClass('button-like-live');
					element.removeClass('active');
				}
			}//<-- RESULT
		}).fail(function (jqXHR, ajaxOptions, thrownError) {
			$('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred).slideDown('500').delay('5000').slideUp('500');
		});//<--- AJAX

	});//<----- LIKE

})(jQuery);
