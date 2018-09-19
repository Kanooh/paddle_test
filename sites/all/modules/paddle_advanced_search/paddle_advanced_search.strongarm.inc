<?php
/**
 * @file
 * paddle_advanced_search.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function paddle_advanced_search_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'field_bundle_settings_node__paddle_advanced_search_page';
  $strongarm->value = array(
    'view_modes' => array(
      'teaser' => array(
        'custom_settings' => TRUE,
      ),
      'diff_standard' => array(
        'custom_settings' => TRUE,
      ),
      'listing_title' => array(
        'custom_settings' => TRUE,
      ),
      'listing_teaser' => array(
        'custom_settings' => TRUE,
      ),
      'full' => array(
        'custom_settings' => FALSE,
      ),
      'rss' => array(
        'custom_settings' => FALSE,
      ),
      'node_content_pane_summary' => array(
        'custom_settings' => FALSE,
      ),
      'node_content_pane_full' => array(
        'custom_settings' => FALSE,
      ),
      'token' => array(
        'custom_settings' => FALSE,
      ),
    ),
    'extra_fields' => array(
      'form' => array(
        'language' => array(
          'weight' => '-1',
        ),
        'title' => array(
          'weight' => '-5',
        ),
        'path' => array(
          'weight' => '5',
        ),
        'redirect' => array(
          'weight' => '30',
        ),
      ),
      'display' => array(
        'language' => array(
          'default' => array(
            'weight' => '0',
            'visible' => FALSE,
          ),
          'diff_standard' => array(
            'weight' => '9',
            'visible' => FALSE,
          ),
          'listing_teaser' => array(
            'weight' => '1',
            'visible' => FALSE,
          ),
          'listing_title' => array(
            'weight' => '0',
            'visible' => FALSE,
          ),
          'teaser' => array(
            'weight' => '0',
            'visible' => FALSE,
          ),
          'doormat' => array(
            'weight' => '0',
            'visible' => FALSE,
          ),
          'spotlight_extended' => array(
            'weight' => '0',
            'visible' => FALSE,
          ),
          'spotlight_short' => array(
            'weight' => '0',
            'visible' => FALSE,
          ),
        ),
      ),
    ),
  );
  $export['field_bundle_settings_node__paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'i18n_node_extended_paddle_advanced_search_page';
  $strongarm->value = '1';
  $export['i18n_node_extended_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'i18n_node_options_paddle_advanced_search_page';
  $strongarm->value = array(
    0 => 'current',
    1 => 'required',
  );
  $export['i18n_node_options_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'language_content_type_paddle_advanced_search_page';
  $strongarm->value = '2';
  $export['language_content_type_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'menu_options_paddle_advanced_search_page';
  $strongarm->value = array(
    0 => 'main-menu',
  );
  $export['menu_options_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'menu_parent_paddle_advanced_search_page';
  $strongarm->value = 'main-menu:0';
  $export['menu_parent_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_options_paddle_advanced_search_page';
  $strongarm->value = array(
    0 => 'moderation',
    1 => 'revision',
  );
  $export['node_options_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_preview_paddle_advanced_search_page';
  $strongarm->value = '0';
  $export['node_preview_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_submitted_paddle_advanced_search_page';
  $strongarm->value = 0;
  $export['node_submitted_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_defaults_node_paddle_advanced_search_page';
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
      'teaser' => array(
        'status' => 0,
        'substitute' => 0,
        'default' => 0,
        'choice' => 0,
      ),
      'diff_standard' => array(
        'status' => 0,
        'substitute' => 0,
        'default' => 0,
        'choice' => 0,
      ),
      'listing_title' => array(
        'status' => 0,
        'substitute' => 0,
        'default' => 0,
        'choice' => 0,
      ),
      'listing_teaser' => array(
        'status' => 0,
        'substitute' => 0,
        'default' => 0,
        'choice' => 0,
      ),
    ),
  );
  $export['panelizer_defaults_node_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_advanced_search_page:default_selection';
  $strongarm->value = 0;
  $export['panelizer_node:paddle_advanced_search_page:default_selection'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_advanced_search_page:diff_standard_selection';
  $strongarm->value = 0;
  $export['panelizer_node:paddle_advanced_search_page:diff_standard_selection'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_advanced_search_page:listing_teaser_selection';
  $strongarm->value = 0;
  $export['panelizer_node:paddle_advanced_search_page:listing_teaser_selection'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_advanced_search_page:listing_title_selection';
  $strongarm->value = 0;
  $export['panelizer_node:paddle_advanced_search_page:listing_title_selection'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_advanced_search_page:page_manager_selection';
  $strongarm->value = 'node:paddle_advanced_search_page:paddle_2_col_3_9';
  $export['panelizer_node:paddle_advanced_search_page:page_manager_selection'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_advanced_search_page:teaser_selection';
  $strongarm->value = 0;
  $export['panelizer_node:paddle_advanced_search_page:teaser_selection'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_advanced_search_page_allowed_layouts';
  $strongarm->value = 'O:22:"panels_allowed_layouts":4:{s:9:"allow_new";b:1;s:11:"module_name";s:42:"panelizer_node:paddle_advanced_search_page";s:23:"allowed_layout_settings";a:33:{s:8:"flexible";b:0;s:23:"paddle_dashboard_layout";b:0;s:19:"paddle_three_column";b:1;s:16:"paddle_no_column";b:1;s:22:"paddle_4_col_multiline";b:1;s:17:"paddle_4_col_full";b:1;s:12:"paddle_4_col";b:1;s:14:"paddle_3_col_c";b:1;s:14:"paddle_3_col_b";b:1;s:22:"paddle_2_cols_3_cols_d";b:1;s:22:"paddle_2_cols_3_cols_c";b:1;s:22:"paddle_2_cols_3_cols_b";b:1;s:20:"paddle_2_cols_3_cols";b:1;s:18:"paddle_2_col_9_3_d";b:1;s:18:"paddle_2_col_9_3_c";b:1;s:23:"paddle_2_col_9_3_bottom";b:0;s:18:"paddle_2_col_9_3_b";b:1;s:18:"paddle_2_col_9_3_a";b:1;s:16:"paddle_2_col_9_3";b:1;s:18:"paddle_2_col_8_4_a";b:1;s:16:"paddle_2_col_6_6";b:1;s:16:"paddle_2_col_4_8";b:1;s:25:"paddle_2_col_3_9_flexible";b:0;s:16:"paddle_2_col_3_9";b:1;s:19:"paddle_1_col_2_cols";b:1;s:14:"twocol_stacked";b:0;s:13:"twocol_bricks";b:0;s:6:"twocol";b:0;s:25:"threecol_33_34_33_stacked";b:0;s:17:"threecol_33_34_33";b:0;s:25:"threecol_25_50_25_stacked";b:0;s:17:"threecol_25_50_25";b:0;s:6:"onecol";b:0;}s:10:"form_state";N;}';
  $export['panelizer_node:paddle_advanced_search_page_allowed_layouts'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_advanced_search_page_allowed_layouts_default';
  $strongarm->value = 0;
  $export['panelizer_node:paddle_advanced_search_page_allowed_layouts_default'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_advanced_search_page_allowed_types_default';
  $strongarm->value = 0;
  $export['panelizer_node:paddle_advanced_search_page_allowed_types_default'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_advanced_search_page_default';
  $strongarm->value = array(
    'token' => 'token',
    'entity_form_field' => 'entity_form_field',
    'entity_field' => 'entity_field',
    'entity_field_extra' => 'entity_field_extra',
    'custom' => 'custom',
    'block' => 'block',
    'entity_view' => 'entity_view',
    'entity_revision_view' => 'entity_revision_view',
    'panels_mini' => 'panels_mini',
    'views' => 'views',
    'views_panes' => 'views_panes',
    'other' => 'other',
  );
  $export['panelizer_node:paddle_advanced_search_page_default'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_enable_paddle_advanced_search_page';
  $strongarm->value = 1;
  $export['scheduler_publish_enable_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_moderation_state_paddle_advanced_search_page';
  $strongarm->value = 'published';
  $export['scheduler_publish_moderation_state_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_default_time_paddle_advanced_search_page';
  $strongarm->value = '';
  $export['scheduler_unpublish_default_time_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_enable_paddle_advanced_search_page';
  $strongarm->value = 1;
  $export['scheduler_unpublish_enable_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_moderation_state_paddle_advanced_search_page';
  $strongarm->value = 'offline';
  $export['scheduler_unpublish_moderation_state_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_required_paddle_advanced_search_page';
  $strongarm->value = 0;
  $export['scheduler_unpublish_required_paddle_advanced_search_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_revision_paddle_advanced_search_page';
  $strongarm->value = 1;
  $export['scheduler_unpublish_revision_paddle_advanced_search_page'] = $strongarm;

  return $export;
}
