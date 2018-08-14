<?php

/**
 * @file
 * Paddle Panels Renderer pipeline for panels_layouts objects.
 */

/**
 * Implements hook_default_panels_renderer_pipeline().
 */
function paddle_panels_renderer_default_panels_renderer_pipeline() {
  $pipelines = array();

  $pipeline = new stdClass();
  $pipeline->disabled = FALSE; /* Edit this to true to make a default pipeline disabled initially */
  $pipeline->api_version = 1;
  $pipeline->name = 'paddle_panels_renderer';
  $pipeline->admin_title = t('Paddle IPE');
  $pipeline->admin_description = t('Provides an interface that allows to rearrange the page using drag and drop.');
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
        'renderer' => 'paddle_panels_renderer',
        'options' => array(),
      ),
    ),
  );
  $pipelines[$pipeline->name] = $pipeline;

  // Define the paddle_standard_renderer.
  $pipeline = new stdClass();
  $pipeline->disabled = FALSE; /* Edit this to true to make a default pipeline disabled initially */
  $pipeline->api_version = 1;
  $pipeline->name = 'paddle_standard_renderer';
  $pipeline->admin_title = t('Paddle Standard Renderer');
  $pipeline->admin_description = t('Provides a rendering of the panel panes.');
  $pipeline->weight = 0;
  $pipeline->settings = array(
    'renderers' => array(
      0 => array(
        'access' => array(),
        'renderer' => 'paddle_standard_renderer',
        'options' => array(),
      ),
    ),
  );
  $pipelines[$pipeline->name] = $pipeline;

  return $pipelines;
}
