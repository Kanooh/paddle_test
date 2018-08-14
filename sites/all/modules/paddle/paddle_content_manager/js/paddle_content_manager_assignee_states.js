(function ($) {

/**
 * Handles assignees dropdown on backend node view.
 */
Drupal.behaviors.paddle_content_manager_assignee_states = {
  attach: function (context, settings) {
    $('li.assignee_state a[data-paddle-content-manager-assignee]').click(function () {
        var parent_id = $(this).attr('data-paddle-content-manager-parent-any-link-id');
        var uid = $(this).attr('data-paddle-content-manager-assignee');
        $('#paddle-node-assignee-uid').val(uid);
        $('#' + parent_id).click();
        return false;
    });
  }
}
})(jQuery);
