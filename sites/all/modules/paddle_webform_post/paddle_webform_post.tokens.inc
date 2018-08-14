<?php

/**
 * @file
 * Builds placeholder replacement tokens for webform-related data.
 */

/**
 * Implements hook_token_info().
 */
function paddle_webform_post_token_info() {
  $info['types']['paddle_webform_post'] = array(
    'name' => t('Post submission actions on a webform'),
    'description' => t('Tokens related to the additional actions undertaken on a webform submission.'),
    'needs-data' => 'post-webform-submission-action',
  );

  $info['tokens']['paddle_webform_post']['sid'] = array(
    'name' => t('Submission ID'),
    'description' => t('The unique identifier for the webform submission.'),
  );
  $info['tokens']['paddle_webform_post']['nid'] = array(
    'name' => t('Node ID'),
    'description' => t('The unique identifier for the node of the webform submission.'),
  );
  $info['tokens']['paddle_webform_post']['submission-url'] = array(
    'name' => t('Submission URL'),
    'description' => t('The URL where we can reach the submission.'),
  );
  $info['tokens']['paddle_webform_post']['date'] = array(
    'name' => t('Date submitted'),
    'description' => t('The date the additional action has been undertaken on submission of the form.'),
    'type' => 'date',
  );
  $info['tokens']['paddle_webform_post']['ip-address'] = array(
    'name' => t('IP address'),
    'description' => t('The IP address that was used when submitting the webform.'),
  );
  $info['tokens']['paddle_webform_post']['first-name'] = array(
    'name' => t('First name'),
    'description' => t('The first name of the user that submitted the webform result.'),
  );
  $info['tokens']['paddle_webform_post']['last-name'] = array(
    'name' => t('Last name'),
    'description' => t('The last name of the user  that submitted the webform result.'),
  );
  $info['tokens']['paddle_webform_post']['e-mail'] = array(
    'name' => t('E-mail'),
    'description' => t('The e-mail of the user that submitted the webform result.'),
  );
  $info['tokens']['paddle_webform_post']['web-service-url'] = array(
    'name' => t('Web service URL'),
    'description' => t('The URL of the web service we talk to.'),
  );
  $info['tokens']['paddle_webform_post']['error-code'] = array(
    'name' => t('HTML error code'),
    'description' => t('The HTML error code which we have retrieved.'),
  );
  $info['tokens']['paddle_webform_post']['error-message'] = array(
    'name' => t('Error message'),
    'description' => t('The message supplied with the error code.'),
  );

  return $info;
}

/**
 * Implements hook_tokens().
 */
function paddle_webform_post_tokens($type, $tokens, array $data = array(), array $options = array()) {
  $url_options = array('absolute' => TRUE);
  if (isset($options['language'])) {
    $url_options['language'] = $options['language'];
    $language_code = $options['language']->language;
  }
  else {
    $language_code = NULL;
  }
  $sanitize = !empty($options['sanitize']);

  $replacements = array();

  $post_data = $data['post_data'];
  $error_data = $data['error_data'];

  if ($type != 'paddle_webform_post' || empty($post_data) || !webform_variable_get('webform_token_access')) {
    return array();
  }

  foreach ($tokens as $name => $original) {
    switch ($name) {
      case 'first-name':
        $replacements[$original] = $post_data['voornaam'] ? $post_data['voornaam'] : '';
        break;

      case 'last-name':
        $replacements[$original] = $post_data['achternaam'] ? $post_data['achternaam'] : '';
        break;

      case 'e-mail':
        $replacements[$original] = $post_data['email'] ? $post_data['email'] : '';
        break;

      case 'sid':
        $replacements[$original] = $error_data['sid'] ? $error_data['sid'] : '';
        break;

      case 'nid':
        $replacements[$original] = $error_data['nid'] ? $error_data['nid'] : '';
        break;

      case 'ip-address':
        $replacements[$original] = $sanitize ? check_plain($error_data['ip']) : $error_data['ip'];
        break;

      case 'web-service-url':
        $replacements[$original] = $error_data['url'] ? $error_data['url'] : '';
        break;

      case 'submission-url':
        $replacements[$original] = $error_data['sid'] ? url("node/{$error_data['nid']}/submission/{$error_data['sid']}", $url_options) : '';
        break;

      case 'date':
        $replacements[$original] = format_date($error_data['timestamp'], webform_variable_get('webform_date_type'), '', NULL, $language_code);
        break;

      case 'error-code':
        $replacements[$original] = $error_data['code'] ? $error_data['code'] : '';
        break;

      case 'error-message':
        $replacements[$original] = $error_data['error_message'] ? $error_data['error_message'] : '';
        break;
    }
  }

  // Chained token relationships.
  if ($date_tokens = token_find_with_prefix($tokens, 'date')) {
    $replacements += token_generate('date', $date_tokens, array('date' => $error_data['timestamp']), $options);
  }

  if (!empty($error_data['sid'])) {
    if ($url_tokens = token_find_with_prefix($tokens, 'submission-url')) {
      $replacements += token_generate('url', $url_tokens, array('path' => "node/{$error_data['nid']}/submission/{$error_data['sid']}"), $options);
    }
  }

  return $replacements;
}
