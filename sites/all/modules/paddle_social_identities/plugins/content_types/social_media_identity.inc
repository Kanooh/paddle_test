<?php

/**
 * @file
 * Plugin to show a group of social identities.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Social media identity'),
  'no title override' => TRUE,
  'description' => t('Add a social media identity.'),
  'category' => array(t('Paddle Landing Page'), -10),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_social_identities_social_media_identity_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $output = '';

  if (!empty($conf['social_media_identity']) && $social_identity = paddle_social_identities_load($conf['social_media_identity'])) {
    $fields = field_get_items('paddle_social_identity', $social_identity, 'field_social_identity_urls');

    if (is_array($fields) && count($fields)) {
      $items = array();

      foreach ($fields as $field) {
        // Get the correct fa icon type.
        $iconless_identities = array('unknown', 'vimeo', 'wordpress', 'blogger');
        $icon_id = paddle_social_identitites_identify_identity($field['value']);
        if (in_array($icon_id, $iconless_identities)) {
          $icon_id = 'link';
        }

        $item = theme('paddle_core_listing_table_item', array(
          'icon' => theme('paddle_core_icon', array('name' => ($icon_id != 'unknown' ? $icon_id : 'link'))),
          'icon_alignment' => 'left',
          'content' => $field['title'] ?: $field['value'],
        ));

        // Put a link on the identity.
        $link_options = array(
          'attributes' => array('target' => '_blank'),
          'html' => TRUE,
        );
        $item = l($item, $field['value'], $link_options);

        // Add the item to the list of which we will generate the listing.
        $items[] = $item;
      }
      // Put all items in a nice listing "table".
      $output = theme('paddle_core_listing_table', array('items' => $items));
    }
    else {
      // Display only the title if there are no identities.
      $output = $social_identity->name;
    }
  }

  // Allow the pane sections to contribute output.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  $block->content = $output;
  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_social_identities_social_media_identity_content_type_edit_form($form, &$form_state) {
  $conf = $form_state['conf'];

  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  // Populate the options array with all the social identities.
  $options = array();
  $social_identities = entity_load_multiple_by_name('paddle_social_identity');

  // Sort entities by human-readable name.
  uasort($social_identities, function($a, $b) {
    return strcmp($a->name, $b->name);
  });

  foreach ($social_identities as $psiid => $social_identity) {
    $options[$psiid] = $social_identity->name;
  }

  if (!empty($options)) {
    // Add a select field to choose the social identity.
    $form['section_body']['social_media_identity'] = array(
      '#type' => 'select',
      '#title' => t('Social media identity'),
      '#required' => TRUE,
      '#default_value' => isset($conf['social_media_identity']) ? $conf['social_media_identity'] : NULL,
      '#options' => $options,
    );
  }
  else {
    $form['section_body']['message'] = array(
      '#markup' => t('No identities have been created yet.'),
    );
  }

  // Include the form elements for the pane sections in the form.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);
  }

  form_load_include($form_state, 'inc', 'paddle_social_identities', 'plugins/content_types/social_media_identity');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_social_identities_social_media_identity_content_type_edit_form_validate($form, &$form_state) {
  // Allow the form elements provided by pane sections to be validated.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_validate($form, $form_state);
  }
}

/**
 * Submit callback for the configuration form.
 */
function paddle_social_identities_social_media_identity_content_type_edit_form_submit($form, &$form_state) {
  foreach (element_children($form['pane_settings']['section_body']) as $key) {
    $form_state['conf'][$key] = $form_state['values'][$key];
  }

  // Allow the form elements provided by pane sections to be saved.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_submit($form, $form_state);
  }
}
