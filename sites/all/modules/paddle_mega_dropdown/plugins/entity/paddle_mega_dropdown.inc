<?php
/**
 * @file
 * Definition of the paddle_mega_dropdown plugin.
 */

$plugin = array(
  'handler' => 'PanelizerEntityPaddleMegaDropdown',
  'entity path' => 'paddle-mega-dropdown/%paddle_mega_dropdown',
  'uses page manager' => TRUE,
  'hooks' => array(
    'menu' => TRUE,
    'admin_paths' => TRUE,
    'permission' => TRUE,
    'panelizer_defaults' => TRUE,
    'default_page_manager_handlers' => TRUE,
  ),
);
