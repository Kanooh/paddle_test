<?php

/**
 * @file
 * Plugin to show a list of Outgoing RSS feeds.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Outgoing RSS feeds list'),
  'no title override' => TRUE,
  'description' => t('Add a list of Outgoing RSS feeds.'),
  'category' => t('Paddle Outgoing RSS'),
  'defaults' => array(),
  'paddle_panes sections' => TRUE,
);

/**
 * Render callback.
 */
function paddle_outgoing_rss_outgoing_rss_feeds_list_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $output = '';

  $feeds = is_array($conf['outgoing_rss_feeds']) && count($conf['outgoing_rss_feeds']) ? $conf['outgoing_rss_feeds'] : array();
  $entities = entity_load('paddle_outgoing_rss_feed', array_values($feeds));
  if (count($entities)) {
    $items = array();

    // Render the icon once only.
    $icon = theme('paddle_core_icon', array('name' => 'rss'));

    foreach ($entities as $entity) {
      $options = array(
        'html' => TRUE,
        'attributes' => array(
          'class' => array('outgoing-rss-feed'),
          'data-feed-id' => $entity->fid,
        ),
      );
      $items[] = l($icon . check_plain($entity->title), $entity->path, $options);
    }

    $output = theme('item_list', array('items' => $items));
  }

  // Allow the pane sections to contribute output.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  $block->content = $output;

  return $block;
}

/**
 * Settings form builder callback.
 */
function paddle_outgoing_rss_outgoing_rss_feeds_list_content_type_edit_form($form, &$form_state) {
  form_load_include($form_state, 'inc', 'paddle_outgoing_rss', 'plugins/content_types/outgoing_rss_feeds_list');

  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  $feeds = entity_load('paddle_outgoing_rss_feed');
  if (!count($feeds)) {
    $form['section_body']['outgoing_rss_feeds'] = array(
      '#markup' => t('No Outgoing RSS feeds added yet.'),
    );
  }
  else {
    $options = array();
    foreach ($feeds as $entity) {
      $options[$entity->fid] = $entity->title;
    }
    $form['section_body']['outgoing_rss_feeds'] = array(
      '#type' => 'checkboxes',
      '#default_value' => $form_state['conf']['outgoing_rss_feeds'],
      '#required' => TRUE,
      '#title' => t('Select an Outgoing RSS feed'),
      '#options' => $options,
    );
  }

  // Include the form elements for the pane sections in the form.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);
  }

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_outgoing_rss_outgoing_rss_feeds_list_content_type_edit_form_validate($form, &$form_state) {
  // Allow the form elements provided by pane sections to be validated.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_validate($form, $form_state);
  }
}

/**
 * Submit callback of the settings form.
 */
function paddle_outgoing_rss_outgoing_rss_feeds_list_content_type_edit_form_submit($form, &$form_state) {
  $form_state['conf']['outgoing_rss_feeds'] = $form_state['values']['outgoing_rss_feeds'];

  // Allow the form elements provided by pane sections to be saved.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_submit($form, $form_state);
  }
}
