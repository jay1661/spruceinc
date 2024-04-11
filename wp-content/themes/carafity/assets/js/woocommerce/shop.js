(function ($) {
    'use strict';
    var $body = $('body');
    var xhr = false;

    function woo_dropdown_sidebar() {

        $body.on('click', '.widget.carafity-widget-woocommerce .widget-title',function (e) {
            e.preventDefault();
            var $parent = $(this).closest('.carafity-menu-filter');
            if($parent.length){

                if($(this).hasClass('toggled-on')){
                    $(this).removeClass('toggled-on')
                        .siblings('.widget-content').stop().slideUp()
                        .closest('.widget').removeClass('active');
                }else {
                    $parent.find('.toggled-on').removeClass('toggled-on');
                    $parent.find('.widget-content').stop().slideUp();
                    $parent.find('.widget').removeClass('active');

                    $(this).addClass('toggled-on')
                        .siblings('.widget-content').stop().slideDown()
                        .closest('.widget').addClass('active');
                }

            }else {

                $(this).toggleClass('toggled-on')
                    .siblings('.widget-content').stop().slideToggle()
                    .closest('.widget').toggleClass('active');
            }
        });
    }

    function woo_widget_categories() {
        var widget = $('.widget_product_categories'),
            main_ul = widget.find('ul');
        if (main_ul.length) {
            var dropdown_widget_nav = function () {

                main_ul.find('li').each(function () {

                    var main = $(this),
                        link = main.find('> a'),
                        ul = main.find('> ul.children');
                    if (ul.length) {

                        //init widget
                        if (main.hasClass('closed')) {
                            ul.hide();

                            link.before('<i class="icon-plus"></i>');
                        } else if (main.hasClass('opened')) {
                            link.before('<i class="icon-minus"></i>');
                        } else {
                            main.addClass('opened');
                            link.before('<i class="icon-minus"></i>');
                        }

                        // on click
                        main.find('i').on('click', function (e) {

                            ul.slideToggle('slow');

                            if (main.hasClass('closed')) {
                                main.removeClass('closed').addClass('opened');
                                main.find('>i').removeClass('icon-plus').addClass('icon-minus');
                            } else {
                                main.removeClass('opened').addClass('closed');
                                main.find('>i').removeClass('icon-minus').addClass('icon-plus');
                            }

                            e.stopImmediatePropagation();
                        });

                        main.on('click', function (e) {

                            if ($(e.target).filter('a').length)
                                return;

                            ul.slideToggle('slow');

                            if (main.hasClass('closed')) {
                                main.removeClass('closed').addClass('opened');
                                main.find('i').removeClass('icon-plus').addClass('icon-minus');
                            } else {
                                main.removeClass('opened').addClass('closed');
                                main.find('i').removeClass('icon-minus').addClass('icon-plus');
                            }

                            e.stopImmediatePropagation();
                        });
                    }
                });
            };
            dropdown_widget_nav();
        }
    }

    function sendRequest(url, append = false) {

        if (xhr) {
            xhr.abort();
        }

        xhr = $.ajax({
            type: "GET",
            url: url,
            beforeSend: function () {
                var $products = $('ul.carafity-products');
                if(!append) {
                    $products.addClass('preloader');
                }
            },
            success: function (data) {
                let $html = $(data);
                if(append) {
                    $('#main ul.carafity-products').append($html.find('#main ul.carafity-products > li'));
                }else {
                    $('#main ul.carafity-products').replaceWith($html.find('#main ul.carafity-products'));
                }
                $('#main .woocommerce-pagination-wrap').replaceWith($html.find('#main .woocommerce-pagination-wrap'));
                window.history.pushState(null, null, url);
                xhr = false;
                $(document).trigger('carafity-products-loaded');
            }
        });
    }

    $body.on('change', '.carafity-products-per-page #per_page', function (e) {
        e.preventDefault();
        var url = this.value;
        sendRequest(url);
    });

    $body.on('click', '.products-load-more-btn', function (e) {
        e.preventDefault();
        $(this).addClass('loading');
        var url = $(this).attr('href');
        sendRequest(url,true);
    });
    function productsPaginationScroll() {
        if (typeof $.fn.waypoint == 'function') {
            var waypoint = $('.products-load-more-btn.load-on-scroll').waypoint(function() {
                $('.products-load-more-btn.load-on-scroll').trigger('click');
            }, {offset: '100%'});
        }
    }

    $(document).ready(function () {
        woo_dropdown_sidebar();
        woo_widget_categories();
    }).on('carafity-products-loaded',function () {
        $('.products-load-more-btn').removeClass('loading');
        productsPaginationScroll();
    });

    productsPaginationScroll();

})(jQuery);
