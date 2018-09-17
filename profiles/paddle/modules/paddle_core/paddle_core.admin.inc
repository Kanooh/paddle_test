<?php

/**
 * @file
 * Administrative page callbacks for Paddle Core module.
 */

/**
 * Form builder for setting the active homepage.
 */
function paddle_core_site_settings_form($form, &$form_state) {
  $form = array();
  $current_language = '';
  $default_language = '';
  $multilingual_enabled = paddle_core_is_multilingual();

  if ($multilingual_enabled) {
    global $language_content;
    $current_language = $language_content->language;
    $default_language = language_default('language');
  }

  if (module_exists('paddle_contextual_toolbar')) {
    $form['#after_build'] = array(
      'paddle_core_add_contextual_actions_site_settings_form',
    );
  }

  $form['website_info'] = array(
    '#type' => 'fieldset',
    '#title' => t('Website info'),
  );
  // Provide a field to change the Site name.
  $form['website_info']['site_name'] = array(
    '#type' => 'textfield',
    '#title' => t('Site name'),
    '#required' => TRUE,
    '#default_value' => $multilingual_enabled ? i18n_variable_get('site_name', $current_language, i18n_variable_get('site_name', $default_language, 'Paddle')) : variable_get('site_name'),
  );

  // Provide a field to change the Site homepage and error page.
  $pages = array(
    'site_frontpage' => t('Active homepage'),
    'site_403' => t('403 (access denied) page'),
    'site_404' => t('404 (not found) page'),
  );
  foreach ($pages as $var_name => $page_title) {
    $page = 'node';
    // Generate user-friendly value in format "Node title (node/1)" from
    // "node/1".
    $variable = $multilingual_enabled ? i18n_variable_get($var_name, $current_language, i18n_variable_get($var_name, $default_language, '')) : variable_get($var_name);

    if (!empty($variable)) {
      $matches = array();
      $result = preg_match('/node\/([0-9]+)/',
        $variable, $matches);
      if ($result > 0) {
        $node = node_load($matches[$result]);
        if ($node) {
          $page = check_plain($node->title) . " (node/$node->nid)";
        }
      }
    }
    $form['website_info'][$var_name] = array(
      '#type' => 'textfield',
      '#title' => $page_title,
      '#required' => TRUE,
      '#default_value' => $page,
      '#size' => 60,
      '#autocomplete_path' => 'paddle_site_settings_autocomplete',
    );
  }

  // Provide a field to change the Site e-mail.
  $form['website_info']['site_mail'] = array(
    '#type' => 'textfield',
    '#title' => t('E-mail address'),
    '#default_value' => $multilingual_enabled ? i18n_variable_get('site_mail', $current_language, i18n_variable_get('site_mail', $default_language, ini_get('sendmail_from'))) : variable_get('site_mail', ini_get('sendmail_from')),
    '#description' => t("The <em>From</em> address in automated e-mails sent during registration and new password requests, and other notifications. (Use an address ending in your site's domain to help prevent this e-mail being flagged as spam.)"),
    '#required' => TRUE,
  );

  $form['social_media_sharing'] = array(
    '#type' => 'fieldset',
    '#title' => t('Social Media Sharing'),
  );

  $form['social_media_sharing']['default_shared_image'] = array(
    '#title' => t('Choose a default image'),
    '#type' => 'paddle_scald_atom',
    '#button_text' => t('Choose an image...'),
    '#default_value' => $multilingual_enabled ? i18n_variable_get('paddle_default_sharing_image', $current_language, i18n_variable_get('paddle_default_sharing_image', $default_language, NULL)) : variable_get('paddle_default_sharing_image', NULL),
    '#allowed_types' => array('image'),
  );

  $form['social_media_sharing']['default_shared_image']['description'] = array(
    '#markup' => t('The image which is used when visitors share a page on social media'),
    '#prefix' => '<div class="content-type-description">',
    '#suffix' => '</div>',
    '#weight' => 10
  );

  $form['maintenance_mode'] = array(
    '#type' => 'fieldset',
    '#title' => t('Maintenance mode'),
  );

  $form['maintenance_mode']['paddle_maintenance_mode'] = array(
    '#type' => 'radios',
    '#title' => t('Enable maintenance mode'),
    '#options' => array(1 => t('Yes'), 0 => t('No')),
    '#default_value' => variable_get('paddle_maintenance_mode', 0),
  );

  if ($multilingual_enabled) {
    $form['maintenance_mode']['paddle_maintenance_mode']['#disabled'] = ($current_language != $default_language) ? TRUE : FALSE;
  }

  $form['maintenance_mode']['paddle_maintenance_mode_message'] = array(
    '#type' => 'textarea',
    '#title' => t('Message to anonymous users'),
    '#default_value' => $multilingual_enabled ? i18n_variable_get('paddle_maintenance_mode_message', $current_language, i18n_variable_get('paddle_maintenance_mode_message', $default_language, t('Behind the scenes we are building a brand new website.<br>Check back soon! Need more info? Paddle to <a href="http://kanooh.be">kanooh.be</a>!'))) : variable_get('paddle_maintenance_mode_message', t('Behind the scenes we are building a brand new website.<br>Check back soon! Need more info? Paddle to <a href="http://kanooh.be">kanooh.be</a>!')),
    '#description' => t("You can use <em>&lt;br&gt;</em> to put a new line in the message. You can also use <em>&lt;a&gt;</em> to add a link, for example <em>&lt;a href=\"http://kanooh.be\"&gt;kanooh.be&lt;a&gt;</em>."),
  );

  $form['no_results_on_search'] = array(
    '#title' => t('No results on search message'),
    '#description' => t('Sets the message which should be displayed when no search results are found after executing a search query.'),
    '#type' => 'text_format',
    '#required' => TRUE,
    '#default_value' => paddle_search_retrieve_no_search_results_message(FALSE),
    '#format' => paddle_search_retrieve_no_search_results_message_format(),
  );
  // No need to show 'plain text' when 'full html' is allowed. Hide text format
  // selection, and related tips.
  $form['no_results_on_search']['better_formats'] = array(
    '#show_selection' => FALSE,
    '#show_tips' => FALSE,
    '#show_tips_link' => FALSE,
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save configuration'),
  );

  return $form;
}

/**
 * Validate callback for the Site Settings form.
 */
function paddle_core_site_settings_form_validate($form, &$form_state) {
  // Validate the e-mail address.
  if ($error = user_validate_mail($form_state['values']['site_mail'])) {
    form_set_error('site_mail', $error);
  }
}

/**
 * Submit callback for the Site Settings form.
 */
function paddle_core_site_settings_form_submit($form, &$form_state) {
  $current_language = '';
  $multilingual_enabled = paddle_core_is_multilingual();

  if ($multilingual_enabled) {
    global $language_content;
    $current_language = $language_content->language;
  }

  $multilingual_enabled ? $old_default_sharing_image = i18n_variable_get('paddle_default_sharing_image', $current_language, variable_get('paddle_default_sharing_image', NULL)) : variable_get('paddle_default_sharing_image', NULL);
  // Save the site name.
  $multilingual_enabled ? i18n_variable_set('site_name', $form_state['values']['site_name'], $current_language) : variable_set('site_name', $form_state['values']['site_name']);

  // Save the autocomplete pages - frontpage and error pages.
  foreach (array('site_frontpage', 'site_403', 'site_404') as $var_name) {
    $page = '';
    $matches = array();
    $result = preg_match('/ \(node\/([0-9]+)\)$/', $form_state['values'][$var_name], $matches);
    if ($result > 0) {
      // It is indeed a value that looks like "node title (node/999)".
      $page = 'node/' . $matches[$result];
    }

    if ($page) {
      $multilingual_enabled ? i18n_variable_set($var_name, $page, $current_language) : variable_set($var_name, $page);
    }
  }

  // Save the site e-mail.
  $multilingual_enabled ? i18n_variable_set('site_mail', $form_state['values']['site_mail'], $current_language) : variable_set('site_mail', $form_state['values']['site_mail']);

  // We need to clear the page cache in order to pick up new access but only if
  // the value has been changed.
  if ($form_state['values']['paddle_maintenance_mode'] !== variable_get('paddle_maintenance_mode', 0) ||
    ($multilingual_enabled && $form_state['values']['paddle_maintenance_mode_message'] !== i18n_variable_get('paddle_maintenance_mode_message', $current_language, '')) ||
    (!$multilingual_enabled && $form_state['values']['paddle_maintenance_mode_message'] !== variable_get('paddle_maintenance_mode_message', ''))
  ) {
    cache_clear_all();
  }
  // Save the maintenance settings. Maintenance mode should not be translatable.
  variable_set('paddle_maintenance_mode', $form_state['values']['paddle_maintenance_mode']);
  $multilingual_enabled ? i18n_variable_set('paddle_maintenance_mode_message', $form_state['values']['paddle_maintenance_mode_message'], $current_language) : variable_set('paddle_maintenance_mode_message', $form_state['values']['paddle_maintenance_mode_message']);

  // Saves the 'no results on search' message.
  if (!empty($form_state['values']['no_results_on_search'])) {
    $no_results_on_search = $form_state['values']['no_results_on_search']['value'];
    $multilingual_enabled ? i18n_variable_set('paddle_no_results_on_search', $no_results_on_search, $current_language) : variable_set('paddle_no_results_on_search', $no_results_on_search);
  }

  // Save the default sharing image.
  $multilingual_enabled ? i18n_variable_set('paddle_default_sharing_image', $form_state['values']['default_shared_image'], $current_language) : variable_set('paddle_default_sharing_image', $form_state['values']['default_shared_image']);

  drupal_set_message(t('The configuration options have been saved.'), 'status');

  // If a new default sharing image has been uploaded or the existing one has
  // been overwritten, all page caches need to be cleared.
  if (!empty($old_default_sharing_image)) {
    if (empty($form_state['values']['default_shared_image']) || $old_default_sharing_image != $form_state['values']['default_shared_image']) {
      cache_clear_all(NULL, 'cache_page');
    }
  }
}

/**
 * After-build of the paddle_core_add_contextual_actions_site_settings_form.
 */
function paddle_core_add_contextual_actions_site_settings_form($form) {
  ctools_include('ajax');
  ctools_include('modal');
  ctools_modal_add_js();
  paddle_contextual_toolbar_add_js('click_delegator');

  $actions = array();

  if (isset($form['submit'])) {
    $actions[] = array(
      'action' => l(t('Save'), '', array(
        'attributes' => array(
          'class' => array('ui-icon', 'fa-floppy-o'),
          'data-paddle-contextual-toolbar-click' => $form['submit']['#id'],
        ),
        'html' => TRUE,
        'external' => TRUE,
      )),
      'class' => array('save'),
      'weight' => 0,
    );
  }

  paddle_contextual_toolbar_actions($actions);

  return $form;
}
