<?php

/**
 * @file
 * Definition of paddle_content_manager_handler_field_node_link_admin_view.
 */

/**
 * Field handler to render the 'admin view' action link of nodes.
 *
 * @ingroup views_field_handlers
 */
class paddle_content_manager_handler_field_node_link_admin_view extends views_handler_field_node_link {

  /**
   * Renders the link.
   */
  public function render_link($node, $values) {
    // Ensure user can view the node and has access to the administrative view.
    // It might seem contradictory to match 'view' permissions with the 'edit'
    // action link, but in reality this does not lead to the edit page but to
    // the administrative node view where the node can be viewed but not edited.
    // This page is the primary gateway to the actual editing of the node, hence
    // the icon.
    if (!user_access('manage paddle_content_manager') || !node_access('view', $node)) {
      return;
    }

    $this->options['alter']['make_link'] = TRUE;

    $this->options['alter']['path'] = 'admin/content_manager/node/' . $node->nid . '/view';

    return !empty($this->options['text']) ? $this->options['text'] : t('admin view');
  }

}
