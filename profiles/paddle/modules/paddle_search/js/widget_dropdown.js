/**
 * @file
 * Behaviour file for the dropdown facetapi widget plugin.
 */

(function ($) {
  Drupal.behaviors.paddleSearchWidgetDropdown = {
    attach: function (context, settings) {
      $('select.facetapi-widget-dropdown', context).once('widget-dropdown', function () {
        $(this).change(function () {
          window.location = $(this).val();
        })
      });
    }
  }
})(jQuery);
