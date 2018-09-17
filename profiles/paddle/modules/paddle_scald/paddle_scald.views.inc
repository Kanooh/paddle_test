<?php

/**
 * @file
 * This file handles views field handler definition.
 */

/**
 * Implements hook_views_data().
 */
function paddle_scald_views_data() {
  $data = array();

  $data['scald_atom']['library_item'] = array(
    'group' => t('Media library'),
    'title' => t('Rendered Media library item'),
    'help' => t('A rendering of a Media library item.'),
    'field' => array(
      'handler' => 'paddle_scald_handler_field_library_item',
    ),
  );

  $data['scald_atom']['atom_file_path'] = array(
    'group' => t('Media library'),
    'title' => t('Path to a Scald atom'),
    'help' => t('The URL to the file of a Media library item.'),
    'field' => array(
      'handler' => 'paddle_scald_handler_field_atom_file_path',
    ),
  );

  $data['scald_atom']['video_thumbnail'] = array(
    'group' => t('Media library'),
    'title' => t('Thumbnail of a Scald Video atom'),
    'help' => t('The path to the video thumbnail of a Scald Video atom.'),
    'field' => array(
      'handler' => 'paddle_scald_handler_field_video_thumbnail',
    ),
  );

  return $data;
}

/**
 * Implements hook_views_data_alter().
 */
function paddle_scald_views_data_alter(&$data) {
  $data['search_api_index_paddle_atoms']['library_item'] = array(
    'title' => t('Rendered Media library item'),
    'help' => t('A rendering of a Media library item.'),
    'field' => array(
      'handler' => 'paddle_scald_handler_field_library_item',
    ),
    'real field' => 'sid',
  );

  $data['search_api_index_paddle_atoms']['atom_file_path'] = array(
    'title' => t('Path to a Media library item'),
    'help' => t('The URL to the file of a Media library item.'),
    'field' => array(
      'handler' => 'paddle_scald_handler_field_atom_file_path',
    ),
    'real field' => 'sid',
  );

  $data['search_api_index_paddle_atoms']['video_thumbnail'] = array(
    'title' => t('Thumbnail of a Scald Video atom'),
    'help' => t('The path to the video thumbnail of a Scald Video atom.'),
    'field' => array(
      'handler' => 'paddle_scald_handler_field_video_thumbnail',
    ),
    'real field' => 'sid',
  );

  // views_handler_field_entity requires an entity type to be set when being called.
  $data['search_api_index_paddle_atoms']['table']['entity type'] = 'scald_atom';

  // Only entityreference fields are able to use the autocomplete on taxonomy
  // terms. We have to set the proper handler manually for core term
  // reference fields.
  // @see https://www.drupal.org/node/2110315#comment-8952753
  $fields = array(
    'field_paddle_tags' => 'paddle_tags',
    'field_paddle_general_tags' => 'paddle_general',
  );
  foreach ($fields as $name => $vocabulary) {
    if (!empty($data['search_api_index_paddle_atoms'][$name]['filter'])) {
      $data['search_api_index_paddle_atoms'][$name]['filter']['handler'] = 'SearchApiViewsHandlerFilterTaxonomyTerm';
      $data['search_api_index_paddle_atoms'][$name]['filter']['vocabulary'] = $vocabulary;
    }
  }
}
