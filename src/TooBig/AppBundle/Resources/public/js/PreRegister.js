/**
 * Created by sergey on 26.02.2015.
 */

jQuery(document).ready(function() {

    var finish_register = function(){
        var $form = jQuery("form[name='FinishRegister']");
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
                    jQuery('.finish-register-button').click(function(){
                        finish_register();
                    });
                    if (response.error != '') {
                        $check_code.html(response.error);
                    }
                }
            },
            error: function () {
                jQuery('.finish-register-button').click(function(){
                    finish_register();
                });
            }
        });
    }

    var pre_register_phone = function(){
        if (jQuery("#PreRegister_phone").val()!=''){
            var $form = jQuery("form[name='PreRegister']");
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
                        jQuery('.finish-register-button').click(function(){
                            finish_register();
                        });
                        jQuery('.pre-register-phone-button').click(function(){
                            pre_register_phone();
                        });
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
    }

    var ajax_to_modal = function($button){
        jQuery.ajax({
            url: $button.attr('path-controller'),
            cache: false,
            type: 'POST',
            data: null ,
            beforeSend: function () {

            },
            success: function (response) {
                if (response !== false) {
                    var $alertModal = jQuery('#alert_modal');
                    $alertModal
                        .find('div.modal-body')
                        .html(response);
                    jQuery('.btn-ajax-to-modal').click(function(){
                        ajax_to_modal(jQuery(this));
                    });
                    jQuery('.pre-register-phone-button').click(function(){
                        pre_register_phone();
                    });
                    jQuery("#PreRegister_phone").mask("+7(999) 999-99-99");
                    $alertModal.modal({show: true});
                }
            },
            error: function () {

            }
        });
    }

    jQuery('.btn-ajax-to-modal').click(function(){
        ajax_to_modal(jQuery(this));
    });

});
