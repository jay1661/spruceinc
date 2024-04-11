(function ($) {
    'use strict';
    var Countdown = function ($countdown, endTime, $) {
        var self = this;
        self.timeInterval = null;

        var elements = {
            $daysSpan: $countdown.find('.countdown-days'),
            $hoursSpan: $countdown.find('.countdown-hours'),
            $minutesSpan: $countdown.find('.countdown-minutes'),
            $secondsSpan: $countdown.find('.countdown-seconds')
        };

        var updateClock = function () {
            var timeRemaining = Countdown.getTimeRemaining(endTime);

            $.each(timeRemaining.parts, function (timePart) {
                var $element = elements['$' + timePart + 'Span'],
                    partValue = this.toString();

                if (1 === partValue.length) {
                    partValue = 0 + partValue;
                }

                if ($element.length) {
                    $element.text(partValue);
                }
            });

            if (timeRemaining.total <= 0) {
                clearInterval(self.timeInterval);
            }
        };

        var initializeClock = function () {
            updateClock();

            self.timeInterval = setInterval(updateClock, 1000);
        };

        initializeClock();
    };

    Countdown.getTimeRemaining = function (endTime) {
        var timeRemaining = endTime - new Date(),
            seconds = Math.floor((timeRemaining / 1000) % 60),
            minutes = Math.floor((timeRemaining / 1000 / 60) % 60),
            hours = Math.floor((timeRemaining / (1000 * 60 * 60)) % 24),
            days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));

        if (days < 0 || hours < 0 || minutes < 0) {
            seconds = minutes = hours = days = 0;
        }

        return {
            total: timeRemaining,
            parts: {
                days: days,
                hours: hours,
                minutes: minutes,
                seconds: seconds
            }
        };
    };

    $(document).ready(function () {
        var $parent = $('.time-sale');
        var $element = $('.carafity-countdown', $parent);
        if ($element.length) {
            let data_date = $element.data('date');
            if ($element.hasClass('simple')) {
                var date = new Date(data_date * 1000);
                new Countdown($element, date, $);
            } else {
                var countdown = false;
                $('form.variations_form').on('found_variation.wc-variation-form', function (e, v) {
                    var time = data_date[v.variation_id];
                    if (time) {
                        var date = new Date(time * 1000);
                        if (!countdown) {
                            countdown = new Countdown($element, date, $);
                        } else {
                            clearInterval(countdown.timeInterval);
                            countdown = new Countdown($element, date, $);
                        }
                        $parent.show();
                    } else {
                        $parent.hide();
                    }
                });
            }
        }
    });

})(jQuery);
