<?php
/**
 * @file
 * Definition of the paddle_pane_collection plugin.
 */

$plugin = array(
  'handler' => 'PanelizerEntityPaddlePaneCollection',
  'entity path' => 'paddle-pane-collection/%paddle_pane_collection',
  'uses page manager' => TRUE,
  'hooks' => array(
    'menu' => TRUE,
    'admin_paths' => TRUE,
    'permission' => TRUE,
    'panelizer_defaults' => TRUE,
    'default_page_manager_handlers' => TRUE,
  ),
);
