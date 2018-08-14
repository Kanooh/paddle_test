(function ($) {

/**
 * Handles warning message when leaving form.
 */
Drupal.behaviors.paddle_content_cancelWarning = {
  attach: function (context, settings) {
    // Show a warning before leaving form page.
    $('.cancel-js').once('cancelTrigger', function() {
      $('.cancel-js').click(function() {
        var answer = confirm('If you proceed, ALL of your changes will be lost.');

        if (!answer) {
          return false;
        }
      });
    });
  }
};

})(jQuery);
