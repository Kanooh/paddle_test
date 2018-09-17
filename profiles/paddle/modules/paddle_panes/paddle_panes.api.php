<?php

/**
 * @file
 * Documentation for Paddle panes API.
 */

/**
 * Allow modules to alter pane configuration info.
 *
 * @param array $info
 *   An array containing information about the current pane configuration.
 * @param array $type
 *   The pane content type.
 * @param array $conf
 *   The pane configuration.
 */
function hook_paddle_panes_pane_configuration_info_alter(&$info, $type, $conf) {
  $info[] = 'Custom info';
}
