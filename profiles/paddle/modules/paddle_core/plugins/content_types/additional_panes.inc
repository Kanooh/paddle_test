<?php

/**
 * @file
 * CTools content type plugin to display additional panes in a region.
 */

$plugin = array(
  'title' => t('Additional panes'),
  'description' => t('Display additional panes in a pane.'),
  'defaults' => array(),
  'category' => array(t('Paddle core'), 0),

  // This content type has no subtypes.
  'single' => TRUE,

  // This content type does not display a title, so don't allow to override it.
  'no title override' => TRUE,
);

/**
 * Renders the pane.
 */
function paddle_core_additional_panes_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  if (!empty($conf['context']) && !empty($conf['region'])) {
    if ($panes = module_invoke_all('paddle_core_additional_panes', $conf['context'], $conf['region'])) {
      // Sort the panes by weight.
      uasort($panes, 'drupal_sort_weight');

      // Render the panes.
      ctools_include('content');
      ctools_include('context');

      /* @var panels_display $display */
      $display = panels_new_display();

      /* @var panels_renderer_standard $renderer */
      $renderer = panels_get_renderer_handler($display->renderer, $display);

      foreach ($panes as $pane) {
        $block->content .= $renderer->render_pane($pane['pane']);
      }
    }
  }

  return $block;
}

/**
 * Configuration form for the pane.
 */
function paddle_core_additional_panes_content_type_edit_form($form, &$form_state) {
  $conf = $form_state['conf'];

  // Retrieve all contexts in which this pane can operate.
  // @see hook_paddle_core_additional_panes_contexts()
  foreach (module_invoke_all('paddle_core_additional_panes_contexts') as $context => $info) {
    foreach ($info['regions'] as $region => $name) {
      $options[$context . '|' . $region] = $info['name'] . ' - ' . $name;
    }
  }

  $default_value = !empty($conf['context']) && !empty($conf['region']) ? $conf['context'] . '|' . $conf['region'] : key($options);

  // @todo The contexts and regions are managed using a simple concatenated
  //   list. Split these up in seperate ajaxified dropdowns if the list becomes
  //   unwieldy.
  $form['context_region'] = array(
    '#type' => 'radios',
    '#title' => t('Context and region'),
    '#options' => $options,
    '#default_value' => $default_value,
  );

  return $form;
}

/**
 * Submit handler for the configuration form.
 */
function paddle_core_additional_panes_content_type_edit_form_submit($form, &$form_state) {
  list ($context, $region) = explode('|', $form_state['values']['context_region']);
  $form_state['conf']['context'] = $context;
  $form_state['conf']['region'] = $region;
}
