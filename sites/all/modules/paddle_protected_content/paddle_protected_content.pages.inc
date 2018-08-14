<?php

/**
 * @file
 * Page callbacks for Protected Content paddlet.
 */

/**
 * Page callback to configure a user role.
 *
 * @param string $js
 *   Whether to open the form in a modal or not.
 * @param object $role
 *   The role to edit.
 *
 * @return mixed
 *   The built form.
 */
function paddle_protected_content_role_form_wrapper($js, $role) {
  // Prepare the form state like drupal_get_form() would do.
  $form_state = array(
    'ajax' => $js,
    'build_info' => array(
      'args' => array($role),
    ),
  );

  // If no js, show a normal page.
  if (!$js) {
    return drupal_build_form('paddle_protected_content_role_form', $form_state);
  }

  // Include modal and ajax helpers.
  ctools_include('ajax');
  ctools_include('modal');
  ctools_modal_add_js();

  // Open the form in a modal.
  $commands = ctools_modal_form_wrapper('paddle_protected_content_role_form', $form_state);

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
 * Page callback for the "Delete role" form.
 *
 * @param string $js
 *   Whether to open the form in a modal or not.
 * @param object $role
 *   The role to delete.
 *
 * @return mixed
 *   The built form.
 */
function paddle_protected_content_delete($js, $role) {
  // Prepare the form state like drupal_get_form() would do.
  $form_state = array(
    'ajax' => $js,
    'build_info' => array(
      'args' => array($role),
    ),
  );

  if (!$js) {
    return drupal_build_form('paddle_protected_content_delete_form', $form_state);
  }

  ctools_include('ajax');
  ctools_include('modal');

  // Open the delete form in a modal.
  $commands = ctools_modal_form_wrapper('paddle_protected_content_delete_form', $form_state);

  if (!empty($form_state['completed'])) {
    $commands = array();
    $commands[] = ctools_modal_command_dismiss();
    $commands[] = ctools_ajax_command_reload();
  }

  print ajax_render($commands);
  ajax_footer();
  drupal_exit();
}
