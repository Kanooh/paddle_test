<?php

/**
 * @file
 * CTools content type plugin to embed an organizational unit contact
 * information.
 */

$plugin = array(
  'title' => t('Organizational unit contact information'),
  'no title override' => TRUE,
  'description' => t('Organizational unit contact information.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Miscellaneous'), -10),
  'edit form' => 'contact_information_content_type_edit_form',
  'render callback' => 'contact_information_content_type_render',
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render the content type.
 */
function contact_information_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $node = $context->data;
  $template = 'paddle_organizational_unit_page_contact_information';
  $wrapper = entity_metadata_wrapper('node', $node);

  if (!empty($wrapper->field_paddle_ou_twitter) && !empty($wrapper->field_paddle_ou_twitter->value())) {
    $variables['twitter'] = check_plain($wrapper->field_paddle_ou_twitter->value->value());
    $variables['twitter_simple'] = preg_replace('#^https?://#', '', $variables['twitter']);
  }

  if (!empty($wrapper->field_paddle_ou_facebook) && !empty($wrapper->field_paddle_ou_facebook->value())) {
    $variables['facebook'] = check_plain($wrapper->field_paddle_ou_facebook->value->value());
    $variables['facebook_simple'] = preg_replace('#^https?://#', '', $variables['facebook']);
  }

  if (!empty($wrapper->field_paddle_ou_linkedin) && !empty($wrapper->field_paddle_ou_linkedin->value())) {
    $variables['linkedin'] = check_plain($wrapper->field_paddle_ou_linkedin->value->value());
    $variables['linkedin_simple'] = preg_replace('#^https?://#', '', $variables['linkedin']);
  }

  if (!empty($wrapper->field_paddle_ou_website) && !empty($wrapper->field_paddle_ou_website->value())) {
    $variables['website'] = check_plain($wrapper->field_paddle_ou_website->value->value());
    $variables['website_simple'] = preg_replace('#^https?://#', '', $variables['website']);
  }

  if (!empty($wrapper->field_paddle_ou_vat_number) && !empty($wrapper->field_paddle_ou_vat_number->value())) {
    $variables['vat_number'] = check_plain($wrapper->field_paddle_ou_vat_number->value());
  }

  // Only show head of unit reference to contact person when that paddlet is
  // enabled. The same logic goes for the backend, as can be seen in:
  // @see paddle_organizational_unit_paddle_content_manager_additional_fields()
  if (!module_exists('paddle_contact_person') && !empty($wrapper->field_paddle_ou_head_unit) && !empty($wrapper->field_paddle_ou_head_unit->value())) {
    $variables['head_of_unit'] = check_plain($wrapper->field_paddle_ou_head_unit->value());
  }
  if (module_exists('paddle_contact_person') && !empty($wrapper->field_paddle_ou_cp_head_unit) && !empty($wrapper->field_paddle_ou_cp_head_unit->value()) && node_access('view', $wrapper->field_paddle_ou_cp_head_unit->value())) {
    $referenced_cp = $wrapper->field_paddle_ou_cp_head_unit->value();
    $variables['head_of_unit_ref'] = l(check_plain($referenced_cp->title), $referenced_cp->path['source']);
  }

  $parent_ids = array($wrapper->getIdentifier());
  $parent_entities = array();
  while (!empty(!empty($wrapper->field_paddle_ou_parent_entity) && $wrapper->field_paddle_ou_parent_entity->value())) {
    $referenced_ou = $wrapper->field_paddle_ou_parent_entity->value();
    if (!in_array($referenced_ou->nid, $parent_ids) && (($referenced_ou->workbench_moderation['current']->state == 'published') || (user_access('view all unpublished content')))) {
      $parent_entities[$referenced_ou->nid]['parent'] = l(check_plain($referenced_ou->title), $referenced_ou->path['source']);
      $parent_entities[$referenced_ou->nid]['id'] = $referenced_ou->nid;
      $wrapper = entity_metadata_wrapper('node', $referenced_ou);
      $parent_ids[] = $referenced_ou->nid;
    }
    else {
      break;
    }
  }

  // Reverse the array to show top parent first.
  if (!empty($parent_entities)) {
    $variables['parent_entities'] = array_reverse($parent_entities);
  }

  $address = !empty($node->field_paddle_ou_address['und'][0]) ? $node->field_paddle_ou_address['und'][0] : array();
  $empty_address = paddle_core_is_address_empty($address);

  if (!$empty_address) {
    $addresses = field_get_items('node', $node, 'field_paddle_ou_address');
    $formatted_address = field_view_value('node', $node, 'field_paddle_ou_address', $addresses[0]);

    $variables['address_formatted'] = render($formatted_address);
  }

  if (!empty($variables)) {
    $block->content = theme($template, $variables);
  }

  return $block;
}

/**
 * Edit form.
 */
function contact_information_content_type_edit_form($form, &$form_state) {
  return $form;
}
