<?php

/**
 * @file
 * Drush integration for the paddle_core module.
 */

/**
 * Implements hook_drush_command().
 */
function paddle_core_drush_command() {
  $items = array();

  $items['paddle-export-translations'] = array(
    'description' => dt('Exports the overwritten translations.'),
    'aliases' => array('pet'),
    'examples' => array(
      'drush paddle-export-translations > translations.csv' => 'Exports the translations to the file translations.csv',
    ),
  );

  $items['paddle-import-translations'] = array(
    'description' => dt('Imports the overwritten translations.'),
    'aliases' => array('pit'),
    'arguments' => array(
      'file' => 'Path to the CSV file to import translations from',
    ),
    'required-arguments' => 1,
    'examples' => array(
      'drush paddle-import-translations translations.csv' => 'Import translations from translations.csv',
    ),
  );

  return $items;
}

/**
 * Callback for the translations export.
 */
function drush_paddle_core_paddle_export_translations() {
  $query = db_select('locales_target', 'lt');
  $query->innerJoin('locales_source', 'ls', 'lt.lid = ls.lid');
  $query->addField('lt', 'translation');
  $query->addField('lt', 'language');
  $query->addField('lt', 'plural');
  $query->addField('ls', 'source');
  $query->addField('ls', 'context');
  $query->addField('ls', 'textgroup');
  $query->condition('lt.l10n_status', '0', '>');
  $query->orderBy('ls.textgroup');
  $query->orderBy('ls.context');
  $query->orderBy('ls.source');
  $query->orderBy('lt.language');
  $query->orderBy('lt.plural');

  $translations = $query->execute()->fetchAll();

  $fp = fopen('php://output', 'w');

  $results = array();
  // Set the header.
  $results[] = array(
    'textgroup',
    'context',
    'source',
    'language',
    'plural',
    'translation',
  );

  foreach ($translations as $translation) {
    $results[] = array(
      $translation->textgroup,
      $translation->context,
      $translation->source,
      $translation->language,
      $translation->plural,
      $translation->translation,
    );
  }

  foreach ($results as $line) {
    fputcsv($fp, $line, ';');
  }

  fclose($fp);
}

/**
 * Callback for the translations import.
 *
 * @param string $file
 *   The path to the file we need to import.
 */
function drush_paddle_core_paddle_import_translations($file) {
  module_load_include('inc', 'l10n_update');

  $fp = fopen($file, 'r');

  // Read out headers row.
  fgetcsv($fp, NULL, ';');

  $imported_count = 0;
  $textgroups = array();
  while ($row = fgetcsv($fp, NULL, ';')) {
    list($textgroup, $context, $source, $language, $plural, $translation) = $row;
    $textgroups[] = $textgroup;

    $select = db_select('locales_source', 'ls')
      ->fields('ls')
      ->condition('context', $context, '=')
      ->condition('source', $source, '=');

    $result = $select->execute()->fetchObject();

    if ($result) {
      $lid = $result->lid;
    }
    else {
      $lid = db_insert('locales_source')
        ->fields(array(
          'context' => $context,
          'source' => $source,
          'textgroup' => $textgroup,
        ))->execute();
    }

    db_merge('locales_target', array('lt'))
      ->key(array(
        'lid' => $lid,
        'language' => $language,
        'plural' => $plural,
      ))
      ->fields(array(
        'translation' => $translation,
        'l10n_status' => L10N_UPDATE_STRING_CUSTOM,
      ))
      ->execute();

    $imported_count++;
  }

  drush_print(dt('Imported translations: @count', array('@count' => $imported_count)));

  // Keep i18n_string in the loop.
  if (module_exists('i18n_string')) {
    $updated_count = 0;

    foreach (array_unique($textgroups) as $textgroup) {
      // Based on i18n_string_locale_translate_import_form_submit().
      if (i18n_string_group_info($textgroup)) {
        i18n_string_textgroup($textgroup)->update_check();
        $updated_count++;
      }
    }

    drush_print(dt('Updated i18n_string text groups: @count', array('@count' => $updated_count)));
  }
}
