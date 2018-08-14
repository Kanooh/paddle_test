<?php
/**
 * @file
 * Pages of output for iframe exporting
 */

/**
 * Callback for paddle_content_manager/node_iframe/% menu path item.
 *
 * @param string $nid
 *   The node id.
 *
 * @return string
 *   HTML of the rendered node.
 */
function paddle_content_manager_node_iframe_output($nid) {
  // Verify we have a valid node.
  if ($node = node_load($nid)) {
    // Load the most recent revision if the user has rights to view it.
    if (user_access('view moderation history')) {
      $node = workbench_moderation_node_current_load($node);
    }
    // Verify we have access to the node.
    if (node_access('view', $node)) {
      $elements = node_view($node);

      return $elements;
    }
    else {
      drupal_access_denied();
    }
  }
  else {
    drupal_not_found();
  }
}

/**
 * Page callback to create comparisons between revisions.
 *
 * Wraps diff_diffs_show() to fix navigation / state links.
 *
 * @param object $node
 *   Node on which to perform comparison.
 * @param int $old_vid
 *   Version ID of the old revision.
 * @param int $new_vid
 *   Version ID of the new revision.
 * @param null|string $state
 *   The state to use to show diffs.
 */
function paddle_content_manager_diffs_show($node, $old_vid, $new_vid, $state = NULL) {
  // Get the build array from the default callback.
  module_load_include('inc', 'diff', 'diff.pages');
  $build = diff_diffs_show($node, $old_vid, $new_vid, $state);

  $node_revisions = node_revision_list($node);

  // Retrieve the state.
  $default_state = variable_get('diff_default_state_node', 'raw');
  if (empty($state)) {
    $state = $default_state;
  }
  $state = str_replace('-', '_', $state);
  if (!array_key_exists($state, diff_available_states())) {
    $state = $default_state;
  }

  // Generate previous diff/next diff links.
  $next_link = $prev_link = '';
  $nav_suffix = ($default_state != $state) ? '/' . str_replace('_', '-', $state) : '';

  $next_vid = _diff_get_next_vid($node_revisions, $new_vid);
  if ($next_vid) {
    $next_link = l(t('Next difference >'), "node/{$node->nid}/moderation/diff/view/{$new_vid}/{$next_vid}{$nav_suffix}", array('absolute' => 1));
  }

  $prev_vid = _diff_get_previous_vid($node_revisions, $old_vid);
  if ($prev_vid) {
    $prev_link = l(t('< Previous difference'), "node/{$node->nid}/moderation/diff/view/{$prev_vid}/{$old_vid}{$nav_suffix}", array('absolute' => 1));
  }

  // Replace navigation links.
  $build['diff_table']['#rows']['navigation'] = array(
    array(
      'data' => $prev_link,
      'class' => array('diff-prevlink'),
      'colspan' => 2,
    ),
    array(
      'data' => $next_link,
      'class' => array('diff-nextlink'),
      'colspan' => 2,
    ),
  );

  // Change state links only if they are already created. If the array element
  // is not there already, there were none, so skip calculations.
  if (!empty($build['diff_table']['#rows']['states'])) {
    $links = array();
    foreach (diff_available_states('node') as $alternative_state => $label) {
      $state_argument = '';
      if ($alternative_state != $default_state) {
        $state_argument = '/' . str_replace('_', '-', $alternative_state);
      }

      $links[$alternative_state] = array(
        'title' => $label,
        'href' => "node/{$node->nid}/moderation/diff/view/{$old_vid}/{$new_vid}{$state_argument}",
      );
    }
    if (count($links) > 1) {
      $state_links = theme('links', array(
        'links' => $links,
        'attributes' => array('class' => array('links', 'inline')),
      ));
      $build['diff_table']['#rows']['states'] = array(
        array(
          'data' => $state_links,
          'class' => 'diff-links',
          'colspan' => 4,
        ),
      );
    }
  }

  return $build;
}

/**
 * Page callback to redirect diff module diffs to workbench_moderation ones.
 */
function paddle_content_manager_diffs_show_redirect($node, $old_vid, $new_vid, $state = NULL) {
  $path = "node/{$node->nid}/moderation/diff/view/{$old_vid}/{$new_vid}";
  $path .= empty($state) ? '' : '/' . $state;

  drupal_goto($path);
}

/**
 * Page callback to wrap a confirmation form page callback into a modal.
 */
function paddle_content_manager_confirm_form_modal($js, $node, $form_id) {
  // Include the form callback file.
  module_load_include('inc', 'paddle_content_manager', 'paddle_content_manager.forms');

  // Prepare the form state like drupal_get_form() would do.
  $form_state = array(
    'ajax' => $js,
    'build_info' => array(
      'args' => array($node),
    ),
  );

  // If no js, show a normal page.
  if (!$js) {
    return drupal_build_form($form_id, $form_state);
  }

  // Include modal and ajax helpers.
  ctools_include('ajax');
  ctools_include('modal');
  ctools_modal_add_js();

  // Open the form in a modal.
  $commands = ctools_modal_form_wrapper($form_id, $form_state);

  if ($form_state['executed'] && !$form_state['rebuild']) {
    $commands = array();
    $commands[] = ctools_modal_command_dismiss();

    if (!empty($form_state['refresh'])) {
      $commands[] = ctools_ajax_command_reload();
    }
  }

  print ajax_render($commands);
  ajax_footer();
  drupal_exit();
}

/**
 * Menu callback: prompt the user to confirm the clone operation.
 *
 * @see clone_node_check()
 */
function paddle_content_manager_clone_node_check($js, $node) {
  $method = variable_get('clone_method', 'prepopulate');

  switch ($method) {
    case 'save-edit':
      if (variable_get('clone_nodes_without_confirm', FALSE)) {
        $new_nid = clone_node_save($node->nid);
        $options = array();
        if (!empty($_GET['node-clone-destination'])) {
          $options['query']['destination'] = $_GET['node-clone-destination'];
        }
        drupal_goto('node/' . $new_nid . '/edit', $options);
      }
      else {
        // Prepare the form state like drupal_get_form() would do.
        $form_state = array(
          'ajax' => TRUE,
          'build_info' => array(
            'args' => array($node),
          ),
        );

        if (!$js) {
          return drupal_build_form('paddle_content_manager_clone_node_confirm', $form_state);
        }

        // Include modal and ajax helpers.
        ctools_include('ajax');
        ctools_include('modal');
        ctools_modal_add_js();

        $commands = ctools_modal_form_wrapper('paddle_content_manager_clone_node_confirm', $form_state);

        if ($form_state['executed'] && !$form_state['rebuild']) {
          $commands = array();
          $commands[] = ctools_modal_command_dismiss();

          if (!empty($form_state['redirect'])) {
            $commands[] = ctools_ajax_command_redirect($form_state['redirect']);
          }
        }

        print ajax_render($commands);
        ajax_footer();
        drupal_exit();
      }
      break;

    case 'prepopulate':
    default:
      return clone_node_prepopulate($node);
  }
}

/**
 * Confirmation form for the node cloning.
 */
function paddle_content_manager_clone_node_confirm($form, &$form_state, $node) {
  $form['nid'] = array('#type' => 'value', '#value' => $node->nid);

  $form['#attributes']['class'][] = 'confirmation';

  $form['body'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
  );

  $form['body']['description'] = array(
    '#type' => 'item',
    '#markup' => t('Are you sure you want to clone %title?', array('%title' => $node->title)),
  );

  $form['actions'] = array(
    '#type' => 'actions',
    '#attributes' => array('class' => array('form-buttons')),
  );

  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Confirm'),
  );

  $form['actions']['cancelling'] = array(
    '#type' => 'submit',
    '#value' => t('Cancel'),
  );

  form_load_include($form_state, 'inc', 'clone', 'clone.pages');
  form_load_include($form_state, 'inc', 'paddle_content_manager', 'paddle_content_manager.pages');

  return $form;
}

/**
 * Handle confirm clone form submission.
 */
function paddle_content_manager_clone_node_confirm_submit($form, &$form_state) {
  if ($form_state['triggering_element']['#value'] == t('Confirm')) {
    $nid = clone_node_save($form_state['values']['nid']);
    $form_state['redirect'] = 'admin/content_manager/node/' . $nid . '/view';
  }
}
