(function ($) {

  Drupal.behaviors.paddle_content_region_actions = {
    attach: function (context, settings) {
      $("ul#contextual-actions-list li.save a").click(function(){
        Drupal.settings.paddle_panels_renderer_redirect_after_successful_ajax_call = true;
      });
    }
  }

})(jQuery);
