<?php

/**
 * Implements theme_menu_link().
 */
function kanooh_theme_v2_vertical_navigation_menu_link(&$variables) {
  $element = $variables['element'];
  $sub_menu = '';
  $element['#attributes']['class'][] = 'paddle-vm-level-' . $element['#original_link']['depth'];

  if ($element['#original_link']['link_path'] == $_GET['q']) {
    $element['#attributes']['class'][] .= 'paddle-vm-active';
  }
  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
