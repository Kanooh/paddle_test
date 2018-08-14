<?php

/**
 * @file
 * Page callbacks for adding, editing, deleting redirects.
 */

/**
 * Page callback for the create/edit redirect form.
 */
function paddle_redirect_configure_redirect($js, $redirect = NULL) {
  module_load_include('inc', 'redirect', 'redirect.admin');
  $form_state = array(
    'ajax' => $js,
    'build_info' => array(
      'args' => array($redirect),
    ),
  );

  if ($js) {
    ctools_include('ajax');
    ctools_include('modal');

    // Open the settings form in a modal.
    $commands = ctools_modal_form_wrapper('redirect_edit_form', $form_state);

    if ($form_state['executed']) {
      $commands = array();
      $commands[] = ctools_modal_command_dismiss();
      $commands[] = ctools_ajax_command_reload();
    }

    // Render all the commands and exit.
    print ajax_render($commands);
    ajax_footer();
    drupal_exit();
  }
  else {
    return drupal_build_form('redirect_edit_form', $form_state);
  }
}

/**
 * Page callback for the "delete redirect" form.
 */
function paddle_redirect_delete_redirect($js, $redirect) {
  $form_state = array(
    'ajax' => $js,
    'redirect' => $redirect,
  );

  if ($js) {
    ctools_include('ajax');
    ctools_include('modal');

    // Open the settings form in a modal.
    $commands = ctools_modal_form_wrapper('paddle_redirect_delete_redirect_form', $form_state);

    if ($form_state['executed']) {
      $commands = array();
      $commands[] = ctools_modal_command_dismiss();
      $commands[] = ctools_ajax_command_reload();
    }

    print ajax_render($commands);
    ajax_footer();
    drupal_exit();
  }
  else {
    return drupal_build_form('paddle_redirect_delete_redirect_form', $form_state);
  }
}

/**
 * Page callback for the import redirect form.
 */
function paddle_redirect_import($js) {
  $form_state = array('ajax' => $js);

  if ($js) {
    ctools_include('ajax');
    ctools_include('modal');

    // Open the import form in a modal.
    $commands = ctools_modal_form_wrapper('paddle_redirect_import_form', $form_state);

    if ($form_state['executed']) {
      $commands = array();
      $commands[] = ctools_modal_command_dismiss();
      $commands[] = ctools_ajax_command_reload();
    }

    // Render all the commands and exit.
    print ajax_render($commands);
    ajax_footer();
    drupal_exit();
  }
  else {
    return drupal_build_form('paddle_redirect_import_form', $form_state);
  }
}

/**
 * The import redirect form.
 */
function paddle_redirect_import_form($form, &$form_state) {
  global $base_url;

  $form = array();

  // Build the url for the example file.
  $template_url = file_create_url(drupal_get_path('module', 'paddle_redirect') . '/example/import_template.csv');

  $form['import_file'] = array(
    '#prefix' => '<div class="form-body">',
    '#type' => 'file',
    '#description' => t('You can import configuration for redirects via a CSV file. Use the <a class="link-highlight" href="@href">import_template.csv</a>', array('@href' => url($template_url))),
  );

  $form['actions'] = array(
    '#type' => 'actions',
    '#attributes' => array(
      'class' => array('form-buttons'),
    ),
    '#prefix' => '</div>',
  );

  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Import'),
  );

  $form['#attributes'] = array('enctype' => "multipart/form-data");

  return $form;
}

/**
 * Form callback for the "delete redirect" form.
 */
function paddle_redirect_delete_redirect_form($form, &$form_state) {
  $redirect = $form_state['redirect'];

  $form['body'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
  );

  $form['body']['message'] = array(
    '#type' => 'item',
    '#markup' => t('Are you sure you want to delete the URL redirect from %source to %redirect?', array('%source' => $redirect->source, '%redirect' => $redirect->redirect)),
  );

  $form['buttons'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-buttons')),
  );

  $form['buttons']['confirm'] = array(
    '#type' => 'submit',
    '#delete' => TRUE,
    '#value' => t('Delete'),
  );

  return $form;
}

/**
 * Validate handler for the import form.
 */
function paddle_redirect_import_form_validate($form, &$form_state) {
  $validators = array('file_validate_extensions' => array('csv'));
  if ($file = file_save_upload('import_file', $validators)) {
    $form_state['uploaded_file'] = $file;
  }
  else {
    form_set_error('form', t('File upload failed.'));
  }
}

/**
 * Submit handler for the import form.
 */
function paddle_redirect_import_form_submit($form, &$form_state) {
  // If no file set, don't do anything.
  if (!isset($form_state['uploaded_file'])) {
    return;
  }

  $result = paddle_redirect_import_read_file($form_state['uploaded_file']->uri, $form_state['values']);

  if ($result['success']) {
    drupal_set_message(implode('<br />', $result['message']));
  }
  else {
    drupal_set_message(implode('<br />', $result['message']), 'error');
  }

  // Delete the file after the import.
  file_delete($form_state['uploaded_file']);
}

/**
 * Function that processes the CSV import file.
 *
 * @return array
 *   The status of the import and messages it generated.
 */
function paddle_redirect_import_read_file($file) {
  if (!$f = fopen($file, 'r')) {
    return array('success' => FALSE, 'message' => array(t('Unable to read the file')));
  }

  $line_no = 0;
  $count = 0;
  $messages = array();
  $success = FALSE;

  // Loop over all lines in the file and save the redirect.
  while ($line = fgetcsv($f, 0, ',')) {
    $message = array();
    $line_no++;

    // We skip the header row.
    if ($line_no != 1) {
      // The line is always an array containing the status code, the source path
      // and the redirect path.
      if (!is_array($line)) {
        $messages[] = t('Line @line_no is invalid.', array('@line_no' => $line_no));
        continue;
      }

      // Source path and redirect path always need to be filled out.
      if (is_array($line) && (empty($line[0]) || empty($line[1]))) {
        $messages[] = t('Line @line_no does not contain a source path or a redirect path.', array('@line_no' => $line_no));
        continue;
      }

      // If no status code is found, we default it to 301.
      if (empty($line[2])) {
        $line[2] = 301;
      }
      else {
        $redirect_options = redirect_status_code_options();

        // Check if other status codes are found in the options.
        if (!array_key_exists($line[2], $redirect_options)) {
          $messages[] = t('Line @line_no contains an invalid status code so used the default 301 code.', array('@line_no' => $line_no));
          $line[2] = 301;
        }
      }

      $source_parts = redirect_parse_url($line[0]);
      $data = array(
        'line_no' => $line_no,
        'source' => $source_parts['url'],
        'redirect' => isset($line[1]) ? $line[1] : NULL,
        'status_code' => $line[2],
        'override' => FALSE,
      );

      if (!empty($source_parts['query'])) {
        $data['source_options']['query'] = $source_parts['query'];
      }

      // Save the data.
      $insert_row = paddle_redirect_import_save_data($data);
      if (!$insert_row['success']) {
        $messages[] = $insert_row['message'];
      }
      else {
        $count++;
      }
    }
  }

  fclose($f);
  if ($count > 0) {
    $messages[] = t('The redirects have been imported.');
    $messages[] = t('@count row(s) imported.', array('@count' => $count));
    $success = TRUE;
  }

  return array('success' => $success, 'message' => $messages);
}

/**
 * Function for save data from .csv file into the database.
 */
function paddle_redirect_import_save_data($data) {
  $redirect = (object) $data;
  if ($redirect->redirect != '<front>') {
    $parts = redirect_parse_url($redirect->redirect);
    if (!empty($parts['query'])) {
      $redirect->redirect_options['query'] = $parts['query'];
    }

    if (!empty($parts['scheme']) && $parts['scheme'] == 'https') {
      $redirect->redirect_options['https'] = TRUE;
    }

    if (!url_is_external($parts['url'])) {
      if (drupal_lookup_path('source', $parts['url']) || drupal_valid_path($parts['url']) || is_file($parts['url'])) {
        $redirect->redirect = drupal_get_normal_path($parts['url']);
      }
    }
    else {
      $redirect->redirect = $parts['url'];
    }
  }

  redirect_object_prepare($redirect);
  redirect_hash($redirect);
  $existing = redirect_load_by_hash($redirect->hash);
  if ($existing && $redirect->override) {
    $query = isset($redirect->source_options['query']) ? $redirect->source_options['query'] : array();
    $rid = redirect_load_by_source($redirect->source, LANGUAGE_NONE, $query);
    $redirect->rid = $rid->rid;
  }

  if ($existing && !$redirect->override) {
    return array(
      'success' => FALSE,
      'message' => filter_xss(t('Line @line_no: The source "@source" is already being redirected.', array(
        '@line_no' => $data['line_no'],
        '@source' => $data['source'],
      ))),
    );
  }
  else {
    redirect_save($redirect);
  }

  return array('success' => TRUE);
}

/**
 * Form submit for the "delete redirect" form.
 */
function paddle_redirect_delete_redirect_form_submit($form, &$form_state) {
  if ($form_state['clicked_button']['#delete']) {
    redirect_delete($form_state['redirect']->rid);
    drupal_set_message(t('The redirect has been deleted.'));
    $form_state['refresh'] = TRUE;
  }
}
