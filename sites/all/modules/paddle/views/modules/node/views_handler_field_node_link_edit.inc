<?php

/**
 * @file
 * Definition of views_handler_field_node_link_edit.
 */

/**
 * Field handler to present a link node edit.
 *
 * @ingroup views_field_handlers
 */
class views_handler_field_node_link_edit extends views_handler_field_node_link {

  /**
   * Renders the link.
   */
  function render_link($node, $values) {
    // Ensure user has access to edit this node.
    if (!node_access('update', $node)) {
      return;
    }

    $this->options['alter']['make_link'] = TRUE;
    $this->options['alter']['path'] = "node/$node->nid/edit";
    if (!empty($this->options['destination']) || !isset($this->options['destination'])) {
      $this->options['alter']['query'] = drupal_get_destination();
    }

    $text = !empty($this->options['text']) ? $this->options['text'] : t('edit');
    return $text;
  }

  function option_definition() {
    $options = parent::option_definition();
    $options['destination'] = array('default' => TRUE, 'bool' => TRUE);

    return $options;
  }

  function options_form(&$form, &$form_state) {
    $options = parent::options_form($form, $form_state);
    $form['destination'] = array(
      '#title' => t('Destination'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['destination'],
      '#description' => t('If checked the link will have destination=$current_url.'),
    );
  }
}
