/**
 * @file
 * Javascript functionality to show a quiz full screen.
 */
(function ($) {
  Drupal.behaviors.quizFullScreen = {
    /**
     * Called whenever the page is loaded and when ajax content is inserted.
     */
    attach: function (context, settings) {
      // Add a click handler to the "show full screen" link.
      $(".paddle-quiz-full-screen-open").once(function () {
        $(this).click(Drupal.behaviors.quizFullScreen.openLinkHandler);
      });

      // Add a click handler to the "close full screen" link.
      $(".paddle-quiz-full-screen-close").once(function () {
        $(this).click(Drupal.behaviors.quizFullScreen.closeLinkHandler);
      });

      // Restrict the focus to any full screen quiz. This needs to be called
      // whenever a new screen is displayed while in full screen mode.
      if ($('body').hasClass("paddle-quiz-full-screen")) {
        Drupal.behaviors.quizFullScreen.restrictFocusToFullScreenQuiz();
      }
    },

    /**
     * Restricts the focus to a full screen quiz.
     */
    restrictFocusToFullScreenQuiz: function() {
      // Focus on the first visible input field.
      var inputs = this.getInputFields();
      if (inputs.length > 0) {
        inputs[0].focus();
      }

      // Add a key press event to the body so we can change the behavior of the
      // tab key.
      $('body').keydown(Drupal.behaviors.quizFullScreen.tabKeyHandler);
    },

    /**
     * Returns the visible input fields inside the full screen quiz.
     */
    getInputFields: function() {
      var pane = $('.pane-paddle-quiz.paddle-quiz-full-screen');
      return pane.find(':input:visible');
    },

    /**
     * Handles the events triggered by pressing the tab key when in full screen
     * mode.
     */
    tabKeyHandler: function(event) {
      // Check that the tab key was pressed.
      if (event.keyCode == 9) {
        // Get the visible input fields inside the full screen quiz pane.
        var inputs = Drupal.behaviors.quizFullScreen.getInputFields();
        if (inputs.length > 0) {
          var first = inputs[0];
          var last = inputs[inputs.length - 1];

          // If the target element was the last input field and we weren't
          // holding the shift key, focus on the first input field again.
          if (event.target == last && !event.shiftKey) {
            event.preventDefault();
            $(first).focus();
          }
          // Otherwise if the target element was the first input field and we
          // actually were holding the shift key, focus on the last input
          // field.
          if (event.target == first && event.shiftKey) {
            event.preventDefault();
            $(last).focus();
          }
        }
      }
    },

    /**
     * Handles the events triggered by clicking a link that should open full
     * screen mode.
     */
    openLinkHandler: function(event) {
      // Prevent the event from bubbling up.
      event.preventDefault();

      // Open full screen mode for a specific quiz pane.
      var pane = $(event.target).parents('.pane-paddle-quiz');
      Drupal.behaviors.quizFullScreen.open(pane);

      // Restrict focus to the full screen quiz.
      Drupal.behaviors.quizFullScreen.restrictFocusToFullScreenQuiz();
    },

    /**
     * Handles the events triggered by clicking a link that should close full
     * screen mode.
     */
    closeLinkHandler: function(event) {
      // Prevent the event from bubbling up.
      event.preventDefault();

      // Close the quiz in full screen mode.
      Drupal.behaviors.quizFullScreen.close();
    },

    /**
     * Handles the events triggered by pressing a key while in full screen mode.
     */
    escapeKeyHandler: function(event) {
      // Check that the escape key was pressed.
      if (event.keyCode == 27) {
        // Prevent the event from bubbling up.
        event.preventDefault();

        // Close the quiz in full screen mode.
        Drupal.behaviors.quizFullScreen.close();
      }
    },

    /**
     * Opens full screen mode for a specific quiz pane.
     */
    open: function(pane) {
      // Add a full screen mode class to the pane.
      $(pane).addClass('paddle-quiz-full-screen');

      // Add a full screen mode class to the body so we can disable scrolling.
      var body = $('body');
      body.addClass('paddle-quiz-full-screen');

      // Now that the quiz is full screen add a key up event so we can close
      // full screen mode by pressing escape.
      body.keyup(Drupal.behaviors.quizFullScreen.escapeKeyHandler);
    },

    /**
     * Closes full screen mode for any quiz in full screen mode.
     */
    close: function() {
      // Remove the full screen mode class from any quiz panes that have it.
      var panes = $('.pane-paddle-quiz.paddle-quiz-full-screen');
      panes.removeClass('paddle-quiz-full-screen');

      // Remove the full screen mode class from the body so scrolling works
      // again.
      var body = $('body');
      body.removeClass('paddle-quiz-full-screen');

      // If the quiz is no longer in full screen mode the escape key should no
      // longer be bound to a key up event.
      body.unbind("keyup", Drupal.behaviors.quizFullScreen.escapeKeyHandler);

      // Remove the focus restriction on the quiz panes.
      body.unbind("keydown", Drupal.behaviors.quizFullScreen.tabKeyHandler);
    }
  }
})(jQuery);
