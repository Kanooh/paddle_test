<?php
/**
 * @file
 * Page callbacks for Glossary paddlet.
 */

/**
 * Page callback to configure a definition.
 *
 * @param string $js
 *   Whether to open the form in a modal or not.
 * @param object $definition
 *   The definition to edit.
 *
 * @return mixed
 *   The built form.
 */
function paddle_glossary_definition_form_wrapper($js, $definition) {
  // Include the form callback file.
  module_load_include('inc', 'paddle_glossary', 'paddle_glossary.forms');

  // Prepare the form state like drupal_get_form() would do.
  $form_state = array(
    'ajax' => $js,
    'build_info' => array(
      'args' => array($definition),
    ),
  );

  // If no js, show a normal page.
  if (!$js) {
    return drupal_build_form('paddle_glossary_definition_form', $form_state);
  }

  // Include modal and ajax helpers.
  ctools_include('ajax');
  ctools_include('modal');
  ctools_modal_add_js();

  // Open the form in a modal.
  $commands = ctools_modal_form_wrapper('paddle_glossary_definition_form', $form_state);

  if ($form_state['executed'] && !$form_state['rebuild']) {
    $commands = array();
    $commands[] = ctools_modal_command_dismiss();
    $commands[] = ctools_ajax_command_reload();
  }

  print ajax_render($commands);
  ajax_footer();
  drupal_exit();
}

/**
 * Page callback for the "Delete glossary definition" form.
 *
 * @param string $js
 *   Whether to open the form in a modal or not.
 * @param object $definition
 *   The definition to delete.
 *
 * @return mixed
 *   The built form.
 */
function paddle_glossary_definition_delete($js, $definition) {
  // Include the form callback file.
  module_load_include('inc', 'paddle_glossary', 'paddle_glossary.forms');

  $form_state = array('ajax' => $js, 'gdid' => $definition->gdid);

  if (!$js) {
    return drupal_build_form('paddle_glossary_definition_delete_form', $form_state);
  }

  ctools_include('ajax');
  ctools_include('modal');

  // Open the delete form in a modal.
  $commands = ctools_modal_form_wrapper('paddle_glossary_definition_delete_form', $form_state);

  if (!empty($form_state['completed'])) {
    $commands = array();
    $commands[] = ctools_modal_command_dismiss();
    $commands[] = ctools_ajax_command_reload();
  }

  print ajax_render($commands);
  ajax_footer();
  drupal_exit();
}
