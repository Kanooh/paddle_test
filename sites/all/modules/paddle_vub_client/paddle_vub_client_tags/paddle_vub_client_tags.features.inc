<?php
/**
 * @file
 * paddle_vub_client_tags.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_vub_client_tags_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "elysia_cron" && $api == "default_elysia_cron_rules") {
    return array("version" => "1");
  }
  if ($module == "feeds" && $api == "feeds_importer_default") {
    return array("version" => "1");
  }
}
