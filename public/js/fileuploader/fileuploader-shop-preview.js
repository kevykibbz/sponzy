/**
 * custom.js - fileuploader
 * Copyright (c) 2021 Innostudio.de
 * Website: https://innostudio.de/fileuploader/
 * Version: 2.2 (27 Nov 2020)
 * License: https://innostudio.de/fileuploader/documentation/#license
 */
$(document).ready(function() {

	// enable fileuploader plugin
	$('input[name="preview"]').fileuploader({
		fileMaxSize: maxSizeInMb,
    limit: 5,
    extensions: [
      'png',
      'jpeg',
      'jpg'
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

		changeInput: ' ',
		theme: 'thumbnails',
        enableApi: true,
		addMore: true,
		thumbnails: {
			box: '<div class="fileuploader-items">' +
                      '<ul class="fileuploader-items-list">' +
					      '<li class="fileuploader-thumbnails-input"><div class="fileuploader-thumbnails-input-inner"><i>+</i></div></li>' +
                      '</ul>' +
                  '</div>',
			item: '<li class="fileuploader-item">' +
				       '<div class="fileuploader-item-inner">' +
                           '<div class="type-holder">${extension}</div>' +
                           '<div class="actions-holder">' +
						   	   '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
                           '</div>' +
                           '<div class="thumbnail-holder">' +
                               '${image}' +
                               '<span class="fileuploader-action-popup"></span>' +
                           '</div>' +
                           '<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' +
                       	   '<div class="progress-holder">${progressBar}</div>' +
                       '</div>' +
                  '</li>',
			item2: '<li class="fileuploader-item">' +
				       '<div class="fileuploader-item-inner">' +
                           '<div class="type-holder">${extension}</div>' +
                           '<div class="actions-holder">' +
						   	   '<a href="${file}" class="fileuploader-action fileuploader-action-download" title="${captions.download}" download><i class="fileuploader-icon-download"></i></a>' +
						   	   '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
                           '</div>' +
                           '<div class="thumbnail-holder">' +
                               '${image}' +
                               '<span class="fileuploader-action-popup"></span>' +
                           '</div>' +
                           '<div class="content-holder"><h5 title="${name}">${name}</h5><span>${size2}</span></div>' +
                       	   '<div class="progress-holder">${progressBar}</div>' +
                       '</div>' +
                   '</li>',
			startImageRenderer: true,
            canvasImage: false,
            exif: true,
			_selectors: {
				list: '.fileuploader-items-list',
				item: '.fileuploader-item',
				start: '.fileuploader-action-start',
				retry: '.fileuploader-action-retry',
				remove: '.fileuploader-action-remove'
			},

			onItemShow: function(item, listEl, parentEl, newInputEl, inputEl) {
				var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                    api = $.fileuploader.getInstance(inputEl.get(0));

                plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? 'hide' : 'show']();

				if(item.format == 'image') {
					item.html.find('.fileuploader-item-icon').hide();
				}
			},

      onItemRemove: function(html, listEl, parentEl, newInputEl, inputEl) {
                var plusInput = listEl.find('.fileuploader-thumbnails-input'),
				    api = $.fileuploader.getInstance(inputEl.get(0));

                html.children().animate({'opacity': 0}, 200, function() {
                    html.remove();

                    if (api.getOptions().limit && api.getChoosedFiles().length - 1 < api.getOptions().limit)
                        plusInput.show();
                });
            }
		},

        dragDrop: {
			container: '.fileuploader-thumbnails-input'
		},
		afterRender: function(listEl, parentEl, newInputEl, inputEl) {
			var plusInput = listEl.find('.fileuploader-thumbnails-input'),
				api = $.fileuploader.getInstance(inputEl.get(0));

			plusInput.on('click', function() {
				api.open();
			});

            api.getOptions().dragDrop.container = plusInput;
		},

		// while using upload option, please set
		// startImageRenderer: false
		// for a better effect
		upload: {
			url: URL_BASE+'/upload/media/shop/preview',
            data: null,
            type: 'POST',
            enctype: 'multipart/form-data',
            start: true,
            synchron: true,
            chunk: 50,
            beforeSend: function(item, listEl, parentEl, newInputEl, inputEl) {

        // here you can create upload headers
        item.upload.headers = {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        };
        return true;
      },

            onSuccess: function(result, item) {
				var data = {};

				if (result && result.files) {
          data = result;
        } else {
          data.hasWarnings = true;
        }

				// if success
          if (data.isSuccess && data.files.length) {
                    item.name = data.files[0].name;
					item.html.find('.content-holder > h5').text(item.name).attr('title', item.name);
          }

				// if warnings
				if (data.hasWarnings) {
          var error = '';

					for (var warning in data.warnings) {
            error += data.warnings[warning];
					}

          swal({
           title: error_oops,
           text: error,
           type: "error",
           confirmButtonText: ok
           });

          item.remove();

					// item.html.removeClass('upload-successful').addClass('upload-failed');
					return this.onError ? this.onError(item) : null;
				}

        item.html.find('.fileuploader-action-remove').addClass('fileuploader-action-success');

				setTimeout(function() {
					item.html.find('.progress-holder').hide();
					item.renderThumbnail();

                    item.html.find('.fileuploader-action-popup, .fileuploader-item-image').show();
				}, 400);

            },
            onError: function(item) {
				item.html.find('.progress-holder, .fileuploader-action-popup, .fileuploader-item-image').hide();

            },
            onProgress: function(data, item) {
                var progressBar = item.html.find('.progress-holder');

                if(progressBar.length > 0) {
                    progressBar.show();
                    progressBar.find('.fileuploader-progressbar .bar').width(data.percentage + "%");
                }

                item.html.find('.fileuploader-action-popup, .fileuploader-item-image').hide();
            }
        },
		onRemove: function(item) {
			$.post(URL_BASE+'/delete/media/shop/preview', {
				file: item.name,
        _token: $('meta[name="csrf-token"]').attr('content')
			});
		}

  }); // End fileuploader()
});
