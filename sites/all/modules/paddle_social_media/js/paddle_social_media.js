(function ($) {
  Drupal.behaviors.paddleSocialMedia = {
    attach: function (context, settings) {
      $('.compat-dropdown', context).once('social-media-compat', function() {
        var $this = $(this),
            timer;

        $this.on('focusin focusout', function(event) {
          if (event.type == 'focusin') {
            clearTimeout(timer);
            $this.addClass('has-focus');
          }
          else {
            // Use a timer to remove the focus class, so tabbing between
            // links inside the list is possible.
            // @see https://github.com/joeldbirch/superfish/blob/master/src/js/superfish.js
            timer = setTimeout(function() {
              $this.removeClass('has-focus');
            }, 100);
          }
        });
      });
    }
  }
})(jQuery);
