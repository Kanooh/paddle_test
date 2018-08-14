(function ($) {
  Drupal.behaviors.paddleGlossary = {
    attach: function (context, settings) {
      $('[data-toggle="tooltip"]', context).tooltip({
        html: true,
        placement: 'auto',
        trigger: 'hover click',
        viewport: function ($element) {
          return $element.closest('.pane-content, .block, body');
        }
      });
    }
  }
})(jQuery);
