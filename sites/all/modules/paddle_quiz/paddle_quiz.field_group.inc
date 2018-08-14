<?php
/**
 * @file
 * paddle_quiz.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function paddle_quiz_field_group_info() {
  $export = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_paddle_quiz_buttons|paddle_quiz|paddle_quiz|form';
  $field_group->group_name = 'group_paddle_quiz_buttons';
  $field_group->entity_type = 'paddle_quiz';
  $field_group->bundle = 'paddle_quiz';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Buttons',
    'weight' => '6',
    'children' => array(
      0 => 'field_paddle_quiz_btn_next_l',
      1 => 'field_paddle_quiz_btn_previous_l',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Buttons',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => 'group-paddle-quiz-buttons field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_paddle_quiz_buttons|paddle_quiz|paddle_quiz|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_paddle_quiz_global_design|paddle_quiz|paddle_quiz|form';
  $field_group->group_name = 'group_paddle_quiz_global_design';
  $field_group->entity_type = 'paddle_quiz';
  $field_group->bundle = 'paddle_quiz';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Global design',
    'weight' => '8',
    'children' => array(
      0 => 'field_paddle_quiz_css',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Global design',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => 'group-paddle-quiz-global-design field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_paddle_quiz_global_design|paddle_quiz|paddle_quiz|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_paddle_quiz_info|paddle_quiz|paddle_quiz|form';
  $field_group->group_name = 'group_paddle_quiz_info';
  $field_group->entity_type = 'paddle_quiz';
  $field_group->bundle = 'paddle_quiz';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Info page',
    'weight' => '3',
    'children' => array(
      0 => 'field_paddle_quiz_info_button_l',
      1 => 'field_paddle_quiz_info_location',
      2 => 'field_paddle_quiz_info_message',
      3 => 'field_paddle_quiz_info_title',
      4 => 'field_paddle_quiz_info_user',
      5 => 'field_paddle_quiz_info_required',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Info page',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => 'group-paddle-quiz-info field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_paddle_quiz_info|paddle_quiz|paddle_quiz|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_paddle_quiz_prize|paddle_quiz|paddle_quiz|form';
  $field_group->group_name = 'group_paddle_quiz_prize';
  $field_group->entity_type = 'paddle_quiz';
  $field_group->bundle = 'paddle_quiz';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Quiz with prize?',
    'weight' => '2',
    'children' => array(
      0 => 'field_paddle_quiz_disclaimer',
      1 => 'field_paddle_quiz_tiebreaker',
      2 => 'field_paddle_quiz_tiebreaker_l',
      3 => 'field_paddle_quiz_tiebreaker_q',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Quiz with prize?',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => 'group-paddle-quiz-prize field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_paddle_quiz_prize|paddle_quiz|paddle_quiz|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_paddle_quiz_result|paddle_quiz|paddle_quiz|form';
  $field_group->group_name = 'group_paddle_quiz_result';
  $field_group->entity_type = 'paddle_quiz';
  $field_group->bundle = 'paddle_quiz';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Result page',
    'weight' => '5',
    'children' => array(
      0 => 'field_paddle_quiz_result_message',
      1 => 'field_paddle_quiz_result_title',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Result page',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => 'group-paddle-quiz-result field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_paddle_quiz_result|paddle_quiz|paddle_quiz|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_paddle_quiz_start_design|paddle_quiz|paddle_quiz|form';
  $field_group->group_name = 'group_paddle_quiz_start_design';
  $field_group->entity_type = 'paddle_quiz';
  $field_group->bundle = 'paddle_quiz';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Start page design',
    'weight' => '7',
    'children' => array(
      0 => 'field_paddle_quiz_start_image',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Start page design',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => 'group-paddle-quiz-start-design field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_paddle_quiz_start_design|paddle_quiz|paddle_quiz|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_paddle_quiz_start|paddle_quiz|paddle_quiz|form';
  $field_group->group_name = 'group_paddle_quiz_start';
  $field_group->entity_type = 'paddle_quiz';
  $field_group->bundle = 'paddle_quiz';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Start page',
    'weight' => '1',
    'children' => array(
      0 => 'field_paddle_quiz_start_button',
      1 => 'field_paddle_quiz_start_message',
      2 => 'field_paddle_quiz_start_title',
      3 => 'field_paddle_quiz_subtitle',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Start page',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => 'group-paddle-quiz-start field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_paddle_quiz_start|paddle_quiz|paddle_quiz|form'] = $field_group;

  return $export;
}
