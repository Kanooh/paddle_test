/**
 * @file
 *
 * Implements tooltip for Paddle Style form elements with hidden labels.
 *
 */
 (function ($) {
  Drupal.behaviors.paddleStylePluginTooltips = {
    attach: function (context, settings) {
    $(".paddle-style-plugin label, .form-type-jquery-colorpicker").tooltip({
      show: false,
      hide: false,
      // If content returns nothing,  no tooltip will show.
      content: function () {
        // Jquery color picker.
        if($(this).hasClass('form-type-jquery-colorpicker')) {
          return $(this).find("label").text();
        }
        // Patterns and Textures.
        else if ($(this).attr('for').match(/background-pattern/) && $(this).attr('for').match(/background-pattern/) !== null && !$(this).attr('for').match(/background-pattern-upload-image/)) {
          var patternclass = $(this).attr('for');
          var labeltext = $(this).find("span.label-hidden").text();
          return '<div class="labeltext">' + labeltext + '</div><div class="' + patternclass + '"></div>';
        }
        // Other.
        else {
          return $(this).find("span.label-hidden").text();
        }
      },
      position: "bottom",
      offset: 3
    });
    }
  }
})(jQuery);
