<?php

/**
 * @file
 * Admin page callbacks for the onelogin_saml module.
 */

/**
 * Menu callback: displays the onelogin_saml module settings page.
 *
 * @ingroup forms
 *
 * @see onelogin_saml_form_validate()
 */
/**
 * Configuration menu of OneLogin SAML
 */
function onelogin_saml_form() {

  $form = array();

  $form['saml_onelogin'] = array(
    '#type' => 'fieldset',
    '#title' => t('ONELOGIN SAML/SSO SETTINGS')    
  );

  $form['saml_onelogin']['info'] = array(
    '#markup' => t('Configure your SAML Service Provider below. Once configured, you can access the metadata <a target="_blank" href="'.url('onelogin_saml/metadata', array('absolute' => true)).'">here</a>.<br>Further information for OneLogin customers can be found <a target="_blank" href="https://onelogin.zendesk.com/hc/en-us/articles/201173604-Configuring-SAML-for-Drupal">here</a>.')
  );

  // IDENTITY PROVIDER SETTINGS
  $form['saml_idp'] = array(
    '#type' => 'fieldset',
    '#title' => t('IDENTITY PROVIDER SETTINGS')
  );

  $form['saml_idp']['info'] = array(
    '#markup' => t('<p>Add information regarding your IdP.</p>')
  );

  $form['saml_idp']['saml_idp_entityid'] = array(
    '#type' => 'textfield',
    '#title' => t('IdP Entity Id'),
    '#default_value' => variable_get('saml_idp_entityid', ''),
    '#description' => t('Identifier of the IdP entity. ("Issuer URL")'),
    '#required' => TRUE
  );

  $form['saml_idp']['saml_idp_sso'] = array(
    '#type' => 'textfield',
    '#title' => t('Single Sign On Service Url'),
    '#default_value' => variable_get('saml_idp_sso', ''),
    '#description' => t('URL target of the IdP where the SP will send the Authentication Request. If your IdP has multiple URL targets, the one that uses the HTTP Redirect Binding should be used here. ("SAML 2.0 Endpoint (HTTP)")'),
    '#required' => TRUE
  );

  $form['saml_idp']['saml_options_slo'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Single Log Out</strong>'),
    '#default_value' => variable_get('saml_options_slo', FALSE),
    '#description' => t('Enable SAML Single Log Out. SLO is complex functionality. The most common SLO implementation is based on front-channel (redirections). Sometimes if the SLO workflow fails, a user can be blocked in an unhandled view. Unless you have a strong grasp of SLO it is recommended that you leave it disabled. If enabled, enter the IdP\'s SLO target URL below.'),
    '#required' => FALSE
  );

  $form['saml_idp']['saml_idp_slo'] = array(
    '#type' => 'textfield',
    '#title' => t('Single Log Out Service Url'),
    '#default_value' => variable_get('saml_idp_slo', ''),
    '#description' => t('URL target for the IdP where the SP will send the SLO Request. ("SLO Endpoint (HTTP)")'),
    '#required' => FALSE
  );

  $form['saml_idp']['saml_logout_link'] = array(
    '#type' => 'textfield',
    '#title' => t('Logout Redirect'),
    '#default_value' => variable_get('saml_logout_link', ''),
    '#description' => t('If Single Log Out is not used, you can choose to redirect a SAML user after they are logged out of Drupal. Some use this to redirect to an IdP logout page, a Central Authentication Service (CAS) logout page, or a custom page warning the user to close their browser to end their SSO session. This only affects users who have logged in via SAML.'),
    '#required' => FALSE
  );

  $form['saml_idp']['saml_idp_x509cert'] = array(
    '#type' => 'textarea',
    '#title' => t('X.509 Certificate'),
    '#default_value' => variable_get('saml_idp_x509cert', ''),
    '#description' => t('Public x509 certificate of the IdP. The full certificate (including -----BEGIN CERTIFICATE----- and -----END CERTIFICATE-----) is required. ("X.509 certificate")'),
    '#required' => TRUE
  );

  // OPTIONS
  $form['saml_options'] = array(
    '#type' => 'fieldset',
    '#title' => t('OPTIONS')
  );

  $form['saml_options']['info'] = array(
    '#markup' => t('<p>In this section the behavior of the plugin is set.</p>')
  );

  $form['saml_options']['saml_options_autocreate'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Create user if not exists</strong>'),
    '#default_value' => variable_get('saml_options_autocreate', FALSE),
    '#description' => t('<p>Auto-provisioning. If user not exists, Drupal will create a new user with the data provided by the IdP.</p><p>Review the Mapping section.</p>'),
    '#required' => FALSE
  );

  $form['saml_options']['saml_options_username_from_email'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Get username from email address</strong>'),
    '#default_value' => variable_get('saml_options_username_from_email', FALSE),
    '#description' => t('<p>Use everything in front of the @ in the email address as the username. This may be useful if you are only sending an email address in your SAML response, but you want to auto-provision accounts which requires a username and email address.</p>'),
    '#required' => FALSE
  );

  $form['saml_options']['saml_options_syncroles'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Sync roles</strong>'),
    '#default_value' => variable_get('saml_options_syncroles', FALSE),
    '#description' => t('<p>Auto-sync. The role of the Drupal user account will be synchronized with the data provided by the IdP.</p><p>Review the Mapping section.</p>'),
    '#required' => FALSE
  );

  $form['saml_options']['saml_options_saml_link'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>SAML link</strong>'),
    '#default_value' => variable_get('saml_options_saml_link', FALSE),
    '#description' => t('<p>Show or not a SAML link to execute a SP-initiated SSO in the login page</p>'),
    '#required' => FALSE
  );

  $form['saml_options']['saml_options_account_matcher'] = array(
    '#type' => 'select',
    '#title' => t('Match Drupal account by'),
    '#default_value' => variable_get('saml_options_account_matcher', 'username'),
    '#options' => array('username', 'email'),
    '#description' => t('Select what field will be used in order to find the user account. If you select the \'email\' fieldname remember to prevent that the user is able to change his mail in his profile.')
  );

  // ATTRIBUTE MAPPING
  $form['saml_attr_mapping'] = array(
    '#type' => 'fieldset',
    '#title' => t('ATTRIBUTE MAPPING')
  );

  $form['saml_attr_mapping']['info'] = array(
    '#markup' => t('<p>Sometimes the names of the attributes sent by the IdP not match the names used by Drupal for the user accounts. In this section we can set the mapping between IdP fields and Drupal fields. Notice that this mapping could be also set at Onelogin\'s IdP.</p>')
  );

  $form['saml_attr_mapping']['saml_attr_mapping_username'] = array(
    '#type' => 'textfield',
    '#title' => t('Username'),
    '#default_value' => variable_get('saml_attr_mapping_username', ''),
    '#required' => TRUE,
    '#description' => t("Be sure that usernames at the IdP don't contain punctuation (periods, hyphens, apostrophes, and underscores are allowed)")
  );

  $form['saml_attr_mapping']['saml_attr_mapping_email'] = array(
    '#type' => 'textfield',
    '#title' => t('E-mail'),
    '#default_value' => variable_get('saml_attr_mapping_email', ''),
    '#required' => TRUE
  );

  $form['saml_attr_mapping']['saml_attr_mapping_role'] = array(
    '#type' => 'textfield',
    '#title' => t('Role'),
    '#default_value' => variable_get('saml_attr_mapping_role', ''),
    '#required' => FALSE
  );

  // ROLE MAPPING
  $form['saml_role_mapping'] = array(
    '#type' => 'fieldset',
    '#title' => t('ROLE MAPPING')
  );

  $form['saml_role_mapping']['info'] = array(
    '#markup' => t('<p>The IdP can use it\'s own roles. Set in this section the mapping between IdP and Drupal roles. Accepts multiple valued comma separated. Example: admin,owner,superuser.</p>')
  );

  $form['saml_role_mapping']['saml_role_mapping_administrator'] = array(
    '#type' => 'textfield',
    '#title' => t('Administrator'),
    '#default_value' => variable_get('saml_role_mapping_administrator', ''),
    '#required' => FALSE
  );

  // USER EXPERIENCE
  $form['saml_user_experience'] = array(
    '#type' => 'fieldset',
    '#title' => t('USER EXPERIENCE')
  );

  $form['saml_user_experience']['info'] = array(
    '#markup' => t('<p>When implementing SSO, our users may become confused with menus and links that allow them to manage a local Drupal password or request a new account. These options allow you to customize the experience for SAML users with the hopes of avoiding some of the confusion.</p>')
  );

  $form['saml_user_experience']['saml_options_current_pass_disabled'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Disable current password field on user profile page.</strong>'),
    '#default_value' => variable_get('saml_options_current_pass_disabled', FALSE),
    '#description' => t('<p>You may wish to limit a user from creating and managing a Drupal password. The user profile form includes a current password field that is required as validation in order to update certain user profile fields (such as email address). If the user does not have a Drupal password, this will get in the way. This option disables the field for users who have logged in via SAML. Users with the Administrator role are exempt.</p>'),
    '#required' => FALSE
  );

  $form['saml_user_experience']['saml_options_password_tab_disabled'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Disable user password tab and related page.</strong>'),
    '#default_value' => variable_get('saml_options_password_tab_disabled', FALSE),
    '#description' => t('<p>You may wish to limit a user from creating and managing a Drupal password. This option disables the menu tabs associated with the user password page. This option disables the password page for users who have logged in via SAML. Users with the Administrator role are exempt.</p>'),
    '#required' => FALSE
  );

  $form['saml_user_experience']['saml_create_new_account'] = array(
    '#type' => 'textfield',
    '#title' => t('Customize the Create new account link.'),
    '#default_value' => variable_get('saml_create_new_account', ''),
    '#description' => t('Depending on your Drupal implementation, you may allow requests for new accounts from the Drupal login page. Rather than using Drupal\'s request form, you can direct users to your company\'s account request form.'),
    '#required' => FALSE
  );

  $form['saml_user_experience']['saml_request_new_password'] = array(
    '#type' => 'textfield',
    '#title' => t('Customize the Request new password link.'),
    '#default_value' => variable_get('saml_request_new_password', ''),
    '#description' => t('If you have enabled the Request new password link in Drupal, a SSO user could click the link and go through the process believing that their SSO account password is being changed. In reality this would only change their local Drupal password. To avoid this confusion you can direct users to your company\'s password management system.'),
    '#required' => FALSE
  );

  // ADVANCED SETTINGS
  $form['saml_advanced_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('ADVANCED SETTINGS')
  );

  $form['saml_advanced_settings']['saml_advanced_settings_debug'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Debug Mode</strong>'),
    '#default_value' => variable_get('saml_advanced_settings_debug', FALSE),
    '#description' => t('Enable it when you are debugging the SAML workflow. Errors and Warnigs will be showed.'),
    '#required' => FALSE
  );

  $form['saml_advanced_settings']['saml_advanced_settings_strict_mode'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Strict Mode</strong>'),
    '#default_value' => variable_get('saml_advanced_settings_strict_mode', FALSE),
    '#description' => t('If Strict mode is Enabled, then Drupal will reject unsigned or unencrypted messages if it expects them signed or encrypted. Also it will reject the messages if they do not strictly follow the SAML standard: Destination, NameId, Conditions ... are validated too.'),
    '#required' => FALSE
  );

  $form['saml_advanced_settings']['saml_advanced_settings_sp_entity_id'] = array(
    '#type' => 'textfield',
    '#title' => t('Service Provider Entity Id'),
    '#default_value' => variable_get('saml_advanced_settings_sp_entity_id', 'php-saml'),
    '#description' => t('Set the Entity ID for the Service Provider. If not provided, \'php-saml\' will be used.'),
    '#required' => FALSE
  );

  $form['saml_advanced_settings']['saml_advanced_settings_nameid_format'] = array(
    '#type' => 'textfield',
    '#title' => t('NameId Format'),
    '#default_value' => variable_get('saml_advanced_settings_nameid_format', 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress'),
    '#description' => t('Set the NameId format that the Service Provider and Identity Provider will use. If not provided, \'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress\' will be used.'),
    '#required' => FALSE
  );

  // SIGNING/ENCRYPTION SETTINGS
  $form['saml_signing_encryption_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('SIGNING/ENCRYPTION SETTINGS')
  );

  $form['saml_signing_encryption_settings']['info'] = array(
    '#markup' => t('<p>If signing/encryption is enabled, then a x509 cert and private key for the SP must be provided. There are two ways to supply the certificate and key:</p><p>1. Store them as files named sp.key and sp.crt in the \'certs\' folder of this Drupal module (be sure that the folder is protected and not exposed to the Internet).<br>2. Paste the certificate and key text in the corresponding textareas (review any database security issues as to limit the exposure of the key).</p><p><strong>Please be aware: if you encrypt the entire SAML Assertion, this module will not be able to decrypt attributes. Much of the functionality of this module depends on attributes (auto-provisioning, role sync, etc.). If you can live without encrypting the entire SAML Assertion, your attributes will work and additional security can be implemented by encrypting the NameId and enforcing signed requests/responses.</strong></p>')
  );

  $form['saml_signing_encryption_settings']['saml_advanced_settings_nameid_encrypted'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Encrypt nameID</strong>'),
    '#default_value' => variable_get('saml_advanced_settings_nameid_encrypted', FALSE),
    '#description' => t('The nameID sent by this SP will be encrypted.'),
    '#required' => FALSE
  );

  $form['saml_signing_encryption_settings']['saml_advanced_settings_authn_request_signed'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Sign AuthnRequest</strong>'),
    '#default_value' => variable_get('saml_advanced_settings_authn_request_signed', FALSE),
    '#description' => t('The samlp:AuthnRequest messages sent by this SP will be signed.'),
    '#required' => FALSE
  );

  $form['saml_signing_encryption_settings']['saml_advanced_settings_logout_request_signed'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Sign LogoutRequest</strong>'),
    '#default_value' => variable_get('saml_advanced_settings_logout_request_signed', FALSE),
    '#description' => t('The samlp:logoutRequest messages sent by this SP will be signed.'),
    '#required' => FALSE
  );

  $form['saml_signing_encryption_settings']['saml_advanced_settings_logout_response_signed'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Sign LogoutResponse</strong>'),
    '#default_value' => variable_get('saml_advanced_settings_logout_response_signed', FALSE),
    '#description' => t('The samlp:logoutResponse messages sent by this SP will be signed.'),
    '#required' => FALSE
  );

  $form['saml_signing_encryption_settings']['advanced_settings_want_message_signed'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Reject Unsigned Messages</strong>'),
    '#default_value' => variable_get('advanced_settings_want_message_signed', FALSE),
    '#description' => t('Reject unsigned samlp:Response, samlp:LogoutRequest and samlp:LogoutResponse received'),
    '#required' => FALSE
  );

  $form['saml_signing_encryption_settings']['saml_advanced_settings_want_assertion_signed'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Reject unsigned saml:Assertion received</strong>'),
    '#default_value' => variable_get('saml_advanced_settings_want_assertion_signed', FALSE),
    '#description' => t('Reject Unsigned Assertions'),
    '#required' => FALSE
  );

  $form['saml_signing_encryption_settings']['saml_advanced_settings_want_assertion_encrypted'] = array(
    '#type' => 'checkbox',
    '#title' => t('<strong>Reject Unencrypted Assertions</strong>'),
    '#default_value' => variable_get('saml_advanced_settings_want_assertion_encrypted', FALSE),
    '#description' => t('Reject unencrypted saml:Assertion received.'),
    '#required' => FALSE
  );

  $form['saml_signing_encryption_settings']['saml_advanced_settings_sp_x509cert'] = array(
    '#type' => 'textarea',
    '#title' => t('Service Provider X.509 Certificate'),
    '#default_value' => variable_get('saml_advanced_settings_sp_x509cert', ''),
    '#description' => t('Public x509 certificate of the SP. The full certificate (including -----BEGIN CERTIFICATE----- and -----END CERTIFICATE-----) is required. Leave this field empty if you have added sp.crt to the certs folder of this module.'),
    '#required' => FALSE
  );

  $form['saml_signing_encryption_settings']['saml_advanced_settings_sp_privatekey'] = array(
    '#type' => 'textarea',
    '#title' => t('Service Provider Private Key'),
    '#default_value' => variable_get('saml_advanced_settings_sp_privatekey', ''),
    '#description' => t('Private Key of the SP. The full certificate (including -----BEGIN CERTIFICATE----- and -----END CERTIFICATE-----) is required. Leave this field empty if have added sp.key to the certs folder of this module.'),
    '#required' => FALSE
  );

  return system_settings_form($form);
}


function search_admin_settings($form) {
  // Collect some stats
  $remaining = 0;
  $total = 0;
  foreach (variable_get('search_active_modules', array('node', 'user')) as $module) {
    if ($status = module_invoke($module, 'search_status')) {
      $remaining += $status['remaining'];
      $total += $status['total'];
    }
  }

  $count = format_plural($remaining, 'There is 1 item left to index.', 'There are @count items left to index.');
  $percentage = ((int)min(100, 100 * ($total - $remaining) / max(1, $total))) . '%';
  $status = '<p><strong>' . t('%percentage of the site has been indexed.', array('%percentage' => $percentage)) . ' ' . $count . '</strong></p>';
  $form['status'] = array('#type' => 'fieldset', '#title' => t('Indexing status'));
  $form['status']['status'] = array('#markup' => $status);
  $form['status']['wipe'] = array('#type' => 'submit', '#value' => t('Re-index site'), '#submit' => array('search_admin_reindex_submit'));

  $items = drupal_map_assoc(array(10, 20, 50, 100, 200, 500));

  // Indexing throttle:
  $form['indexing_throttle'] = array(
    '#type' => 'fieldset',
    '#title' => t('Indexing throttle')
  );
  $form['indexing_throttle']['search_cron_limit'] = array(
    '#type' => 'select',
    '#title' => t('Number of items to index per cron run'),
    '#default_value' => variable_get('search_cron_limit', 100),
    '#options' => $items,
    '#description' => t('The maximum number of items indexed in each pass of a <a href="@cron">cron maintenance task</a>. If necessary, reduce the number of items to prevent timeouts and memory errors while indexing.', array('@cron' => url('admin/reports/status')))
  );
  // Indexing settings:
  $form['indexing_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Indexing settings')
  );
  $form['indexing_settings']['info'] = array(
    '#markup' => t('<p><em>Changing the settings below will cause the site index to be rebuilt. The search index is not cleared but systematically updated to reflect the new settings. Searching will continue to work but new content won\'t be indexed until all existing content has been re-indexed.</em></p><p><em>The default settings should be appropriate for the majority of sites.</em></p>')
  );
  $form['indexing_settings']['minimum_word_size'] = array(
    '#type' => 'textfield',
    '#title' => t('Minimum word length to index'),
    '#default_value' => variable_get('minimum_word_size', 3),
    '#size' => 5,
    '#maxlength' => 3,
    '#description' => t('The number of characters a word has to be to be indexed. A lower setting means better search result ranking, but also a larger database. Each search query must contain at least one keyword that is this size (or longer).'),
    '#element_validate' => array('element_validate_integer_positive'),
  );
  $form['indexing_settings']['overlap_cjk'] = array(
    '#type' => 'checkbox',
    '#title' => t('Simple CJK handling'),
    '#default_value' => variable_get('overlap_cjk', TRUE),
    '#description' => t('Whether to apply a simple Chinese/Japanese/Korean tokenizer based on overlapping sequences. Turn this off if you want to use an external preprocessor for this instead. Does not affect other languages.')
  );

  $form['active'] = array(
    '#type' => 'fieldset',
    '#title' => t('Active search modules')
  );
  $module_options = _search_get_module_names();
  $form['active']['search_active_modules'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Active modules'),
    '#title_display' => 'invisible',
    '#default_value' => variable_get('search_active_modules', array('node', 'user')),
    '#options' => $module_options,
    '#description' => t('Choose which search modules are active from the available modules.')
  );
  $form['active']['search_default_module'] = array(
    '#title' => t('Default search module'),
    '#type' => 'radios',
    '#default_value' => variable_get('search_default_module', 'node'),
    '#options' => $module_options,
    '#description' => t('Choose which search module is the default.')
  );
  $form['#validate'][] = 'search_admin_settings_validate';
  $form['#submit'][] = 'search_admin_settings_submit';

  // Per module settings
  foreach (variable_get('search_active_modules', array('node', 'user')) as $module) {
    $added_form = module_invoke($module, 'search_admin');
    if (is_array($added_form)) {
      $form = array_merge($form, $added_form);
    }
  }

  return system_settings_form($form);
}

/**
 * Form validation handler for onelogin_saml_form().
 */
function onelogin_saml_form_validate($form, &$form_state) {
  if (empty($form_state['values']['saml_advanced_settings_sp_entity_id'])) {
    $form_state['values']['saml_advanced_settings_sp_entity_id'] = 'php-saml';
  }
}
