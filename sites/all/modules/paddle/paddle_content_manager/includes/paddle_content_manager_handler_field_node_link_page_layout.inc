<?php

/**
 * @file
 * Definition of paddle_content_manager_handler_field_node_link_page_layout.
 */

/**
 * Field handler to render the 'page layout' action link of nodes.
 *
 * @ingroup views_field_handlers
 */
class paddle_content_manager_handler_field_node_link_page_layout extends views_handler_field_node_link {

  /**
   * Renders the link.
   */
  public function render_link($node, $values) {
    if (!node_access('update', $node)) {
      return;
    }

    $this->options['alter']['make_link'] = TRUE;

    if ($node->type == 'landing_page') {
      $this->options['alter']['path'] = 'admin/content_manager/node/' . $node->nid . '/panels_content?destination=admin/content_manager/list/search';
    }
    else {
      $this->options['alter']['path'] = 'admin/content_manager/node/' . $node->nid . '/layout?destination=admin/content_manager/list/search';
    }

    return !empty($this->options['text']) ? $this->options['text'] : t('page layout');
  }

}
