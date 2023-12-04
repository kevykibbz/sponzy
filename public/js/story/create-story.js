(function($) {
	"use strict";

	//<---------------- Create Story ----------->>>>
	$(document).on('click','#createStoryBtn',function(s) {

		s.preventDefault();
		var element = $(this);

		element.attr({'disabled' : 'true'});
		element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

		(function() {

			 $("#addStoryForm").ajaxForm({
			 dataType : 'json',
			 error: function(responseText, statusText, xhr, $form) {
				element.removeAttr('disabled');

				if (! xhr) {
					xhr = '- ' + error_occurred;
				} else {
					xhr = '- ' + xhr;
				}

				$('.popout').removeClass('popout-success').addClass('popout-error').html(error_oops+' '+xhr+'').fadeIn('500').delay('5000').fadeOut('500');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
			 },
			 success: function(result) {

			 if (result.success) {
				if (result.encode) {
					swal({
						type: 'info',
						title: story_on_way,
						text: video_processed_info,
						confirmButtonText: ok
					});

					var api = $.fileuploader.getInstance($('input[name="media"]'));
			 		api.reset();

					 $('input[name="media"]').val('');
					 $('#title').val('');

					 element.removeAttr('disabled');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

				} else {
					window.location.href = URL_BASE;
				}

			 } else {
				var error = '';
				var $key = '';

				for ($key in result.errors) {
					error += '<li><i class="fa fa-times-circle"></i> ' + result.errors[$key] + '</li>';
				}

				$('#showErrorsCreateStory').html(error);
				$('#errorCreateStory').fadeIn(500);

				element.removeAttr('disabled');
				element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

				var api = $.fileuploader.getInstance($('input[name="media"]'));
		 		api.reset();
				}
			}//<----- SUCCESS
			}).submit();
		})(); //<--- FUNCTION %
	});//<<<-------- * END FUNCTION CLICK * ---->>>>

})(jQuery);
