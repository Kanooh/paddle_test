(function ($) {

/**
 * Click delegator for the paddle contextual toolbar.
 */
Drupal.behaviors.paddle_contextual_toolbar_click_delegator = {
  attach: function (context, settings) {
    $('a[data-paddle-contextual-toolbar-click]').once(function () {
      $(this).click(function () {
        // Disable all contextual toolbar buttons, unless the clicked button
        // doesn't want us to.
        if ($(this).attr('data-repeatable-clicks') != true) {
          var selectors = $(this).parents('#contextual-actions-list').find('a');
          // Prevent multiple clicks from triggering the action again.
          selectors.each(function(index) {
            $(this).unbind();
            $(this).removeAttr('href');
          });
        }

        // Trigger the action.
        var id = $(this).data('paddle-contextual-toolbar-click');
        $('#' + id).click();
        return false;
      });
    });
  }
}

})(jQuery);
