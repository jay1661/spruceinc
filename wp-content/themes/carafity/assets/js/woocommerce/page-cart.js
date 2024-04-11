(function ($) {
    'use strict';

    function setupCarousel(selector) {
        if (typeof elementorFrontendConfig === 'undefined') {
            return;
        }

        var settingCarousel = {
            slidesPerView: 3,
            spaceBetween: 30,
            handleElementorBreakpoints: true,
            watchSlidesVisibility: true,
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                992: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 2,
                },
                1500: {
                    slidesPerView: 3,
                },
                1920: {
                    slidesPerView: 3,
                },
            }
        };

        var $container = $(selector);
        const swiperClass = elementorFrontend.config.swiperClass;

        $container.append(`<div class="products-carousel"><div class="${swiperClass}"></div></div>`);

        $container.find('ul.products').appendTo($container.find(`.products-carousel .${swiperClass}`));

        $container.find(`.${swiperClass}`).append('<div class="swiper-pagination"></div>');
        settingCarousel.pagination = {
            el: $container.find('.swiper-pagination').get(0),
            type: 'bullets',
            clickable: true,
        };

        if ($container.find('li.product').length > 1) {
            $container.find('ul.products').addClass('swiper-wrapper').find('>li').addClass('swiper-slide');
            var gallery_swiper = new Swiper($container.find(`.${swiperClass}`).get(0), settingCarousel)
            $container.data('swiper', gallery_swiper);
        }
    }

    $(document).ready(function () {
        setupCarousel('.cross-sells');
    });

})(jQuery);
