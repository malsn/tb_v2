/**
 * Created by sergey on 26.02.2015.
 */

var ajax_to_modal = function($button){
    $.ajax({
        url: $button.attr('path-controller'),
        cache: false,
        type: 'POST',
        data: null ,
        beforeSend: function () {

        },
        success: function (response) {
            if (response !== false) {
                var $alertModal = $('#alert_modal');
                $alertModal
                    .find('div.modal-body')
                    .html(response);
                $('.btn-ajax-to-modal').click(function(){
                    ajax_to_modal($(this));
                });
                $('.pre-register-phone-button').click(function(){
                    pre_register_phone();
                });
                $("#PreRegister_phone").mask("+7(999) 999-99-99");
                $alertModal.modal({show: true});
            }
        },
        error: function () {

        }
    });
}

var pre_register_phone = function(){
    if ($("#PreRegister_phone").val()!=''){
        var $form = $("form[name='PreRegister']");
        var $check_code = $("#sms-check-code");
        $.ajax({
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
                    $('.finish-register-button').click(function(){
                        finish_register();
                    });
                    $('.btn-ajax-to-modal').click(function(){
                        ajax_to_modal($(this));
                    });
                    if (response.error != '') {
                        $check_code.html(response.error);
                    }
                    var $form = $("form");
                    $('#sms-check-code-button').click(function(){
                        $.ajax({
                            url: $(this).attr('path-controller'),
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

var finish_register = function(){
    var $form = $("form[name='FinishRegister']");
    var $check_code = $("#sms-check-code");
    $.ajax({
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
                $('.finish-register-button').click(function(){
                    finish_register();
                });
                if (response.error != '') {
                    $check_code.html(response.error);
                }
            }
        },
        error: function () {
            $('.finish-register-button').click(function(){
                finish_register();
            });
        }
    });
}

$('.btn-ajax-to-modal').click(function(){
    ajax_to_modal($(this));
});
