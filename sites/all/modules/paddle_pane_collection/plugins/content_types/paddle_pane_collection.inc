<?php

/**
 * @file
 * CTools content type plugin to embed an pane collection.
 */

$plugin = array(
  'title' => t('Pane Collection'),
  'no title override' => TRUE,
  'description' => t('Add a pane collection.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Pane Collections'), -10),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_pane_collection_paddle_pane_collection_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  if (!empty($conf['pane_collection'])) {
    $menu_item = menu_get_item();
    $pane_collection = paddle_pane_collection_load($conf['pane_collection']);

    // Check if the pane collection has not been deleted yet.
    if (isset($pane_collection) && $pane_collection !== FALSE) {
      // The panes should not be rendered on the layout page, this way it
      // is easier to drag and drop them on the IPE panel.
      if (!empty($menu_item['page_arguments']) && array_search('node_panels_layout', $menu_item['page_arguments']) !== FALSE) {
        $output_string = t('Pane Collection @collection', array('@collection' => $pane_collection->title));
        $output = render($output_string);
      }
      else {
        $display = $pane_collection->panelizer['page_manager']->display;
        $output = panels_render_display($display);
      }

      $block->content = $output;
    }
  }

  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_pane_collection_paddle_pane_collection_content_type_edit_form($form, &$form_state) {
  form_load_include($form_state, 'inc', 'paddle_pane_collection', 'plugins/content_types/paddle_pane_collection');
  $conf = $form_state['conf'];

  $form['pane_settings'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
  );

  $form['pane_settings']['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  // Add a selection field for selecting a pane collection.
  $form['pane_settings']['section_body']['pane_collection'] = array(
    '#type' => 'select',
    '#title' => t('Select a Pane collection'),
    '#default_value' => isset($conf['pane_collection']) ? $conf['pane_collection'] : '',
    '#options' => paddle_pane_collection_get_list(),
    '#empty_option' => t('< Pick a collection >'),
  );

  $form['#attributes'] = array('class' => array('paddle-add-pane-form'));
  $form['buttons']['#attributes'] = array('class' => array('form-buttons'));

  // We still want our anchor to be created, even when we don't use.
  if (isset($form_state['contexts']['panelizer']->data->nid)) {
    $nid = $form_state['contexts']['panelizer']->data->nid;
    $uuid = $form_state['pane']->uuid;
    $form['anchor'] = array(
      '#markup' => l('<i class="fa fa-link" aria-hidden="true"></i>', 'node/' . $nid, array(
        'fragment' => $uuid,
        'alias' => TRUE,
        'html' => TRUE,
        'attributes' => array('class' => 'panel-link', 'title' => t('Click on your right mouse button and copy the link to obtain a direct link to this pane.')),
      )),
    );
  }

  return $form;
}

/**
 * Submit callback for the configuration form.
 */
function paddle_pane_collection_paddle_pane_collection_content_type_edit_form_submit($form, &$form_state) {
  foreach (element_children($form['pane_settings']['section_body']) as $key) {
    $form_state['conf'][$key] = $form_state['values'][$key];
  }
}
