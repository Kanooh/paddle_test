<?php
/**
 * @file
 * Form callbacks for Incoming RSS paddlet.
 */

/**
 * Form callaback to add and edit a feed entry.
 *
 * @param array $form
 *   The form.
 * @param array $form_state
 *   The form state.
 * @param PaddleIncomingRSSFeed $feed
 *   The feed object. NULL when creating a new one.
 *
 * @return array
 *   The finished form.
 */
function paddle_incoming_rss_feed_form($form, &$form_state, $feed) {
  // Always include this file.
  form_load_include($form_state, 'inc', 'paddle_incoming_rss', 'paddle_incoming_rss.forms');

  // Save the feed entity for later processing. Use the one in the form_state
  // during a rebuild.
  if (!isset($form_state['feed'])) {
    // Create a new feed instance if needed.
    if (is_null($feed)) {
      $feed = entity_create('paddle_incoming_rss_feed', array());
    }
    $form_state['feed'] = $feed;
  }
  else {
    $feed = $form_state['feed'];
  }

  // Use a wrapper for better styling in modals.
  $form['wrapper'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
    '#tree' => FALSE,
  );

  $form['wrapper']['title'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#description' => t('Give the RSS feed a title, which you can edit when creating an RSS feed.'),
    '#required' => TRUE,
    '#default_value' => $feed->label() ?: '',
  );

  // Retrieve the source form.
  // @see feeds_import_form()
  /* @var FeedsSource $source */
  $source = feeds_source('paddle_incoming_rss', is_null($feed) ? 0 : $feed->identifier());
  $source_form = $source->configForm($form_state);
  if (!empty($source_form)) {
    $form['wrapper']['feeds'] = array(
      '#tree' => TRUE,
    ) + $source_form;
  }

  $form['actions'] = array(
    '#type' => 'actions',
    '#attributes' => array('class' => array('form-buttons')),
  );

  $form['actions']['save'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );

  return $form;
}

/**
 * Validate callback for paddle_incoming_rss_feed_form.
 *
 * @see feeds_import_form_validate()
 */
function paddle_incoming_rss_feed_form_validate($form, &$form_state) {
  /* @var FeedsSource $source */
  $source = feeds_source('paddle_incoming_rss');
  $source->configFormValidate($form_state['values']['feeds']);

  // Validate the URL.
  if ($form_state['values']['feeds']['FeedsHTTPFetcher']['source'] != 'http://') {
    try {
      $source->addConfig($form_state['values']['feeds']);
      $preview_result = $source->preview();
      // Do another check to see if the feed is empty or not. If the URL is valid
      // but doesn't contain RSS the title and link will be empty.
      if (!$preview_result->title && !$preview_result->link) {
        throw new Exception('The URL doesn\'t contain RSS.');
      }
    }
    catch (Exception $e) {
      form_set_error('feeds', t('The URL is not a valid RSS source.'));
    }
  }
}

/**
 * Submit callback for paddle_incoming_rss_feed_form.
 */
function paddle_incoming_rss_feed_form_submit($form, &$form_state) {
  // And save our entity.
  /* @var PaddleIncomingRSSFeed $feed */
  $feed = $form_state['feed'];
  entity_form_submit_build_entity('paddle_incoming_rss_feed', $feed, $form, $form_state);
  $feed->save();

  // Set a success message.
  drupal_set_message(t('RSS feed saved.'));

  // Save the source configuration.
  /* @var FeedsSource $source */
  $source = feeds_source('paddle_incoming_rss', $feed->identifier());
  $source->addConfig($form_state['values']['feeds']);
  $source->save();
  $source->startImport();
  $source->schedule();

  // Redirect to the paddlet configuration page.
  $form_state['redirect'] = 'admin/paddlet_store/app/paddle_incoming_rss/configure';
}
