jQuery(document).ready(function ($) {
    var $form = $('form[data-hc-form]');

    if ( ! $form.length) {
        console.log('no form[data-hc-form] found');
        return;
    }

    var $feedback = $('[data-hc-feedback]');

    if ( ! $feedback.length) {
        console.log('no [data-hc-feedback] found');
        return;
    }

    var $button = $form.find('button[type=submit]');

    if ( ! $button.length) {
        console.log('no button[type=submit] found');
        return;
    }

    var oldText = $button.text();

    $form.ajaxForm({
        dataType: 'json',
        data: {
            action: hc.action,
            nonce : hc.nonce
        },
        beforeSend: function () {
            $feedback.removeClass('error success').html('');
            $button.text(hc.loadingText).attr('disabled', '');
        },
        complete: function () {
            $button.text(oldText).removeAttr('disabled');
        },
        success: function (response) {
            if (response.success) {
                $feedback.addClass('success');
            } else {
                $feedback.addClass('error');
            }

            $feedback.html(response.message);
        }
    });
});
