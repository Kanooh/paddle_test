<?php
/**
 * @file
 * paddle_ebl.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function paddle_ebl_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'additional_settings__active_tab_paddle_ebl_page';
  $strongarm->value = 'edit-panelizer';
  $export['additional_settings__active_tab_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'clone_reset_paddle_ebl_page';
  $strongarm->value = 1;
  $export['clone_reset_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'diff_enable_revisions_page_node_paddle_ebl_page';
  $strongarm->value = 1;
  $export['diff_enable_revisions_page_node_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'diff_show_preview_changes_node_paddle_ebl_page';
  $strongarm->value = 1;
  $export['diff_show_preview_changes_node_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'diff_view_mode_preview_node_paddle_ebl_page';
  $strongarm->value = 'full';
  $export['diff_view_mode_preview_node_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'field_bundle_settings_node__paddle_ebl_page';
  $strongarm->value = array(
    'view_modes' => array(
      'teaser' => array(
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
      'diff_standard' => array(
        'custom_settings' => TRUE,
      ),
      'node_content_pane_summary' => array(
        'custom_settings' => TRUE,
      ),
      'node_content_pane_full' => array(
        'custom_settings' => TRUE,
      ),
      'token' => array(
        'custom_settings' => FALSE,
      ),
    ),
    'extra_fields' => array(
      'form' => array(
        'language' => array(
          'weight' => '-4',
        ),
        'title' => array(
          'weight' => '-5',
        ),
        'path' => array(
          'weight' => '6',
        ),
        'redirect' => array(
          'weight' => '7',
        ),
      ),
      'display' => array(
        'language' => array(
          'default' => array(
            'weight' => '2',
            'visible' => FALSE,
          ),
          'listing_teaser' => array(
            'weight' => '0',
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
          'node_content_pane_summary' => array(
            'weight' => '0',
            'visible' => FALSE,
          ),
          'node_content_pane_full' => array(
            'weight' => '0',
            'visible' => FALSE,
          ),
          'diff_standard' => array(
            'weight' => '0',
            'visible' => FALSE,
          ),
        ),
      ),
    ),
  );
  $export['field_bundle_settings_node__paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'i18n_node_extended_paddle_ebl_page';
  $strongarm->value = '1';
  $export['i18n_node_extended_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'i18n_node_options_paddle_ebl_page';
  $strongarm->value = array(
    0 => 'current',
    1 => 'required',
  );
  $export['i18n_node_options_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'language_content_type_paddle_ebl_page';
  $strongarm->value = '2';
  $export['language_content_type_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'menu_options_paddle_ebl_page';
  $strongarm->value = array(
    0 => 'main-menu',
  );
  $export['menu_options_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'menu_parent_paddle_ebl_page';
  $strongarm->value = 'main-menu:0';
  $export['menu_parent_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_options_paddle_ebl_page';
  $strongarm->value = array(
    0 => 'moderation',
    1 => 'revision',
  );
  $export['node_options_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_preview_paddle_ebl_page';
  $strongarm->value = '1';
  $export['node_preview_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_submitted_paddle_ebl_page';
  $strongarm->value = 1;
  $export['node_submitted_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_defaults_node_paddle_ebl_page';
  $strongarm->value = array(
    'status' => 1,
    'view modes' => array(
      'page_manager' => array(
        'status' => 1,
        'default' => 1,
        'choice' => 0,
      ),
      'default' => array(
        'status' => 0,
        'default' => 0,
        'choice' => 0,
      ),
      'teaser' => array(
        'status' => 0,
        'default' => 0,
        'choice' => 0,
      ),
      'listing_title' => array(
        'status' => 0,
        'default' => 0,
        'choice' => 0,
      ),
      'listing_teaser' => array(
        'status' => 0,
        'default' => 0,
        'choice' => 0,
      ),
    ),
  );
  $export['panelizer_defaults_node_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'panelizer_node:paddle_ebl_page:page_manager_selection';
  $strongarm->value = 'node:paddle_ebl_page:default';
  $export['panelizer_node:paddle_ebl_page:page_manager_selection'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'save_continue_paddle_ebl_page';
  $strongarm->value = 'Save and add fields';
  $export['save_continue_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_expand_fieldset_paddle_ebl_page';
  $strongarm->value = '0';
  $export['scheduler_expand_fieldset_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_allow_state_paddle_ebl_page';
  $strongarm->value = 'scheduled';
  $export['scheduler_publish_allow_state_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_enable_paddle_ebl_page';
  $strongarm->value = 1;
  $export['scheduler_publish_enable_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_moderation_state_paddle_ebl_page';
  $strongarm->value = 'published';
  $export['scheduler_publish_moderation_state_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_past_date_paddle_ebl_page';
  $strongarm->value = 'error';
  $export['scheduler_publish_past_date_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_required_paddle_ebl_page';
  $strongarm->value = 0;
  $export['scheduler_publish_required_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_revision_paddle_ebl_page';
  $strongarm->value = 1;
  $export['scheduler_publish_revision_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_touch_paddle_ebl_page';
  $strongarm->value = 0;
  $export['scheduler_publish_touch_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_default_time_paddle_ebl_page';
  $strongarm->value = '';
  $export['scheduler_unpublish_default_time_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_enable_paddle_ebl_page';
  $strongarm->value = 1;
  $export['scheduler_unpublish_enable_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_moderation_state_paddle_ebl_page';
  $strongarm->value = 'offline';
  $export['scheduler_unpublish_moderation_state_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_required_paddle_ebl_page';
  $strongarm->value = 0;
  $export['scheduler_unpublish_required_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_revision_paddle_ebl_page';
  $strongarm->value = 0;
  $export['scheduler_unpublish_revision_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_use_vertical_tabs_paddle_ebl_page';
  $strongarm->value = '1';
  $export['scheduler_use_vertical_tabs_paddle_ebl_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'workbench_moderation_default_state_paddle_ebl_page';
  $strongarm->value = 'draft';
  $export['workbench_moderation_default_state_paddle_ebl_page'] = $strongarm;

  return $export;
}
