<?php
/**
 * @file
 * opening_hours_sets.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function opening_hours_sets_field_group_info() {
  $field_groups = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_ous_exc_oh_days|field_collection_item|field_ous_exc_opening_hours|form';
  $field_group->group_name = 'group_ous_exc_oh_days';
  $field_group->entity_type = 'field_collection_item';
  $field_group->bundle = 'field_ous_exc_opening_hours';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Days',
    'weight' => '2',
    'children' => array(
      0 => 'field_ous_monday',
      1 => 'field_ous_tuesday',
      2 => 'field_ous_wednesday',
      3 => 'field_ous_thursday',
      4 => 'field_ous_friday',
      5 => 'field_ous_saturday',
      6 => 'field_ous_sunday',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Days',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-ous-exc-oh-days',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_ous_exc_oh_days|field_collection_item|field_ous_exc_opening_hours|form'] = $field_group;

  // Translatables
  // Included for use with string extractors like potx.
  t('Days');

  return $field_groups;
}
