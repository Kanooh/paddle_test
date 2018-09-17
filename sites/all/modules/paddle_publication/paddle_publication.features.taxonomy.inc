<?php
/**
 * @file
 * paddle_publication.features.taxonomy.inc
 */

/**
 * Implements hook_taxonomy_default_vocabularies().
 */
function paddle_publication_taxonomy_default_vocabularies() {
  return array(
    'publications_collections' => array(
      'name' => 'Publications collections',
      'machine_name' => 'publications_collections',
      'description' => 'Publications collections',
      'hierarchy' => 0,
      'module' => 'taxonomy',
      'weight' => 0,
      'language' => 'und',
      'i18n_mode' => 4,
    ),
  );
}
