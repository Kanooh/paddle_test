/**
 * @file
 * Empty the image style selection whenever image selection got deleted or a
 * new image selection was made.
 */
(function ($) {
  "use strict";

  Drupal.behaviors.paddleScaldImageUpdateStyleSelect = {
    attach: function (context, settings) {
      $('div.form-type-paddle-scald-image-atom').once('paddle-scald-image-update-style-select', function() {
        // Relies on Drupal.paddle.scald.removeAtomFromFieldHandler triggering
        // the change event.
        $('input.atom-ids').bind('change', function (event, $image_picker, position) {
            $image_picker.closest('div.form-type-paddle-scald-image-atom').find('select').eq(position).val('');
        })
      });
    }
  };
})(jQuery);
