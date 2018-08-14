<?php
/**
 * @file
 * paddle_pane_collection.pipelines.inc
 */

/**
 * Implements hook_default_panels_renderer_pipeline().
 */
function paddle_pane_collection_default_panels_renderer_pipeline() {
  $export = array();

  $pipeline = new stdClass();
  $pipeline->disabled = FALSE; /* Edit this to true to make a default pipeline disabled initially */
  $pipeline->api_version = 1;
  $pipeline->name = 'paddle_pane_collection';
  $pipeline->admin_title = 'Paddle Pane Collection';
  $pipeline->admin_description = 'Provides an interface that allows to rearrange the page using drag and drop, and redirects to the admin overview page after saving.';
  $pipeline->weight = 0;
  $pipeline->settings = array(
    'renderers' => array(
      0 => array(
        'access' => array(
          'plugins' => array(
            0 => array(
              'name' => 'perm',
              'settings' => array(
                'perm' => 'use panels in place editing',
              ),
              'context' => 'logged-in-user',
            ),
          ),
          'logic' => 'and',
        ),
        'renderer' => 'paddle_pane_collection',
        'options' => array(),
      ),
    ),
  );
  $export['paddle_pane_collection'] = $pipeline;

  return $export;
}
