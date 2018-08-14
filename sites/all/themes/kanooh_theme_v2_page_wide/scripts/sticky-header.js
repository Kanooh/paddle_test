/**
 * @file
 * Contains the sticky navigation behaviors.
 */
(function ($) {
  /**
   * Initializes the elements.
   */
  Drupal.behaviors.stickyHeader = {
    attach: function (context, settings) {
      window.addEventListener('scroll', function (e) {
        if ($(window).width() > 889) {
          var distanceY = window.pageYOffset || document.documentElement.scrollTop,
            header = document.querySelector("header");
          if (distanceY > 127) {
            $('header, body').addClass('smaller-header');
            // Also add it to the body so that we can hide the vo-global-header on scroll.
          }
          else {
            if ($('header').hasClass('smaller-header')) {
              $('header, body').removeClass('smaller-header');
            }
          }
        }
      });
    }
  };
})(jQuery);
