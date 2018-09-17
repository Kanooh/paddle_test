<?php

/**
 * @file
 * Hook implementations for the VO theme.
 */

/**
 * Implements hook_paddle_themer_style_set().
 */
function vo_theme_paddle_themer_style_set() {
  $style_sets = array();

  // 1. Branding.
  $style_sets['branding'] = array(
    'title' => t('Branding'),
    'plugins' => array(
      // Options to turn on/off global header & footer + favicon upload.
      'branding_global_header' => t('Header and footer branding'),
      'branding_favicon' => t('Favicon'),
      'color_palettes' => '',
    ),
    'weight' => 0,
  );

  // 2. Header.
  $style_sets['header'] = array(
    'title' => t('Header'),
    'sections' => array(
      'website_header' => array(
        'title' => t('Website header title and logo'),
        'plugins' => array(
          'branding_logo' => t('Logo'),
          'boxmodel_logo' => '',
          'header_title_text' => t('Header title'),
          'header_title_font' => '',
          'boxmodel_header_title' => '',
          'header_subtitle_text' => t('Header subtitle'),
          'header_subtitle_font' => '',
          'boxmodel_header_subtitle' => '',
        ),
      ),
      'website_header_styling' => array(
        'plugins' => array(
          'header_background' => t('Header background region image'),
          'header_image' => t('Header image'),
        ),
      ),
      'search_box' => array(
        'title' => t('Search'),
        'plugins' => array(
          'show_search_box' => '',
          'search_box_options' => '',
        ),
      ),
      'menu_style' => array(
        'title' => t('Menu style'),
        'plugins' => array(
          'menu_style' => '',
        ),
      ),
    ),
    'weight' => 1,
  );

  // 3. Body.
  $style_sets['body'] = array(
    'title' => t('Body'),
    'sections' => array(
      'panes' => array(
        'title' => t('Pane settings'),
        'plugins' => array(
          'display_pane_top_as_h2' => '',
        ),
      ),
    ),
  );

  // Get all types of our nodes and create the breadcrumb trail
  // and next level checkboxes for them.
  $types = node_type_get_types();
  $style_sets['body']['sections']['breadcrumbs_navigation']['title'] = t('Breadcrumb navigation');

  foreach ($types as $type) {
    $style_sets['body']['sections']['breadcrumbs_navigation']['plugins']['show_breadcrumbs_for_' . $type->type] = '';
  }

  // The breadcrumb can also be shown on pages which are not nodes.
  $style_sets['body']['sections']['breadcrumbs_navigation']['plugins']['show_breadcrumbs_for_other_pages'] = '';

  foreach ($types as $type) {
    $style_sets['body']['sections']['breadcrumbs_navigation']['plugins']['show_level_below_' . $type->type] = '';
  }

  // 4. Footer.
  $style_sets['footer'] = array(
    'title' => t('Footer'),
    'sections' => array(
      'structure' => array(
        'title' => t('Structure'),
        'plugins' => array(
          'show_disclaimer' => '',
          'footer' => '',
        ),
      ),
      'styling' => array(
        'title' => t('Styling'),
        'plugins' => array(
          'footer_background' => t('Background'),
        ),
      ),
    ),
    'weight' => 3,
  );

  return $style_sets;
}

/**
 * Implements hook_paddle_themer_plugin_instances().
 */
function vo_theme_paddle_themer_plugin_instances() {
  $plugin_instances = array();

  // 1. Branding.
  $plugin_instances['branding_global_header'] = array(
    'plugin' => 'paddle_core_branding',
    'allowed_values' => array('global_vo_tokens' => TRUE),
    'default_values' => array('vo_branding' => 'vo_branding'),
  );
  $plugin_instances['branding_favicon'] = array('plugin' => 'favicon');

  $plugin_instances['color_palettes'] = array(
    'plugin' => 'color_palettes',
    'allowed_values' => array(
      'palette_p_light' => array(
        'palette_p_light' => array(
          'title' => 'Corporate',
          'colors' => array(
            '#FFE615',
            '#B5A51E',
            '#FFFFFF',
            '#5E5E5E',
            '#55493D',
            '#55493D',
            '#FFE615',
            '#D0C400',
          ),
        ),
        'palette_p_light1' => array(
          'title' => 'First Darker Corporate',
          'colors' => array(
            '#6B6B6B',
            '#B5A51E',
            '#989898',
            '#FFFFFF',
            '#FFFFFF',
            '#FFFFFF',
          ),
        ),
        'palette_p_light2' => array(
          'title' => 'Second Darker Corporate',
          'colors' => array(
            '#443838',
            '#B5A51E',
            '#FFFFFF',
            '#5A5A5A',
            '#696969',
            '#FFFFFF',
          ),
        ),
      ),
    ),
    'default_values' => array(
      'primary_color_palettes' => 'palette_p_light',
    ),
  );

  // 2. Header.
  $plugin_instances['show_lion'] = array(
    'plugin' => 'checkbox',
    'label' => t('Show lion'),
  );
  $plugin_instances['search_box'] = array(
    'plugin' => 'checkbox',
    'label' => t('Show search box'),
  );

  $plugin_instances['branding_logo'] = array(
    'plugin' => 'paddle_core_branding',
    'allowed_values' => array('logo' => TRUE, 'header_show_logo' => TRUE),
  );
  $plugin_instances['boxmodel_logo'] = array(
    'plugin' => 'boxmodel',
    'selector' => '#logo img',
    'allowed_values' => array('margin_top' => TRUE, 'margin_left' => TRUE),
  );
  $plugin_instances['header_title_text'] = array(
    'plugin' => 'paddle_core_header',
    'allowed_values' => array('header_title' => TRUE),
    'default_values' => array('header_title' => 'Vlaamse organisatie'),
  );
  $plugin_instances['boxmodel_header_title'] = array(
    'plugin' => 'boxmodel',
    'selector' => 'h1.header-title',
    'allowed_values' => array('margin_top' => TRUE, 'margin_left' => TRUE),
  );
  $plugin_instances['header_subtitle_text'] = array(
    'plugin' => 'paddle_core_header',
    'allowed_values' => array('header_subtitle' => TRUE),
    'default_values' => array('header_subtitle' => 'voor een doelgroep'),
  );
  $plugin_instances['boxmodel_header_subtitle'] = array(
    'plugin' => 'boxmodel',
    'selector' => 'h2.header-subtitle',
    'allowed_values' => array('margin_top' => TRUE, 'margin_left' => TRUE),
  );

  // Patterned background plugin definitions.
  $background_mapping = array(
    'header_background' => 'header',
    'footer_background' => 'footer',
  );

  foreach ($background_mapping as $key => $value) {
    $plugin_instances[$key] = array(
      'plugin' => 'background',
      'selector' => $value,
      'allowed_values' => array(
        'background_color' => TRUE,
        'background_pattern' => array(
          'tiles' => array(
            'title' => t('Stripe'),
            'file' => drupal_get_path('theme', 'paddle_theme') . '/patterns/stripe.png',
          ),
          'squares' => array(
            'title' => t('Squares'),
            'file' => drupal_get_path('theme', 'paddle_theme') . '/patterns/square_bg.png',
          ),
          'weave' => array(
            'title' => t('Binding Dark'),
            'file' => drupal_get_path('theme', 'paddle_theme') . '/patterns/binding_dark.png',
          ),
          'honey' => array(
            'title' => t('Honey'),
            'file' => drupal_get_path('theme', 'paddle_theme') . '/patterns/honey.png',
          ),
          'p6' => array(
            'title' => t('Textile'),
            'file' => drupal_get_path('theme', 'paddle_theme') . '/patterns/p6.png',
          ),
          'squared_metal' => array(
            'title' => t('Squared Metal'),
            'file' => drupal_get_path('theme', 'paddle_theme') . '/patterns/squared_metal.png',
          ),
          'subtle_carbon' => array(
            'title' => t('Subtle Carbon'),
            'file' => drupal_get_path('theme', 'paddle_theme') . '/patterns/subtle_carbon.png',
          ),
          'white_carbonfiber' => array(
            'title' => t('White Carbonfiber'),
            'file' => drupal_get_path('theme', 'paddle_theme') . '/patterns/white_carbonfiber.png',
          ),
        ),
        'background_image' => TRUE,
        'background_repeat' => TRUE,
        'background_attachment' => TRUE,
        'background_position' => TRUE,
      ),
      'default_values' => array('background_color' => 'FFFFFF'),
    );
  }

  $plugin_instances['show_search_box'] = array(
    'plugin' => 'checkbox',
    'label' => t('Show search box'),
    'default_values' => array(
      'show_search_box' => TRUE,
    ),
  );

  $plugin_instances['search_box_options'] = array(
    'plugin' => 'paddle_core_search',
  );

  $plugin_instances['menu_style'] = array(
    'plugin' => 'paddle_core_menu_style',
  );

  $plugin_instances['header_image'] = array(
    'plugin' => 'background',
    'selector' => 'header .header-background-canvas',
    'allowed_values' => array(
      'background_image' => array(
        'min_resolution' => '1140x100',
        'max_resolution' => '1140x1140',
      ),
      'repeat' => TRUE,
      'attachment' => TRUE,
      'position' => TRUE,
    ),
  );

  $font_mapping = array(
    'header_title_font' => '.header-title',
    'header_subtitle_font' => '.header-subtitle',
  );

  foreach ($font_mapping as $key => $value) {
    $plugin_instances[$key] = array(
      'plugin' => 'font',
      'selector' => $value,
      'allowed_values' => array(
        'font_size' => array(
          '35px' => t('Largest'),
          '30px' => t('Very large'),
          '25px' => t('Large'),
          '20px' => t('Normal'),
          '18px' => t('Smaller'),
          '15px' => t('Very small'),
          '14px' => t('Smallest'),
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
        'font_size' => '20px',
      ),
    );
  }

  // 3. Body.
  // Pane settings.
  $plugin_instances['display_pane_top_as_h2'] = array(
    'plugin' => 'checkbox',
    'label' => t("Make pane titles accessible following the WCAG guidelines. Take care: this setting can have a visual impact."),
    'default_values' => array(
      'display_pane_top_as_h2' => FALSE,
    ),
  );

  // Get all types of our nodes and create the breadcrumb trail
  // and next level checkboxes for them.
  $types = node_type_get_types();

  foreach ($types as $type) {
    $plugin_instances['show_breadcrumbs_for_' . $type->type] = array(
      'plugin' => 'checkbox',
      'label' => t('Show the breadcrumb trail for a @type',
        array('@type' => $type->name)),
      'default_values' => array('show_breadcrumbs_for_' . $type->type => TRUE),
    );
  }

  $plugin_instances['show_breadcrumbs_for_other_pages'] = array(
    'plugin' => 'checkbox',
    'label' => t('Show the breadcrumb trail for pages which are not included in a page type category'),
    'default_values' => array('show_breadcrumbs_for_other_pages' => TRUE),
  );

  foreach ($types as $type) {
    $plugin_instances['show_level_below_' . $type->type] = array(
      'plugin' => 'checkbox',
      'label' => t('Show next level menu items on a @type', array('@type' => $type->name)),
      'default_values' => array('show_level_below_' . $type->type => FALSE),
    );
  }

  // 4. Footer.
  $plugin_instances['footer'] = array(
    'plugin' => 'paddle_core_footer',
    'allowed_values' => array('footer_style' => TRUE),
  );
  $plugin_instances['show_disclaimer'] = array(
    'plugin' => 'checkbox',
    'label' => t('Show disclaimer'),
  );

  return $plugin_instances;
}

/**
 * Implements hook_paddle_color_palettes_color_selectors_alter().
 */
function vo_theme_paddle_color_palettes_color_selectors_alter(&$data, $theme) {
  if ($theme->name == 'vo_theme') {
    $data[0]['border-top-color'][] = 'body > footer';
    $data[1]['color'][] = '#block-paddle-menu-display-first-level .level-1 > .menu-item > a.active:hover';
    $data[1]['color'][] = '.menuslider-controls > button.menuslider-button:hover';
    $data[1]['background-color'][] = '#menuslider-next';
    $data[2]['background-color'][] = '#block-paddle-menu-display-first-level .level-1 > .menu-item > a:hover';
    $data[2]['background-color'][] = '#menuslider-next:hover';
    $data[2]['background-color'][] = '#menuslider-prev:hover';
    $data[2]['color'][] = '#block-paddle-menu-display-first-level .level-1 > .menu-item.active > a';
    $data[3]['border-color'][] = 'form[id*="search-api-page"] .form-type-textfield .form-text';
    $data[4]['color'][] = '.pane-facetapi .pane-title';
    $data[5]['color'][] = '.form-submit-container .form-submit:hover';
    $data[5]['color'][] = '.field-type-simple-contact-form .form-submit:hover';
    $data[5]['color'][] = '.panel-pane{} .pane-section-top';
    $data[5]['color'][] = '.panel-pane{} .pane-section-top h2';
    $data[7]['background-color'][] = '#menuslider-prev';

    $data[4]['color'][] = '.region-content h3';
    $pos = array_search('.region-content h3', $data[6]['color']);
    if ($pos !== FALSE) {
      unset($data[6]['color'][$pos]);
    }
  }
}
