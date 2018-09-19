<?php

/**
 * @file
 * CTools content type plugin to add an side navigation component.
 */

$plugin = array(
  'category' => array(t('Paddle Web Universe'), -20),
  'defaults' => array(),
  'description' => t('Add a side navigation component.'),
  'no title override' => TRUE,
  'paddle_panes sections' => FALSE,
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'single' => TRUE,
  'title' => t('Side navigation'),
);

/**
 * Render the content type.
 */
function paddle_web_universe_panes_web_universe_side_navigation_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $pane_links = array();

  // Get all panes of the current page.
  $node = $context->data;
  $panes = $node->panelizer['page_manager']->display->content;
  foreach ($panes as $pane) {
    if (
      // Don't link to itself.
      $pane->type != 'web_universe_side_navigation' &&
      // Only when top section is available.
      !empty($pane->configuration['top']) &&
      // Only when top section is enabled.
      $pane->configuration['top']['enable_section'] &&
      // Only when top section is text.
      $pane->configuration['top']['section_content_type'] == 'text'
    ) {
      $pane_title = $pane->configuration['top']['section_text'];
      $pane_links[] = array(
        'class' => array('side-navigation__item'),
        // Similar to how paddle_panes_pane_sections_build_edit_form() builds
        // the anchor link.
        'data' => l($pane_title, $_GET['q'], array('fragment' => $pane->uuid)),
      );
    }
  }

  if (count($pane_links)) {
    $variables['skip_links'] = theme(
      'item_list',
      array(
        'attributes' => array('class' => array('side-navigation__group')),
        'items' => $pane_links,
        'type' => 'ul',
      )
    );

    $variables['is_sticky'] = isset($conf['is_sticky']) ? $conf['is_sticky'] : FALSE;

    $block->content = theme('paddle_web_universe_theme_side_navigation', $variables);
  }

  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_web_universe_panes_web_universe_side_navigation_content_type_edit_form($form, &$form_state) {
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

  $form['section_body']['is_sticky'] = array(
    '#type' => 'checkbox',
    '#title' => t('Allow the pane to be sticky'),
    '#default_value' => isset($conf['is_sticky']) ? $conf['is_sticky'] : '',
  );

  return $form;
}

/**
 * Submit callback for the configuration form.
 */
function paddle_web_universe_panes_web_universe_side_navigation_content_type_edit_form_submit($form, &$form_state) {
  // Store all values in the pane configuration.
  foreach (element_children($form['section_body']) as $key) {
    $form_state['conf'][$key] = $form_state['values'][$key];
  }
}
