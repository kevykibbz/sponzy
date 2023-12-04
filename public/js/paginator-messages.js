//<--------- Add Payment Card -------//>
(function($) {
	"use strict";

	$('.wrapper-msg-inbox').on('scroll', function(){
   if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
      $('.paginatorMsg').trigger('click');
   }
});

	// Paginator Messages
 $(document).on('click','.paginatorMsg', function(r) {
		 r.preventDefault();
		 $(this).remove();
		 $('<div class="w-100 p-3 d-block text-center loadMoreMsgSpinner"><span class="spinner-border text-primary"></span></div>').appendTo( "#messagesContainer");
		 // $(this).addClass('disabled').html('<span class="spinner-border spinner-border-sm"></span>');

				 var page = $(this).attr('data-url').split('page=')[1];
				 $.ajax({
						 url: URL_BASE+'/messages?page=' + page,

				 }).done(function(data) {
					 if (data) {
						 $('.paginatorMsg, .loadMoreMsgSpinner').remove();

						 $(data).appendTo( "#messagesContainer" );
						 jQuery(".timeAgo").timeago();

					 } else {
						 $('.popout').html(error_reload_page).slideDown('500').delay('2500').slideUp('500');
					 }
				 });
		 });// End Paginator Messages

})(jQuery);
