/**
 * Created by sergey on 26.02.2015.
 */

jQuery(document).ready(function() {

    jQuery('.pre-register-phone-button').click(function(){
        var $form = jQuery("form");
        var $check_code = jQuery("#sms-check-code");
        jQuery.ajax({
            url: $form.attr('action'),
            cache: false,
            type: 'POST',
            data: $form.serialize() ,
            beforeSend: function () {
                alert($form.attr('action'));
            },
            success: function (response) {
                if (response !== false) {
                    $check_code.html(response);
                    var $form = jQuery("form");
                    jQuery('#sms-check-code-button').click(function(){
                        jQuery.ajax({
                            url: jQuery(this).attr('path-controller'),
                            cache: false,
                            type: 'POST',
                            data: $form.serialize() ,
                            beforeSend: function () {

                            },
                            success: function (response) {
                                if (response !== false) {
                                    $check_code.html(response);
                                }
                            },
                            error: function () {

                            }
                        });
                    });
                }
            },
            error: function () {

            }
        });
    });

});
