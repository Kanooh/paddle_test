<?php

/**
 * @file
 * This file handles views api definitions.
 */

/**
 * Implements hook_views_data().
 */
function paddle_glossary_views_data() {
  $data = array();

  $data['paddle_glossary_definition']['edit_link'] = array(
    'group' => t('Glossary definition'),
    'title' => t('Edit link'),
    'help' => t('A link to edit the definition.'),
    'field' => array(
      'handler' => 'paddle_glossary_handler_field_edit_link',
    ),
  );

  $data['paddle_glossary_definition']['delete_link'] = array(
    'group' => t('Glossary definition'),
    'title' => t('Delete link'),
    'help' => t('A link to delete the definition.'),
    'field' => array(
      'handler' => 'paddle_glossary_handler_field_delete_link',
    ),
  );

  return $data;
}
