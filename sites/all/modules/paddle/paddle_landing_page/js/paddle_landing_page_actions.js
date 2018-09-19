(function ($) {

  Drupal.behaviors.paddle_landing_page_actions = {
    attach: function (context, settings) {
      // Go to preview after save if 'Save and preview' was clicked.
      $('#paddle_save_and_preview').click(function(){
        Drupal.settings.paddle_panels_renderer_redirect_after_successful_ajax_call = true;
      });
    }
  }

  // After the publication state of the node was toggled, change the text of the
  // toggle button to either 'Publish' or 'Offline', and reopen the 'customize
  // page' display.
  // @todo Check if this is still needed. This probably can be removed since we
  //   have full moderation on landing pages now.
  Drupal.ajax.prototype.commands.toggleStatus = function(ajax, data, status) {
    // Toggle the status button.
    $("a[data-paddle-contextual-toolbar-click='panels-ipe-toggle-status'] span").text(data.key);
    // Reopen the customize page form.
    $('#panels-ipe-customize-page').click();
  };

})(jQuery);
