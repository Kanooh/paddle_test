/**
 * @file
 * Contains a fallback to the details HTML5 element.
 */
(function ($) {
  /**
   * Initializes the elements.
   */
  Drupal.behaviors.initializeDetailsFallback = {
    attach: function (context, settings) {
      // Add conditional classname based on HTML5 details support.
      $('html').addClass($.fn.details.support ? 'details' : 'no-details');
      // Emulate <details> where necessary and enable open/close event handlers.
      $('details').details();
    }
  };
})(jQuery);
