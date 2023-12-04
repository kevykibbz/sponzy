//<--------- Add Payment Card -------//>
(function($) {
	"use strict";

	$(window).on('scroll', function () {
		if ($(document).height() - $(this).height() - 100 < $(this).scrollTop()) {
				$('.paginatorMsg').trigger('click');
		}
	 }).scroll();

	// Paginator Messages
 $(document).on('click','.paginatorMsg', function(r) {
		 r.preventDefault();
		 $(this).remove();
		 $('<div class="w-100 p-3 d-block text-center loadMoreMsgSpinner"><span class="spinner-border text-primary"></span></div>').appendTo( "#containerWrapCreators");
				 var page = $(this).attr('data-url').split('page=')[1];
				 var params = query != '' || requestGender ? '&page=' + page : '?page=' + page;

				 $.ajax({
						 url: currentPage + params,

				 }).done(function(data) {
					 if (data) {
						 $('.paginatorMsg, .loadMoreMsgSpinner').remove();

						 $(data).appendTo( "#containerWrapCreators" );

						 $(function () {
							$('[data-toggle="tooltip"]').tooltip()
						});

					 } else {
						 $('.popout').html(error_reload_page).slideDown('500').delay('2500').slideUp('500');
					 }
				 });
		 });// End Paginator Messages

})(jQuery);
