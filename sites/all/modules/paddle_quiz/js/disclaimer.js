/**
 * @file
 * Javascript functionality to show/hide a quiz's disclaimer.
 */
(function ($) {
  Drupal.behaviors.quizDisclaimer = {
    attach: function (context, settings) {

      // Add a click handler to the show link.
      $(".paddle-quiz-disclaimer-link").once(function () {

        $(this).click(function(event) {
          event.preventDefault();

          var disclaimer = Drupal.behaviors.quizDisclaimer.getDisclaimer(event.target);
          Drupal.behaviors.quizDisclaimer.showDisclaimer(disclaimer);
        });

      });

      // Add a click handler to the close button and link.
      $(".paddle-quiz-disclaimer-close").once(function () {

        $(this).click(function(event) {
          event.preventDefault();

          var disclaimer = Drupal.behaviors.quizDisclaimer.getDisclaimer(event.target);
          Drupal.behaviors.quizDisclaimer.hideDisclaimer(disclaimer);
        });

      });
    },

    getDisclaimer: function(quizChildElement) {
      var screen = $(quizChildElement).parents('.paddle-quiz-screen');
      return screen.find('.paddle-quiz-disclaimer');
    },

    showDisclaimer: function(disclaimer) {
      disclaimer.css('opacity', 0);
      disclaimer.removeClass('paddle-quiz-disclaimer-hidden');
      disclaimer.animate({opacity: 1}, 300, function() {
        // Don't use data() as we need to set an actual data-attribute for the
        // Selenium tests and using data() only stores the data somewhere in
        // Javascript.
        disclaimer.attr('data-visible', 1);
      });
    },

    hideDisclaimer: function(disclaimer) {
      disclaimer.animate({opacity: 0}, 300, function() {
        // Don't use data() as we need to set an actual data-attribute for the
        // Selenium tests and using data() only stores the data somewhere in
        // Javascript.
        disclaimer.attr('data-visible', 0);
        disclaimer.addClass('paddle-quiz-disclaimer-hidden');
      });
    }
  }
})(jQuery);
