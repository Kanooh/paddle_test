<?php
/**
 * @file
 * paddle_maps.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function paddle_maps_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_anonymous_paddle_maps_page';
  $strongarm->value = '0';
  $export['comment_anonymous_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_mode_paddle_maps_page';
  $strongarm->value = 1;
  $export['comment_default_mode_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_per_page_paddle_maps_page';
  $strongarm->value = '30';
  $export['comment_default_per_page_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_form_location_paddle_maps_page';
  $strongarm->value = 1;
  $export['comment_form_location_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_paddle_maps_page';
  $strongarm->value = '0';
  $export['comment_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_preview_paddle_maps_page';
  $strongarm->value = 0;
  $export['comment_preview_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_subject_field_paddle_maps_page';
  $strongarm->value = 0;
  $export['comment_subject_field_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'diff_enable_revisions_page_node_paddle_maps_page';
  $strongarm->value = 1;
  $export['diff_enable_revisions_page_node_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'diff_show_preview_changes_node_paddle_maps_page';
  $strongarm->value = 1;
  $export['diff_show_preview_changes_node_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'diff_view_mode_preview_node_paddle_maps_page';
  $strongarm->value = 'full';
  $export['diff_view_mode_preview_node_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_comment_actions_paddle_maps_page';
  $strongarm->value = array();
  $export['expire_comment_actions_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_comment_comment_page_paddle_maps_page';
  $strongarm->value = 1;
  $export['expire_comment_comment_page_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_comment_custom_paddle_maps_page';
  $strongarm->value = 0;
  $export['expire_comment_custom_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_comment_custom_pages_paddle_maps_page';
  $strongarm->value = '';
  $export['expire_comment_custom_pages_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_comment_front_page_paddle_maps_page';
  $strongarm->value = 0;
  $export['expire_comment_front_page_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_comment_node_page_paddle_maps_page';
  $strongarm->value = 1;
  $export['expire_comment_node_page_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_comment_node_reference_field_collection_pages_paddle_maps_page';
  $strongarm->value = 0;
  $export['expire_comment_node_reference_field_collection_pages_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_comment_node_reference_pages_paddle_maps_page';
  $strongarm->value = 0;
  $export['expire_comment_node_reference_pages_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_comment_node_term_pages_paddle_maps_page';
  $strongarm->value = 0;
  $export['expire_comment_node_term_pages_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_comment_override_defaults_paddle_maps_page';
  $strongarm->value = 0;
  $export['expire_comment_override_defaults_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_comment_reference_pages_paddle_maps_page';
  $strongarm->value = 0;
  $export['expire_comment_reference_pages_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_node_actions_paddle_maps_page';
  $strongarm->value = array();
  $export['expire_node_actions_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_node_custom_paddle_maps_page';
  $strongarm->value = 0;
  $export['expire_node_custom_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_node_custom_pages_paddle_maps_page';
  $strongarm->value = '';
  $export['expire_node_custom_pages_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_node_front_page_paddle_maps_page';
  $strongarm->value = 0;
  $export['expire_node_front_page_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_node_node_page_paddle_maps_page';
  $strongarm->value = 1;
  $export['expire_node_node_page_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_node_override_defaults_paddle_maps_page';
  $strongarm->value = 0;
  $export['expire_node_override_defaults_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_node_reference_field_collection_pages_paddle_maps_page';
  $strongarm->value = 0;
  $export['expire_node_reference_field_collection_pages_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_node_reference_pages_paddle_maps_page';
  $strongarm->value = 0;
  $export['expire_node_reference_pages_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'expire_node_term_pages_paddle_maps_page';
  $strongarm->value = 0;
  $export['expire_node_term_pages_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'field_bundle_settings_node__paddle_maps_page';
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
          'weight' => '-2',
        ),
        'title' => array(
          'weight' => '-5',
        ),
        'path' => array(
          'weight' => '7',
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
  $export['field_bundle_settings_node__paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'gmap_markerfiles';
  $strongarm->value = array(
    drupal_get_path('module', 'gmap') . '/markers',
    'sites/default/files/paddle_map_markers',
    drupal_get_path('module', 'paddle_maps') . '/markers',
  );
  $export['gmap_markerfiles'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'gmap_default';
  $strongarm->value = array(
    'width' => '100%',
    'height' => '600px',
    'latlong' => '50.53590380335898,4.757080078125',
    'zoom' => '8',
    'maxzoom' => '14',
    'styles' => array(
      'line_default' => array(
        0 => '0000ff',
        1 => '5',
        2 => '45',
        3 => '',
        4 => '',
      ),
      'poly_default' => array(
        0 => '000000',
        1 => '3',
        2 => '25',
        3 => 'ff0000',
        4 => '45',
      ),
      'highlight_color' => 'ff0000',
    ),
    'controltype' => 'Small',
    'pancontrol' => 1,
    'streetviewcontrol' => 0,
    'mtc' => 'standard',
    'maptype' => 'Map',
    'baselayers' => array(
      'Map' => 1,
      'Satellite' => 1,
      'Hybrid' => 1,
      'Physical' => 0,
    ),
    'behavior' => array(
      'locpick' => FALSE,
      'nodrag' => 0,
      'nokeyboard' => 1,
      'nomousezoom' => 0,
      'nocontzoom' => 0,
      'autozoom' => 1,
      'dynmarkers' => 0,
      'overview' => 0,
      'collapsehack' => 0,
      'scale' => 0,
      'extramarkerevents' => FALSE,
      'clickableshapes' => FALSE,
      'highlight' => 0,
    ),
    'markermode' => '0',
    'line_colors' => array(
      0 => '#00cc00',
      1 => '#ff0000',
      2 => '#0000ff',
    ),
  );
  $export['gmap_default'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'i18n_node_extended_paddle_maps_page';
  $strongarm->value = '1';
  $export['i18n_node_extended_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'i18n_node_options_paddle_maps_page';
  $strongarm->value = array(
    0 => 'current',
    1 => 'required',
  );
  $export['i18n_node_options_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'language_content_type_paddle_maps_page';
  $strongarm->value = '2';
  $export['language_content_type_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'menu_options_paddle_maps_page';
  $strongarm->value = array(
    0 => 'main-menu',
  );
  $export['menu_options_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'menu_parent_paddle_maps_page';
  $strongarm->value = 'main-menu:0';
  $export['menu_parent_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_options_paddle_maps_page';
  $strongarm->value = array(
    0 => 'moderation',
    1 => 'revision',
  );
  $export['node_options_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_preview_paddle_maps_page';
  $strongarm->value = '0';
  $export['node_preview_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_submitted_paddle_maps_page';
  $strongarm->value = 0;
  $export['node_submitted_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_defaults_node_paddle_maps_page';
  $strongarm->value = array(
    'status' => 1,
    'help' => '',
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
  $export['panelizer_defaults_node_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_maps_page:page_manager_selection';
  $strongarm->value = 'node:paddle_maps_page:paddle_2_col_3_9';
  $export['panelizer_node:paddle_maps_page:page_manager_selection'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_maps_page_allowed_layouts';
  $strongarm->value = 'O:22:"panels_allowed_layouts":4:{s:9:"allow_new";b:1;s:11:"module_name";s:42:"panelizer_node:paddle_advanced_search_page";s:23:"allowed_layout_settings";a:33:{s:8:"flexible";b:0;s:23:"paddle_dashboard_layout";b:0;s:19:"paddle_three_column";b:1;s:16:"paddle_no_column";b:1;s:22:"paddle_4_col_multiline";b:1;s:17:"paddle_4_col_full";b:1;s:12:"paddle_4_col";b:1;s:14:"paddle_3_col_c";b:1;s:14:"paddle_3_col_b";b:1;s:22:"paddle_2_cols_3_cols_d";b:1;s:22:"paddle_2_cols_3_cols_c";b:1;s:22:"paddle_2_cols_3_cols_b";b:1;s:20:"paddle_2_cols_3_cols";b:1;s:18:"paddle_2_col_9_3_d";b:1;s:18:"paddle_2_col_9_3_c";b:1;s:23:"paddle_2_col_9_3_bottom";b:0;s:18:"paddle_2_col_9_3_b";b:1;s:18:"paddle_2_col_9_3_a";b:1;s:16:"paddle_2_col_9_3";b:1;s:18:"paddle_2_col_8_4_a";b:1;s:16:"paddle_2_col_6_6";b:1;s:16:"paddle_2_col_4_8";b:1;s:25:"paddle_2_col_3_9_flexible";b:0;s:16:"paddle_2_col_3_9";b:1;s:19:"paddle_1_col_2_cols";b:1;s:14:"twocol_stacked";b:0;s:13:"twocol_bricks";b:0;s:6:"twocol";b:0;s:25:"threecol_33_34_33_stacked";b:0;s:17:"threecol_33_34_33";b:0;s:25:"threecol_25_50_25_stacked";b:0;s:17:"threecol_25_50_25";b:0;s:6:"onecol";b:0;}s:10:"form_state";N;}';
  $export['panelizer_node:paddle_maps_page_allowed_layouts'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_maps_page_allowed_layouts_default';
  $strongarm->value = 0;
  $export['panelizer_node:paddle_maps_page_allowed_layouts_default'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_maps_page_allowed_types_default';
  $strongarm->value = 0;
  $export['panelizer_node:paddle_maps_page_allowed_types_default'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_maps_page_default';
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
  $export['panelizer_node:paddle_maps_page_default'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_expand_fieldset_paddle_maps_page';
  $strongarm->value = '0';
  $export['scheduler_expand_fieldset_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_allow_state_paddle_maps_page';
  $strongarm->value = '';
  $export['scheduler_publish_allow_state_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_enable_paddle_maps_page';
  $strongarm->value = 1;
  $export['scheduler_publish_enable_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_moderation_state_paddle_maps_page';
  $strongarm->value = 'published';
  $export['scheduler_publish_moderation_state_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_past_date_paddle_maps_page';
  $strongarm->value = 'error';
  $export['scheduler_publish_past_date_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_required_paddle_maps_page';
  $strongarm->value = 0;
  $export['scheduler_publish_required_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_revision_paddle_maps_page';
  $strongarm->value = 1;
  $export['scheduler_publish_revision_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_touch_paddle_maps_page';
  $strongarm->value = 0;
  $export['scheduler_publish_touch_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_default_time_paddle_maps_page';
  $strongarm->value = '';
  $export['scheduler_unpublish_default_time_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_enable_paddle_maps_page';
  $strongarm->value = 1;
  $export['scheduler_unpublish_enable_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_moderation_state_paddle_maps_page';
  $strongarm->value = 'offline';
  $export['scheduler_unpublish_moderation_state_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_required_paddle_maps_page';
  $strongarm->value = 0;
  $export['scheduler_unpublish_required_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_revision_paddle_maps_page';
  $strongarm->value = 1;
  $export['scheduler_unpublish_revision_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_use_vertical_tabs_paddle_maps_page';
  $strongarm->value = '1';
  $export['scheduler_use_vertical_tabs_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'webform_node_paddle_maps_page';
  $strongarm->value = 0;
  $export['webform_node_paddle_maps_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'workbench_moderation_default_state_paddle_maps_page';
  $strongarm->value = 'draft';
  $export['workbench_moderation_default_state_paddle_maps_page'] = $strongarm;

  return $export;
}
