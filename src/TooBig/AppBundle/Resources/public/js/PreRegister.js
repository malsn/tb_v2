/**
 * Created by sergey on 26.02.2015.
 */

jQuery(document).ready(function() {

    jQuery('.pre-register-phone-button').click(function(){
        if (jQuery("#PreRegister_phone").val()!=''){
            var $form = jQuery("form");
            var $check_code = jQuery("#sms-check-code");
            jQuery.ajax({
                url: $form.attr('action'),
                cache: false,
                type: 'POST',
                data: $form.serialize() ,
                beforeSend: function () {
                    $check_code.html("<img src='/bundles/toobigapp/images/loading.gif' border='0'>");
                },
                success: function (response) {
                    if (response !== false) {
                        $check_code.html(response);
                        if (response.error != '') {
                            $check_code.html(response.error);
                        }
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
                                        if (response.error != '') {
                                            $check_code.html(response.error);
                                        } else if (response.success != '') {

                                        }
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
        } else {
            alert('Требуется заполнить поле телефонного номера!');
        }
    });

});
