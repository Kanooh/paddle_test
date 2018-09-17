<?php
/**
 * @file
 * paddle_pane_collection.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function paddle_pane_collection_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_defaults_paddle_pane_collection_paddle_pane_collection';
  $strongarm->value = array(
    'status' => 1,
    'view modes' => array(
      'page_manager' => array(
        'status' => 1,
        'substitute' => '',
        'default' => 1,
        'choice' => 0,
      ),
      'default' => array(
        'status' => 0,
        'substitute' => 0,
        'default' => 0,
        'choice' => 0,
      ),
    ),
  );
  $export['panelizer_defaults_paddle_pane_collection_paddle_pane_collection'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_paddle_pane_collection:paddle_pane_collection:page_manager_selection';
  $strongarm->value = 'paddle_pane_collection:paddle_pane_collection:paddle_no_column';
  $export['panelizer_paddle_pane_collection:paddle_pane_collection:page_manager_selection'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_paddle_pane_collection:paddle_pane_collection_allowed_layouts';
  $strongarm->value = 'O:22:"panels_allowed_layouts":4:{s:9:"allow_new";b:1;s:11:"module_name";s:55:"panelizer_paddle_pane_collection:paddle_pane_collection";s:23:"allowed_layout_settings";a:34:{s:8:"flexible";b:0;s:23:"paddle_dashboard_layout";b:0;s:19:"paddle_three_column";b:0;s:16:"paddle_no_column";b:1;s:22:"paddle_4_col_multiline";b:0;s:17:"paddle_4_col_full";b:0;s:12:"paddle_4_col";b:0;s:14:"paddle_3_col_c";b:0;s:14:"paddle_3_col_b";b:0;s:22:"paddle_2_cols_3_cols_d";b:0;s:22:"paddle_2_cols_3_cols_c";b:0;s:22:"paddle_2_cols_3_cols_b";b:0;s:20:"paddle_2_cols_3_cols";b:0;s:18:"paddle_2_col_9_3_d";b:0;s:18:"paddle_2_col_9_3_c";b:0;s:23:"paddle_2_col_9_3_bottom";b:0;s:18:"paddle_2_col_9_3_b";b:0;s:18:"paddle_2_col_9_3_a";b:0;s:16:"paddle_2_col_9_3";b:0;s:18:"paddle_2_col_8_4_a";b:0;s:16:"paddle_2_col_6_6";b:0;s:16:"paddle_2_col_4_8";b:0;s:25:"paddle_2_col_3_9_flexible";b:0;s:16:"paddle_2_col_3_9";b:0;s:19:"paddle_1_col_3_cols";b:0;s:19:"paddle_1_col_2_cols";b:0;s:14:"twocol_stacked";b:0;s:13:"twocol_bricks";b:0;s:6:"twocol";b:0;s:25:"threecol_33_34_33_stacked";b:0;s:17:"threecol_33_34_33";b:0;s:25:"threecol_25_50_25_stacked";b:0;s:17:"threecol_25_50_25";b:0;s:6:"onecol";b:0;}s:10:"form_state";N;}';
  $export['panelizer_paddle_pane_collection:paddle_pane_collection_allowed_layouts'] = $strongarm;

  return $export;
}
