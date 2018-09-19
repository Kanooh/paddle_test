<?php
/**
 * @file
 * paddle_rich_footer.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function paddle_rich_footer_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_defaults_paddle_rich_footer_paddle_rich_footer';
  $strongarm->value = array(
    'status' => 1,
    'view modes' => array(
      'page_manager' => array(
        'status' => 1,
        'substitute' => '',
        'default' => 0,
        'choice' => 1,
      ),
      'default' => array(
        'status' => 0,
        'substitute' => 0,
        'default' => 0,
        'choice' => 0,
      ),
    ),
  );
  $export['panelizer_defaults_paddle_rich_footer_paddle_rich_footer'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_paddle_rich_footer:paddle_rich_footer:page_manager_selection';
  $strongarm->value = 'paddle_rich_footer:paddle_rich_footer:paddle_4_col_full';
  $export['panelizer_paddle_rich_footer:paddle_rich_footer:page_manager_selection'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_paddle_rich_footer:paddle_rich_footer_allowed_layouts';
  $strongarm->value = 'O:22:"panels_allowed_layouts":4:{s:9:"allow_new";b:0;s:11:"module_name";s:51:"panelizer_paddle_rich_footer:paddle_rich_footer";s:23:"allowed_layout_settings";a:22:{s:8:"flexible";b:0;s:23:"paddle_dashboard_layout";b:0;s:19:"paddle_three_column";b:1;s:16:"paddle_no_column";b:0;s:12:"paddle_4_col";b:0;s:20:"paddle_2_cols_3_cols";b:0;s:18:"paddle_2_col_9_3_c";b:0;s:18:"paddle_2_col_9_3_b";b:0;s:18:"paddle_2_col_9_3_a";b:0;s:16:"paddle_2_col_9_3";b:0;s:16:"paddle_2_col_6_6";b:1;s:16:"paddle_2_col_3_9";b:0;s:14:"twocol_stacked";b:0;s:13:"twocol_bricks";b:0;s:6:"twocol";b:0;s:25:"threecol_33_34_33_stacked";b:0;s:17:"threecol_33_34_33";b:0;s:25:"threecol_25_50_25_stacked";b:0;s:17:"threecol_25_50_25";b:0;s:6:"onecol";b:0;s:17:"paddle_4_col_full";b:1;s:23:"paddle_2_col_9_3_bottom";b:0;}s:10:"form_state";N;}';
  $export['panelizer_paddle_rich_footer:paddle_rich_footer_allowed_layouts'] = $strongarm;

  return $export;
}
