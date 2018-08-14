/**
 * @file
 * Add Colorbox functionality for images.
 */
(function ($) {
  Drupal.behaviors.paddleScaldImageColorbox = {
    attach: function (context, settings) {
      $('a.colorbox-link').colorbox({
        'transition': 'none',
        'maxWidth': '80%',
        'maxHeight': '80%'
      });

      // When needed, render the arrow keys.
      $('a.colorbox-link-group').colorbox({
        'arrowKey': 'true',
        'transition': 'none',
        'width': '75%',
        'height': '75%',
        'scrolling':'false',
        'rel':"colorbox-link-group",
      });
    }
  };
})(jQuery);
