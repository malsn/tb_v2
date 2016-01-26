/**
 * Created by sergey on 24.11.2015.
 */

jQuery(document).ready(function() {
    jQuery('.brand.form-control').on('change', function(){
        var $select = jQuery(this);
        jQuery(".ajax-model").remove();
        jQuery(".form-group:has(select.model)").remove();
        jQuery.ajax({
            url: $select.attr('path-controller'),
            cache: false,
            type: 'POST',
            data: jQuery("form").serialize() ,
            beforeSend: function () {

            },
            success: function (response) {
                if (response !== false) {
                    $select.parent().parent().after(response);
                }
            },
            error: function () {

            }
        });
    })
    jQuery('.size-type.form-control').on('change', function(){
        var $select = jQuery(this);
        jQuery(".ajax-size").remove();
        jQuery(".form-group:has(select.size)").remove();
        jQuery.ajax({
            url: $select.attr('path-controller'),
            cache: false,
            type: 'POST',
            data: jQuery("form").serialize() ,
            beforeSend: function () {

            },
            success: function (response) {
                if (response !== false) {
                    $select.parent().parent().after(response);
                }
            },
            error: function () {

            }
        });
    })

    var $blueimp = jQuery('.blueimp');
    jQuery.ajax({
        url: $blueimp.attr('path-controller'),
        cache: false,
        type: 'POST',
        data: jQuery("form").serialize() ,
        beforeSend: function () {

        },
        success: function (response) {
            if (response !== false) {
                $blueimp.parent().parent().after(response);
                jQuery('.jqzoom').jqzoom({
                    zoomType: 'standard',
                    lens:true,
                    preloadImages: false,
                    alwaysOn:false
                });
            }
        },
        error: function () {

        }
    });
});
