<?php

/**
 * @file
 * CTools content type plugin to embed images.
 */

$plugin = array(
  'title' => t('Add a photo album'),
  'no title override' => TRUE,
  'description' => t('Add a photo album.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Paddle Photo Album'), -10),
  'paddle_panes sections' => TRUE,
  'reference tracker' => array(
    'image_scald' => array(
      'value_type' => 'paddle_scald_atom',
    ),
    'internal_url' => array(
      'value_type' => 'conditional_node_autocomplete',
      'conditional element' => 'url_type',
      'conditional value' => 'internal',
    ),
  ),
);

/**
 * Render the content type.
 */
function paddle_photo_album_photo_album_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $output = '';

  $query = new EntityFieldQuery();
  $query->entityCondition('entity_type', 'scald_atom')
    ->entityCondition('bundle', 'image');

  if (!empty($conf['terms']['paddle_tags'])) {
    $query->fieldCondition('field_paddle_tags', 'tid', $conf['terms']['paddle_tags'], 'IN');
  }

  if (!empty($conf['terms']['paddle_general'])) {
    $query->fieldCondition('field_paddle_general_tags', 'tid', $conf['terms']['paddle_general'], 'IN');
  }

  $query->propertyOrderBy('created', 'DESC');

  $result = $query->execute();

  if (!empty($result['scald_atom'])) {
    libraries_load('colorbox');

    if (!path_is_admin(current_path())) {
      // Add the colorbox JS file.
      drupal_add_js(drupal_get_path('module', 'paddle_scald_image') . '/js/paddle_scald_image_colorbox.js', 'file');
    }

    // Specify the attributes.
    $attributes = array('class' => array('colorbox-link-group'));

    $sids = array_keys($result['scald_atom']);

    foreach ($sids as $sid) {
      $atom = scald_fetch($sid);

      $render = array(
        '#prefix' => '<div class="photo-album-image">',
        '#markup' => l(
          theme('paddle_scald_render_atom', array('atom' => $atom, 'image_style' => 'square')),
          file_create_url($atom->file_source),
          array('attributes' => $attributes, 'html' => TRUE)
        ),
        '#suffix' => '</div>',
      );

      $output .= drupal_render($render);
    }

    // Allow the pane sections to contribute output.
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  $block->content = $output;
  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_photo_album_photo_album_content_type_edit_form($form, &$form_state) {
  $conf = $form_state['conf'];

  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  // Add a "Tags" selection widget for every available vocabulary.
  // @see taxonomy_field_widget_form()
  $form['section_body']['terms'] = array('#tree' => TRUE);
  foreach (taxonomy_vocabulary_load_multiple(FALSE) as $vocabulary) {
    $terms = array();
    if (isset($conf['terms'][$vocabulary->machine_name]) && is_array($conf['terms'][$vocabulary->machine_name])) {
      foreach ($conf['terms'][$vocabulary->machine_name] as $item) {
        $terms[$item['tid']] = isset($item['taxonomy_term']) ? $item['taxonomy_term'] : taxonomy_term_load($item['tid']);
      }
    }

    $context_node_id = 0;
    if (!is_null($form_state['contexts'])) {
      $context_node_id = $form_state['contexts']['panelizer']->data->nid;
    }
    $form['section_body']['terms'][$vocabulary->machine_name] = array(
      '#type' => 'textfield',
      '#title' => t('Filter by %vocabulary tags', array('%vocabulary' => $vocabulary->name)),
      '#description' => t('Use commas to separate multiple tags.'),
      '#default_value' => taxonomy_implode_tags($terms),
      '#autocomplete_path' => 'paddle_panes/taxonomy/autocomplete/' . $context_node_id . '/' . $vocabulary->vid,
      '#size' => 60,
      '#maxlength' => 1024,
      '#element_validate' => array('paddle_panes_taxonomy_autocomplete_validate'),
    );
  }

  // Include the form elements for the pane sections in the form.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_build_edit_form($form, $form_state);

  form_load_include($form_state, 'inc', 'paddle/photo_album', 'plugins/content_types/photo_album');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_photo_album_photo_album_content_type_edit_form_validate($form, &$form_state) {
  // Allow the form elements provided by pane sections to be validated.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_validate($form, $form_state);
}

/**
 * Submit callback for the configuration form.
 */
function paddle_photo_album_photo_album_content_type_edit_form_submit($form, &$form_state) {
  // Store only the term ids, not the entire term data array.
  if (!empty($form_state['values']['terms'])) {
    foreach ($form_state['values']['terms'] as $vocabulary_name => $terms) {
      if (!empty($terms)) {
        foreach ($terms as $key => $term) {
          $form_state['conf']['terms'][$vocabulary_name][$key] = array('tid' => $term['tid']);
        }
      }
      else {
        $form_state['conf']['terms'][$vocabulary_name] = array();
      }
    }
  }

  // Allow the form elements provided by pane sections to be saved.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_submit($form, $form_state);
}
