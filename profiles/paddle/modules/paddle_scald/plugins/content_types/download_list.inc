<?php

/**
 * @file
 * CTools content type plugin to embed images.
 */

$plugin = array(
  'title' => t('Download list'),
  'no title override' => TRUE,
  'description' => t('Add a download list.'),
  'single' => TRUE,
  'defaults' => array(
    'selection_type' => 'manual',
  ),
  'category' => array(t('Paddle Landing Page'), -10),
  'edit form' => 'paddle_scald_download_list_content_type_edit_form',
  'configuration info' => 'paddle_scald_download_list_content_type_configuration_info',
  'paddle_panes sections' => TRUE,
  'reference tracker' => array(
    'selection_type' => array(
      'value_type' => 'manual_download_list',
    ),
  ),
);

/**
 * Render the content type.
 */
function paddle_scald_download_list_content_type_render($subtype, $conf, $args, $context) {
  // Create a new block with an empty title.
  $block = new stdClass();
  $block->title = '';

  // Add defaults for already existing panes. We don't have access to the
  // plugin information here, so we can't do like in the edit form callback.
  $conf += array('selection_type' => 'manual');

  $callback = 'paddle_scald_download_list_content_type_get_atoms_' . $conf['selection_type'];
  $atoms = $callback($conf);

  // Initialize the items array.
  $items = array();

  foreach ($atoms as $sid) {
    $atom = scald_atom_load($sid);

    if (!$atom) {
      continue;
    }

    // This is a list of atoms that we know have "real" downloads, aka files.
    $file_atoms = array(
      'paddle_scald_image',
      'paddle_scald_file',
      'paddle_scald_video_file',
    );

    $url = '';
    $link_options = array();

    // First check if the atom provider has a custom url generator.
    $url_callback = $atom->provider . '_download_url';
    if (function_exists($url_callback)) {
      $url = $url_callback($atom);
    }
    // If not, and the atom is known to have a file as download, generate a url
    // ourselves.
    elseif (in_array($atom->provider, $file_atoms)) {
      $url = file_create_url($atom->file_source);
    }

    // See if the atom provider has additional options for the download link.
    $options_callback = $atom->provider . '_download_link_options';
    if (function_exists($options_callback)) {
      $link_options = $options_callback();
    }
    $link_options['attributes']['target'] = '_blank';
    $link_options['html'] = TRUE;

    // Only certain atom types should have the file type icon.
    if (in_array($atom->provider, $file_atoms)) {
      // Always use lowercase extension for classes.
      $extension = strtolower(pathinfo($atom->file_source, PATHINFO_EXTENSION));

      // Get the mime type from the file.
      $type = FALSE;
      if (!empty($atom->paddle_scald_file[LANGUAGE_NONE][0]['filemime'])) {
        $type = $atom->paddle_scald_file[LANGUAGE_NONE][0]['filemime'];
      }
      elseif (!empty($atom->base_entity->filemime)) {
        $type = $atom->base_entity->filemime;
      }

      // The scald atoms don't always include the filesize but the original file managed object does.
      $original_file = file_load($atom->base_id);
      $file_size = $original_file->filesize;

      $attributes = array();
      // If we have found a mime type, add it as class.
      if ($type) {
        $attributes['class'] = 'file-' . drupal_html_class($type);
      }

      $icon = theme('paddle_core_icon', array(
        'type' => 'file',
        'name' => $extension,
        'attributes' => $attributes,
      ));
    }
    // Other atoms use a chevron because they are not "real" downloads.
    else {
      $icon = theme('paddle_core_icon', array(
        'name' => 'file',
        'type' => 'file',
      ));
    }

    // Get the HTML for the list item.
    $item = theme('paddle_core_listing_table_item', array(
      'content' => $atom->title,
      'icon' => $icon,
      'extension' => $extension,
      'size' => paddle_scald_file_format_size($file_size),
      'type' => 'file',
    ));

    // If an url exists, wrap the item inside a link. If no url was found, we're
    // out of luck.
    if (!empty($url)) {
      $item = l($item, $url, $link_options);
    }

    // Add the item to the list of which we will generate the listing.
    $items[] = $item;
  }

  // Put all items in a nice listing "table".
  $output = theme('paddle_core_listing_table', array('items' => $items));

  // Allow the pane sections to contribute output.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_render($output, $conf, $context);

  $block->content = $output;
  return $block;
}

/**
 * Retrieves atom ids from configuration when manual mode is selected.
 *
 * @param array $conf
 *   The pane configuration.
 *
 * @return array
 *   An array of atoms ids.
 */
function paddle_scald_download_list_content_type_get_atoms_manual($conf) {
  // Normalize the atoms configuration.
  $atoms = paddle_scald_download_list_normalize_atoms_conf($conf['atoms']);

  // Sort atoms by their weight.
  uasort($atoms, 'drupal_sort_weight');

  // Extract ids from the configuration.
  $sids = array();
  foreach ($atoms as $data) {
    $sids[$data['atom'][0]] = $data['atom'][0];
  }

  return $sids;
}

/**
 * Retrieves atom ids from configuration when tags mode is selected.
 *
 * @param array $conf
 *   The pane configuration.
 *
 * @return array
 *   An array of atoms ids.
 */
function paddle_scald_download_list_content_type_get_atoms_tags($conf) {
  $query = new EntityFieldQuery();
  $query->entityCondition('entity_type', 'scald_atom');

  if (!empty($conf['terms'])) {
    $fields = _paddle_scald_atom_taxonomy_fields();
    foreach ($conf['terms'] as $vocabulary_name => $tids) {
      if (isset($fields[$vocabulary_name])) {
        $query->fieldCondition($fields[$vocabulary_name], 'tid', $tids);
      }
    }
  }

  // Extract sort information.
  list($sort_field, $sort_dir) = explode('_', $conf['sorting_type']);

  // We can sort only by properties or fields with EFQ.
  if ($sort_field == 'title') {
    $query->propertyOrderBy('title', strtoupper($sort_dir));
  }
  else {
    // Tag the query to add a join for additional sort fields.
    $query->addTag('download_list_pane_sort');
    $query->addMetaData('sort_field', $sort_field);
    $query->addMetaData('sort_dir', $sort_dir);
  }

  $results = $query->execute();

  return !empty($results['scald_atom']) ? array_keys($results['scald_atom']) : array();
}

/**
 * Edit form callback for the content type.
 */
function paddle_scald_download_list_content_type_edit_form($form, &$form_state) {
  $conf = $form_state['conf'];

  // Existing pane don't take into account pane defaults anymore.
  $plugin = $form_state['plugin'];
  $conf += $plugin['defaults'];

  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'id' => drupal_html_id('pane-section-body'),
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  $form['section_body']['selection_type'] = array(
    '#type' => 'radios',
    '#title' => t('Selection type'),
    '#options' => array(
      'manual' => t('Manually select files'),
      'tags' => t('List based on tags'),
    ),
    '#default_value' => $conf['selection_type'],
    '#ajax' => array(
      'callback' => 'paddle_scald_download_list_content_type_edit_form_selection_type_ajax_callback',
      'wrapper' => $form['section_body']['#attributes']['id'],
    ),
  );

  // Render the form for the chosen selection type.
  $type = !empty($form_state['values']['selection_type']) ? $form_state['values']['selection_type'] : $conf['selection_type'];
  $callback = 'paddle_scald_download_list_content_type_edit_form_' . $type;
  $form = $callback($form, $form_state);

  // Include the form elements for the pane sections in the form.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_build_edit_form($form, $form_state);

  form_load_include($form_state, 'inc', 'paddle_scald', 'plugins/content_types/download_list');

  return $form;
}

/**
 * Callback to render the manual atom selection form.
 */
function paddle_scald_download_list_content_type_edit_form_manual($form, &$form_state) {
  $conf = $form_state['conf'];

  $form['section_body']['atoms'] = array(
    '#theme' => 'paddle_scald_draggable_atoms',
    '#tree' => TRUE,
    '#prefix' => '<div id="paddle-scald-draggable-atoms-wrapper">',
    '#suffix' => '</div>',
    '#after_build' => array('paddle_scald_download_list_content_type_edit_form_draggable_atoms_after_build'),
  );

  if (isset($form_state['atoms'])) {
    $atoms = $form_state['atoms'];
  }
  elseif (isset($conf['atoms'])) {
    $atoms = paddle_scald_download_list_normalize_atoms_conf($conf['atoms']);

    // Save the atom list in the form state to be able to unset specific
    // row ids through the related button.
    $form_state['atoms'] = $atoms;
  }
  else {
    // Always start with at least one row.
    $atoms = $form_state['atoms'] = array(array());
  }

  foreach ($atoms as $index => $info) {
    $form['section_body']['atoms'][$index] = array(
      'atom' => array(
        '#title' => t('File'),
        '#type' => 'paddle_scald_atom',
        '#required' => TRUE,
        '#default_value' => isset($info['atom']) ? $info['atom'] : NULL,
        '#button_text' => t('Add media'),
      ),
      'weight' => array(
        '#type' => 'weight',
        '#title' => t('Weight'),
        '#default_value' => isset($info['weight']) ? $info['weight'] : $index,
        '#delta' => 50,
      ),
      'remove' => array(
        '#type' => 'submit',
        '#value' => t('Remove'),
        '#name' => "remove_atom_$index",
        '#limit_validation_errors' => array(),
        // Prevent this button from cluttering the form state values.
        '#parents' => array(),
        '#submit' => array('paddle_scald_download_list_content_type_edit_form_remove_atom_submit'),
        '#ajax' => array(
          'callback' => 'paddle_scald_download_list_content_type_edit_form_atoms_ajax_callback',
          'wrapper' => 'paddle-scald-draggable-atoms-wrapper',
        ),
      ),
    );
  }

  $form['section_body']['add_atom'] = array(
    '#type' => 'submit',
    '#value' => t('Add row'),
    '#limit_validation_errors' => array(),
    '#submit' => array('paddle_scald_download_list_content_type_edit_form_add_atom_submit'),
    '#ajax' => array(
      'callback' => 'paddle_scald_download_list_content_type_edit_form_atoms_ajax_callback',
      'wrapper' => 'paddle-scald-draggable-atoms-wrapper',
    ),
  );

  return $form;
}

/**
 * Callback to render the tags atom selection form.
 */
function paddle_scald_download_list_content_type_edit_form_tags($form, &$form_state) {
  $conf = $form_state['conf'];

  $form['section_body']['filters'] = array(
    '#type' => 'fieldset',
    '#title' => t('Filters'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );

  // Add a "Tags" selection widget for every supported vocabulary.
  // @see taxonomy_field_widget_form()
  $form['section_body']['filters']['terms'] = array('#tree' => TRUE);
  foreach (array_keys(_paddle_scald_atom_taxonomy_fields()) as $vocabulary_name) {
    $vocabulary = taxonomy_vocabulary_machine_name_load($vocabulary_name);
    $terms = array();
    if (isset($conf['terms'][$vocabulary->machine_name]) && is_array($conf['terms'][$vocabulary->machine_name])) {
      foreach ($conf['terms'][$vocabulary->machine_name] as $tid) {
        $terms[$tid] = taxonomy_term_load($tid);
      }
    }

    $form['section_body']['filters']['terms'][$vocabulary->machine_name] = array(
      '#type' => 'textfield',
      '#title' => t('Filter by %vocabulary tags', array('%vocabulary' => $vocabulary->name)),
      '#description' => t('Use commas to separate multiple tags.'),
      '#default_value' => taxonomy_implode_tags($terms),
      '#autocomplete_path' => 'paddle_panes/taxonomy/autocomplete/' . $form_state['contexts']['panelizer']->data->nid . '/' . $vocabulary->vid,
      '#size' => 60,
      '#maxlength' => 1024,
      '#element_validate' => array('paddle_panes_taxonomy_autocomplete_validate'),
    );
  }

  $form['section_body']['sorting'] = array(
    '#type' => 'fieldset',
    '#title' => t('Sorting'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );

  $form['section_body']['sorting']['sorting_type'] = array(
    '#type' => 'radios',
    '#title' => t('Sort by'),
    '#title_display' => 'invisible',
    '#options' => array(
      'title_asc' => t('Alphabetical - (a-z)'),
      'title_desc' => t('Alphabetical - (z-a)'),
      'filesize_asc' => t('File size ascending'),
      'filesize_desc' => t('File size descending'),
    ),
    '#default_value' => isset($conf['sorting_type']) ? $conf['sorting_type'] : 'title_asc',
  );

  return $form;
}

/**
 * After-build callback to sort the atom form elements by their weight.
 */
function paddle_scald_download_list_content_type_edit_form_draggable_atoms_after_build($element, &$form_state) {
  foreach (element_children($element) as $key) {
    if (!empty($element[$key]['weight']['#value'])) {
      $element[$key]['#weight'] = $element[$key]['weight']['#value'];
    }
  }

  // Mark element to be sorted.
  $element['#sorted'] = FALSE;

  return $element;
}

/**
 * Validate handler for the edit form.
 */
function paddle_scald_download_list_content_type_edit_form_validate($form, &$form_state) {
  $values = $form_state['values'];

  if ($values['selection_type'] == 'manual' && empty($values['atoms'])) {
    form_set_error('', t('You need to select at least one file.'));
  }

  // Allow the form elements provided by pane sections to be validated.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_validate($form, $form_state);
}

/**
 * Submit callback for the configuration form.
 */
function paddle_scald_download_list_content_type_edit_form_submit($form, &$form_state) {
  $values = $form_state['values'];

  // Save single values into configuration.
  $keys = array('selection_type', 'atoms', 'sorting_type');
  foreach ($keys as $index) {
    if (isset($values[$index])) {
      $form_state['conf'][$index] = $values[$index];
    }
  }

  if (!empty($values['terms'])) {
    $form_state['conf']['terms'] = array();
    foreach ($values['terms'] as $vocabulary_name => $terms) {
      foreach ($terms as $term) {
        // Store only the term ids, not the entire term data array.
        $form_state['conf']['terms'][$vocabulary_name][] = $term['tid'];
      }
    }
  }

  // Allow the form elements provided by pane sections to be saved.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_submit($form, $form_state);
}

/**
 * Submit callback to add a new empty row in the atoms table.
 */
function paddle_scald_download_list_content_type_edit_form_add_atom_submit($form, &$form_state) {
  $form_state['atoms'][] = array();
  $form_state['rebuild'] = TRUE;
}

/**
 * Submit callback to remove a specific row from the atoms table.
 */
function paddle_scald_download_list_content_type_edit_form_remove_atom_submit($form, &$form_state) {
  $parents = array_slice($form_state['clicked_button']['#array_parents'], 0, -1);
  $row_id = array_pop($parents);

  unset($form_state['atoms'][$row_id]);
  $form_state['rebuild'] = TRUE;
}

/**
 * Ajax callback to render the form after changing the selection type.
 */
function paddle_scald_download_list_content_type_edit_form_selection_type_ajax_callback($form, &$form_state) {
  return $form['pane_settings']['section_body'];
}

/**
 * Ajax callback to render the updated atom table after updating its rows.
 */
function paddle_scald_download_list_content_type_edit_form_atoms_ajax_callback($form, &$form_state) {
  return $form['pane_settings']['section_body']['atoms'];
}

/**
 * Normalizes old panes atoms configurations.
 *
 * @param array $atoms
 *   The atoms set in the pane configuration.
 *
 * @return array
 *   The normalized atoms array.
 */
function paddle_scald_download_list_normalize_atoms_conf($atoms) {
  $atoms = array_values($atoms);

  // Handle old configurations which don't have weight.
  if (!isset($atoms[0]['weight'])) {
    foreach ($atoms as $index => $atom_id) {
      $atoms[$index] = array(
        'atom' => array($atom_id),
        'weight' => $index,
      );
    }
  }

  return $atoms;
}

/**
 * Provides information about the current page configuration.
 *
 * @param array $conf
 *   The configuration of the pane.
 *
 * @return array
 *   An array representing the actual configuration.
 */
function paddle_scald_download_list_content_type_configuration_info($conf) {
  $info = array();

  // Ensure that a selection type value is always present, even for old
  // configuration.
  $conf += array('selection_type' => 'manual');

  if ($conf['selection_type'] == 'manual') {
    $info[] = t('Manually selected files');

    $sids = paddle_scald_download_list_content_type_get_atoms_manual($conf);
    $atoms = scald_fetch_multiple($sids);
    foreach ($atoms as $atom) {
      $info[] = $atom->title;
    }
  }
  else {
    $info[] = t('List based on tags');

    if (!empty($conf['terms'])) {
      foreach ($conf['terms'] as $vocabulary => $terms) {
        foreach ($terms as $tid) {
          $term = taxonomy_term_load($tid);
          $info[] = $term->name;
        }
      }
    }
  }

  if (!empty($conf['sorting_type'])) {
    switch ($conf['sorting_type']) {
      case 'title_asc':
        $info[] = t('Sorting: Alphabetical - (a-z)');
        break;
      case 'title_desc':
        $info[] = t('Sorting: Alphabetical - (z-a)');
        break;
      case 'filesize_asc':
        $info[] = t('Sorting: File size ascending');
        break;
      case 'filesize_desc':
        $info[] = t('Sorting: File size descending');
        break;
    }

  }

  return $info;
}
