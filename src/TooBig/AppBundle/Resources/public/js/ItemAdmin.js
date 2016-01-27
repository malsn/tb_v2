/**
 * Created by sergey on 24.11.2015.
 */

jQuery(document).ready(function() {
    
    $ajax_req = function(aj_obj){
        jQuery.ajax({
            url: aj_obj.attr('path-controller'),
            cache: false,
            type: 'POST',
            data: jQuery("form").serialize() ,
            beforeSend: function () {

            },
            success: function (response) {
                if (response !== false) {
                    aj_obj.parent().parent().after(response);
                }
            },
            error: function () {

            }
        });
    };

    var $brand = jQuery('.brand.form-control');
    $brand.on('change', function(){
        jQuery(".ajax-model").remove();
        jQuery(".form-group:has(select.model)").remove();
        $ajax_req(jQuery(this));
    });    
    if ($brand.attr('path-controller')){
        $ajax_req($brand);
    }

    var $sizetype = jQuery('.size-type.form-control');
    $sizetype.on('change', function(){
        jQuery(".ajax-model").remove();
        jQuery(".form-group:has(select.size)").remove();
        $ajax_req(jQuery(this));
    });
    if ($sizetype.attr('path-controller')){
        $ajax_req($sizetype);
    }

    var $blueimp = jQuery('.blueimp-item-admin');
    if ($blueimp.attr('path-controller')){
        $ajax_req($blueimp);
    }

});
