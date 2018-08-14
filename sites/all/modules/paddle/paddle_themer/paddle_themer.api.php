<?php

/**
 * @file
 * API documentation for the Paddle Themer module.
 */

/**
 * Defines style sets containing theming information.
 *
 * If you are creating a theme or app to use with Paddle Themer you can use this
 * hook to define which parts you wish to be themable.
 *
 * When creating a theme, this hook needs to be placed in a file called
 * 'theme-settings.php' in the theme's root folder. Apps and modules can
 * implement the hook as usual in the .module file.
 *
 * The hook defines a list of style sets containing information about the
 * machine names that are used for each selector. You can also specify sections
 * so each section can be separate from another and each have its own title.
 * These sections can also be divided in other sections. The "plugins" key is
 * where you define your machine names for your specific selectors. The
 * 'plugins' key cannot be defined outside the deepest level of a section.
 *
 * Every style set has a machine name. Themes must define a 'global' style set
 * which contains styling of basic HTML elements, and may define additional
 * style sets. Apps and modules that define style sets should prefix the machine
 * name with their module name to make sure all names are unique.
 */
function hook_paddle_themer_style_set() {
  $style_sets = array();

  $style_sets['demo'] = array(
    'title' => t('Demo'),
    'sections' => array(
      'section_1' => array(
        'title' => t('Section 1'),
        'sections' => array(
          'section_1a' => array(
            'title' => t('Section 1a'),
            'plugins' => array(
              'description' => t('Plugin title goes here'),
            ),
          ),
          'section_1b' => array(
            // This section has no title.
            'plugins' => array(
              // This plugin does not display a title.
              'some_cool_plugin' => '',
            ),
          ),
          // This will not work because it is not placed within the deepest
          // level of a section.
// @codingStandardsIgnoreStart
//          'plugins' => array(
//              // This plugin does not display a title.
//              'some_cool_plugin' => '',
//          ),
// @codingStandardsIgnoreEnd
        ),
      ),
    ),
  );

  // A more realistic example.
  $style_sets['styling'] = array(
    'title' => t('Styling'),
    'sections' => array(

      // "Font" section.
      'font' => array(
        'title' => t('Font'),
        'plugins' => array(
          'top_navigation_font' => t('Top navigation'),
          'level_1_navigation_font' => t('Level 1 navigation'),
          'breadcrumb_font' => t('Breadcrumb navigation'),
          'page_title_font' => t('Title of the content page'),
          'h1_font' => t('H1'),
          'h2_font' => t('H2'),
          'h3_font' => t('H3'),
          'h4_font' => t('H4'),
          'blockquote_font' => t('Quote'),
          'paragraph_font' => t('Paragraph'),
          'read_more_font' => t('Read more link'),
          'footer_navigation_font' => t('Footer navigation'),
        ),
      ),

      // "Background" section.
      'background' => array(
        'title' => t('Background'),
        'plugins' => array(
          'body_background' => '',
        ),
      ),
    ),
  );

  return $style_sets;
}

/**
 * Defines resources to be loaded together with the styles edit form.
 *
 * Any types of resources like stylesheets, JavaScript etc. supported
 * by the #attached property of form API elements are allowed.
 *
 * Implementations of this hook need to be placed in a file called
 * 'theme-settings.php' in the theme's root folder.
 */
function hook_paddle_themer_styles_edit_wizard_form_attachments() {
  return array(
    'css' => array(
      drupal_get_path('theme', 'paddle_theme') . '/css/paddle_themer_styles_edit_wizard_form.css',
    ),
  );
}

/**
 * Defines plugin instances containing plugin information.
 *
 * If you are creating a theme or app to use with Paddle Themer you can use this
 * hook to define which parts you wish to be themable.
 *
 * When creating a theme, this hook needs to be placed in a file called
 * 'theme-settings.php' in the theme's root folder. Apps and modules can
 * implement the hook as usual in the .module file.
 *
 * The hook defines a list of plugin instances containing information about the
 * CSS selectors that are used for each machine name. For each selector you can
 * decide which style plugins to use.
 *
 * You can limit the settings that are available for each plugin by whitelisting
 * the allowed settings in the 'allowed_values' key. If this key is not given or
 * is empty the plugin will by default provide all settings that it supports.
 * Refer to the documentation of the style plugins for a list of which settings
 * are available.
 *
 * You can provide default values for each form element in the 'default_values'
 * array.
 *
 * Every plugin instance has a machine name, which is the key for the array
 * containing the settings, plugin and selector.
 */
function hook_theme_paddle_themer_plugin_instances() {
  $plugin_instances = array();

  $plugin_instances = array(
    'h2_font' => array(
      'plugin' => 'font',
      'selector' => '.region-content h2',
      'allowed_values' => array(
        'font_family' => array(
          '"Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lucida Sans Unicode',
          '"Arial", "Helvetica", sans-serif' => 'Arial',
        ),
        'font_size' => array(
          '34px' => t('Largest'),
          '27px' => t('Very large'),
          '21px' => t('Large'),
          '18px' => t('Normal'),
          '16px' => t('Smaller'),
          '14px' => t('Very small'),
          '12px' => t('Smallest'),
        ),
        'font_style' => array(
          'bold' => t('Bold'),
          'italic' => t('Italic'),
          'underline' => t('Underline'),
        ),
        'font_capitalization' => array(
          'none' => t('Normal'),
          'uppercase' => t('Uppercase'),
          'capitalize' => t('Capitalize'),
          'lowercase' => t('Lowercase'),
        ),
        'font_color' => array(),
      ),
      'default_values' => array(
        'font_family' => '"Arial", "Helvetica", sans-serif',
        'font_size' => '16px',
      ),
    ),
  );

  return $plugin_instances;
}
