(function ($) {
    "use strict";

    $(document).on('click', '.livePrivateBtn', function (s) {

        var isValid = this.form.checkValidity();

        if (isValid) {
            s.preventDefault();
            var element = $(this);
            element.attr({ 'disabled': 'true' });
            element.find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

            (function () {
                $('#formRequestLivePrivate').ajaxForm({
                    dataType: 'json',
                    success: function (response) {

                        if (response.success && response.url) {
                            window.location.href = response.url;
                        } else {

                            if (response.errors) {
                                var error = '';
                                var $key = '';

                                for ($key in response.errors) {
                                    error += '<li><i class="far fa-times-circle"></i> ' + response.errors[$key] + '</li>';
                                }

                                $('#showLivePrivate').html(error);
                                $('#errorLivePrivate').show();
                                element.removeAttr('disabled');
                                element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
                            }
                        }

                    },
                    error: function (responseText, statusText, xhr, $form) {
                        // error
                        element.removeAttr('disabled');
                        element.find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
                        swal({
                            type: 'error',
                            title: error_oops,
                            text: error_occurred + ' (' + xhr + ')',
                        });
                    }
                }).submit();
            })(); //<--- FUNCTION %
        }// isValid
    });//<<<-------- * END FUNCTION CLICK * ---->>>>
    //============ End Payment =================//

    function toFixed(number, decimals) {
        var x = Math.pow(10, Number(decimals) + 1);
        return (Number(number) + (1 / x)).toFixed(decimals);
    }

    $('#modalLivePrivateRequest').on('show.bs.modal', function (event) {
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        var minutes = modal.find('.minutes');
        var pricePerMinute = modal.find('.minutes').attr('data-price-minute');
        var taxes = modal.find('li.isTaxable').length;


        $(minutes).on('change', function () {
            var totalTax = 0;

            var valueOriginal = $(this).val();
            var amount = parseFloat($(this).val()) * pricePerMinute;

            console.log(amount);

            if (valueOriginal.length == 0 || valueOriginal == '') {
                // Reset
                for (var i = 1; i <= taxes; i++) {
                    modal.find('.amount' + i).html('0');
                }

                modal.find('.subtotalTip').html('0');
                modal.find('.totalTip').html('0');
            } else {
                // Taxes
                for (var i = 1; i <= taxes; i++) {
                    var percentage = modal.find('.percentageAppliedTax' + i).attr('data');
                    var value = (amount * percentage / 100);
                    modal.find('.amount' + i).html(toFixed(value, decimalZero));
                    totalTax += value;
                }

                var totalTaxes = (Math.round(totalTax * 100) / 100).toFixed(2);
                modal.find('.subtotalTip').html(parseFloat(amount).toFixed(decimalZero));

                var totalTip = parseFloat((parseFloat(amount) + parseFloat(totalTaxes))).toFixed(decimalZero);
                modal.find('.totalTip').html(totalTip);
            }

        });

    });// show.bs.modal

    $('#modalLivePrivateRequest').on('hidden.bs.modal', function (e) {
        var modal = $(this);
        $('#errorLivePrivate').hide();
        $('#formRequestLivePrivate').trigger("reset");

        var taxes = modal.find('li.isTaxable').length;

        for (var i = 1; i <= taxes; i++) {
            modal.find('.amount' + i).html('0');
        }

        modal.find('.subtotalTip').html('0');
        modal.find('.totalTip').html('0');
    });

})(jQuery);