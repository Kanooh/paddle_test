(function ($) {

  /**
   * Click panels-ipe-customize-page button automatically and hide it.
   */
  Drupal.behaviors.paddlePanelsRendererActions = {
    attach: function (context, settings) {
      // Get to the customise view.
      if ($('#panels-old-actions').length == 0) {
        // Invoke click of 'customise this page' link.
        $('#panels-ipe-customize-page').click();
        // Create a wrapper for panels control actions.
        // This allows us to always hide the controls container
        // but preserve the action buttons for re-use.
        $('#panels-ipe-control-container').wrap('<div id="panels-old-actions"></div>');
        $('#panels-old-actions').hide();
      }
    }
  }

  /**
   * Override Drupal.ajax.prototype.success so after a successful ajax we can
   * redirect to another url.
   */
  Drupal.ajax.prototype.paddlePanelsRendererSuccess = Drupal.ajax.prototype.success;
  Drupal.ajax.prototype.success = function (response, status) {
    this.paddlePanelsRendererSuccess(response, status);
    if (Drupal.settings.paddle_panels_renderer_redirect_after_successful_ajax_call) {
      window.location.href = Drupal.settings.paddle_panels_renderer_redirect_after_successful_ajax_call_url;
    }
  }

})(jQuery);
