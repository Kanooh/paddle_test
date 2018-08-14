(function ($) {

  Drupal.behaviors.paddleAppsOverviewTooltips = {
    attach: function (context, settings) {
      $('.paddle-apps-paddlet-status', context).once('ptt-processed', function () {
        $('span.disabled', this).tooltip({
          show: false,
          hide: false,
          position: 'top',
          offset: 0,
          tooltipClass: 'paddle-tooltip-warning',
          content: function() {
            return $(this).data('title');
          }
        });
      });
    }
  };

})(jQuery);