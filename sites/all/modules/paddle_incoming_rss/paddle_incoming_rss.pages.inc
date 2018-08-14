<?php
/**
 * @file
 * Page callbacks for Incoming RSS paddlet.
 */

/**
 * Page callback to configure a feed entry.
 */
function paddle_incoming_rss_feed_form_wrapper($js, $feed) {
  // Include the form callback file.
  module_load_include('inc', 'paddle_incoming_rss', 'paddle_incoming_rss.forms');

  // Prepare the form state like drupal_get_form() would do.
  $form_state = array(
    'ajax' => $js,
    'build_info' => array(
      'args' => array($feed),
    ),
  );

  // If no js, show a normal page.
  if (!$js) {
    return drupal_build_form('paddle_incoming_rss_feed_form', $form_state);
  }

  // Include modal and ajax helpers.
  ctools_include('ajax');
  ctools_include('modal');
  ctools_modal_add_js();

  // Open the form in a modal.
  $commands = ctools_modal_form_wrapper('paddle_incoming_rss_feed_form', $form_state);

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
 * Page callback for the "Delete Incoming RSS feed" form.
 */
function paddle_incoming_rss_feed_delete($js, $feed) {
  $form_state = array('ajax' => $js, 'feed_id' => $feed->feed_id);

  if (!$js) {
    return drupal_build_form('paddle_incoming_rss_feed_delete_form', $form_state);
  }

  ctools_include('ajax');
  ctools_include('modal');

  // Open the delete form in a modal.
  $commands = ctools_modal_form_wrapper('paddle_incoming_rss_feed_delete_form', $form_state);

  if (!empty($form_state['completed'])) {
    $commands = array();
    $commands[] = ctools_modal_command_dismiss();
    $commands[] = ctools_ajax_command_reload();
  }

  print ajax_render($commands);
  ajax_footer();
  drupal_exit();
}

/**
 * Form callback for the "Delete Incoming RSS feed" form.
 */
function paddle_incoming_rss_feed_delete_form($form, &$form_state) {
  $feed = entity_load_single('paddle_incoming_rss_feed', $form_state['feed_id']);

  $form['body'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
  );

  $form['body']['message'] = array(
    '#type' => 'item',
    '#markup' => t('Are you sure you want to delete the RSS feed %title?', array('%title' => $feed->title)),
  );

  $form['buttons'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-buttons')),
  );

  $form['buttons']['confirm'] = array(
    '#type' => 'submit',
    '#value' => t('Delete'),
  );

  // Can't use "cancel" as a key here, as that would cause the button to be
  // hidden by CSS...
  $form['buttons']['cancelling'] = array(
    '#type' => 'submit',
    '#value' => t('Cancel'),
  );

  return $form;
}

/**
 * Form submit for the "delete RSS feed" form.
 */
function paddle_incoming_rss_feed_delete_form_submit($form, &$form_state) {
  $form_state['completed'] = TRUE;
  $form_state['refresh'] = FALSE;

  if ($form_state['clicked_button']['#value'] == t('Delete')) {
    // Delete the Feeds source first.
    $source = feeds_source('paddle_incoming_rss', $form_state['feed_id']);
    $source->delete();

    // Now delete our entity,
    entity_delete('paddle_incoming_rss_feed', $form_state['feed_id']);
    $form_state['refresh'] = TRUE;
    drupal_set_message(t('Incoming RSS deleted.'));
  }
  $form_state['redirect'] = 'admin/paddlet_store/app/paddle_incoming_rss/configure';
}
