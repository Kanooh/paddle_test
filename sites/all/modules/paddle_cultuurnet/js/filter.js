/**
 * @file
 * Javascript functionality for Cultuurnet search page filters.
 */
(function ($) {

    var helper = {
        /**
         * Autosubmit the Cultuurnet period filter form when one of the checkboxes is clicked.
         *
         * @param {object} context
         */
        setPeriodAutoSubmit: function (context) {
            var $periodFilterForm = $("#culturefeed-search-ui-block-filter-form-2", context);
            $("input[type=checkbox]", $periodFilterForm).click(function (e) {
                $periodFilterForm.submit();
            });
        }
    };

    Drupal.behaviors.paddleCultuurnetAutoSubmit = {
        attach: function (context) {
            // Set autosubmit for the Cultuurnet period filter form.
            helper.setPeriodAutoSubmit(context);
        }
    }

})(jQuery);