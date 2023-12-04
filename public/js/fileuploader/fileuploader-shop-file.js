$(document).ready(function() {

	// enable fileupload plugin
	$('input[name="file"]').fileuploader({
        limit: 1,
        fileMaxSize: maxSizeInMb,
        extensions: [
          'png',
          'jpeg',
          'jpg',
          'gif',
          'ief',
          'video/mp4',
          'video/quicktime',
          'video/3gpp',
          'video/mpeg',
          'video/x-matroska',
          'video/x-ms-wmv',
          'video/vnd.avi',
          'video/avi',
          'video/x-flv',
          'audio/x-matroska',
          'audio/mpeg',
          'application/x-zip-compressed',
					'application/zip',
					'application/pdf'
        ],

        captions: lang,
        dialogs: {
        // alert dialog
        alert: function(text) {
            return swal({
             title: error_oops,
             text: text,
             type: "error",
             confirmButtonText: ok
             });
        },

        // confirm dialog
        confirm: function(text, callback) {
            confirm(text) ? callback() : null;
        }
    },

		upload: {
            url: URL_BASE+'/upload/media/shop/file',
            data: null,
            type: 'POST',
            enctype: 'multipart/form-data',
            start: true,
            synchron: true,
            chunk: 50,
            beforeSend: function(item) {

							// here you can create upload headers
			        item.upload.headers = {
			            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			        };

				var input = $('#custom_file_name');

				// set the POST field
				if(input.length)
					item.upload.data.custom_name = input.val();

				// reset input value
				input.val("");
			},
            onSuccess: function(result, item) {
                var data = {};

				if (result && result.files)
                    data = result;
                else
					data.hasWarnings = true;

				// get the new file name
                if(data.isSuccess && data.files[0]) {
                    item.name = data.files[0].name;
                    item.html.find('.column-title div').animate({opacity: 0}, 400);
                }

                item.html.find('.fileuploader-action-remove').addClass('fileuploader-action-success');
                setTimeout(function() {
					item.html.find('.column-title div').attr('title', item.name).text(item.name).animate({opacity: 1}, 400);
                    item.html.find('.progress-bar2').fadeOut(400);
                }, 400);
            },
            onError: function(item) {
				var progressBar = item.html.find('.progress-bar2');

				// make HTML changes
				if(progressBar.length > 0) {
					progressBar.find('span').html(0 + "%");
                    progressBar.find('.fileuploader-progressbar .bar').width(0 + "%");
					item.html.find('.progress-bar2').fadeOut(400);
				}

                item.upload.status != 'cancelled' && item.html.find('.fileuploader-action-retry').length == 0 ? item.html.find('.column-actions').prepend(
                    '<button type="button" class="fileuploader-action fileuploader-action-retry" title="Retry"><i class="fileuploader-icon-retry"></i></button>'
                ) : null;
            },
            onProgress: function(data, item) {
                var progressBar = item.html.find('.progress-bar2');

				// make HTML changes
                if(progressBar.length > 0) {
                    progressBar.show();
                    progressBar.find('span').html(data.percentage + "%");
                    progressBar.find('.fileuploader-progressbar .bar').width(data.percentage + "%");
                }
            },
            onComplete: null,
        },
		onRemove: function(item) {
			// send POST request
			$.post(URL_BASE+'/delete/media/shop/file', {
				file: item.name,
        _token: $('meta[name="csrf-token"]').attr('content')
			});
		}
	});

});
