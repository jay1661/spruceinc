(function ($) {
    'use strict';
    var $body = $('body');

    function singleProductGalleryImages() {
        var lightbox = $('.single-product .woocommerce-product-gallery__image > a');
        if (lightbox.length) {
            lightbox.attr("data-elementor-open-lightbox", "no");
        }

        if (typeof elementorFrontendConfig !== 'undefined') {
            const swiperClass = elementorFrontend.config.swiperClass;
            var galleryHorizontal = $('.woocommerce-product-gallery.woocommerce-product-gallery-horizontal .flex-control-thumbs');

            if (galleryHorizontal.length > 0) {
                galleryHorizontal.wrap(`<div class='${swiperClass} ${swiperClass}-thumbs-horizontal'></div>`).addClass(`${swiperClass}-wrapper`).find('li').addClass('swiper-slide');
                new Swiper(`.${swiperClass}-thumbs-horizontal`, {
                    slidesPerView: 'auto',
                    spaceBetween: 10,
                });
            }
            var galleryVertical = $('.woocommerce-product-gallery.woocommerce-product-gallery-vertical .flex-control-thumbs');
            if (galleryVertical.length > 0) {
                galleryVertical.wrap(`<div class='${swiperClass} ${swiperClass}-thumbs-vertical'></div>`).addClass(`${swiperClass}-wrapper`).find('li').addClass('swiper-slide');
                new Swiper(`.${swiperClass}-thumbs-vertical`, {
                    slidesPerView: 'auto',
                    spaceBetween: 10,
                    autoHeight: true,
                    direction: 'vertical',
                    breakpoints: {
                        0: {
                            direction: 'horizontal',
                        },
                        426: {
                            direction: 'vertical',
                        }
                    }
                });
            }
        }
    }

    function setupCarousel(selector) {
        if (typeof elementorFrontendConfig === 'undefined') {
            return;
        }
        if (typeof elementorFrontendConfig.kit === 'undefined') {
            return;
        }

        var kit = elementorFrontendConfig.kit;

        var settingCarousel = {

            slidesPerView: kit.woo_carousel_slides_to_show || 1,
            spaceBetween: kit.woo_carousel_spaceBetween.size || 0,
            handleElementorBreakpoints: true,
            watchSlidesVisibility: true,
            breakpoints: {}
        };

        if (kit.woo_carousel_autoplay === 'yes') {
            settingCarousel.autoplay = {
                delay: kit.woo_carousel_autoplay_speed
            }
        }

        var elementorBreakpoints = kit.active_breakpoints;
        var lastBreakpintOption = {
            slidesPerView: settingCarousel.slidesPerView,
            spaceBetween: settingCarousel.spaceBetween
        };
        let breakpointsKey = Object.keys(elementorBreakpoints).reverse();

        for (let _index in breakpointsKey) {

            var breakpointName = elementorBreakpoints[_index].replace('viewport_', '');

            let currentSettings = {
                spaceBetween: +kit['woo_carousel_spaceBetween_' + breakpointName]['size'] || lastBreakpintOption.spaceBetween,
                slidesPerView: +kit['woo_carousel_slides_to_show_' + breakpointName] || lastBreakpintOption.slidesPerView
            };
            lastBreakpintOption = currentSettings;
            var viewport = elementorFrontend.config.responsive.activeBreakpoints[breakpointName].value;

            settingCarousel.breakpoints[viewport] = currentSettings;

            if (breakpointName === 'mobile') {
                settingCarousel.breakpoints[0] = currentSettings;
            }
        }

        var $container = $(selector);
        const swiperClass = elementorFrontend.config.swiperClass;

        $container.append(`<div class="products-carousel"><div class="${swiperClass}"></div></div>`);

        if (kit.woo_carousel_navigation === 'dots' || kit.woo_carousel_navigation === 'both') {
            $container.find('.products-carousel').append('<div class="swiper-pagination"></div>');
            settingCarousel.pagination = {
                el       : $container.find('.swiper-pagination').get(0),
                type     : 'bullets',
                clickable: true,
            };
        }
        if (kit.woo_carousel_navigation === 'arrows' || kit.woo_carousel_navigation === 'both') {
            $container.append('<div class="elementor-swiper-button elementor-swiper-button-prev"><i class="eicon-chevron-left" aria-hidden="true"></i><span class="elementor-screen-only">Previous</span></div><div class="elementor-swiper-button elementor-swiper-button-next"><i class="eicon-chevron-right" aria-hidden="true"></i><span class="elementor-screen-only">Next</span></div>');
            settingCarousel.navigation = {
                prevEl: $container.find('.elementor-swiper-button-prev').get(0),
                nextEl: $container.find('.elementor-swiper-button-next').get(0),
            };
        }


        $container.find('ul.products').appendTo($container.find(`.products-carousel .${swiperClass}`));
        if ($container.find('li.product').length > 1) {
            $container.find('ul.products').addClass('swiper-wrapper').find('>li').addClass('swiper-slide');
            var gallery_swiper = new Swiper($container.find(`.${swiperClass}`).get(0), settingCarousel)
            $container.data('swiper', gallery_swiper);
        }
    }


    function productTogerther() {

        var $fbtProducts = $('.carafity-frequently-bought');

        if ($fbtProducts.length <= 0) {
            return;
        }
        var priceAt = $fbtProducts.find('.carafity-total-price .woocommerce-Price-amount'),
            $button = $fbtProducts.find('.carafity_add_to_cart_button'),
            totalPrice = parseFloat($fbtProducts.find('#carafity-data_price').data('price')),
            currency = $fbtProducts.data('currency'),
            thousand = $fbtProducts.data('thousand'),
            decimal = $fbtProducts.data('decimal'),
            price_decimals = $fbtProducts.data('price_decimals'),
            currency_pos = $fbtProducts.data('currency_pos');

        let formatNumber = function (number) {
            let n = number;
            if (parseInt(price_decimals) > 0) {
                number = number.toFixed(price_decimals) + '';
                var x = number.split('.');
                var x1 = x[0],
                    x2 = x.length > 1 ? decimal + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + thousand + '$2');
                }

                n = x1 + x2
            }
            switch (currency_pos) {
                case 'left' :
                    return currency + n;
                case 'right' :
                    return n + currency;
                case 'left_space' :
                    return currency + ' ' + n;
                case 'right_space' :
                    return n + ' ' + currency;
            }
        }

        $fbtProducts.find('input[type=checkbox]').on('change', function () {
            let id = $(this).val();
            $(this).closest('label').toggleClass('uncheck');
            let currentPrice = parseFloat($(this).closest('label').data('price'));
            if ($(this).closest('label').hasClass('uncheck')) {
                $fbtProducts.find('#fbt-product-' + id).addClass('un-active');
                totalPrice -= currentPrice;

            } else {
                $fbtProducts.find('#fbt-product-' + id).removeClass('un-active');
                totalPrice += currentPrice;
            }

            let $product_ids = $fbtProducts.data('current-id');
            $fbtProducts.find('label.select-item').each(function () {
                if (!$(this).hasClass('uncheck')) {
                    $product_ids += ',' + $(this).find('input[type=checkbox]').val();
                }
            });

            $button.attr('value', $product_ids);

            priceAt.html(formatNumber(totalPrice));
        });

        // Add to cart ajax
        $fbtProducts.on('click', '.carafity_add_to_cart_button.ajax_add_to_cart', function () {
            var $singleBtn = $(this);
            $singleBtn.addClass('loading');

            var currentURL = window.location.href;

            $.ajax({
                url: carafityAjax.ajaxurl,
                dataType: 'json',
                method: 'post',
                data: {
                    action: 'carafity_woocommerce_fbt_add_to_cart',
                    product_ids: $singleBtn.attr('value')
                },
                error: function () {
                    window.location = currentURL;
                },
                success: function (response) {
                    if (typeof wc_add_to_cart_params !== 'undefined') {
                        if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
                            window.location = wc_add_to_cart_params.cart_url;
                            return;
                        }
                    }

                    $(document.body).trigger('updated_wc_div');
                    $(document.body).on('wc_fragments_refreshed', function () {
                        $singleBtn.removeClass('loading');
                    });
                    $('body').trigger('added_to_cart');

                }
            });

        });

    }
    
    function sizechart_popup() {

        $('.sizechart-button').on('click', function (e) {
            e.preventDefault();
            $('.sizechart-popup').toggleClass('active');
        });

        $('.sizechart-close,.sizechart-overlay').on('click', function (e) {
            e.preventDefault();
            $('.sizechart-popup').removeClass('active');
        });
    }

    $('.woocommerce-product-gallery').on('wc-product-gallery-after-init', function () {
        singleProductGalleryImages();
    });

    function output_accordion() {
        $('.js-card-body.active').slideDown();
        /*   Produc Accordion   */
        $('.js-btn-accordion').on('click', function () {
            if (!$(this).hasClass('active')) {
                $('.js-btn-accordion').removeClass('active');
                $('.js-card-body').removeClass('active').slideUp();
            }
            $(this).toggleClass('active');
            var card_toggle = $(this).parent().find('.js-card-body');
            card_toggle.slideToggle();
            card_toggle.toggleClass('active');

            setTimeout(function () {
                $('.product-sticky-layout').trigger('sticky_kit:recalc');
            }, 1000);
        });
    }

    function _makeStickyKit() {
        var top_sticky = 0,
            $adminBar = $('#wpadminbar');

        if ($adminBar.length > 0) {
            top_sticky += $adminBar.height();
        }

        if (window.innerWidth < 992) {
            $('.product-sticky-layout').trigger('sticky_kit:detach');
        } else {
            $('.product-sticky-layout').stick_in_parent({
                offset_top: top_sticky
            });

        }
    }

    $body.on('click', '.wc-tabs li a, ul.tabs li a', function (e) {
        e.preventDefault();
        var $tab = $(this);
        var $tabs_wrapper = $tab.closest('.wc-tabs-wrapper, .woocommerce-tabs');
        var $control = $tab.closest('li').attr('aria-controls');
        $tabs_wrapper.find('.resp-accordion').removeClass('active');
        $('.' + $control).addClass('active');

    }).on('click', 'h2.resp-accordion', function (e) {
        e.preventDefault();
        var $tab = $(this);
        var $tabs_wrapper = $tab.closest('.wc-tabs-wrapper, .woocommerce-tabs');
        var $tabs = $tabs_wrapper.find('.wc-tabs, ul.tabs');

        if ($tab.hasClass('active')) {
            return;
        }
        $tabs_wrapper.find('.resp-accordion').removeClass('active');
        $tab.addClass('active');
        $tabs.find('li').removeClass('active');
        $tabs.find($tab.data('control')).addClass('active');
        $tabs_wrapper.find('.wc-tab, .panel:not(.panel .panel)').hide(300);
        $tabs_wrapper.find($tab.attr('aria-controls')).show(300);

    });


    $(document).ready(function () {
        setupCarousel('.related');
        setupCarousel('.upsells');
        sizechart_popup();
        output_accordion();
        productTogerther();
        if ($('.product-sticky-layout').length > 0) {
            _makeStickyKit();
            $(window).resize(function () {
                setTimeout(function () {
                    _makeStickyKit();
                }, 100);
            });
        }
    });

})(jQuery);
