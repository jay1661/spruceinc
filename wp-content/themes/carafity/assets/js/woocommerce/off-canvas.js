(function ($) {
    'use strict';

    $(function () {
        var $body = $('body');
        var $dropdownWrapper = $('.carafity-dropdown-filter');
        $body.on('click', '.filter-toggle', function (e) {
            e.preventDefault();
            if ($body.hasClass('shop_filter_dropdown') && $(window).width() > 1024) {
                $dropdownWrapper.toggleClass('active-dropdown').slideToggle();
            } else {
                $('html').toggleClass('off-canvas-active');
            }
        });

        $body.on('click', '.filter-close, .carafity-overlay-filter', function (e) {
            e.preventDefault();
            $('html').removeClass('off-canvas-active');
        });

        function clone_sidebar() {
            var $canvas = $('.carafity-canvas-filter-wrap');
            if (!$body.hasClass('shop_filter_canvas')) {
                if ($(window).width() < 1025) {
                    $('#secondary').children().appendTo(".carafity-canvas-filter-wrap");
                    $('.carafity-dropdown-filter-wrap').children().appendTo(".carafity-canvas-filter-wrap");
                    $dropdownWrapper.removeClass('active-dropdown').slideUp();
                } else {
                    $canvas.children().appendTo("#secondary");

                    $canvas.children().appendTo(".carafity-dropdown-filter-wrap");
                }
            }
        }
        // menu filter width min max
        function wooMenuFilter() {
            let $widget_filter = $('.carafity-menu-filter-wrap .widget'),
                count = $widget_filter.length,
                $parrent_filter = $('.carafity-sorting'),
                parrent_width = $parrent_filter.outerWidth(),
                child_width = 0;

            if ($widget_filter.length > 0) {
                $widget_filter.each((index, element) => {
                    child_width += $(element).outerWidth() + 30;
                    if (!--count) addClassActive(parrent_width,child_width,$parrent_filter);
                });

            }
            function addClassActive(parrent_width,child_width,$parrent_filter) {
                if (child_width > ( parrent_width - 300)) {
                    $parrent_filter.addClass('active-filter-toggle');
                } else {
                    $parrent_filter.removeClass('active-filter-toggle');
                }
            }
        }

        $(document).ready(function () {
            clone_sidebar();
            wooMenuFilter();
        });

        $(window).on('resize', function () {
            wooMenuFilter();
            clone_sidebar();
        });
    });


})(jQuery);
