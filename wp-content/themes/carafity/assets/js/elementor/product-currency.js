(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/carafity-product-currency.default', ($scope) => {

            if($scope.hasClass('carafity-woocs-action-click')){
                let $button = $('.carafity-woocs-select',$scope),
                    $parent = $('.carafity-woocs-dropdown',$scope);

                $button.on('click',function (e) {
                    e.preventDefault();
                    $parent.toggleClass('active');
                })
            }

            $('.carafity-woocs-dropdown-menu li').on('click', function () {

                var l = woocs_remove_link_param('currency', window.location.href);
                l = l.replace("#", "");

                if (woocs_special_ajax_mode) {
                    var data = {
                        action: "woocs_set_currency_ajax",
                        currency: $(this).data('currency')
                    };

                    $.post(woocs_ajaxurl, data, function (value) {
                        window.location = l;
                    });
                } else {
                    if (Object.keys(woocs_array_of_get).length === 0) {
                        window.location = l + '?currency=' + $(this).data('currency');
                    } else {
                        woocs_redirect($(this).data('currency'));
                    }
                }
            });
            document.addEventListener('after_woocs_get_products_price_html', function (e) {
                var current_currency = e.detail.current_currency;
                $.each($('.carafity-woocs-dropdown'), function (i, d) {
                    $(d).find('.carafity-woocs-select > span').html($(d).find(`li[data-currency=${current_currency}]`).html());
                });
            });
        });
    });
})(jQuery);
