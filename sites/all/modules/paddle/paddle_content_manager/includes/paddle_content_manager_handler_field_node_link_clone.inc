<?php
/**
 * @file
 * Views field handler for Node Clone link.
 */

/**
 * Field handler to present a clone node link.
 */
class paddle_content_manager_handler_field_node_link_clone extends views_handler_field_node_link_clone {

  /**
   * Renders the link.
   */
  function render_link($node, $values) {

    if (!clone_access_cloning($node)) {
      return;
    }

    ctools_include('modal');
    ctools_modal_add_js();

    $this->options['alter']['make_link'] = TRUE;
    $this->options['alter']['path'] = "admin/content_manager/nojs/node/{$node->nid}/clone/" . clone_get_token($node->nid);

    $text = !empty($this->options['text']) ? $this->options['text'] : t('clone');
    return $text;
  }
}
