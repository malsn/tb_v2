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
        //jQuery(".form-group:has(select.model)").remove();
        $ajax_req(jQuery(this));
    });
    if ($brand.attr('path-controller')){
        $init = jQuery(".form-group:has(select.model)");
        $init.hide();
        $ajax_req($brand);
        $init.remove();
    }

    var $sizetype = jQuery('.size-type.form-control');
    $sizetype.on('change', function(){
        $search = $(this).parent().parent().next();
        if ( $search.hasClass('ajax-size') ) $search.remove();
        //jQuery(".form-group:has(select.size)").remove();
        $ajax_req(jQuery(this));
    });
    if ($sizetype.attr('path-controller')){
        $init = jQuery(".form-group:has(select.size)");
        $init.hide();
        $ajax_req($sizetype);
        $init.remove();
    }
    var $sizecountry = jQuery('.size-country.form-control');
    $sizecountry.on('change', function(){
        $sizetype = $(this).next();
        $sizetype.trigger('change');
    });

    var $blueimp = jQuery('.blueimp-item-admin');
    if ($blueimp.attr('path-controller')){
        $ajax_req($blueimp);
    }

});
