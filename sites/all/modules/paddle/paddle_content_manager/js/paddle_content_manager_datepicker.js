/**
 * Adds month & year select boxes to each BEF datepicker.
 */
(function($) {
    $(window).load(function () {
        $('.bef-datepicker').datepicker('option', 'changeYear', true);
        $('.bef-datepicker').datepicker('option', 'changeMonth', true);
    });
})(jQuery);
