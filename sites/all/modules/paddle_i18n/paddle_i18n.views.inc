<?php

/**
 * @file
 * This file handles views api definitions.
 */

/**
 * Implements hook_views_data().
 */
function paddle_i18n_views_data() {
  $data = array();

  $data['node']['paddle_i18n_missing_translation'] = array(
    'group' => t('Node translation'),
    'title' => t('Missing translation'),
    'help' => t('Enables the search for nodes with missing translation for the specified language'),
    'filter' => array(
      'handler' => 'paddle_i18n_handler_filter_missing_translation',
      'real field' => 'nid',
    ),
  );

  return $data;
}
