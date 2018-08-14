<?php

/**
 * @file
 * Enables modules and site configuration for a Paddle site installation.
 */

include_once DRUPAL_ROOT . '/includes/password.inc';

/**
 * Implements hook_install_tasks().
 */
function paddle_install_tasks($install_state) {
  $tasks = array(
    'paddle_install_users_form' => array(
      'display_name' => st('Create demo users'),
      'display' => TRUE,
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
      'type' => 'form',
    ),
    'paddle_install_translation_settings_form' => array(
      'display_name' => st('Translations settings'),
      'display' => TRUE,
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
      'type' => 'form',
    ),
    'paddle_install_import_translation' => array(
      'display_name' => st('Import translations'),
      'display' => TRUE,
      'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
      'type' => 'batch',
    ),
    'paddle_install_finish' => array(),
  );

  return $tasks;
}

/**
 * Helper method to do batch processing during updates.
 *
 * @param array $sandbox
 *   Sandbox to store info so it's available on subsequent calls.
 * @param int $limit
 *   Amount of items to retrieve & process at once.
 * @param callback $count_callback
 *   Callback to get the total count of items to process.
 * @param callback $range_retrieval_callback
 *   Callback to get a range of items to process.
 * @param callback $item_update_callback
 *   Callback to update one item.
 * @param callback $progress_message_callback
 *   Callback to generate a progress message.
 *
 * @throws DrupalUpdateException
 *   When no progress is being detected.
 */
function paddle_update_batch_helper(&$sandbox, $limit, $count_callback, $range_retrieval_callback, $item_update_callback, $progress_message_callback) {
  $drush = function_exists('drush_main');

  if (!isset($sandbox['max'])) {

    $max = $count_callback($sandbox);

    $sandbox['progress'] = 0;
    $sandbox['max'] = $max;
    $sandbox['previous_item'] = NULL;
  }

  if ($sandbox['max'] == 0) {
    if ($drush) {
      drush_print('Nothing to update');
    }

    return;
  }

  // Work around an endless loop in drush by calling the update function
  // ourselves rather than using batch processing if the update process is
  // invoked with drush.
  if ($drush) {
    $left_zero_padding_len = strlen($sandbox['max']);

    while ($sandbox['progress'] < $sandbox['max']) {
      $previous_progress = $sandbox['progress'];

      // We pass limit 1 as we want to output a status message for each node
      // that was processed.
      paddle_update_batch_helper_process_range(1, $sandbox, $range_retrieval_callback, $item_update_callback);

      if ($sandbox['progress'] == $previous_progress) {
        throw new DrupalUpdateException('Did not make any progress, possible mistake in the update process.');
      }

      $progress_indicator = str_pad($sandbox['progress'], $left_zero_padding_len, '0', STR_PAD_LEFT) . '/' . $sandbox['max'];

      $status_message = $progress_indicator . ' .... ' . $progress_message_callback($sandbox);

      drush_print($status_message);
    }
  }
  else {
    paddle_update_batch_helper_process_range($limit, $sandbox, $range_retrieval_callback, $item_update_callback);
  }

  $sandbox['#finished'] = empty($sandbox['max']) ? 1 : ($sandbox['progress'] / $sandbox['max']);
}

/**
 * Helper method to process a range of items.
 *
 * @param int $limit
 *   Amount of items to retrieve & process at once.
 * @param array $sandbox
 *   Sandbox to store info so it's available on subsequent calls.
 * @param callback $range_retrieval_callback
 *   Callback to get a range of items to process.
 * @param callback $item_update_callback
 *   Callback to update one item.
 */
function paddle_update_batch_helper_process_range($limit, &$sandbox, $range_retrieval_callback, $item_update_callback) {
  $results = $range_retrieval_callback($limit, $sandbox);

  foreach ($results as $item) {
    $sandbox['last_item_update_status'] = $item_update_callback($item, $sandbox);
    $sandbox['last_item'] = $item;

    $sandbox['progress']++;
  }
}

/**
 * Form builder callback for the translation settings installation step.
 */
function paddle_install_translation_settings_form($form, &$form_state, &$install_state) {
  $form = array();

  $form['import_translations'] = array(
    '#type' => 'checkbox',
    '#default_value' => variable_get('paddle_import_translations', FALSE),
    '#title' => st('Import translations'),
    '#description' => st('Should the Drupal core and module translations be imported? Warning: this can take quite some time.'),
  );

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['save'] = array(
    '#type' => 'submit',
    '#value' => st('Save and continue'),
    '#submit' => array('paddle_install_translation_settings_form_submit'),
  );

  return $form;
}

/**
 * Form submit callback for the translation settings installation step.
 */
function paddle_install_translation_settings_form_submit($form, &$form_state) {
  variable_set('paddle_import_translations', $form_state['values']['import_translations']);
}

/**
 * Install step callback.
 *
 * Cleans up any temporary variables used during installation.
 *
 * @param array $install_state
 *   An array of information about the current installation state.
 */
function paddle_install_finish(&$install_state) {
  variable_del('paddle_import_translations');
}

/**
 * Installation step callback.
 *
 * @param array $install_state
 *   An array of information about the current installation state.
 */
function paddle_install_import_translation(&$install_state) {
  $import_translations = variable_get('paddle_import_translations', FALSE);

  if ($import_translations) {
    // Fetch and batch the translations!
    // Code inspired by l10n_install_import_translation() from the Localized
    // Drupal distribution.
    // @see https://www.drupal.org/project/l10n_install
    module_load_include('fetch.inc', 'l10n_update');
    $options = _l10n_update_default_update_options();
    $last_checked = variable_get('l10n_update_last_check');
    if ($last_checked < REQUEST_TIME - L10N_UPDATE_STATUS_TTL) {
      l10n_update_clear_status();
      $batch = l10n_update_batch_update_build(array(), array(), $options);
    }
    else {
      $batch = l10n_update_batch_fetch_build(array(), array(), $options);
    }
    return $batch;
  }
}

/**
 * Form builder callback to create 1 user per role.
 */
function paddle_install_users_form($form, &$form_state, &$install_state) {
  $form = array();

  $form['add_users'] = array(
    '#type' => 'checkbox',
    '#default_value' => FALSE,
    '#title' => st('Create demo users'),
    '#description' => st('Do you want to create a demo user per role for the paddle distribution? Please note that these users are only intended for testing purposes. These should not be added to production sites.'),
  );

  // Fields for the site manager user.
  $form['site_manager'] = paddle_install_users_generate_form('site_manager');

  // Fields for the chief editor user.
  $form['chief_editor'] = paddle_install_users_generate_form('chief_editor');

  // Fields for the editor user.
  $form['editor'] = paddle_install_users_generate_form('editor');

  // Fields for the read only user.
  $form['read_only'] = paddle_install_users_generate_form('read_only');

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['save'] = array(
    '#type' => 'submit',
    '#value' => st('Save and continue'),
    '#submit' => array('paddle_install_users_form_submit'),
  );

  return $form;
}

/**
 * Form submit callback for the users installation step.
 */
function paddle_install_users_form_submit($form, &$form_state) {
  $values = $form_state['values'];

  if ($values['add_users']) {
    // Create the site manager user.
    paddle_install_user_save('site_manager', $values);

    // Create the chief editor user.
    paddle_install_user_save('chief_editor', $values);

    // Create the editor user.
    paddle_install_user_save('editor', $values);

    // Create the read only user.
    paddle_install_user_save('read_only', $values);
  }
}

/**
 * Generate the form element for a specific user.
 *
 * @param string $machine_name
 *   The machine name of the user we're going to create.
 *
 * @return array
 *   An array containing the username and password fields for the user.
 */
function paddle_install_users_generate_form($machine_name) {
  $name = str_replace('_', ' ', $machine_name);
  return array(
    $machine_name . '_role_username' => array(
      '#type' => 'textfield',
      '#default_value' => $machine_name,
      '#title' => st(ucfirst($name) . ' username'),
      '#description' => st('The username for the ' . $name . ' user.'),
      '#maxlength' => 64,
      '#states' => array(
        'visible' => array(
          ':input[name="add_users"]' => array('checked' => TRUE),
        ),
      ),
    ),
    $machine_name . '_role_password' => array(
      '#type' => 'password',
      '#default_value' => 'demo',
      '#title' => st(ucfirst($name) . ' password'),
      '#description' => st('The password for the ' . $name . ' user. (The password is "demo" by default.)'),
      '#maxlength' => 64,
      '#size' => 15,
      '#states' => array(
        'visible' => array(
          ':input[name="add_users"]' => array('checked' => TRUE),
        ),
      ),
    ),
  );
}

/**
 * Save the user for which we have filled out the username and password fields.
 *
 * @param string $machine_name
 *   The machine name of the user we're going to create.
 * @param array $values
 *   An array containing the values for each user.
 */
function paddle_install_user_save($machine_name, $values) {
  $name = str_replace('_', ' ', $machine_name);
  // Create the site manager user.
  $role = user_role_load_by_name(ucwords($name));
  $pass = !empty($values[$machine_name . '_role_password']) ? $values[$machine_name . '_role_password'] : 'demo';
  $account = new stdClass();
  $account->is_new = TRUE;
  $account->name = !empty($values[$machine_name . '_role_username']) ? $values[$machine_name . '_role_username'] : $machine_name;
  $account->pass = user_hash_password($pass);
  $account->mail = 'foo@example.com';
  $account->init = 'foo@example.com';
  $account->status = TRUE;
  $account->roles = array($role->rid => $role->name);
  $account->timezone = variable_get('date_default_timezone', '');

  user_save($account);
}
