<?php

/**
 * @file
 * CTools content type plugin to embed a page.
 */

define('NODE_CONTENT_VIEW_MODE_SUMMARY', 0);
define('NODE_CONTENT_VIEW_MODE_FULL', 1);
define('NODE_CONTENT_VIEW_MODE_PAGE_TITLE', 2);

$plugin = array(
  'title' => t('Add page content'),
  'description' => t('Add page content.'),
  'single' => TRUE,
  'no title override' => TRUE,
  'defaults' => array(),
  'category' => array(t('Paddle Landing Page'), -9),
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
function paddle_panes_node_content_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  if (!empty($conf['node']) && preg_match('/node\/(\d+)/', $conf['node'], $matches)) {
    $nid = $matches[1];
    $node = node_load($nid);
  }

  // Do not output anything if the node is invalid or the user has no access.
  if (empty($node) || !node_access('view', $node)) {
    return $block;
  }

  switch ($conf['view_mode']) {
    case NODE_CONTENT_VIEW_MODE_SUMMARY:
      $node_view = node_view($node, 'node_content_pane_summary');
      $block->content .= render($node_view);
      break;

    case NODE_CONTENT_VIEW_MODE_FULL:
      $node_view = node_view($node, 'node_content_pane_full');
      $block->content .= render($node_view);
      break;
  }

  // Transform the customized pane section configuration into something that
  // paddle_panes_pane_sections_render() understands.
  // @see paddle_panes_pane_sections_render()
  if (!empty($conf['top']['section_content_type']) && $conf['top']['section_content_type'] == 'title') {
    $conf['top']['section_content_type'] = 'text';
    $conf['top']['section_text'] = check_plain($node->title);
  }
  if (!empty($conf['top']['section_url_type']) && $conf['top']['section_url_type'] == 'node_link') {
    $conf['top']['section_url_type'] = 'internal';
    $conf['top']['section_internal_url'] = "node/$node->nid";
  }
  if (!empty($conf['bottom']['section_url_type']) && $conf['bottom']['section_url_type'] == 'more_link') {
    $conf['bottom']['section_url_type'] = 'internal';
    $conf['bottom']['section_internal_url'] = "node/$node->nid";
    $conf['bottom']['section_text'] = t('Read more');
  }

  // Allow the pane sections to contribute output.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_render($block->content, $conf, $context);

  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_panes_node_content_content_type_edit_form($form, &$form_state) {
  $conf = $form_state['conf'];

  $context_language = '';
  if (isset($form_state['contexts']['panelizer']->data->language)) {
    $context_language = $form_state['contexts']['panelizer']->data->language;
  }

  // Get a list of content types that support the custom "node content pane"
  // view modes. All content types that have the option "Use custom display
  // settings for the following view modes" enabled in the Field UI can be
  // chosen in the autocomplete field. Both the "Node content pane summary" and
  // "Node content pane full" view modes must be enabled.
  $node_types = paddle_panes_get_node_types_by_custom_view_modes(array('node_content_pane_summary', 'node_content_pane_full'));
  if (empty($node_types)) {
    $form = array(
      'error' => array(
        '#markup' => '<p>' . t('There is no content available for displaying in content panes.') . '</p>',
      ),
    );
    return $form;
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

  // Add an autocomplete field for selecting a node.
  $form['section_body']['node'] = array(
    '#type' => 'textfield',
    '#title' => t('Page'),
    '#required' => TRUE,
    '#default_value' => isset($conf['node']) ? $conf['node'] : '',
    '#size' => 60,
    '#maxlength' => 300,
    '#autocomplete_path' => 'admin/paddle_panes/node_autocomplete_callback/' . $context_language . '/' . implode(',', $node_types),
    '#attributes' => array('placeholder' => t('Search for page titles, or enter a node number directly: "node/123".')),
  );

  // Add radio buttons for selecting the view mode.
  $form['section_body']['view_mode'] = array(
    '#type' => 'radios',
    '#title' => t('Display'),
    '#default_value' => isset($conf['view_mode']) ? $conf['view_mode'] : 0,
    '#options' => array(
      NODE_CONTENT_VIEW_MODE_SUMMARY => t('Summary'),
      NODE_CONTENT_VIEW_MODE_FULL => t('Full'),
      NODE_CONTENT_VIEW_MODE_PAGE_TITLE => t('Just the page title as page top'),
    ),
    '#ajax' => array(
      'callback' => 'paddle_panes_node_content_view_mode_callback',
      'wrapper' => 'edit-top',
    ),
  );

  // Include the form elements for the pane sections in the form.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_build_edit_form($form, $form_state);

  // Add the option to show the node title in the top pane section. Select this
  // by default. Hide the text input field if this option is selected.
  $form['pane_settings']['top']['section_wrapper']['section_content_type']['#options'] = array('title' => t('Use content title')) + $form['pane_settings']['top']['section_wrapper']['section_content_type']['#options'];
  $form['pane_settings']['top']['section_wrapper']['section_content_type']['#default_value'] = !empty($conf['top']['section_content_type']) ? $conf['top']['section_content_type'] : 'title';

  // Provide an option to link the title to the node content.
  $form['pane_settings']['top']['section_wrapper']['section_url_type']['#options']['node_link'] = t('Link to the content page');

  if ($form_state['values']['view_mode'] == NODE_CONTENT_VIEW_MODE_PAGE_TITLE) {
    unset($form_state['input']['top']['enable_section']);
    $form['pane_settings']['top']['enable_section']['#default_value'] = 1;
    unset($form_state['input']['top']['section_wrapper']['section_url_type']);
    $form['pane_settings']['top']['section_wrapper']['section_url_type']['#default_value'] = 'node_link';
  }

  // Provide an option to show a read more link in the bottom pane section.
  $form['pane_settings']['bottom']['section_wrapper']['section_url_type']['#options']['more_link'] = t('Read more link');
  $form['pane_settings']['bottom']['section_wrapper']['section_url_type']['#default_value'] = !empty($conf['bottom']['section_url_type']) ? $conf['bottom']['section_url_type'] : 'more_link';

  $form['pane_settings']['bottom']['section_wrapper']['section_text']['#states']['visible'][':input[name="bottom[section_wrapper][section_url_type]"]'] = array(
    '!value' => 'more_link',
  );
  $form['pane_settings']['bottom']['section_wrapper']['section_text']['#states']['required'][':input[name="bottom[section_wrapper][section_url_type]"]'] = array(
    '!value' => 'more_link',
  );
  $form['pane_settings']['bottom']['section_wrapper']['section_text']['#weight'] = 15;
  $form['pane_settings']['bottom']['section_wrapper']['section_text']['#skip_pane_section_validation'] = TRUE;

  form_load_include($form_state, 'inc', 'paddle_panes', 'plugins/content_types/node_content');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_panes_node_content_content_type_edit_form_validate($form, &$form_state) {
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');

  // Only nodes which exist and have the two custom node content pane view modes
  // are allowed to be selected.
  $node = FALSE;
  if (!empty($form_state['values']['node'])) {
    if (preg_match('/node\/(\d+)/', $form_state['values']['node'], $matches)) {
      $nid = $matches[1];
      $node = node_load($nid);
    }
  }

  $node_types = paddle_panes_get_node_types_by_custom_view_modes(array(
    'node_content_pane_summary',
    'node_content_pane_full',
  ));
  if (empty($node) || !in_array($node->type, $node_types)) {
    form_set_error('node', t('Please enter a valid page.'));
  }

  // Bottom section text should be required when bottom section is not set to
  // "read more link".
  $bottom_values = $form_state['values']['bottom'];
  if (!empty($bottom_values['enable_section'])
    && $bottom_values['section_wrapper']['section_url_type'] != 'more_link'
    && empty($bottom_values['section_wrapper']['section_text'])
  ) {
    $field_title = $form['pane_settings']['bottom']['section_wrapper']['section_text']['#title'];
    form_set_error("bottom][section_wrapper][section_text", t('@field field is required for section @section.', array('@field' => $field_title, '@section' => t('Bottom'))));
  }

  // Allow the form elements provided by pane sections to be validated.
  paddle_panes_pane_sections_validate($form, $form_state);
}

/**
 * Submit callback for the configuration form.
 */
function paddle_panes_node_content_content_type_edit_form_submit($form, &$form_state) {
  foreach (element_children($form['pane_settings']['section_body']) as $key) {
    $form_state['conf'][$key] = $form_state['values'][$key];
  }

  // Allow the form elements provided by pane sections to be saved.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_submit($form, $form_state);
}

/**
 * Ajax callback when having chosen a view mode.
 *
 * Callback element needs only select the portion of the form to be updated.
 * Since #ajax['callback'] return can be HTML or a renderable array (or an
 * array of commands), we can just return a piece of the form.
 */
function paddle_panes_node_content_view_mode_callback($form, $form_state) {
  // When the view mode has been changed, the content of
  // the edit pane elements will be changed according to the view mode.
  $form['pane_settings']['top']['enable_section']['#default_value'] = 1;

  return $form['pane_settings']['top'];
}
