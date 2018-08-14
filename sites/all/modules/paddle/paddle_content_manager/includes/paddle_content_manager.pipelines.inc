<?php

/**
 * @file
 * Content region renderer pipeline for panels_layouts objects.
 */

/**
 * Implements hook_default_panels_renderer_pipeline().
 */
function paddle_content_manager_default_panels_renderer_pipeline() {
  $pipelines = array();

  $pipeline = new stdClass();
  $pipeline->disabled = FALSE; /* Edit this to true to make a default pipeline disabled initially */
  $pipeline->api_version = 1;
  $pipeline->name = 'paddle_admin_node_view';
  $pipeline->admin_title = t('Paddle Content Manager');
  $pipeline->admin_description = t('Provides an interface that allows to rearrange the page using drag and drop.');
  $pipeline->weight = -101;
  $pipeline->settings = array(
    'renderers' => array(
      0 => array(
        'access' => array(),
        'renderer' => 'paddle_admin_node_view',
        'options' => array(),
      ),
    ),
  );
  $pipelines[$pipeline->name] = $pipeline;

  return $pipelines;
}
