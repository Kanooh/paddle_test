/*
 * @file
 * JavaScript fixing keyboard select of autocomplete option in some browsers.
 *
 */

(function($) {

  Drupal.behaviors.autocompleteKeyboardSelect = {
    attach: function (context, settings) {
    /**
     * Handler for the "keydown" event.
     */
    Drupal.jsAC.prototype.onkeydown = function (input, e) {
      // Do what the overriden fn does.
      if (!e) {
        e = window.event;
      }
      switch (e.keyCode) {
        case 40: // down arrow.
          this.selectDown();
          return false;
        case 38: // up arrow.
          this.selectUp();
          return false;
        case 13: // Enter.
          // Then our stuff.
          if (this.selected == false) {
            // If no selection is there, use the default enter behaviour.
            return true;
          }

          this.input.value = $(this.selected).text();
          if(navigator.userAgent.toLowerCase().indexOf('firefox') == -1) {
            $(this.input).trigger('change');
          }
          return false;
        default: // All other keys.
          return true;
      }
    };
    }
  };

})(jQuery);
