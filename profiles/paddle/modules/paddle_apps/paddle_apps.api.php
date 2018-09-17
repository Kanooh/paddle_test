<?php
/**
 * @file
 * Hook provided by the Paddle Apps module.
 */

/**
 * Is the module configured, regardless of whether it's enabled.
 *
 * Helps with checking whether a module was enabled, now disabled but not
 * uninstalled.
 *
 * @return bool
 *   False when the module is not configured.
 */
function hook_is_configured() {
  // Do something.
  return !empty(variable_get('api_key'));
}
