<?php

/**
 * @file
 * Paddle landing page renderer pipeline for panels_layouts objects.
 */

/**
 * Implements hook_default_panels_renderer_pipeline().
 */
function paddle_landing_page_default_panels_renderer_pipeline() {
  $pipelines = array();

  $pipeline = new stdClass();
  $pipeline->disabled = FALSE; /* Edit this to true to make a default pipeline disabled initially */
  $pipeline->api_version = 1;
  $pipeline->name = 'paddle_landing_page';
  $pipeline->admin_title = t('Paddle Landing Page');
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
        'renderer' => 'paddle_landing_page',
        'options' => array(),
      ),
    ),
  );
  $pipelines[$pipeline->name] = $pipeline;

  return $pipelines;
}
