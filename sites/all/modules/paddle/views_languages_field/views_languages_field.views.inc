<?php

/**
 * @file
 * views_languages_field.views.inc
 */

/**
 * Implements hook_views_data_alter().
 */
function views_languages_field_views_data_alter(&$data) {
  $data['node']['translations'] = array(
    'group' => t('Node translation'),
    'title' => t('Translation controls'),
    'help' => t('A list of links pointing to the different translations of the node'),
    'field' => array(
      'handler' => 'views_handler_field_translation_controls',
      'click sortable' => TRUE,
    ),
  );

  return $data;
}

/**
 * Implements hook_views_handlers().
 */
function views_languages_field_views_handlers() {
  return array(
    'info' => array(
      'path' => drupal_get_path('module', 'views_languages_field'),
    ),
    'handlers' => array(
      'views_handler_field_translation_controls' => array(
        'parent' => 'views_handler_field_prerender_list',
      ),
    ),
  );
}
