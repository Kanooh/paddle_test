(function ($) {
    Drupal.behaviors.openinghoursExceptionPeriod = {
        attach: function (context, settings) {
            $('table[id^=field-ous-exc-opening-hours-values]', context).children('tbody').find('> tr > td:nth-child(2)').once(function () {
                var startDateInput = $("input[name$='[field_ous_exc_oh_date][und][0][value][date]']", this);
                var endDateInput = $("input[name$='[field_ous_exc_oh_date][und][0][value2][date]']", this);
                var daysFieldset = $("fieldset.group-ous-exc-oh-days", this);
                // Initial filter of days.
                Drupal.behaviors.openinghoursExceptionPeriod.filterDays(startDateInput, endDateInput, daysFieldset);
                // Bind change events on the input fields.
                // Using .live because of an older jQuery version (1.4).
                startDateInput.live("change", function(){
                    Drupal.behaviors.openinghoursExceptionPeriod.filterDays(startDateInput, endDateInput, daysFieldset);
                });
                endDateInput.live("change", function(){
                    Drupal.behaviors.openinghoursExceptionPeriod.filterDays(startDateInput, endDateInput, daysFieldset);
                });
            });
        },
        filterDays: function (startDateInput, endDateInput, daysFieldset) {
            // Read and create the 2 dates.
            var startDateValues = startDateInput.val().split('/');
            var endDateValues = endDateInput.val().split('/');
            // JavaScript counts months from 0 to 11. January is 0. December is 11.
            var startDate = new Date(startDateValues[2], startDateValues[1] - 1, startDateValues[0]);
            var endDate = new Date(endDateValues[2], endDateValues[1] - 1, endDateValues[0]);

            // Calculate the number of days between the 2 dates.
            var diff = (endDate - startDate) / (1000 * 60 * 60 * 24);
            if (diff < 0 || diff > 6) {
                return;
            }

            // First hide all elements.
            // I'm using Core classes so it is clear by looking at the DOM.
            $("div.weekday", daysFieldset).addClass('element-invisible');
            startWeekday = startDate.getDay();
            for (var i = diff; i >= 0; i--) {
                weekday = startWeekday + i;
                // Only 7 days in a week.
                if (weekday >= 7)
                    weekday -= 7;
                $("div[data-weekday='" + weekday + "']", daysFieldset).removeClass('element-invisible');
            }
        }
    };
})(jQuery);
