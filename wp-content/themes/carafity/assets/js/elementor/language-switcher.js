(function ($) {
    "use strict";

    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/carafity-language-switcher.default', ( $scope ) => {
            let $button = $('.menu > .item',$scope);
            if ($scope.hasClass('language-switcher-action-click')) {
                $button.on('click',function (e) {
                    e.preventDefault();
                    $button.toggleClass('active');
                })
            }
        } );
    });

})(jQuery);