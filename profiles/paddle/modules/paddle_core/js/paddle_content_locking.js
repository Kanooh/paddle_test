(function ($) {

/**
 * Handles content locking and unlocking.
 */
Drupal.behaviors.paddle_content_locking = {
  attach: function (context, settings) {
    // Prevent unlock message appearing when saving.
    $('.content_lock_no_leave_msg a').click(function() {
      if (Drupal.settings.content_lock.unload_js_message_enable) {
        userMovingWithinSite();
      }
    });
  }
};


  /**
   * Only shows a browser change content message on pane add forms.
   */
  Drupal.behaviors.removeBrowserMessage = {
    attach: function (context, settings) {
      $('form').each(function () {
        var obj = $(this);

        if (!$(this).hasClass('paddle-add-pane-form')) {
          var currentonSubmit = obj.attr('onSubmit');
          if (typeof currentonSubmit === 'undefined') {
            currentonSubmit = '';
          }

          obj.attr('onSubmit', currentonSubmit + ' userMovingWithinSite();');
        }
      });
    }
  };

})(jQuery);


