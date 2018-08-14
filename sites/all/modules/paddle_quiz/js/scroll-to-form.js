/**
 * @file
 * Javascript functionality to scroll to the top of the page when a button in a
 * quiz is clicked.
 */
(function ($) {
  Drupal.behaviors.quizScrollToForm = {
    /**
     * Called whenever the page is loaded and when ajax content is inserted.
     */
    attach: function (context, settings) {
      var id;
      try {
        id = $(context).attr('id');
      } catch (e) {
        // Context is an object without attributes, eg. the whole HTML document.
        id = false;
      }

      // Check that the context is the quiz form, which means the quiz form was
      // reloaded.
      if (id === 'paddle-quiz-form-container') {
        // Get the form's pane parent div. It has some padding so we should
        // scroll to the pane instead of the form, for the whole pane to be
        // visible.
        var pane = context.parents('.pane-paddle-quiz');

        // Scroll the quiz pane into view.
        scrollIntoView(pane);
      }
    }
  };

  Drupal.behaviors.quizScrollDisclaimer = {
    attach: function ($context) {
      $('.paddle-quiz-disclaimer-link').click(function() {
        // Scroll the disclaimer into view.
        scrollIntoView($('#edit-disclaimer').parent());
      });
    }
  };

  /**
   * Scrolls a certain element into view, only if the top position of the
   * element is not within the display port of the window.
   */
  function scrollIntoView(element) {
    // Get the element's position.
    element = $(element);
    var offset = element.offset();

    // Take the body's top margin/padding into account. This is usually used
    // to move everything down a bit when there's a fixed element, like the
    // preview bar.
    var body = $('body');
    var marginTop = parseInt(body.css('marginTop'));
    var paddingTop = parseInt(body.css('paddingTop'));
    var spaceTop = marginTop + paddingTop;

    // Check that the element's y position is lower than the y position of the
    // scroll view.
    if (offset && (offset.top - spaceTop) < $(window).scrollTop()) {
      // If the top position is not in view, scroll to it.
      $('html, body').animate({scrollTop: offset.top - spaceTop + 'px'}, 300);
    }
  }
})(jQuery);
