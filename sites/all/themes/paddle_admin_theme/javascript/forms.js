/**
 * @file
 *
 * Implements a styled browse button.
 *
 */
 (function ($) {
  Drupal.behaviors.browseButton = {
    attach: function (context, settings) {
      // Check for file inputs.
      if($('input:file').length == 0) {
        return;
      }
      // Fix clickable area for IE.
      if ($.browser.msie) {
        $('.input-file-wrapper').bind('mousemove',function(e){
        var offset = $(this).offset();
        $(this).find('input:file').css({
            'top': e.pageY - offset.top - ($('.input-file-wrapper input:file').innerHeight() / 2),
            'left': e.pageX - offset.left - ($('.input-file-wrapper input:file').innerWidth() * 0.95)
          });
        $(this).find('input:file').css('opacity',0);
        })
      }

      var inputFiles = $('.input-file-wrapper input:file');
      // Sets the filename and truncate if too long
      $.each(inputFiles, function() {
        $(this).parent().find('input[type="text"]').remove();
        if ($.browser.msie  && parseInt($.browser.version, 10) === 8) {
          $('<input type="text" class="dummy-input-text" value="' + Drupal.t("Select a file ...") + '" readonly />').insertBefore($(this));
        }
        else {
        $('<input type="text" class="dummy-input-text" placeholder="' + Drupal.t("Select a file ...") + '" readonly />').insertBefore($(this));
        }
        $(this).change(function () {
          var file = $(this).val().split(/[\\/]/);
          var imageName = file[file.length - 1];
          var truncLength = 25;
          var fileNameLength = file[file.length - 1].length;

          if (fileNameLength > truncLength) {
            var truncatedName = "..." + imageName.substring((fileNameLength - truncLength));
          }
          else {
            var truncatedName = imageName;
          }
           if ($.browser.msie  && parseInt($.browser.version, 10) === 8) {
             $(this).parent().find('input.dummy-input-text').attr('value', truncatedName);
          }
          else {
            $(this).parent().find('input.dummy-input-text').attr('placeholder', truncatedName);
          }
        });
      });
    }
  }
})(jQuery);
