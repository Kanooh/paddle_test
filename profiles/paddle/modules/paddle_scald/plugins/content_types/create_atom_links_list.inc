<?php

/**
 * @file
 * Plugin to provide list of links to create Paddle Scald atoms.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Create Paddle Scald Atom List'),
  'description' => t('Renders a list of links to create Paddle Scald atoms.'),
  'category' => t('Paddle Scald'),
  'defaults' => array(
    'excluded_atom_types' => array(),
  ),
);

/**
 * Render callback.
 */
function paddle_scald_create_atom_links_list_content_type_render($subtype, $conf, $args, $context) {
  global $language;

  ctools_include('modal');
  ctools_modal_add_js();
  $block = new stdClass();
  $block->title = t('Create Media Assets');
  $block->content = t('You are not allowed to create Media assets or no assets enabled.');

  if (user_access('insert any atom')) {
    // Get all the available atom types.
    $atom_types = scald_types();

    $filtered_atom_types = array_diff_key($atom_types, array_filter($conf['excluded_atom_types']));

    if (!empty($filtered_atom_types)) {
      $item_list = array();
      foreach ($filtered_atom_types as $atom_type) {
        $description = theme('html_tag', array(
          'element' =>
            array(
              '#tag' => 'span',
              '#attributes' => array('class' => 'asset-type-description'),
              '#value' => locale($atom_type->description, NULL, $language->language),
            ),
          )
        );
        $item_list[] = array(
          'title' => scald_type_property_translate($atom_type),
          'icon' => 'file' . ($atom_type->type != 'file' ? '-' . $atom_type->type : '') . '-o',
          'help_text' => $description,
          'link' => array(
            'url' => 'admin/paddle_scald/nojs/add/' . $atom_type->type,
            'attributes' => array('class' => array('ctools-use-modal', 'create-asset-' . $atom_type->type)),
            'query' => array('destination' => 'admin/content_manager/assets'),
          ),
        );
      }

      $block->content = theme('paddle_core_titled_iconed_links_list', array('items' => $item_list));
    }
  }

  return $block;
}

/**
 * Settings form builder callback.
 */
function paddle_scald_create_atom_links_list_content_type_edit_form($form, &$form_state) {
  $options = array();
  foreach (scald_types() as $atom_type) {
    $options[$atom_type->type] = scald_type_property_translate($atom_type);
  }
  $form['excluded_atom_types'] = array(
    '#title' => t('Excluded atom types'),
    '#type' => 'checkboxes',
    '#options' => $options,
    '#description' => t('Atom types to exclude from the list.'),
    '#default_value' => $form_state['conf']['excluded_atom_types'],
  );

  return $form;
}

/**
 * Submit callback of the settings form.
 */
function paddle_scald_create_atom_links_list_content_type_edit_form_submit($form, &$form_state) {
  $form_state['conf']['excluded_atom_types'] = $form_state['values']['excluded_atom_types'];
}

/**
 * Displays the administrative title for a panel pane in the drag & drop UI.
 */
function paddle_scald_create_atom_links_list_content_type_admin_title($subtype, $conf, $context) {
  return t('Create Paddle Scald Atom List');
}
