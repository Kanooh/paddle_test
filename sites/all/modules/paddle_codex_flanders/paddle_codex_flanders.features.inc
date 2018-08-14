<?php
/**
 * @file
 * paddle_codex_flanders.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_codex_flanders_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "elysia_cron" && $api == "default_elysia_cron_rules") {
    return array("version" => "1");
  }
}
