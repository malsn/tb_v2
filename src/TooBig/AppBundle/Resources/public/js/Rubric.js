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
                    var $select = jQuery('.form-control');
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


});
