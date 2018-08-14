<?php
/**
 * @file
 * Page callbacks for Formbuilder paddlet.
 */

/**
 * Page callback wrapper around webform submission results page.
 */
function paddle_formbuilder_results_submissions($node, $user_filter, $pager_count) {
  module_load_include('inc', 'webform', 'includes/webform.report');
  $build = webform_results_submissions($node, $user_filter, $pager_count);

  // Add support for contextual bar.
  if (module_exists('paddle_contextual_toolbar')) {
    // Add the back button.
    $action_link = l(
      t('Back'),
      'admin/content_manager/node/' . $node->nid . '/view',
      array(
        'attributes' => array(
          'class' => array('ui-icon', 'fa-arrow-left'),
        ),
      )
    );
    $actions[] = array(
      'action' => $action_link,
      'class' => array('cancel'),
      'weight' => 0,
    );

    paddle_contextual_toolbar_actions($actions);
  }

  return $build;
}

/**
 * Page callback wrapper around webform submission page.
 */
function paddle_formbuilder_submission_page($node, $submission, $format) {
  // Save the original menu item as webform changes it with
  // webform_set_breadcrumb(), altering the href and preventing the management
  // menu to be shown.
  $original_item = menu_get_item();

  // Include the webform file and take the original content of the page.
  module_load_include('inc', 'webform', 'includes/webform.submissions');
  $build = webform_submission_page($node, $submission, $format);

  // Restore the original menu item.
  menu_set_item(NULL, $original_item);

  // Add support for contextual bar.
  if (module_exists('paddle_contextual_toolbar')) {
    $actions = paddle_contextual_toolbar_actions();

    // Add the back button.
    $action_link = l(
      t('Back'),
      'admin/content_manager/node/' . $node->nid . '/submissions',
      array(
        'attributes' => array(
          'class' => array('ui-icon', 'fa-arrow-left'),
        ),
      )
    );
    $actions[] = array(
      'action' => $action_link,
      'class' => array('cancel'),
      'weight' => 0,
    );

    paddle_contextual_toolbar_actions($actions);
  }

  return $build;
}

/**
 * Page callback that wraps submission forms of the webform module.
 */
function paddle_formbuilder_submission_form_wrapper() {
  // Save the original menu item as webform changes the href.
  $original_item = menu_get_item();

  // Get the original form.
  $args = func_get_args();
  $form = call_user_func_array('drupal_get_form', $args);

  // Restore the original menu item.
  menu_set_item(NULL, $original_item);

  return $form;
}
