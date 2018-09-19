<?php

/**
 * @file
 * This file handles views api definitions.
 */

/**
 * Implements hook_views_data_alter().
 */
function paddle_holiday_participation_views_data_alter(&$data) {
  $data['field_data_field_hp_contract_type']['table'] = array(
    'group' => t('Holiday participation'),
    'join' => array(
      'node' => array(
        'left_field' => 'nid',
        'field' => 'entity_id',
      ),
    ),
  );

  $data['field_data_field_hp_contract_type']['field_hp_contract_type_value'] = array(
    'title' => t('Event state'),
    'help' => t('A checkbox to see if an offer is a temporary event or not.'),
    'filter' => array(
      'handler' => 'paddle_holiday_participation_handler_filter_event',
    ),
  );

  $data['holiday_participation_capacity_range']['table'] = array(
    'group' => t('Holiday participation'),
    'join' => array(
      '#global' => array(),
    ),
  );

  $data['holiday_participation_capacity_range']['capacity_range'] = array(
    'title' => t('Capacity range'),
    'help' => t('Provides capacity range exposed filter.'),
    'filter' => array(
      'handler' => 'paddle_holiday_participation_handler_filter_range',
    ),
  );

  $data['field_data_field_paddle_geofield']['table'] = array(
    'group' => t('Holiday participation'),
    'join' => array(
      'node' => array(
        'left_field' => 'nid',
        'field' => 'entity_id',
      ),
    ),
  );

  $data['field_data_field_paddle_geofield']['field_geofield_distance'] = array(
    'title' => t('Proximity'),
    'help' => t('Proximity filters which shows everything less then 25km from the center.'),
    'filter' => array(
      'handler' => 'paddle_holiday_participation_handler_filter_proximity',
    ),
  );

  $data['holiday_participation_contract_year']['table'] = array(
    'group' => t('Holiday participation'),
    'join' => array(
      '#global' => array(),
    ),
  );

  $data['holiday_participation_contract_year']['contract_year'] = array(
    'title' => t('Contract year'),
    'help' => t('Provides contract year exposed filter.'),
    'filter' => array(
      'handler' => 'paddle_holiday_participation_handler_filter_year',
    ),
  );

  $data['field_data_field_hp_validity_period']['table'] = array(
    'group' => t('Holiday participation'),
    'join' => array(
      'node' => array(
        'left_field' => 'nid',
        'field' => 'entity_id',
      ),
    ),
  );

  $data['field_data_field_hp_validity_period']['month'] = array(
    'title' => t('Month'),
    'help' => t('Provides month exposed filter.'),
    'filter' => array(
      'handler' => 'paddle_holiday_participation_handler_filter_validity_period_month',
    ),
  );

  return $data;
}
