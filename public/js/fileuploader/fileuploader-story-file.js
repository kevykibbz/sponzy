$(document).ready(function () {
    // enable fileuploader plugin
    $('input[name="media"]').fileuploader({
        limit: 1,
        fileMaxSize: maxSizeInMb,
        extensions: extensionsStories,

        captions: lang,
        dialogs: {
            // alert dialog
            alert: function (text) {
                return swal({
                    title: error_oops,
                    text: text,
                    type: "error",
                    confirmButtonText: ok
                });
            },

            // confirm dialog
            confirm: function (text, callback) {
                confirm(text) ? callback() : null;
            }
        },

        changeInput: '<div class="fileuploader-input">' +
            '<div class="fileuploader-input-inner">' +
            '<div class="fileuploader-icon-main"></div>' +
            '<h3 class="fileuploader-input-caption"><span>${captions.feedback}</span></h3>' +
            '<p>${captions.or}</p>' +
            '<button type="button" class="fileuploader-input-button"><span>${captions.button}</span></button>' +
            '</div>' +
            '</div>',
        theme: 'dragdrop',
        enableApi: true,
        upload: {
            url: URL_BASE + '/upload/media/story/file',
            data: null,
            type: 'POST',
            enctype: 'multipart/form-data',
            start: true,
            synchron: false,
            chunk: 50,
            beforeSend: function (item, listEl, parentEl, newInputEl, inputEl) {
                // here you can create upload headers
                item.upload.headers = {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                };

                if (item.format == 'video') {
                    let file = item.file;
                    let dataURL = URL.createObjectURL(file);
                    let el = document.createElement("video");
                    el.src = dataURL;
                    el.onloadedmetadata = () => {
                        let videoDurarion = Math.trunc(el.duration);

                        if (videoDurarion > storyMaxVideosLength) {
                            swal({
                                title: error_oops,
                                text: errorStoryMaxVideosLength,
                                type: "error",
                                confirmButtonText: ok
                            });

                            let api = $.fileuploader.getInstance('input[name="media"]');
                            api.reset();
                        }
                    };
                }

            },
            onSuccess: function (result, item) {
                var data = {};

                // get data
                if (result && result.files)
                    data = result;
                else
                    data.hasWarnings = true;

                // if success
                if (data.isSuccess && data.files[0]) {
                    item.name = data.files[0].name;
                    item.html.find('.column-title > div:first-child').text(data.files[0].name).attr('title', data.files[0].name);
                }

                // if warnings
                if (data.hasWarnings) {
                    var errors = '';

                    for (var warning in data.warnings) {
                        errors += data.warnings[warning];
                    }

                    // if errors
                    if (result.errors) {
                        for (var error in result.errors) {
                            errors += result.errors[error];
                        }
                    }

                    // item.remove();
                    item.html.removeClass('upload-successful').addClass('upload-failed');
                    item.html.find('.fileuploader-action-retry').remove();
                    item.html.find('.column-title').html('<div class="text-danger">' + errors + '</div>')

                    // go out from success function by calling onError function
                    // in this case we have a animation there
                    // you can also response in PHP with 404
                    return this.onError ? this.onError(item) : null;
                }

                item.html.find('.fileuploader-action-remove').addClass('fileuploader-action-success');
                setTimeout(function () {
                    item.html.find('.progress-bar2').fadeOut(400);
                }, 400);
            },
            onError: function (item) {
                var progressBar = item.html.find('.progress-bar2');

                if (progressBar.length) {
                    progressBar.find('span').html(0 + "%");
                    progressBar.find('.fileuploader-progressbar .bar').width(0 + "%");
                    item.html.find('.progress-bar2').fadeOut(400);
                }

            },
            onProgress: function (data, item) {
                var progressBar = item.html.find('.progress-bar2');

                if (progressBar.length > 0) {
                    progressBar.show();
                    progressBar.find('span').html(data.percentage + "%");
                    progressBar.find('.fileuploader-progressbar .bar').width(data.percentage + "%");
                }
            },
            onComplete: null,
        },
        onRemove: function (item) {
            $.post(URL_BASE + '/story/delete/media', {
                file: item.name,
                _token: $('meta[name="csrf-token"]').attr('content')
            });
        }
    });
});
