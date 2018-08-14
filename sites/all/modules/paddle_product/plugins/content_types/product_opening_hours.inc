<?php

/**
 * @file
 * CTools content type plugin to embed the openings hours.
 */

$plugin = array(
  'title' => t('Product openings hours'),
  'description' => t('Opening hours of the products responsible organizational unit.'),
  'category' => t('Paddle Product'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_product_product_opening_hours_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  if ($conf['paddle_organizational_unit']) {
    $node = node_load($conf['paddle_organizational_unit']);

    // Check if returned node is valid.
    if (!$node) {
      return $block;
    }

    $email = field_get_items('node', $node, 'field_paddle_ou_email');
    $phone = field_get_items('node', $node, 'field_paddle_ou_phone');
    $website = field_get_items('node', $node, 'field_paddle_ou_website');
    $address = field_get_items('node', $node, 'field_paddle_ou_address');

    $empty_address = TRUE;

    if (!empty($address[0])) {
      foreach ($address[0] as $key => $value) {
        $address[$key] = check_plain($value);
        if ($key != 'country') {
          // Ignore country because that always has a value.
          // Check if any other key has a non empty value.
          if (!empty($address[$key])) {
            $empty_address = FALSE;
            break;
          }
        }
      }
    }

    $formatted_address = '';
    if (!$empty_address) {
      $formatted_address = field_view_value('node', $node, 'field_paddle_ou_address', $address[0]);
      $formatted_address = render($formatted_address);
    }

    $variables = array(
      'link' => l($node->title, 'node/' . $node->nid),
      'email' => !empty($email) ? $email[0]['email'] : '',
      'website' => !empty($website) ? $website[0]['value'] : '',
      'phone' => !empty($phone) ? $phone[0]['value'] : '',
      'address' => $formatted_address,
    );

    if (module_exists('paddle_opening_hours')) {
      $wrapper = entity_metadata_wrapper('node', $node);
      $opening_hours_set = $wrapper->field_paddle_opening_hours->value();

      if (!empty($opening_hours_set)) {
        $ohs_wrapper = entity_metadata_wrapper('opening_hours_set', $opening_hours_set);
        $current_opening_hour = opening_hours_sets_get_current_opening_hour($ohs_wrapper);

        if (!$current_opening_hour || empty($current_opening_hour)) {
          $current_opening_status = t("Now closed");
        }
        else {
          $current_opening_status = t("Now open");
          $variables['current_opening_hour'] = $current_opening_hour['time'];
          $variables['description'] = $current_opening_hour['description'];
        }
        $variables['opening_hour_status'] = $current_opening_status;
      }
    }

    $block->content = theme('paddle_product_opening_hours', $variables);

    // Allow the pane sections to contribute output.
    if (module_exists('paddle_panes')) {
      $conf['top'] = array(
        'enable_section' => 1,
        'section_text' => t('Opening hours and contact'),
        'section_content_type' => 'text',
        'section_image' => array(
          'sid' => array(),
          'style' => '',
        ),
        'icon_image' => array(),
        'section_url_type' => 'no_link',
        'section_internal_url' => '',
        'section_external_url' => 'http://',
      );

      module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
      paddle_panes_pane_sections_render($block->content, $conf, $context);
    }
  }

  return $block;
}
