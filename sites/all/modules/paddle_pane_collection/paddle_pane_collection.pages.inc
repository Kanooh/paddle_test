<?php
/**
 * @file
 * Page callbacks for Pane Collection app.
 */

/**
 * Page callback to configure a pane collection.
 *
 * @param string $js
 *   Whether to open the form in a modal or not.
 * @param object $pane_collection
 *   The pane collection to edit.
 *
 * @return mixed
 *   The built form.
 */
function paddle_pane_collection_form_wrapper($js, $pane_collection = NULL) {
  // Include the form callback file.
  module_load_include('inc', 'paddle_pane_collection', 'paddle_pane_collection.forms');

  // Prepare the form state like drupal_get_form() would do.
  $form_state = array(
    'ajax' => $js,
    'build_info' => array(
      'args' => array($pane_collection),
    ),
  );

  // If no js, show a normal page.
  if (!$js) {
    return drupal_build_form('paddle_pane_collection_form', $form_state);
  }

  // Include modal and ajax helpers.
  ctools_include('ajax');
  ctools_include('modal');
  ctools_modal_add_js();

  // Open the form in a modal.
  $commands = ctools_modal_form_wrapper('paddle_pane_collection_form', $form_state);

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
 * Page callback for the "Delete Pane Collection" form.
 */
function paddle_pane_collection_form_delete_wrapper($js, $pane_collection) {
  // Include the form callback file.
  module_load_include('inc', 'paddle_pane_collection', 'paddle_pane_collection.forms');

  // Prepare the form state like drupal_get_form() would do.
  $form_state = array(
    'ajax' => $js,
    'build_info' => array(
      'args' => array($pane_collection),
    ),
  );

  if (!$js) {
    return drupal_build_form('paddle_pane_collection_delete_form', $form_state);
  }

  ctools_include('ajax');
  ctools_include('modal');

  // Open the delete form in a modal.
  $commands = ctools_modal_form_wrapper('paddle_pane_collection_delete_form', $form_state);

  if (!empty($form_state['completed'])) {
    $commands = array();
    $commands[] = ctools_modal_command_dismiss();
    $commands[] = ctools_ajax_command_reload();
  }

  print ajax_render($commands);
  ajax_footer();
  drupal_exit();
}
