<?php
/**
 * @file
 * Definition of the paddle_content_region plugin.
 */

$plugin = array(
  'handler' => 'PanelizerEntityPaddleContentRegion',
  'entity path' => 'paddle-content-region/%paddle_content_region',
  'uses page manager' => TRUE,
  'hooks' => array(
    'menu' => TRUE,
    'admin_paths' => TRUE,
    'permission' => TRUE,
    'panelizer_defaults' => TRUE,
    'default_page_manager_handlers' => TRUE,
  ),
);
