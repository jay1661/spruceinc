(function ($) {
    "use strict";

    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/carafity-button-popup.default', ($scope) => {
            var $button = $scope.find('.carafity-button-popup a.button-popup');
            if ($('body').hasClass('elementor-editor-active')) {
                return;
            }
            if ($button.length > 0) {
                $button.magnificPopup({
                    type: 'inline',
                    removalDelay: 500,
                    closeBtnInside: true,
                    callbacks: {
                        beforeOpen: function () {
                            this.st.mainClass = this.st.el.attr('data-effect');
                        },
                        open: function () {
                            $button.addClass('active');
                        },
                        afterClose: function () {
                            $button.removeClass('active');
                        }
                    },
                    midClick: true
                });
            }
        });
    });

})(jQuery);