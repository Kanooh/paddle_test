<?php
/**
 * @file
 * paddle_simple_contact.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function paddle_simple_contact_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'field_bundle_settings_node__simple_contact_page';
  $strongarm->value = array(
    'view_modes' => array(
      'teaser' => array(
        'custom_settings' => TRUE,
      ),
      'full' => array(
        'custom_settings' => FALSE,
      ),
      'rss' => array(
        'custom_settings' => FALSE,
      ),
      'token' => array(
        'custom_settings' => FALSE,
      ),
      'node_content_pane_summary' => array(
        'custom_settings' => TRUE,
      ),
      'node_content_pane_full' => array(
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
    ),
    'extra_fields' => array(
      'form' => array(
        'title' => array(
          'weight' => '0',
        ),
        'path' => array(
          'weight' => '7',
        ),
        'language' => array(
          'weight' => '3',
        ),
      ),
      'display' => array(
        'language' => array(
          'default' => array(
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
          'diff_standard' => array(
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
  $export['field_bundle_settings_node__simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'i18n_node_extended_simple_contact_page';
  $strongarm->value = '1';
  $export['i18n_node_extended_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'i18n_node_options_simple_contact_page';
  $strongarm->value = array(
    0 => 'current',
    1 => 'required',
    2 => 'lock',
  );
  $export['i18n_node_options_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'language_content_type_simple_contact_page';
  $strongarm->value = '2';
  $export['language_content_type_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'menu_options_simple_contact_page';
  $strongarm->value = array(
    0 => 'main-menu',
  );
  $export['menu_options_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'menu_parent_simple_contact_page';
  $strongarm->value = 'main-menu:0';
  $export['menu_parent_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_options_simple_contact_page';
  $strongarm->value = array(
    0 => 'moderation',
    1 => 'revision',
  );
  $export['node_options_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_preview_simple_contact_page';
  $strongarm->value = '1';
  $export['node_preview_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_submitted_simple_contact_page';
  $strongarm->value = 0;
  $export['node_submitted_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_expand_fieldset_simple_contact_page';
  $strongarm->value = '0';
  $export['scheduler_expand_fieldset_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_allow_state_simple_contact_page';
  $strongarm->value = 'scheduled';
  $export['scheduler_publish_allow_state_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_enable_simple_contact_page';
  $strongarm->value = 1;
  $export['scheduler_publish_enable_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_moderation_state_simple_contact_page';
  $strongarm->value = 'published';
  $export['scheduler_publish_moderation_state_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_past_date_simple_contact_page';
  $strongarm->value = 'error';
  $export['scheduler_publish_past_date_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_required_simple_contact_page';
  $strongarm->value = 0;
  $export['scheduler_publish_required_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_revision_simple_contact_page';
  $strongarm->value = 1;
  $export['scheduler_publish_revision_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_publish_touch_simple_contact_page';
  $strongarm->value = 0;
  $export['scheduler_publish_touch_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_default_time_simple_contact_page';
  $strongarm->value = '';
  $export['scheduler_unpublish_default_time_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_enable_simple_contact_page';
  $strongarm->value = 1;
  $export['scheduler_unpublish_enable_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_moderation_state_simple_contact_page';
  $strongarm->value = 'offline';
  $export['scheduler_unpublish_moderation_state_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_required_simple_contact_page';
  $strongarm->value = 0;
  $export['scheduler_unpublish_required_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_unpublish_revision_simple_contact_page';
  $strongarm->value = 0;
  $export['scheduler_unpublish_revision_simple_contact_page'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scheduler_use_vertical_tabs_simple_contact_page';
  $strongarm->value = '1';
  $export['scheduler_use_vertical_tabs_simple_contact_page'] = $strongarm;

  return $export;
}
