<?php

/**
 * @file
 * Provides i18n integration for the Paddle Menu Manager.
 */

/**
 * Translation set.
 */
class paddle_menu_manager_translation_set extends i18n_translation_set {

  /**
   * Load all menu translations.
   */
  public function load_translations() {
    $query = db_select('menu_custom', 'mc');
    $query->fields('mc');
    $query->condition('mc.i18n_tsid', $this->tsid);

    $translations = array();
    foreach ($query->execute()->fetchAll(PDO::FETCH_ASSOC) as $item) {
      $translations[$item['language']] = $item;
    }
    return $translations;
  }
}
