/**
 * @file
 * Javascript functionality for Cultuurnet search page form.
 */
(function ($) {

    var helper = {
        initEndDateToggle: function (context) {
            var $checkbox = $(".form-item-show-end-date", context);
            var $endDateInput = $(".form-item-end-date-wrapper-end-date-date input", context);
            $checkbox.change(function () {
                $(".end-date-wrapper", context).toggleClass('element-invisible');
                $endDateInput.val("");
            });
        }
    };

    Drupal.behaviors.paddleCultuurnet = {
        attach: function (context) {
            helper.initEndDateToggle(context);
        }
    }

})(jQuery);
