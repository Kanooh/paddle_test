<?php

/**
 * @file
 * Administration forms for the Paddle Content Manager module.
 */

/**
 * Form constructor for the workflow form.
 *
 * This form provides buttons that allow to change the moderation and
 * publication state of the node that is being viewed in the administrative node
 * view as well as buttons that allow to assign the content to other users.
 */
function paddle_content_manager_workflow_form($form, &$form_state, $node) {
  $form_state['nid'] = $node->nid;

  $form['#tree'] = TRUE;

  // Add buttons for each state transition that the current user is allowed to
  // perform.
  $current_state = $node->workbench_moderation['current']->state;
  $next_states = workbench_moderation_states_next($current_state, NULL, $node);
  foreach ($next_states as $state => $label) {
    $form['moderate'][$state] = array(
      '#type' => 'submit',
      '#value' => $label,
    );

    // Add buttons for each potential assignee.
    foreach (paddle_content_manager_get_assignee_users_for_state($state) as $uid => $username) {
      $form['assign'][$state][$uid] = array(
        '#type' => 'submit',
        '#value' => t('Assign to @username', array('@username' => $username)),
      );
    }
  }

  // Add a button to unpublish the node if the node is published and the current
  // user has the rights to unpublish it. Only add this if the node cannot be
  // unpublished using regular moderation transitions.
  $moderate_to_offline_allowed = array_key_exists('offline', $next_states);
  $user_can_unpublish = user_access('unpublish published content');
  if ($node->status && !$moderate_to_offline_allowed && $user_can_unpublish) {
    $form['moderate']['offline'] = array(
      '#type' => 'submit',
      '#value' => t('Offline'),
    );
  }

  return $form;
}

/**
 * Validate handler for the workflow form.
 */
function paddle_content_manager_workflow_form_validate($form, &$form_state) {
  // Get a freshly loaded node, so we can check if the requested action can
  // still be taken. The node status might have changed between the moment the
  // form was generated and was submitted. Note that this is a rare case, so in
  // regular use the user will never encounter these error messages.
  $node = node_load($form_state['nid']);
  $current_state = $node->workbench_moderation['current']->state;

  // Check if the node is locked.
  if ($lock = content_lock_fetch_lock($node->nid)) {
    // Retrieve the standard message for a locked node.
    $message = content_lock_lock_owner($lock);

    // Inform the user if they have permission to break the lock.
    // @see content_lock_node()
    if (user_access('administer checked out documents')) {
      $url = "admin/content/content_lock/release/$node->nid";
      $token = content_lock_get_release_token($node->nid);
      $options = array(
        'query' => array(
          'token' => $token,
          'destination' => $_GET['q'],
        ),
      );
      $args = array('!here' => l(t('here'), $url, $options));
      $message .= '<br />' . t('Click !here to check back in now.', $args);
    }

    form_set_error($form_state['clicked_button']['#parents'][0], $message);
  }

  switch ($form_state['clicked_button']['#parents'][0]) {
    // Check if the moderation state change that was requested is valid.
    case 'moderate':
      $to_state = $form_state['clicked_button']['#parents'][1];
      if ($to_state == 'offline') {
        // The node may only be unpublished if it is currently published, if
        // the user has permission, and if the unpublication cannot be done
        // using a regular moderation transition to the 'offline' state.
        $user_can_unpublish = user_access('unpublish published content');
        if (!$user_can_unpublish) {
          form_set_error('moderate', t('The page cannot be unpublished.'));
        }
        break;
      }
      elseif (!workbench_moderation_state_allowed(NULL, $current_state, $to_state, $node->type)) {
        form_set_error('moderate', t('The page cannot be moderated to state %to_state.', array('%to_state' => $to_state)));
      }
      break;

    // Check if the node can still be assigned to the user as requested.
    case 'assign':
      $to_state = $form_state['clicked_button']['#parents'][1];
      $assignee = $form_state['clicked_button']['#parents'][2];
      if (!array_key_exists($assignee, paddle_content_manager_get_assignee_users_for_state($to_state))) {
        form_set_error('assign', t('The page cannot be assigned to the user.'));
      }
      break;
  }
}

/**
 * Submit handler for the workflow form.
 */
function paddle_content_manager_workflow_form_submit($form, &$form_state) {
  $node = node_load($form_state['nid']);

  // Load the correct revision so we have the correct panelizer pipeline.
  $node = node_load($node->nid, $node->vid, TRUE);

  switch ($form_state['clicked_button']['#parents'][0]) {
    case 'moderate':
      $new_state = $form_state['clicked_button']['#parents'][1];

      if ($new_state == 'offline' && $node->status) {
        // This is taken from the Workbench Moderation module.
        // @see workbench_moderation_node_unpublish_form_submit()
        // Remove the moderation record's "published" flag.
        $query = db_update('workbench_moderation_node_history')
          ->condition('hid', $node->workbench_moderation['published']->hid)
          ->fields(array('published' => 0))
          ->execute();

        // Make sure the 'current' revision is the 'live' revision -- ie, the
        // revision in {node}.
        $live_revision = workbench_moderation_node_current_load($node);
        $live_revision->status = 0;
        $live_revision->revision = 0;
        $live_revision->workbench_moderation['updating_live_revision'] = TRUE;
        node_save($live_revision);

        if ($node->workbench_moderation['current']->state == 'published') {
          paddle_content_manager_workbench_moderate($node, $new_state);
        }
        drupal_set_message(t('The live revision of this content has been unpublished.'));

      }
      else {
        paddle_content_manager_workbench_moderate($node, $new_state);
      }
      break;

    case 'assign':
      $new_state = $form_state['clicked_button']['#parents'][1];
      $uid = $form_state['clicked_button']['#parents'][2];
      paddle_content_manager_workbench_moderate($node, $new_state, $uid);
      break;
  }
}
