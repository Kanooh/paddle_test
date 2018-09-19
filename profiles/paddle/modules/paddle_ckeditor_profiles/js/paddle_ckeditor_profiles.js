/**
 * @file
 * Javascript functionality for CKEditor.
 */

(function ($) {
  Drupal.behaviors.filtermedia = {
    attach: function (context, settings) {

      // Prevent being redirected to the content page of the assets when
      // pressing the enter button when searching in the library tab of the add
      // asset functionality in CKEditor.
      // @see https://drupal.org/node/1976400
      $('#edit-filename').keypress(function(event) {
        if (event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      });
      // Show a throbber when clicking the submit button when uploading new
      // media.
      // Chrome does not handle animated gifs which are small,
      // we need to already insert it into the DOM before clicking it.
      $('#edit-submit', context).live("mouseenter", function () {
        if ($(this).parent().attr('class') !== 'throbber-upload-wrapper') {
          $(this).wrap('<div class="throbber-upload-wrapper" />');
          $('<img src="' + Drupal.settings.paddle_ckeditor_profiles.throbber_image_path + '" width="23" height="23" alt="" />').insertBefore($(this)).hide();
        }
      });

      $('#edit-submit', context).click(function () {
        $(this).parent().find('img').show();
      });
    }
  }
})(jQuery);
