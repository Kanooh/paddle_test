<?php

/**
 * @file
 * User page callbacks for the Paddle i18n module.
 */

/**
 * Page callback: Displays a list of a node's translations.
 *
 * @param object $node
 *   A node object.
 *
 * @return array
 *   A render array for a page containing a list of content.
 *
 * @see i18n_translation_node_overview()
 */
function paddle_i18n_translation_node_overview($node) {
  $add_modal_js = FALSE;
  module_load_include('inc', 'i18n_node', 'i18n_node.pages');
  $build = i18n_node_translation_overview($node);

  // Add an id on the table to be able to target it.
  $build['translation_node_overview']['#attributes'] = array('id' => 'translation-table');

  if (!empty($node->tnid)) {
    // Already part of a set, grab that set.
    $translations = translation_node_get_translations($node->tnid);
  }
  else {
    // We have no translation source nid, this could be a new set, emulate that.
    $translations = array($node->language => $node);
  }

  // Rework the rows to enable attributes to be added to them.
  foreach ($build['translation_node_overview']['#rows'] as $index => $rows) {
    $build['translation_node_overview']['#rows'][$index] = array('data' => $rows);
  }
  $rows =& $build['translation_node_overview']['#rows'];

  foreach (i18n_node_language_list($node) as $langcode => $language_name) {
    // Find the row.
    $current_row_index = 0;
    foreach ($rows as $index => $row) {
      if (strpos($row['data'][0], $language_name) !== FALSE) {
        $current_row_index = $index;
        break;
      }
    }
    // Add data attributes indicating the language of the row.
    $rows[$current_row_index]['data-language'] = $langcode;

    if ($langcode == LANGUAGE_NONE) {
      // Never show language neutral on the overview.
      continue;
    }
    $options = array();
    if (!isset($translations[$langcode])) {
      // No such translation in the set yet: help user to create it.
      if (node_access('create', $node->type)) {
        $text = t('add translation');
        $path = "admin/content_manager/nojs/{$node->type}/add";
        $query = array(
          'attributes' => array(
            'class' => array(
              'ctools-use-modal',
              'create-' . $node->type,
            ),
          ),
          'query' => array('translation' => $node->nid, 'target' => $langcode),
        );
        $options[] = i18n_node_translation_link($text, $path, $langcode, $query);

        // Change the row link to open the "Paddle Node create modal".
        $rows[$current_row_index]['data'][3] = array(
          'data' => implode(" | ", $options),
          'class' => array('translation-link'),
        );

        $add_modal_js = TRUE;
      }
    }
  }

  if ($add_modal_js) {
    ctools_include('modal');
    ctools_modal_add_js();
  }

  return $build;
}
