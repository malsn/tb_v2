/**
 * Created by sergey on 05.01.2015.
 */

jQuery(document).ready(function() {
    jQuery('#app_watch_item').click(function(){
        var $button = jQuery(this);
        jQuery.ajax({
            url: $button.attr('path-controller'),
            cache: false,
            type: 'POST',
            data: null ,
            beforeSend: function () {

            },
            success: function (response) {
                if (response !== false) {
                    $button.html(response.caption);
                    $button.attr('path-controller', response.path );
                    if (response.error){
                        alert(response.error);
                    }
                }
            },
            error: function () {

            }
        });
    });

    jQuery('.a-unwatch-item').click(function(){
        var $button = jQuery(this);
        jQuery.ajax({
            url: $button.attr('path-controller'),
            cache: false,
            type: 'POST',
            data: null ,
            beforeSend: function () {

            },
            success: function (response) {
                if (response !== false) {
                    $button.parent().parent().remove();
                }
            },
            error: function () {

            }
        });
    });

    jQuery('#app_comment_item').click(function(){
        var $button = jQuery(this);
        jQuery.ajax({
            url: $button.attr('path-controller'),
            cache: false,
            type: 'POST',
            data: null ,
            beforeSend: function () {

            },
            success: function (response) {
                if (response !== false) {
                    $button.remove();
                    jQuery('.app_comment_form').html( response );
                    jQuery('#RateComment_save').click(function(){
                        var $save_button = jQuery(this);
                        jQuery.ajax({
                            url: $save_button.attr('path-controller'),
                            cache: false,
                            type: 'POST',
                            data: jQuery("form.rate-comment").serialize() ,
                            beforeSend: function () {

                            },
                            success: function (response) {
                                if (response !== false) {
                                    if ( response.message != 'undefined' ){
                                        jQuery('.app_comment_form').html( response.message );
                                    }
                                }
                            },
                            error: function () {

                            }
                        });
                    })

                }
            },
            error: function () {

            }
        });
    });

    jQuery('.filter-form-item').on('change', function(){
        jQuery('#ItemsFilter_form').submit();
    });

    jQuery('#price-slider-ui').slider({
        animate: "fast",
        range: true,
        values: [ 0, 100 ],
        change: function( event, ui ) {
            var price_min = ui.values[0]*100000/100;
            var price_max = ui.values[1]*100000/100;
            jQuery('.ui-slider-handle:first').html(price_min);
            jQuery('.filter-form-item.price-min').val(price_min);
            jQuery('.ui-slider-handle:last').html(price_max);
            jQuery('.filter-form-item.price-max').val(price_max);
        }
    });

});
