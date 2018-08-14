/**
 * @file
 * Javascript functionality to fold/unfold panes.
 */
(function ($) {
  Drupal.behaviors.foldable = {
    attach: function (context, settings) {
      // Add a span to be used as a hook for the icon only once.
      // @see http://archive.plugins.jquery.com/project/once
      $(".foldable h2.pane-title").once(function () {
        $("<span>").addClass( "foldable-ui-icon").prependTo($(this));
      });
      // Unfold the pane/tab that has an error in it.
      if ($("input.error").length > 0) {
        $("input.error").each(function () {
          $(this).parents('.foldable').removeClass('folded').end().parents('.pane-content').addClass('pane-visible');
        });
      }

      // Hide the content of all folded panes.
      $('div.foldable.folded').children('div.pane-content').addClass('pane-hidden').attr('aria-hidden', true);

      // Fold/unfold panes when clicked.
      $('.foldable h2.pane-title').click(function () {
        var content_panes = $(this).siblings('div.pane-content');
        var content_pane = content_panes[0];
        if ($(this).parent().hasClass('folded')) {
          $(content_pane).slideDown({
            duration: 'fast',
            easing: 'linear',
            complete: function () {
              $(content_pane).removeClass('pane-hidden').addClass('pane-visible');
              $(content_pane).attr('aria-hidden', false);
              $(this).parent().removeClass('folded');
            }
          });
        }
        else {
          $(content_pane).slideUp({
            duration: 'fast',
            complete: function () {
              $(content_pane).removeClass('pane-visible').addClass('pane-hidden');
              $(content_pane).attr('aria-hidden', true);
              $(this).parent().addClass('folded');
            }
          });
        }
      });
    }
  }
})(jQuery);
