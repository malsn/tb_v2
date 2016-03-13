/**
 * Created by sergey on 14.02.2016.
 */

jQuery(document).ready(function() {

    $rubric_req = function($struct_obj){
        jQuery.ajax({
            url: jQuery("#Rubric_form").attr('path-controller'),
            cache: false,
            type: 'POST',
            data: jQuery("form").serialize(),
            beforeSend: function () {

            },
            success: function (response) {
                if (response !== false) {
                    $struct_obj.html(response);
                    var $select = jQuery('.form-rubric.form-control');
                    $select.on('change', function(){
                        jQuery('#rubric').val(jQuery(this).val());
                        jQuery('#struct').val(jQuery(this).attr('data-struct'));
                        var $struct = jQuery('#struct-'+jQuery('#struct').val());
                        $rubric_req($struct);
                    })
                }
            },
            error: function () {

            }
        });
    };


    if (jQuery('#Rubric_form').attr('path-controller')){
        var $struct = jQuery('#struct-1');
        $rubric_req($struct);
    }

    $rubric_subscription_req = function($struct_obj){
        jQuery.ajax({
            url: jQuery("#Subscription").attr('path-controller'),
            cache: false,
            type: 'POST',
            data: jQuery("form").serialize(),
            beforeSend: function () {

            },
            success: function (response) {
                if (response !== false) {
                    $struct_obj.html(response);
                    jQuery('a.btn-rubric').remove();
                    var $select = jQuery('.form-rubric.form-control');
                    $select.on('change', function(){
                        jQuery('#rubric').val(jQuery(this).val());
                        jQuery('#Subscription_rubric').val(jQuery(this).val());
                        jQuery('#struct').val(jQuery(this).attr('data-struct'));
                        var $struct = jQuery('#struct-'+jQuery('#struct').val());
                        $rubric_subscription_req($struct);
                    })
                }
            },
            error: function () {

            }
        });
    };


    if (jQuery('#Subscription').attr('path-controller')){
        var $struct = jQuery('#struct-1');
        $rubric_subscription_req($struct);
    }


});
