/**
 * @file
 * JS behaviour for the Paddle Color Palettes plugin for Paddle Themer form.
 */

(function ($) {
  /**
   * Javascript functionality to transform the color-palette-color attribute to
   * background-color style.
   */
  Drupal.behaviors.paddle_color_palettes_background_colors = {
    attach: function () {
      $('div.paddle-color-palettes-color').once('color-palette', function() {
        $(this).each(function () {
          $(this).css('background-color', $(this).attr('color-palette-color'));
        });
      });
    }
  };

  /**
   * Javascript functionality to transform the color box into a color picker.
   */
  Drupal.behaviors.paddle_color_palettes_color_pickers = {
    attach: function () {
      $("div.palette-with-color-picker div.paddle-color-palettes-color").once('color-pickers', function() {
        $(this).each(function () {
          var $box = $(this);

          $box.ColorPicker({
            color: $box.attr('color-palette-color'),
            onChange: function (hsb, hex) {
              $box.css("backgroundColor", "#" + hex);
              $("#" + $box.attr('color-palette-color-palette-name') + "-color-picker-value-" + $box.attr('color-palette-color-index')).val('#' + hex);
            }
          });
        });
      });
    }
  };
})(jQuery);
