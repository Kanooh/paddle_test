<?php
/**
 * @file
 * Ctools export plugin that defines exportable Paddle themes.
 */

$plugin = array(
  'schema' => 'paddle_themer_theme',
  'access' => 'administer paddle_themer',

  'menu' => array(
    'menu item' => 'paddle_themer',
    'menu title' => 'Paddle themer themes',
    'menu description' => 'Add, edit or delete Paddle Themer themes.',
  ),

  'title singular' => t('theme'),
  'title singular proper' => t('Theme'),
  'title plural' => t('themes'),
  'title plural proper' => t('Themes'),
  'use wizard' => TRUE,
  'form info' => array(
    'add order' => array(
      'admin' => t('Administrative settings'),
      'theme' => t('Select theme'),
      'styles' => t('Customize theme'),
      'image' => t('Customize theme image'),
    ),
    'order' => array(
      'admin' => t('Administrative settings'),
      'styles' => t('Styles'),
      'image' => t('Theme image'),
    ),
    'forms' => array(
      'theme' => array(
        'form id' => 'paddle_themer_theme_selection_form',
      ),
      'styles' => array(
        'form id' => 'paddle_themer_styles_edit_wizard_form',
      ),
      'image' => array(
        'form id' => 'paddle_themer_image_wizard_form',
        'include' => drupal_get_path('module', 'paddle_themer') . '/paddle_themer.admin.inc',
      ),
    ),
  ),
);
