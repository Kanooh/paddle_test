/**
 * @file
 * Fly out menu js.
 */

(function ($) {
  // Handles the focus when tabbing over menu items.
  Drupal.behaviors.paddleFlyOutMenuAccessibility = {
    attach: function (context, settings) {
      $('#block-paddle-menu-display-first-level').once('has-focus', function() {
        $('.has-children a').bind('focus blur', function(event) {
          $(this).parents('li')[event.type == 'focus' ? 'addClass' : 'removeClass']('has-focus');
        });
      });
    }
  }
})(jQuery);
