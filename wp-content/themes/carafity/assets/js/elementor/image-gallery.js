(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        const addHandler = ($element) => {
            elementorFrontend.elementsHandler.addHandler(carafitySwiperBase, {
                $element,
            });

            let $iso = $element.find('.isotope-grid');
            if ($iso) {
                let currentIsotope = $iso.isotope({filter: '*'});
                $element.find('.elementor-galerry__filters li').on('click', function () {
                    $(this).parents('ul.elementor-galerry__filters').find('li.elementor-galerry__filter').removeClass('elementor-active');
                    $(this).addClass('elementor-active');
                    let selector = $(this).attr('data-filter');
                    currentIsotope.isotope({filter: selector});
                });
            }
        };
        elementorFrontend.hooks.addAction('frontend/element_ready/carafity-image-gallery.default', addHandler);
    });

})(jQuery);
