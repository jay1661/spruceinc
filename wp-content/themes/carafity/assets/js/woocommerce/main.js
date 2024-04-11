(function ($) {
    'use strict';
    var $body = $('body');

    function tooltip() {

        $body.on('mouseenter', '.shop-action .woosw-btn:not(.tooltipstered), .shop-action .woosq-btn:not(.tooltipstered), .shop-action .woosc-btn:not(.tooltipstered)', function () {
            var $element = $(this);
            if (typeof $.fn.tooltipster !== 'undefined') {
                $element.tooltipster({
                    position: 'top',
                    functionBefore: function (instance, helper) {
                        instance.content(instance._$origin.text());
                    },
                    theme: 'opal-product-tooltipster',
                    delay: 0,
                    animation: 'grow'
                }).tooltipster('show');
            }
        });
    }

    function ajax_wishlist_count() {

        $(document).on('added_to_wishlist removed_from_wishlist', function () {
            var counter = $('.header-wishlist .count, .footer-wishlist .count');
            $.ajax({
                url: yith_wcwl_l10n.ajax_url,
                data: {
                    action: 'yith_wcwl_update_wishlist_count'
                },
                dataType: 'json',
                success: function (data) {
                    counter.html(data.count);
                },
            });
        });

        $body.on('woosw_change_count', function (event, count) {
            var counter = $('.header-wishlist .count, .footer-wishlist .count');
            if(count == 0) {
                counter.addClass('hide');
            }else  {
                counter.removeClass('hide');
            }
            counter.html(count);
        });
    }

    $(document).ready(function () {
        tooltip();
    });
    ajax_wishlist_count();
})(jQuery);
