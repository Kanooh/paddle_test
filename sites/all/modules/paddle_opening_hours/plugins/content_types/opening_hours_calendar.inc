<?php

/**
 * @file
 * CTools content type plugin to embed an opening hours set.
 */

$plugin = array(
  'title' => t('Opening hours calendar'),
  'no title override' => TRUE,
  'description' => t('Add an opening hours calendar.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Opening hours sets'), -10),
  'paddle_panes sections' => TRUE,
  'reference tracker' => array(
    'node' => array(
      'value_type' => 'node_autocomplete',
    ),
  ),
);

/**
 * Render the content type.
 */
function paddle_opening_hours_opening_hours_calendar_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  if ($conf['view_mode'] == 'detailed' &&
    !empty($conf['node']) &&
    preg_match('/node\/(\d+)/', $conf['node'], $matches)
  ) {
    $nid = $matches[1];
    $node = node_load($nid);

    $field_name = opening_hours_status_get_field_name($node->type);

    if ($field_name) {
      $item = field_get_items('node', $node, $field_name);

      if ($item) {
        $template = 'opening_hours_info';

        $wrapper = entity_metadata_wrapper('node', $node);
        $opening_hours_set = $wrapper->$field_name->value();

        $ohs_wrapper = entity_metadata_wrapper('opening_hours_set', $opening_hours_set);
        $current_week = opening_hours_sets_get_current_week($ohs_wrapper);
        $exceptional_opening_hours = opening_hours_sets_get_exceptional_opening_hours($ohs_wrapper);
        $closing_days = opening_hours_sets_get_closing_days($ohs_wrapper);
        $variables['current_week'] = $current_week;
        $variables['title'] = l($node->title, 'node/' . $node->nid);
        $variables['closing_days'] = $closing_days;
        $variables['exceptional_opening_hours'] = $exceptional_opening_hours;
        drupal_add_js(drupal_get_path('module', 'opening_hours_sets') . '/js/opening_hours.js', 'file');
        // Allow other modules to alter the fields.
        drupal_alter('opening_hours_info', $variables);
        $output = theme($template, $variables);

        // Allow the pane sections to contribute output.
        if (module_exists('paddle_panes')) {
          module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
          paddle_panes_pane_sections_render($output, $conf, $context);
        }
        $block->content = $output;
        return $block;
      }
    }
  }
  elseif ($conf['view_mode'] == 'list' && !empty($conf['nodes']['list'])) {
    $variables['nodes'] = array();
    $template = 'opening_hours_status_list';

    foreach ($conf['nodes']['list'] as $node) {
      if (preg_match('/node\/(\d+)/', $node['node'], $matches)) {
        $nid = $matches[1];
        $node = node_load($nid);

        $field_name = opening_hours_status_get_field_name($node->type);

        if ($field_name) {
          $item = field_get_items('node', $node, $field_name);

          if ($item) {
            $wrapper = entity_metadata_wrapper('node', $node);
            $opening_hours_set = $wrapper->$field_name->value();
            $variables['nodes'][$node->nid]['title'] = l($node->title, 'node/' . $node->nid);

            if (isset($opening_hours_set)) {
              $ohs_wrapper = entity_metadata_wrapper('opening_hours_set', $opening_hours_set);
              $current_opening_hour = opening_hours_sets_get_current_opening_hour($ohs_wrapper);

              if (!$current_opening_hour || empty($current_opening_hour)) {
                $variables['nodes'][$node->nid]['open'] = t("Now closed");
              }
              else {
                $variables['nodes'][$node->nid]['open'] = t("Now open");
                $variables['nodes'][$node->nid]['time'] = $current_opening_hour['time'];
                $variables['nodes'][$node->nid]['description'] = $current_opening_hour['description'];
              }
            }
            else {
              $variables['nodes'][$node->nid]['open'] = t("Now closed");
            }
          }
        }
      }
    }
    $output = theme($template, $variables);

    // Allow the pane sections to contribute output.
    if (module_exists('paddle_panes')) {
      module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
      paddle_panes_pane_sections_render($output, $conf, $context);
    }
    $block->content = $output;
    return $block;
  }
  return FALSE;
}

/**
 * Edit form callback for the content type.
 */
function paddle_opening_hours_opening_hours_calendar_content_type_edit_form($form, &$form_state) {
  form_load_include($form_state, 'inc', 'paddle_opening_hours', 'plugins/content_types/opening_hours_calendar');
  $conf = $form_state['conf'];

  $context_language = '';
  if (isset($form_state['contexts']['panelizer']->data->language)) {
    $context_language = $form_state['contexts']['panelizer']->data->language;
  }

  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  // Add radio buttons for selecting the view mode.
  $form['section_body']['view_mode'] = array(
    '#type' => 'radios',
    '#title' => t('Display'),
    '#default_value' => isset($conf['view_mode']) ? $conf['view_mode'] : 'detailed',
    '#options' => array(
      'detailed' => t('One detailed opening hours set'),
      'list' => t('List of actual opening hours'),
    ),
  );

  // Add an autocomplete field for selecting a node.
  $form['section_body']['node'] = array(
    '#type' => 'textfield',
    '#title' => t('Page'),
    '#default_value' => isset($conf['node']) ? $conf['node'] : '',
    '#size' => 60,
    '#maxlength' => 300,
    '#autocomplete_path' => 'admin/paddle_panes/node_autocomplete_callback/' . $context_language . '/organizational_unit',
    '#attributes' => array('placeholder' => t('Search for page titles, or enter a node number directly: "node/123".')),
    '#states' => array(
      'visible' => array(
        ':input[name="view_mode"]' => array('value' => 'detailed'),
      ),
    ),
  );

  $form['section_body']['nodes'] = array(
    '#tree' => TRUE,
    '#prefix' => '<div id="nodes-wrapper">',
    '#suffix' => '</div>',
  );

  $form['section_body']['nodes']['list'] = array(
    '#type' => 'fieldset',
    '#states' => array(
      'visible' => array(
        ':input[name="view_mode"]' => array('value' => 'list'),
      ),
    ),
  );

  if (empty($form_state['count_nodes'])) {
    $form_state['count_nodes'] = 1;
  }

  $nodes = $conf['nodes']['list'];
  if (empty($nodes)) {
    $nodes[0]['node'] = '';
    $form_state['conf']['nodes']['list'][0]['node'] = '';
  }

  foreach ($nodes as $key => $node) {
    $form['section_body']['nodes']['list'][$key]['#prefix'] ="<div class='opening-hours-list-item'>";
    $form['section_body']['nodes']['list'][$key]['node'] = array(
      '#type' => 'textfield',
      '#title' => t('Page'),
      '#size' => 60,
      '#maxlength' => 300,
      '#default_value' => $node['node'],
      '#autocomplete_path' => 'admin/paddle_panes/node_autocomplete_callback/' . $context_language . '/organizational_unit',
      '#attributes' => array('placeholder' => t('Search for page titles, or enter a node number directly: "node/123".')),
    );
    // Add a delete button for each new node.
    $form['section_body']['nodes']['list'][$key]['delete'] = array(
      '#name' => 'remove_' . $key,
      '#type' => 'submit',
      '#value' => t('Remove page'),
      '#submit' => array('paddle_opening_hours_remove_one'),
      '#ajax' => array(
        'callback' => 'paddle_opening_hours_add_more_callback',
        'wrapper' => 'nodes-wrapper',
      ),
      '#limit_validation_errors' => array(),
    );
   $form['section_body']['nodes']['list'][$key]['#suffix'] ="</div>";
  }

  $form['section_body']['nodes']['add'] = array(
    '#type' => 'submit',
    '#value' => t('Add page'),
    '#submit' => array('paddle_opening_hours_add_more_add_one'),
    '#ajax' => array(
      'callback' => 'paddle_opening_hours_add_more_callback',
      'wrapper' => 'nodes-wrapper',
    ),
    '#limit_validation_errors' => array(),
    '#states' => array(
      'visible' => array(
        ':input[name="view_mode"]' => array('value' => 'list'),
      ),
    ),
  );

  // Include the form elements for the pane sections in the form.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_build_edit_form($form, $form_state);

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_opening_hours_opening_hours_calendar_content_type_edit_form_validate($form, &$form_state) {
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');

  // Only nodes which exist and have the two custom node content pane view modes
  // are allowed to be selected.
  $node = FALSE;
  if ($form_state['values']['view_mode'] == 'detailed') {
    if (!empty($form_state['values']['node'])) {
      if (preg_match('/node\/(\d+)/', $form_state['values']['node'], $matches)) {
        $nid = $matches[1];
        $node = node_load($nid);
      }
      // This appears when the user gave an invalid string.
      if (empty($node)) {
        form_set_error('node', t('Please enter a valid page.'));
      }
    }
    else {
      form_set_error('node', t('Please enter a valid page.'));
    }
  }


  if ($form_state['values']['view_mode'] == 'list') {
    $nodes = array();
    foreach ($form_state['values']['nodes']['list'] as $key => $node) {
      if (preg_match('/node\/(\d+)/', $node['node'], $matches)) {
        $nid = $matches[1];
        $node = node_load($nid);

        if (!empty($node)) {
          $nodes[] = $node;
        }

      }
      else {
        form_set_error('nodes[list][0][node]', t('Please enter a valid page.'));
      }
    }
  }

  // Allow the form elements provided by pane sections to be validated.
  paddle_panes_pane_sections_validate($form, $form_state);
}

/**
 * Submit callback for the configuration form.
 */
function paddle_opening_hours_opening_hours_calendar_content_type_edit_form_submit($form, &$form_state) {
  foreach (element_children($form['pane_settings']['section_body']) as $key) {
    $form_state['conf'][$key] = $form_state['values'][$key];
  }
  // Allow the form elements provided by pane sections to be saved.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_submit($form, $form_state);
}

/**
 * Callback for both ajax-enabled buttons.
 *
 * Selects and returns the nodes wrapper with the nodes in it.
 */
function paddle_opening_hours_add_more_callback($form, $form_state) {
  return $form['pane_settings']['section_body']['nodes'];
}

/**
 * Submit handler for the "add-one-more" button.
 *
 * Increments the max counter and causes a rebuild.
 */
function paddle_opening_hours_add_more_add_one($form, &$form_state) {
  $form_state['count_nodes']++;
  // Add the empty slide to the form configuration.
  $form_state['conf']['nodes']['list'][$form_state['count_nodes']]['node'] = '';
  $form_state['rebuild'] = TRUE;
}

/**
 * Submit handler for the "remove" button.
 */
function paddle_opening_hours_remove_one($form, &$form_state) {
  // Get the key from the clicked button.
  $key = paddle_opening_hours_remove_button_key($form_state['clicked_button']);

  // Remove the node with the corresponding key from the configuration.
  unset($form_state['conf']['nodes']['list'][$key]);

  // Rebuild the form so the changes are visible.
  $form_state['rebuild'] = TRUE;
}

/**
 * Gets a remove button's key from the element array.
 *
 * @param array $button
 *   The remove button.
 *
 * @return string
 *   The button key
 */
function paddle_opening_hours_remove_button_key(array $button) {
  return $button['#parents'][2];
}
