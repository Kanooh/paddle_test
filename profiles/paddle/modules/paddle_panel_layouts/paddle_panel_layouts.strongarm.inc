<?php
/**
 * @file
 * paddle_panel_layouts.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function paddle_panel_layouts_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_defaults_node_landing_page';
  $strongarm->value = array(
    'status' => 1,
    'view modes' => array(
      'page_manager' => array(
        'status' => 1,
        'default' => 0,
        'choice' => 1,
      ),
      'default' => array(
        'status' => 0,
        'default' => 0,
        'choice' => 0,
      ),
      'full' => array(
        'status' => 0,
        'default' => 0,
        'choice' => 0,
      ),
      'teaser' => array(
        'status' => 0,
        'default' => 0,
        'choice' => 0,
      ),
      'rss' => array(
        'status' => 0,
        'default' => 0,
        'choice' => 0,
      ),
      'token' => array(
        'status' => 0,
        'default' => 0,
        'choice' => 0,
      ),
    ),
  );
  $export['panelizer_defaults_node_landing_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:landing_page_allowed_layouts';
  $strongarm->value = 'O:22:"panels_allowed_layouts":4:{s:9:"allow_new";b:1;s:11:"module_name";s:27:"panelizer_node:landing_page";s:23:"allowed_layout_settings";a:27:{s:8:"flexible";b:0;s:23:"paddle_dashboard_layout";b:0;s:19:"paddle_three_column";b:1;s:16:"paddle_no_column";b:1;s:20:"paddle_2_cols_3_cols";b:1;s:18:"paddle_2_col_9_3_c";b:1;s:18:"paddle_2_col_9_3_b";b:1;s:18:"paddle_2_col_9_3_a";b:1;s:16:"paddle_2_col_9_3";b:1;s:16:"paddle_2_col_6_6";b:1;s:16:"paddle_2_col_3_9";b:1;s:14:"twocol_stacked";b:0;s:13:"twocol_bricks";b:0;s:6:"twocol";b:0;s:25:"threecol_33_34_33_stacked";b:0;s:17:"threecol_33_34_33";b:0;s:25:"threecol_25_50_25_stacked";b:0;s:17:"threecol_25_50_25";b:0;s:6:"onecol";b:0;s:17:"paddle_4_col_full";b:1;s:12:"paddle_4_col";b:1;s:23:"paddle_2_col_9_3_bottom";b:0;s:14:"paddle_3_col_b";b:1;s:22:"paddle_2_cols_3_cols_c";b:1;s:22:"paddle_2_cols_3_cols_b";b:1;s:18:"paddle_2_col_9_3_d";b:1;s:25:"paddle_2_col_3_9_flexible";b:0;}s:10:"form_state";N;}';
  $export['panelizer_node:landing_page_allowed_layouts'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:landing_page_allowed_layouts_default';
  $strongarm->value = 0;
  $export['panelizer_node:landing_page_allowed_layouts_default'] = $strongarm;

  return $export;
}
