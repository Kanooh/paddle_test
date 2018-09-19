<?php

/**
 * @file
 * CTools content type plugin to embed the basic info of Company pages.
 */

$plugin = array(
  'title' => t('Company info'),
  'description' => t('Displays the basic information of a company.'),
  'category' => t('Paddle Company Guide'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => FALSE,
);

/**
 * Render the content type.
 */
function paddle_company_guide_basic_info_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $node = $context->data;
  $wrapper = entity_metadata_wrapper('node', $node);

  if (!empty($wrapper->field_paddle_featured_image) && !empty($wrapper->field_paddle_featured_image->value())) {
    $main_image_field = $wrapper->field_paddle_featured_image->value();
    $logo_image = scald_atom_load($main_image_field['sid']);
    $variables['logo'] = file_create_url($logo_image->file_source);
  }

  if (!empty($node->title)) {
    $variables['title'] = $node->title;
  }

  if (!empty($wrapper->field_company_page_email) && !empty($wrapper->field_company_page_email->value())) {
    $variables['mail'] = $wrapper->field_company_page_email->value();
  }

  if (!empty($wrapper->field_company_page_phone) && !empty($wrapper->field_company_page_phone->value())) {
    $variables['phone'] = $wrapper->field_company_page_phone->value();
  }

  if (!empty($wrapper->field_company_page_website) && !empty($wrapper->field_company_page_website->value())) {
    $variables['site'] = $wrapper->field_company_page_website->value()['value'];
    $variables['site_simple'] = preg_replace('#^https?://#', '', $variables['site']);
  }

  if (!empty($wrapper->field_company_page_facebook) && !empty($wrapper->field_company_page_facebook->value())) {
    $variables['facebook'] = $wrapper->field_company_page_facebook->value();
  }

  if (!empty($wrapper->field_company_page_twitter) && !empty($wrapper->field_company_page_twitter->value())) {
    $variables['twitter'] = $wrapper->field_company_page_twitter->value();
  }

  if (!empty($wrapper->field_company_page_vat) && !empty($wrapper->field_company_page_vat->value())) {
    $variables['vat'] = $wrapper->field_company_page_vat->value();
  }

  if (!empty($wrapper->field_company_page_linkedin) && !empty($wrapper->field_company_page_linkedin->value())) {
    $variables['linkedin'] = $wrapper->field_company_page_linkedin->value();
  }

  $address = !empty($node->field_company_page_address['und'][0]) ? $node->field_company_page_address['und'][0] : array();
  $empty_address = paddle_core_is_address_empty($address);

  if (!$empty_address) {
    $address = field_get_items('node', $node, 'field_company_page_address');
    $formatted_address = field_view_value('node', $node, 'field_company_page_address', $address[0]);

    $variables['address'] = render($formatted_address);
  }

  if (!empty($variables)) {
    $block->content = theme('paddle_company_guide_basic_info', $variables);
  }

  return $block;
}
