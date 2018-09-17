<?php
/**
 * @file
 * Drush commands to help test the Paddle Menu Manager manually.
 */

/**
 * Implements hook_drush_command().
 */
function paddle_menu_manager_test_drush_command() {
  $items = array();

  $items['paddle-menu-manager-generate-test-menu-items'] = array(
    'description' => 'Generates 200 test menu items.',
    'arguments' => array(
      'langcode' => 'The language code of the language in which to create the menu items',
      'menu' => 'The machine name of the menu in which to place the menu items',
    ),
    'required-arguments' => TRUE,
    'examples' => array(
      'drush pmmgtmi en main_menu' => 'Create a number of test menu items in the main menu in the English language.',
    ),
    'drupal dependencies' => array('devel_generate'),
    'aliases' => array('pmmgtmi'),
  );

  return $items;
}

/**
 * Generates test menu items through the Devel Generate module.
 *
 * @param string $langcode
 *   The language code of the language in which the menu links should be
 *   generated.
 * @param string $menu
 *   The machine name of the menu in which to place the menu links.
 */
function drush_paddle_menu_manager_test_paddle_menu_manager_generate_test_menu_items($langcode, $menu) {
  module_load_include('inc', 'devel_generate', 'devel_generate');
  $menus = drupal_map_assoc(array($menu));
  $link_types = drupal_map_assoc(array('external', 'front'));
  devel_generate_links(200, $menus, 20, $link_types, 9, 20);
}
