<?php
/**
 * @file
 * Form callback for Paddlet formbuilder module.
 */

/**
 * Form callback to configure settings for a webform.
 */
function paddle_formbuilder_configure_webform($form, &$form_state, $node) {
  global $user;
  form_load_include($form_state, 'inc', 'webform', 'includes/webform.pages');

  // Include the core ajax library which is needed for the CKEditor on the page.
  drupal_add_library('system', 'drupal.ajax');

  // Add the Ctools Javascript to make sure Paddle Scald plugins for CKEditor
  // can load.
  ctools_include('ajax');
  ctools_add_js('ajax-responder');
  ctools_include('modal');
  ctools_modal_add_js();

  // Get all the form elements from the original form.
  $form = webform_configure_form($form, $form_state, $node);

  // The submit handlers are already added by the webform callback, so we
  // need to add only the validate one.
  $form['#validate'][] = 'webform_configure_form_validate';

  // Hide some unwanted form elements.
  $form['role_control']['#access'] = FALSE;
  $form['preview']['#access'] = FALSE;
  $form['advanced']['#access'] = FALSE;

  // Always start with open fieldsets.
  $form['progressbar']['#collapsed'] = FALSE;

  // Move the submit button label inside the submission fieldset.
  $form['submission']['submit_text'] = $form['advanced']['submit_text'];
  unset($form['advanced']['submit_text']);
  // Move it at the top.
  $form['submission']['submit_text']['#weight'] = -99;

  // The shield submissions setting can only be set by Site Managers or admins.
  if (user_access('manage paddle formbuilder permissions')) {
    $form['submission']['shield_submissions'] = array(
      '#type' => 'checkbox',
      '#title' => t('Shield submissions'),
      '#default_value' => !empty($form['#node']->webform['shield_submissions']) ? 1 : 0,
      '#description' => t('If you check this, editors and chief editors can not longer access or download submissions.'),
      '#parents' => array('shield_submissions'),
    );

    // Our own submit handler needs to run right after
    // webform_configure_form_submit().
    array_splice($form['#submit'], 0, 1, array(
      'paddle_formbuilder_webform_configure_form_submit',
      'webform_configure_form_submit',
    ));
  }

  // Add an after build handler.
  $form['#after_build'][] = 'paddle_formbuilder_configure_webform_after_build';

  // Add our submit handler to go back to administrative page.
  $form['#submit'][] = 'paddle_formbuilder_goto_view';

  // Set the value for the next_serial field to prevent errors.
  $form_state['values']['next_serial'] = $node->webform['next_serial'];

  return $form;
}

/**
 * Submit handler to set the shield_submissions.
 */
function paddle_formbuilder_webform_configure_form_submit($form, &$form_state) {
  // Edit the node by reference just to shorten it up.
  $node = &$form['#node'];

  $node->webform['shield_submissions'] = $form_state['values']['shield_submissions'];
}

/**
 * Form callback to set emails notification for a formbuilder.
 */
function paddle_formbuilder_webform_emails($form, &$form_state, $node) {
  form_load_include($form_state, 'inc', 'webform', 'includes/webform.emails');

  // Get all the form elements from the original form.
  $form = webform_emails_form($form, $form_state, $node);

  // Add back the handlers that form API would pick on the original form.
  $form['#submit'][] = 'webform_emails_form_submit';
  $form['#theme'] = 'webform_emails_form';

  // Add the contextual toolbar on the form page.
  if (module_exists('paddle_contextual_toolbar')) {
    $form['#after_build'][] = 'paddle_formbuilder_webform_emails_add_contextual_toolbar';

    // Hide the add button. It is not needed if there is a contextual toolbar.
    $form['actions']['add_button']['#attributes']['class'][] = 'hidden';
  }

  return $form;
}

/**
 * After build handler to add contextual toolbar on the configure webform form.
 */
function paddle_formbuilder_configure_webform_after_build($form, &$form_state) {
  if (module_exists('paddle_contextual_toolbar')) {
    // Hide the save button. It is not needed if there is a contextual toolbar.
    $form['actions']['submit']['#attributes']['class'][] = 'hidden';
    // The cancel button is not needed on the configuration form.
    unset($form['actions']['cancel']);

    paddle_contextual_toolbar_add_js('click_delegator');

    // Add the back button.
    $action_link = l(
      t('Back'),
      'admin/content_manager/node/' . $form['nid']['#value'] . '/view',
      array(
        'attributes' => array(
          'class' => array('ui-icon', 'fa-arrow-left'),
        ),
      )
    );
    $actions[] = array(
      'action' => $action_link,
      'class' => array('cancel', 'cancel-js'),
      'weight' => 0,
    );

    // Add the save button.
    $actions[] = array(
      'action' => l(t('Save'), '', array(
        'attributes' => array(
          'data-paddle-contextual-toolbar-click' => 'edit-submit',
          'class' => array('ui-icon', 'fa-check'),
        ),
      )),
      'class' => array('save'),
      'weight' => 0,
    );

    paddle_contextual_toolbar_actions($actions);
  }

  // Users are not allowed to access the format of the confirmation message.
  $form['submission']['confirmation']['format']['#access'] = FALSE;

  return $form;
}

/**
 * After build handler to add contextual toolbar on the webform emails form.
 */
function paddle_formbuilder_webform_emails_add_contextual_toolbar($form, &$form_state) {
  paddle_contextual_toolbar_add_js('click_delegator');

  // Add the back button.
  $action_link = l(
    t('Back'),
    'admin/content_manager/node/' . $form['#node']->nid . '/view',
    array(
      'attributes' => array(
        'class' => array('ui-icon', 'fa-arrow-left'),
      ),
    )
  );
  $actions[] = array(
    'action' => $action_link,
    'class' => array('cancel', 'cancel-js'),
    'weight' => 0,
  );

  // Add the "add" button.
  $actions[] = array(
    'action' => l(t('Add'), '', array(
      'attributes' => array(
        'data-paddle-contextual-toolbar-click' => 'edit-actions-add-button',
        'class' => array('ui-icon', 'fa-plus'),
      ),
    )),
    'class' => array('add'),
    'weight' => 0,
  );

  paddle_contextual_toolbar_actions($actions);

  return $form;
}

/**
 * Form callback to download the results of a webform.
 */
function paddle_formbuilder_webform_results_download($form, &$form_state, $node) {
  form_load_include($form_state, 'inc', 'webform', 'includes/webform.report');

  // Get all the form elements from the original form.
  $form = webform_results_download_form($form, $form_state, $node);

  // Add the handlers back that form API would pick on the original form.
  $form['#submit'][] = 'webform_results_download_form_submit';

  // Add the contextual toolbar on the form page.
  if (module_exists('paddle_contextual_toolbar')) {
    $form['#after_build'][] = 'paddle_formbuilder_webform_download_add_contextual_toolbar';

    // Hide the add button. It is not needed if there is a contextual toolbar.
    $form['actions']['submit']['#attributes']['class'][] = 'hidden';
  }

  return $form;
}

/**
 * After build handler to add contextual toolbar on the webform download form.
 */
function paddle_formbuilder_webform_download_add_contextual_toolbar($form, &$form_state) {
  paddle_contextual_toolbar_add_js('click_delegator');

  // Add the back button.
  $action_link = l(
    t('Back'),
    'admin/content_manager/node/' . $form['node']['#value']->nid . '/view',
    array(
      'attributes' => array(
        'class' => array('ui-icon', 'fa-arrow-left'),
      ),
    )
  );
  $actions[] = array(
    'action' => $action_link,
    'class' => array('cancel', 'cancel-js'),
    'weight' => 0,
  );

  // Add the "download" button.
  $actions[] = array(
    'action' => l(t('Download'), '', array(
      'attributes' => array(
        'data-paddle-contextual-toolbar-click' => 'edit-submit',
        'class' => array('ui-icon', 'fa-download'),
      ),
    )),
    'class' => array('download'),
    'weight' => 0,
  );

  paddle_contextual_toolbar_actions($actions);

  return $form;
}
