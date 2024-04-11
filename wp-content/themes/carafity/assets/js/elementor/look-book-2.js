(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/carafity-lookbook-2.default', ($scope) => {
            let imgHotspotsElem = $scope.find('.carafity-image-lookbook-container'),
                imgHotspotsSettings = imgHotspotsElem.data('settings'),
                triggerClick = null,
                triggerHover = null;


            if (imgHotspotsSettings['trigger'] === 'click') {
                triggerClick = true;
                triggerHover = false;
            } else if (imgHotspotsSettings['trigger'] === 'hover') {
                triggerClick = false;
                triggerHover = true;
            }
            imgHotspotsElem.find('.tooltip-wrapper').tooltipster({
                functionBefore() {
                    if (imgHotspotsSettings['hideMobiles'] && $(window).outerWidth() < 768) {
                        return false;
                    }
                },
                functionInit: function (instance, helper) {
                    var content = $(helper.origin).find('#tooltip_content').detach();
                    instance.content(content);
                },
                functionReady: function () {
                    $(".tooltipster-box").addClass("tooltipster-box-" + imgHotspotsSettings['id']);
                    $(".tooltipster-arrow").addClass("tooltipster-arrow-" + imgHotspotsSettings['id']);
                },
                contentCloning: true,
                plugins: ['sideTip'],
                animation: imgHotspotsSettings['anim'],
                animationDuration: imgHotspotsSettings['animDur'],
                delay: imgHotspotsSettings['delay'],
                trigger: "custom",
                triggerOpen: {
                    click: triggerClick,
                    tap: true,
                    mouseenter: triggerHover
                },
                triggerClose: {
                    click: triggerClick,
                    tap: true,
                    mouseleave: triggerHover
                },
                arrow: imgHotspotsSettings['arrow'],
                contentAsHTML: true,
                autoClose: false,
                minWidth: imgHotspotsSettings['minWidth'],
                maxWidth: imgHotspotsSettings['maxWidth'],
                distance: imgHotspotsSettings['distance'],
                interactive: true,
                minIntersection: 16,
                side: imgHotspotsSettings['side']
            });
        });
    });


})(jQuery);