<?php
/**
 * @file
 * Definition of the paddle_rich_footer plugin.
 */

$plugin = array(
  'handler' => 'PanelizerEntityPaddleRichFooter',
  'entity path' => 'paddle-rich-footer/%paddle_rich_footer',
  'uses page manager' => TRUE,
  'hooks' => array(
    'menu' => TRUE,
    'admin_paths' => TRUE,
    'permission' => TRUE,
    'panelizer_defaults' => TRUE,
    'default_page_manager_handlers' => TRUE,
  ),
);
