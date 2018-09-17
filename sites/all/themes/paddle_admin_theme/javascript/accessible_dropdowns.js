/**
 * @file
 * Javascript functionality to fold/unfold panes.
 */
(function ($) {
  Drupal.behaviors.accessible = {
    attach: function (context, settings) {
      $('.accessible-dropdown-trigger').live('focus', function(e) {
        $(this).children('.accessible-dropdown-target').addClass('visible-block');
      });
      $('.accessible-dropdown-trigger').live('blur', function(e) {
        $(this).children('.accessible-dropdown-target').removeClass('visible-block');
      });
    }
  }
})(jQuery);
