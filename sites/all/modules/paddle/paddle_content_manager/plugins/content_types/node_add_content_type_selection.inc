<?php

/**
 * @file
 * Plugin to provide a content type for the paddle content manager node
 * add list.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Node Add Content Type Selection'),
  'description' => t('Renders a list of content types linking to the node add form.'),
  'category' => t('Paddle Content Manager'),
  'defaults' => array(
    'excluded_node_types' => array(),
  ),
);

/**
 * Render callback.
 */
function paddle_content_manager_node_add_content_type_selection_content_type_render($subtype, $conf, $args, $context) {
  ctools_include('modal');
  ctools_modal_add_js();
  $block = new stdClass();
  $block->title = t('Pages');

  // Get all the available content types.
  $node_types = node_type_get_types();

  $filtered_node_types = array_diff_key($node_types, array_filter($conf['excluded_node_types']));

  if (!empty($filtered_node_types)) {
    $item_list = array();
    foreach ($filtered_node_types as $node_type) {
      if (node_access('create', $node_type->type)) {
        $description = theme('html_tag', array(
          'element' =>
            array(
              '#tag' => 'span',
              '#attributes' => array('class' => 'content-type-description'),
              '#value' => $node_type->description,
            ),
          )
        );
        $item_list[] = array(
          'title' => $node_type->name,
          'icon' => 'file',
          'help_text' => $description,
          'link' => array(
            'url' => 'admin/content_manager/nojs/' . $node_type->type . '/add',
            'attributes' => array('class' => array('ctools-use-modal', 'create-' . $node_type->type)),
          ),
        );
      }
    }

    $block->content = theme('paddle_content_manager_add_links', array('items' => $item_list));
  }

  return $block;
}

/**
 * Settings form builder callback.
 */
function paddle_content_manager_node_add_content_type_selection_content_type_edit_form($form, &$form_state) {
  $form['excluded_node_types'] = array(
    '#title' => t('Excluded node types'),
    '#type' => 'checkboxes',
    '#options' => node_type_get_names(),
    '#description' => t('Node types to exclude from the list.'),
    '#default_value' => $form_state['conf']['excluded_node_types'],
  );

  return $form;
}

/**
 * Submit callback of the settings form.
 */
function paddle_content_manager_node_add_content_type_selection_content_type_edit_form_submit($form, &$form_state) {
  $form_state['conf']['excluded_node_types'] = $form_state['values']['excluded_node_types'];
}

/**
 * Displays the administrative title for a panel pane in the drag & drop UI.
 */
function paddle_content_manager_node_add_content_type_selection_content_type_admin_title($subtype, $conf, $context) {
  return t('Node add type selection');
}
