(function($) {

Drupal.behaviors.paddleThemerPreview = {
  attach: function (context, settings) {

    // Restore position from cookies, so position is maintained over page
    // requests.
    position = [parseInt($.cookie('paddle-themer-preview-selection-left')), parseInt($.cookie('paddle-themer-preview-selection-top'))];

    $('#paddle-themer-preview-selection:not(.paddle-themer-preview-processed)')
      .dialog({
        height: 300,
        width: 300,
        resizable: false,
        position: position,
        dragStop: function(event, ui) {
          // Store position in cookies, so it can be maintained over page
          // requests.
          $.cookie('paddle-themer-preview-selection-left', parseInt(ui.position.left), {
            path: Drupal.settings.basePath
          });
          $.cookie('paddle-themer-preview-selection-top', parseInt(ui.position.top), {
            path: Drupal.settings.basePath
          });
        },
        close: function(event, ui) {
          location.href = Drupal.settings.basePath + 'admin/themes';
        }
      })
      .show()
      .addClass('paddle-themer-preview-processed');
  }
}

})(jQuery);
