<?php

/**
 * @file
 * Definition of paddle_content_manager_handler_field_restore_node_link.
 */

/**
 * Field handler to render a link to the restore node confirmation page.
 */
class paddle_content_manager_handler_field_restore_node_link extends views_handler_field_node_link_edit {
  /**
   * {@inheritdoc}
   */
  public function render_link($node, $values) {
    // Ensure user has permission to restore this node.
    if (!paddle_content_manager_restore_node_access($node)) {
      return;
    }

    $this->options['alter']['make_link'] = TRUE;
    $this->options['alter']['path'] = "admin/content_manager/node/nojs/{$node->nid}/restore";

    if (!empty($this->options['destination'])) {
      $this->options['alter']['query'] = drupal_get_destination();
    }

    if (!empty($this->options['modal'])) {
      ctools_include('modal');
      ctools_modal_add_js();
      $this->options['alter']['link_class'] .= ' ctools-use-modal';
    }

    $text = !empty($this->options['text']) ? $this->options['text'] : t('Restore');
    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function option_definition() {
    $options = parent::option_definition();
    $options['modal'] = array('default' => FALSE, 'bool' => TRUE);

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);

    $form['modal'] = array(
      '#type' => 'checkbox',
      '#title' => t('Use modal'),
      '#description' => t('If checked, the link will be opened in a modal.'),
      '#default_value' => $this->options['modal'],
    );
  }

}
