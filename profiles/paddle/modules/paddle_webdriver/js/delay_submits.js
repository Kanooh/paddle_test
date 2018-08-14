(function ($) {

  /**
   * Delay clicks on submit elements to allow WebDriver to keep
   * clicking around.
   */
  Drupal.behaviors.paddle_webdriver_delay_submits = {
    attach: function (context, settings) {
      $(':submit', context).once('paddle_webdriver_delay_submits', function () {
        $(this).click(function (event, delayed) {
          if (!delayed) {
            var delay = 2000;
            var clickEvent = event;
            button = this;

            setTimeout(function () {
              $(button).trigger('click', [true]);
            }, delay);

            return false;
          }
        });
      });
    }
  }

})(jQuery);
