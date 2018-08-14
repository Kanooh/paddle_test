<?php

/**
 * @file
 * Definition of views_languages_field_translations_controls.
 */

/**
 * Field handler to provide a list of languages specific for node.
 *
 * @ingroup views_field_handlers
 */
class views_handler_field_translation_controls extends views_handler_field_prerender_list {
  /**
   * Constructor.
   */
  public function construct() {
    parent::construct();
    $this->additional_fields['nid'] = array('table' => 'node', 'field' => 'nid');
    $this->additional_fields['tnid'] = array('table' => 'node', 'field' => 'tnid');
    $this->additional_fields['type'] = array('table' => 'node', 'field' => 'type');

    // Add stylesheet.
    drupal_add_css(
      drupal_get_path('module', 'views_languages_field') . '/views_languages_field.css',
      array('preprocess' => FALSE)
    );
  }

  /**
   * Reimplements views_handler_field::query().
   */
  public function query() {
    $this->add_additional_fields();
    $this->field_alias = $this->aliases['nid'];
  }

  /**
   * Run before any fields are rendered.
   *
   * This gives the handlers some time to set up before any handler has
   * been rendered.
   *
   * @param array $values
   *   An array of all objects returned from the query.
   */
  public function pre_render(&$values) {
    $translations_sets = array();
    $types = array();
    $this->items = array();
    $languages = language_list('language', TRUE);
    $translation_sets = array();

    // Remove non-enabled languages.
    foreach ($languages as $key => $language) {
      if (!$language->enabled) {
        unset($languages[$key]);
      }
    }

    // Collect tnid or nid to search for.
    foreach ($values as $result) {
      $tnid = $this->get_value($result, 'tnid', TRUE);
      $nid = $this->get_value($result, 'nid', TRUE);
      $tnid = $tnid <> 0 ? $tnid : $nid;

      $types[$tnid] = $this->get_value($result, 'type', TRUE);
      $translation_sets[$tnid] = array();
      $this->items[$nid] = &$translation_sets[$tnid];
    }

    // Get all available translations for the nodes in the result list.
    if (!empty($translation_sets)) {
      $result = db_query("SELECT tnid, nid, type, language, status FROM {node} WHERE tnid IN (:tnid) OR nid IN (:nid)",
        array(':tnid' => array_keys($translation_sets), ':nid' => array_keys($translation_sets)));

      foreach ($result as $translation) {
        if ($translation->tnid == 0) {
          $translation->tnid = $translation->nid;
        }
        $translation_sets[$translation->tnid][$translation->language] = (array) $translation;
      }
    }

    // Add missing languages.
    $language_codes = array_keys($languages);
    foreach ($translation_sets as $tnid => $translations) {
      $current_language_codes = array_keys($translations);
      $missing_langs = array_diff($language_codes, $current_language_codes);
      foreach ($missing_langs as $missing_lang) {
        $translation_sets[$tnid][$missing_lang] = array(
          'language' => $missing_lang,
          'tnid' => $tnid,
          'type' => $types[$tnid],
        );
      }
      ksort($translation_sets[$tnid]);
    }
  }

  /**
   * Renders the item by calling the theme function.
   */
  public function render_item($count, $item) {
    return theme('views_languages_field_link', array('item' => $item));
  }
}
