<?php

/**
 * @file
 * Provides default theme functions for the views_languages_field module.
 */

/**
 * Wraps a link.
 */
function theme_views_languages_field_link($vars) {
  global $language_content;

  $item = $vars['item'];

  $translated = !empty($item['nid']);
  $query = drupal_get_destination();
  $published = isset($item['status']) ? $item['status'] : 0;

  if (!empty($item['nid'])) {
    $link = views_languages_field_i18n_node_translation_link(
      $item['language'],
      'node/' . $item['nid'] . '/edit',
      $item['language'],
      array('query' => $query));
  }
  elseif (user_access('translate content')) {
    $query['translation'] = $item['tnid'];
    $query['target'] = $item['language'];
    $link = views_languages_field_i18n_node_translation_link(
      $item['language'],
      'node/add/' . str_replace('_', '-', $item['type']),
      $item['language'],
      array('query' => $query));
  }
  else {
    $link = $item['language'];
  }

  $attributes = array();

  // Class whether translation is available or not.
  if ($translated) {
    $attributes['class'][] = 'locale-translated';
    $attributes['title'] = t('View');
  }
  else {
    $attributes['class'][] = 'locale-untranslated';
    $attributes['title'] = t('Add translation');
  }

  // Class whether published or not.
  if ($published) {
    $attributes['class'][] = 'locale-published';
  }
  else {
    $attributes['class'][] = 'locale-unpublished';
  }

  // Class for active language.
  if ($item['language'] == $language_content->language) {
    $attributes['class'][] = 'locale-active-language';
  }

  return '<span ' . drupal_attributes($attributes) . '>' . $link . '</span>';
}
