<?php

/**
 * @file
 * Plugin to provide a content type for the paddle content manager node summary
 * metadata.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Node Summary Metadata'),
  'description' => t('Renders a summary of the node metadata.'),
  'category' => t('Paddle Content Manager'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'defaults' => array(
    'extended' => FALSE,
    'collapsible' => FALSE,
    'horizontal' => FALSE,
  ),
);

/**
 * Render callback.
 */
function paddle_content_manager_node_summary_metadata_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = t('Overview');
  $block->content = '';

  // Get all necessary metadata. Include extended metadata if configured to
  // show it. This has nothing to do with the collapsing, it's just "extra"
  // metadata that may not need to be showed everywhere.
  $extended = isset($conf['extended']) ? $conf['extended'] : FALSE;
  $metadata = paddle_content_manager_node_metadata($context->data, $extended);

  // Use a different theme based on the "collapsible" settings. If the node
  // metadata is collapsible only the first group will be visible by default,
  // and expanding the summary will show the other metadata (including but not
  // limited to the "extended" metadata).
  $collapsible = isset($conf['collapsible']) ? $conf['collapsible'] : FALSE;
  $variables = array('metadata' => $metadata);
  if ($collapsible) {
    $output = theme('paddle_content_manager_node_summary_metadata_container_collapsible', $variables);
  }
  else {
    $output = theme('paddle_content_manager_node_summary_metadata_container', $variables);
  }

  if (!empty($output)) {
    $horizontal = isset($conf['horizontal']) ? $conf['horizontal'] : FALSE;

    $classes = array();
    $classes[] = $collapsible ? 'collapsible' : 'non-collapsible';
    $classes[] = $horizontal ? 'horizontal' : 'vertical';

    $attributes = array(
      'id' => 'node-metadata',
      'class' => implode($classes, " "),
    );
    $output = '<div' . drupal_attributes($attributes) . '>' . $output . '</div>';
  }

  $block->content = $output;

  return $block;
}

/**
 * Settings form builder callback.
 */
function paddle_content_manager_node_summary_metadata_content_type_edit_form($form, &$form_state) {
  return $form;
}

/**
 * Displays the administrative title for a panel pane in the drag & drop UI.
 */
function paddle_content_manager_node_summary_metadata_content_type_admin_title($subtype, $conf, $context) {
  return t('Node Summary Metadata');
}
