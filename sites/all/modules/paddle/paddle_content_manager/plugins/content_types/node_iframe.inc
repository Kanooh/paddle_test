<?php

/**
 * @file
 * Plugin to provide a custom content type pane for the node view.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Node iframe'),
  'description' => t('Renders the node in an iframe'),
  'category' => t('Paddle Content Manager'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render callback.
 */
function paddle_content_manager_node_iframe_content_type_render($subtype, $conf, $args, $context) {
  global $base_url;

  $block = new stdClass();

  $href = 'paddle_content_manager/node_iframe/' . $context->data->nid;

  // Build properties array for rendering an iframe.
  $properties = array(
    'width' => '100%',
    'src' => $base_url . '/' . $href,
    'frameborder' => '0',
    'class' => 'node_iframe',
    'id' => 'node_iframe_' . $context->data->nid,
    'scrolling' => 'no',
    'onload' => 'resizeIframe(this)',
  );

  $block->content = paddle_content_manager_build_iframe($properties);

  return $block;
}

/**
 * Settings form builder callback.
 */
function paddle_content_manager_node_iframe_content_type_edit_form($form, &$form_state) {
  // Just return default form.
  return $form;
}

/**
 * Displays the administrative title for a panel pane in the drag & drop UI.
 */
function paddle_content_manager_node_iframe_content_type_admin_title($subtype, $conf, $context) {
  return t('Node iframe');
}
