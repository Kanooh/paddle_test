<?php

/**
 * @file
 * Hook implementations for the VO theme.
 */

/**
 * Implements hook_paddle_themer_style_set().
 */
function go_theme_paddle_themer_style_set() {
  $style_sets = array();

  // 1. Branding.
  $style_sets['branding'] = array(
    'title' => t('Branding'),
    'plugins' => array(
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
          'branding_logo_alt' => t('Logo alt tag'),
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
          'search_placeholder_popup_checkbox' => '',
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
        'title' => '',
        'plugins' => array(
          'show_disclaimer' => '',
          'footer' => '',
        ),
      ),
      'styling' => array(
        'title' => '',
        'plugins' => array(
          'footer_background' => '',
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
function go_theme_paddle_themer_plugin_instances() {
  $plugin_instances = array();

  // 1. Branding.
  $plugin_instances['branding_favicon'] = array('plugin' => 'favicon');

  $plugin_instances['color_palettes'] = array(
    'plugin' => 'color_palettes',
    'allowed_values' => array(
      'go_default' => array(
        'go_default' => array(
          'title' => 'GO Default',
          'colors' => array(
            '#c3004b',
            '#FFFFFF',
            '#FFFFFF',
            '#737373',
            '#c3004b',
            '#737373',
            '#00b2d5',
            '#EEEEEE',
          ),
        ),
        'go_default1' => array(
          'title' => 'Blue Palette',
          'colors' => array(
            '#00b2d5',
            '#FFFFFF',
            '#FFFFFF',
            '#737373',
            '#00b2d5',
            '#737373',
            '#00b2d5',
            '#EEEEEE',
          ),
        ),
        'go_default2' => array(
          'title' => 'Green Palette',
          'colors' => array(
            '#aab905',
            '#FFFFFF',
            '#FFFFFF',
            '#737373',
            '#aab905',
            '#737373',
            '#00b2d5',
            '#EEEEEE',
          ),
        ),
        'go_default3' => array(
          'title' => 'Orange Palette',
          'colors' => array(
            '#f08800',
            '#FFFFFF',
            '#FFFFFF',
            '#737373',
            '#f08800',
            '#737373',
            '#00b2d5',
            '#EEEEEE',
          ),
        ),
      ),
      'custom_palette' => array(
        'custom_palette' => array(
          'title' => t('Custom color palette'),
          'color_pickers' => TRUE,
          'colors' => array(
            '#c3004b',
            '#FFFFFF',
            '#FFFFFF',
            '#737373',
            '#c3004b',
            '#737373',
            '#00b2d5',
            '#EEEEEE',
          ),
        ),
        'custom_palette1' => array(
          'title' => t('Custom sub-palette @number', array('@number' => 1)),
          'color_pickers' => TRUE,
          'colors' => array(
            '#00b2d5',
            '#FFFFFF',
            '#FFFFFF',
            '#737373',
            '#00b2d5',
            '#737373',
            '#00b2d5',
            '#EEEEEE',
          ),
        ),
        'custom_palette2' => array(
          'title' => t('Custom sub-palette @number', array('@number' => 2)),
          'color_pickers' => TRUE,
          'colors' => array(
            '#aab905',
            '#FFFFFF',
            '#FFFFFF',
            '#737373',
            '#aab905',
            '#737373',
            '#00b2d5',
            '#EEEEEE',
          ),
        ),
        'custom_palette3' => array(
          'title' => t('Custom sub-palette @number', array('@number' => 3)),
          'color_pickers' => TRUE,
          'colors' => array(
            '#f08800',
            '#FFFFFF',
            '#FFFFFF',
            '#737373',
            '#f08800',
            '#737373',
            '#00b2d5',
            '#EEEEEE',
          ),
        ),
      ),
    ),
    'default_values' => array(
      'primary_color_palettes' => 'go_default',
    ),
  );

  // 2. Header.
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

  $plugin_instances['branding_logo_alt'] = array(
      'plugin' => 'paddle_core_branding',
      'allowed_values' => array('logo_alt' => TRUE),
      'default_values' => array('logo_alt' => 'Home'),
  );

  $plugin_instances['header_title_text'] = array(
    'plugin' => 'paddle_core_header',
    'allowed_values' => array('header_title' => TRUE),
  );
  $plugin_instances['boxmodel_header_title'] = array(
    'plugin' => 'boxmodel',
    'selector' => 'h1.header-title',
    'allowed_values' => array('margin_top' => TRUE, 'margin_left' => TRUE),
  );
  $plugin_instances['header_subtitle_text'] = array(
    'plugin' => 'paddle_core_header',
    'allowed_values' => array('header_subtitle' => TRUE),
  );
  $plugin_instances['boxmodel_header_subtitle'] = array(
    'plugin' => 'boxmodel',
    'selector' => 'h2.header-subtitle',
    'allowed_values' => array('margin_top' => TRUE, 'margin_left' => TRUE),
  );

  // Patterned background plugin definitions.
  $background_mapping = array(
    'header_background' => 'header',
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

  $plugin_instances['search_placeholder_popup_checkbox'] = array(
    'plugin' => 'checkbox',
    'label' => t('Use search box pop-up'),
    'default_values' => array(
      'search_placeholder_popup_checkbox' => FALSE,
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
        'font_family' => array(),
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
  $plugin_instances['show_disclaimer'] = array(
    'plugin' => 'checkbox',
    'label' => t('Show disclaimer'),
  );

  $plugin_instances['footer'] = array(
    'plugin' => 'paddle_core_footer',
    'allowed_values' => array('footer_style' => TRUE),
    'default_values' => array('footer_style' => 'fat_footer'),
  );

  $plugin_instances['footer_background'] = array(
    'plugin' => 'background',
    'selector' => 'footer',
    'allowed_values' => array('background_color' => FALSE),
    'default_values' => array('background_color' => 'FFFFFF'),
  );

  return $plugin_instances;
}

/**
 * Implements hook_paddle_color_palettes_color_selectors().
 */
function go_theme_paddle_color_palettes_color_selectors() {
  return array(
    array(
      'background-color' => array(
        'header',
        'footer',
        '.pane-facetapi .pane-title',
        '.panel-pane{} .pane-section-top',
        '.panel-pane{} .pane-section-bottom',
        '.panel-pane{} .pane-section-bottom:after',
        '.panel-pane{} .pane-content .carousel .flex-direction-nav i',
        '.panel-pane{} .pane-content .carousel .autoplay-control i',
      ),
      'color' => array(
        '.region-content .panel-pane{} .pane-section-body blockquote',
        '#block-paddle-menu-display-first-level .level-2 > .menu-item > a:hover',
        '.panel-pane{} .pane-content .carousel .flex-direction-nav i:hover',
        '.panel-pane{} .pane-content .carousel .autoplay-control i:hover',
      ),
      'border-color' => array(
        'div.pane-listing .paddle-landing-page-listing-teaser .node',
        '.region-content .panel-pane{} table tr',
        '#main-nav',
        '#fake-menu-bg',
        '.pane-google-custom-search .form-submit:hover',
        '.form-submit-container .form-submit:hover',
        '.form-submit:hover',
        'hr',
        'a.label-link.active-facet.active',
        '#menu-display-current-level-plus-one .menuslider-controls button:hover',
        '.mobile-menu',
        '.region-content table tr',
      ),
    ),
    array(
      'background-color' => array(
        '.panel-pane{} .pane-section-body',
        '.panel-pane{} .pane-section-body:after',
        '.panel-pane{} .pane-content .carousel .flex-direction-nav i:hover',
        '.panel-pane{} .pane-content .carousel .autoplay-control i:hover',
      ),
    ),
    array(
      'background-color' => array(
        '.form-submit-container .form-submit:hover',
        '.form-submit:hover',
        '#menu-display-top-menu',
        '.panel-pane{} .pane-section-body .form-submit:hover',
      ),
      'color' => array(
        '.panel-pane{} .pane-section-bottom a',
        '.panel-pane{} .pane-content .pane-section-top a',
        '.panel-pane{} .pane-content .pane-section-top a h2',
        '.panel-pane{} .pane-section-top',
        '.panel-pane{} .pane-section-top h2',
        '.panel-pane{} .pane-section-bottom',
        '#block-paddle-menu-display-first-level .level-1 > .menu-item:hover > a',
        '#block-paddle-menu-display-first-level .level-1 > .menu-item.active-trail > a',
        '#block-paddle-menu-display-first-level .level-1 > .menu-item > a.megadropdown-expanded',
        '#block-paddle-menu-display-first-level .level-2 > .menu-item > a',
      ),
    ),
    array(
      'color' => array(
        '.panel-pane{} .pane-section-body p',
        '.panel-pane{} .pane-section-body',
        '.panel-pane{} .pane-section-body a',
        '.panel-pane{} .menu',
        '.panel-pane{} .menu a',
      ),
    ),
    array(
      'color' => array(
        '.region-content .panel-pane{} .pane-section-body h2',
        '.region-content .panel-pane{} .pane-section-body h3',
        '.region-content .panel-pane{} .pane-section-body h4',
        '.region-content .panel-pane{} .pane-section-body h4 a',
        '.region-content .panel-pane{} .pane-section-body h5',
        '.region-content .panel-pane{} table th',
        '.pane-incoming-rss .entity.paddle-incoming-rss-feed-item--magazine > h2 > a',
        '.panel-pane{} .pane-section-body .list-news-item .date',
      ),
    ),
    array(
      'background-color' => array(
        '.panel-pane{} .pane-section-body .form-submit',
      ),
      'color' => array(
        '.header-title',
        '.header-subtitle',
        '#block-paddle-menu-display-first-level .level-1 > .menu-item > a',
        '#block-paddle-menu-display-footer-menu a',
        '#menu-display-disclaimer-menu .menu-item a',
        '.panel-pane{} .pane-section-top a',
        '.panel-pane{} .pane-section-top a h2',
        '.mobile-menu a',
        '#menu-display-top-menu a',
        '.region-content blockquote',
        '.region-content h3',
        '.region-content a',
        '.search-api-page-results h3 a',
        '.panel-pane{} .pane-section-body .form-submit:hover',
      ),
    ),
    array(
      'background-color' => array(
        '.pane-google-custom-search .form-submit',
        '.form-submit-container .form-submit',
        '.form-submit',
        '#block-paddle-menu-display-first-level .level-1 > .menu-item:hover',
        '#block-paddle-menu-display-first-level .level-1 > .active-trail',
        '#block-paddle-menu-display-first-level .level-1 > .menu-item > a.megadropdown-expanded',
        '.paddle-sub-nav',
        'figcaption.image-pane-caption:before',
      ),
      'color' => array(
        '.region-content a:hover',
        '#block-paddle-menu-display-footer-menu .level-1 > .menu-item > a',
        '#block-paddle-menu-display-current-level-plus-one .menu-item a:hover',
        '#page-title',
        '#menu-display-top-menu a:hover',
        '.form-submit-container .form-submit:hover',
        '.form-submit:hover',
      ),
      'border-top-color' => array(
        '.region-footer',
      ),
    ),
    array(
      'border-color' => array(
        '.panel-pane{} .pane-section-body',
      ),
    ),
  );
}
