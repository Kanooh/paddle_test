<?php
/**
 * @file
 * Form callbacks for Paddle content manager.
 */

/**
 * Generates a confirmation form array for Paddle modals.
 *
 * @param array $form
 *   The form build array.
 * @param string $question
 *   The question to ask to the user.
 * @param null|string $yes
 *   A caption for the button that confirms the action.
 *   Defaults to t('Confirm').
 * @param null|string $no
 *   A caption for the button that aborts the action.
 *   Defaults to t('Cancel').
 *
 * @return array
 *   The form array.
 *
 * @see confirm_form()
 */
function paddle_content_manager_confirm_form($form, $question, $yes = NULL, $no = NULL) {
  // Always include this file.
  module_load_include('inc', 'paddle_content_manager', 'paddle_content_manager.forms');

  $form['body'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
  );

  $form['body']['message'] = array(
    '#type' => 'item',
    '#markup' => $question,
  );

  $form['buttons'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-buttons')),
  );

  $form['buttons']['confirm'] = array(
    '#type' => 'submit',
    '#value' => $yes ?: t('Confirm'),
  );

  // Can't use "cancel" as a key here, as that would cause the button to be
  // hidden by CSS...
  $form['buttons']['cancelling'] = array(
    '#type' => 'submit',
    '#value' => $no ?: t('Cancel'),
  );

  return $form;
}

/**
 * Form callback to confirm the archiving of a node.
 */
function paddle_content_manager_archive_node_confirm_form($form, &$form_state, $node) {
  // Save the node nid for submit handler usage. We save the node to get a fresh
  // copy in the submit handler to avoid working with an old node.
  // @see paddle_content_manager_workflow_form_validate()
  $form_state['nid'] = $node->nid;

  $question = t('Are you sure you want to archive the node <em>@title</em>?', array('@title' => $node->title));

  return paddle_content_manager_confirm_form($form, $question, t('Archive'));
}

/**
 * Submit handler for the archive node confirm form.
 */
function paddle_content_manager_archive_node_confirm_form_submit($form, &$form_state) {
  $node = workbench_moderation_node_current_load(node_load($form_state['nid']));

  if ($form_state['triggering_element']['#value'] == t('Archive')) {
    // When a node has some scheduling set, remove it when archiving.
    $is_scheduled = !empty($node->publish_on) || !empty($node->unpublish_on) ? TRUE : FALSE;
    if ($is_scheduled) {
      $node->publish_on = 0;
      $node->unpublish_on = 0;
      scheduler_node_delete($node);
      node_save($node);
      // Reload the node.
      $node = workbench_moderation_node_current_load(node_load($form_state['nid']));
    }

    // Check if there is a published revision of the node that is not the
    // current live revision. If so, unpublish it.
    // If the live revision is the one published, workbench_moderation will
    // take care of that already in workbench_moderation_store().
    if (!empty($node->workbench_moderation['published']) && $node->vid != $node->workbench_moderation['published']->vid) {
      // Remove the workbench moderation 'published' property.
      // @see workbench_moderation_moderate()
      db_update('workbench_moderation_node_history')
          ->condition('hid', $node->workbench_moderation['published']->hid)
          ->fields(array('published' => 0))
          ->execute();

      // Unpublish the node and make sure that the entry in the {node} table
      // points to the current revision.
      // @see workbench_moderation_store()
      $live_revision = workbench_moderation_node_current_load($node);
      $live_revision->status = 0;
      $live_revision->revision = 0;
      $live_revision->workbench_moderation['updating_live_revision'] = TRUE;
      node_save($live_revision);
      // Reload the node.
      $node = workbench_moderation_node_current_load(node_load($form_state['nid']));
    }

    workbench_moderation_moderate($node, 'archived');
    $form_state['refresh'] = TRUE;
  }

  // Handle also non-js pages.
  $form_state['redirect'] = 'admin/content_manager/node/' . $node->nid . '/view';
}

/**
 * Form callback to confirm restoring a node from the archive.
 */
function paddle_content_manager_restore_node_confirm_form($form, &$form_state, $node) {
  // Save the node nid for submit handler usage.
  // We save the node it to get a fresh node in the submit handler to avoid
  // working with an old node.
  // @see paddle_content_manager_workflow_form_validate()
  $form_state['nid'] = $node->nid;

  $question = t('Are you sure you want to restore from the archive the node <em>@title</em>?', array('@title' => $node->title));

  return paddle_content_manager_confirm_form($form, $question, t('Restore'));
}

/**
 * Submit handler for the node restore confirm form.
 */
function paddle_content_manager_restore_node_confirm_form_submit($form, &$form_state) {
  $node = node_load($form_state['nid']);

  if ($form_state['triggering_element']['#value'] == t('Restore')) {
    workbench_moderation_moderate($node, 'draft');
    $form_state['refresh'] = TRUE;
  }

  // Handle also non-js pages.
  $form_state['redirect'] = 'admin/content_manager/node/' . $node->nid . '/view';
}
