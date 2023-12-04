//<--------- Upload Avatar and Cover -------//>
(function ($) {
  "use strict";

  //<<<<<<<=================== * UPLOAD AVATAR  * ===============>>>>>>>//
  $(document).on('change', '#uploadAvatar', function () {

    $('.progress-upload').show();

    (function () {

      var percent = $('.progress-upload');
      var percentVal = '0%';

      $("#formAvatar").ajaxForm({
        dataType: 'json',
        error: function (responseText, statusText, xhr, $form) {

          $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred + ' ' + xhr + '').fadeIn('500').delay('5000').fadeOut('500');
          $('.progress-upload').hide();
          $('#uploadAvatar').val('');
          percent.html(percentVal);
        },

        beforeSend: function () {
          percent.html(percentVal);
        },
        uploadProgress: function (event, position, total, percentComplete) {
          var percentVal = percentComplete + '%';
          percent.html(percentVal);
        },
        success: function (e) {
          if (e) {

            if (e.success == false) {
              $('.progress-upload').hide();

              var error = '';
              var $key = '';

              for ($key in e.errors) {
                error += '' + e.errors[$key] + '';
              }
              swal({
                title: error_oops,
                text: "" + error + "",
                type: "error",
                confirmButtonText: ok
              });

              $('#uploadAvatar').val('');
              percent.html(percentVal);

            } else {

              $('#uploadAvatar').val('');
              $('.avatarUser').attr('src', e.avatar);
              $('.progress-upload').hide();
              percent.html(percentVal);
            }

          }//<-- e
          else {
            $('.progress-upload').hide();
            percent.html(percentVal);
            swal({
              title: error_oops,
              text: error_occurred,
              type: "error",
              confirmButtonText: ok
            });

            $('#uploadAvatar').val('');
          }
        }//<----- SUCCESS
      }).submit();
    })(); //<--- FUNCTION %
  });//<<<<<<<--- * ON * --->>>>>>>>>>>
  //<<<<<<<=================== * END UPLOAD AVATAR  * ===============>>>>>>>//

  //<<<<<<<=================== * UPLOAD COVER  * ===============>>>>>>>//
  $(document).on('change', '#uploadCover', function () {


    $('#coverFile').attr({ 'disabled': 'true' }).html('<i class="spinner-border spinner-border-sm"></i>');

    $('.progress-upload-cover').show();

    (function () {

      var percent = $('.progress-upload-cover');
      var percentVal = '0%';

      $("#formCover").ajaxForm({
        dataType: 'json',
        error: function (responseText, statusText, xhr, $form) {

          $('.popout').removeClass('popout-success').addClass('popout-error').html(error_occurred + ' ' + xhr + '').fadeIn('500').delay('5000').fadeOut('500');
          $('#coverFile').removeAttr('disabled').html('<i class="fa fa-camera mr-1"></i> <span class="d-none d-lg-inline">' + change_cover + '</span>');
          $('.progress-upload-cover').hide();
          $('#uploadCover').val('');
          percent.width(percentVal);
        },

        beforeSend: function () {
          percent.width(percentVal);
        },
        uploadProgress: function (event, position, total, percentComplete) {
          var percentVal = percentComplete + '%';
          percent.width(percentVal);
        },
        success: function (e) {
          if (e) {

            if (e.success == false) {

              $('#coverFile').removeAttr('disabled').html('<i class="fa fa-camera mr-1"></i> <span class="d-none d-lg-inline">' + change_cover + '</span>');
              $('.progress-upload-cover').hide();

              var error = '';
              var $key = '';

              for ($key in e.errors) {
                error += '' + e.errors[$key] + '';
              }
              swal({
                title: error_oops,
                text: "" + error + "",
                type: "error",
                confirmButtonText: ok
              });

              $('#uploadCover').val('');
              percent.width(percentVal);

            } else {

              $('#coverFile').removeAttr('disabled').html('<i class="fa fa-camera mr-1"></i> <span class="d-none d-lg-inline">' + change_cover + '</span>');
              $('#uploadCover').val('');
              $('.jumbotron-cover-user').css({ padding: '240px 0', background: 'url("' + e.cover + '") center center #505050', backgroundSize: 'cover', transition: 'padding .6s ease' });
              $('.progress-upload-cover').hide();
              percent.width(percentVal);
            }

          }//<-- e
          else {
            $('#coverFile').removeAttr('disabled').html('<i class="fa fa-camera mr-1"></i> <span class="d-none d-lg-inline">' + change_cover + '</span>');
            $('.progress-upload-cover').hide();
            percent.width(percentVal);
            swal({
              title: error_oops,
              text: error_occurred,
              type: "error",
              confirmButtonText: ok
            });

            $('#uploadCover').val('');
          }
        }//<----- SUCCESS
      }).submit();
    })(); //<--- FUNCTION %
  });//<<<<<<<--- * ON * --->>>>>>>>>>>
  //<<<<<<<=================== * END UPLOAD COVER  * ===============>>>>>>>//

})(jQuery);
