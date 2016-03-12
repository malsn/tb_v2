/**
 * Created by sergey on 05.01.2015.
 */

jQuery(document).ready(function() {
    jQuery('.app_watch_item').click(function(){
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
                                        jQuery('.app_comment_form').html('');
                                        var $alertModal = jQuery('#alert_modal');
                                        $alertModal
                                            .find('div.modal-body')
                                            .html(response.message);
                                        $alertModal.modal('toggle');
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

    jQuery('.filter-title').on('click', function(){
        jQuery('.filter-title').removeClass('filter-open');
        jQuery(this).addClass('filter-open');
    });

    jQuery('.filter-form-submit').on('click', function(){
        jQuery('#ItemsFilter_form').submit();
    });

    jQuery('.form-filter.search').keypress(function(e) {
        if(e.which == 13) {
            jQuery('#ItemsFilter_form').submit();
        }
    });

    jQuery('#price-slider-ui').slider({
        animate: "fast",
        range: true,
        values: [
            jQuery('.filter-form-item.price-min').val() != '' ? jQuery('.filter-form-item.price-min').val()/1000 : jQuery('#rubric-price-min').val()/1000,
            jQuery('.filter-form-item.price-max').val() != '' ? jQuery('.filter-form-item.price-max').val()/1000 : jQuery('#rubric-price-max').val()/1000 ],
        change: function( event, ui ) {
            var price_min = ui.values[0]*1000;
            var price_max = ui.values[1]*1000;
            jQuery('.filter-form-item.price-min').val(price_min);
            jQuery('.filter-form-item.price-max').val(price_max);
        }
    });

    var phone_request = function ($phone_container, $button) {
        jQuery.ajax({
            url: $button.attr('path-controller'),
            cache: false,
            type: 'POST',
            data: jQuery("form").serialize() ,
            beforeSend: function () {

            },
            success: function (response) {
                if (response !== false) {
                    $phone_container.html(response);
                    jQuery('.item-phone-captcha').click(function(){
                        phone_request($phone_container, $button);
                    });
                }
            },
            error: function () {

            }
        });
    }

    jQuery('.show-phone-link').click(function(){
        var $button = jQuery(this);
        var $phone_container = jQuery('.item-author-phone-value');
        phone_request($phone_container, $button);
    });

    /* Items Filter */

    jQuery(".filter-color-box")
        .each(function(){
            sibcheckbox = jQuery(this).siblings("input");
            sibcheckbox.attr("checked") ? jQuery(this).addClass('glyphicon glyphicon-ok'): null;
        })
        .on('click', function(){
            sibcheckbox = jQuery(this).siblings("input");
            jQuery(".Item_color .filter-color-box")
                .removeClass('glyphicon glyphicon-ok')
                .siblings("input[type=radio]")
                .attr("checked",false);
            jQuery(this).toggleClass('glyphicon glyphicon-ok');
            sibcheckbox.attr("checked", !sibcheckbox.attr("checked"));
        });


});
