<?php

/**
 * @file
 * Hook implementations for the Paddle theme.
 */

/**
 * Implements hook_paddle_themer_style_set().
 */
function paddle_theme_paddle_themer_style_set() {
  // Temporary style set for testing purposes.
  $style_sets = array();

  // 1. Branding.
  $style_sets['branding'] = array(
    'title' => t('Branding'),
    'plugins' => array(
      // Options to turn on/off global header & footer + favicon upload.
      'branding_global_header' => t('Header and footer branding'),
      'branding_favicon' => t('Favicon'),
      'color_palettes' => t('Color scheme'),
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
      'styling' => array(
        'title' => t('Styling'),
        'plugins' => array(
          'body_background' => t('Background'),
        ),
      ),
      'text' => array(
        'title' => t('WYSIWYG text'),
        'plugins' => array(
          'page_title_font' => t('Title of the content page'),
          'breadcrumb_font' => t('Breadcrumb trail'),
          'landingpage_description_font' => t('Landing page body'),
          'landingpage_pane_listing_teaser_title' => t('Listing panes title'),
          'h2_font' => t('H2'),
          'h3_font' => t('H3'),
          'h4_font' => t('H4'),
          'h5_font' => t('H5'),
          'paragraph_font' => t('Paragraph'),
          'link_bp_font' => t('Links in Basic Pages'),
          'link_bp_font_visited' => t('Visited state of links in Basic Pages'),
          'link_bp_font_hover' => t('Hover state of links in Basic Pages'),
          'list_font' => t('Lists in Basic Pages'),
          'paragraph_pane_font' => t('Paragraph in Panes'),
          'link_pane_font' => t('Links in Panes'),
          'list_pane_font' => t('Lists in Panes'),
          'blockquote_font' => t('Quote'),
        ),
      ),
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
          'footer_level_1_menu_items_font' => t('Footer links level 1'),
          'footer_level_2_menu_items_font' => t('Footer links level 2'),
          'disclaimer_link_font' => t('Disclaimer links'),
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
function paddle_theme_paddle_themer_plugin_instances() {
  $plugin_instances = array();

  // 1. Branding.
  $plugin_instances['branding_global_header'] = array(
    'plugin' => 'paddle_core_branding',
    'allowed_values' => array(
      'vo_branding' => TRUE,
      'global_vo_tokens' => TRUE,
      'federal_branding' => TRUE,
    ),
  );
  $plugin_instances['branding_favicon'] = array('plugin' => 'favicon');

  // The color palettes are a bit more special - here, in the allowed values we
  // keep the palettes definitions. Each primary palette array has a key with
  // the machine name of that palette (its definition if the first sub-element
  // which has the same key) and the other values are the sub-palettes.
  $plugin_instances['color_palettes'] = array(
    'plugin' => 'color_palettes',
    'allowed_values' => paddle_theme_define_color_palettes(),
    'default_values' => array(
      'primary_color_palettes' => 'palette_a_light',
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
  $plugin_instances['branding_logo_alt'] = array(
    'plugin' => 'paddle_core_branding',
    'allowed_values' => array('logo_alt' => TRUE),
    'default_values' => array('logo_alt' => 'Home'),
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
    'header_background' => 'body header',
    'body_background' => '.content-background-canvas',
    'footer_background' => 'body footer',
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
      'default_values' => array(),
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

  // All font plugins are configured in the same way. Define a mapping of plugin
  // machine name to selector. The configuration will be applied in a loop.
  $font_mapping = array(
    // @todo These will surely have to be adapted.
    'breadcrumb_font' => '.breadcrumb-item a, .breadcrumb-item > span',
    'header_title_font' => '.header-title',
    'header_subtitle_font' => '.header-subtitle',
    'page_title_font' => 'h1#page-title',
    'landingpage_description_font' => '.region-content .landing-page-body p',
    'landingpage_pane_listing_teaser_title' => '.pane-section-body .paddle-landing-page-listing-teaser h4 a',
    'menu_display_first-level_font' => '#menu-display-first-level a',
    'menu-display-current-level-plus-one_font' => '#menu-display-current-level-plus-one a',
    'h2_font' => '.region-content h2',
    'h3_font' => '.region-content h3',
    'h4_font' => '.region-content h4',
    'h5_font' => '.region-content h5',
    'blockquote_font' => '.region-content blockquote',
    'paragraph_font' => '.region-content p',
    'link_bp_font' => '.node-type-basic-page .region-content a:link',
    'link_bp_font_visited' => '.node-type-basic-page .region-content a:visited',
    'link_bp_font_hover' => '.node-type-basic-page .region-content a:hover',
    'list_font' => '.node-type-basic-page .region-content li',
    'paragraph_pane_font' => '.panel-pane .pane-section-body p',
    'link_pane_font' => '.panel-pane .pane-section-body a',
    'list_pane_font' => '.panel-pane .pane-section-body li',
    'footer_title_font' => '.footer-title p',
    'footer_subtitle_font' => '.footer-subtitle p',
    'footer_level_1_menu_items_font' => '#block-paddle-menu-display-footer-menu a',
    'footer_level_2_menu_items_font' => '#block-paddle-menu-display-footer-menu .level-2 a',
    'disclaimer_link_font' => '#menu-display-disclaimer-menu .menu-item a',
  );

  foreach ($font_mapping as $key => $value) {
    $plugin_instances[$key] = array(
      'plugin' => 'font',
      'selector' => $value,
      'allowed_values' => array(
        'font_family' => array(
          'Arial, "Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Arial',
          '"Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lucida',
        ),
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
        'font_family' => 'Arial, "Lucida Sans Unicode", "Lucida Grande", sans-serif',
        'font_size' => '20px',
      ),
    );
  }

  /*
   * WYSIWYG text default values
   */
  // Header-Logo Title.
  $plugin_instances['header_title_font']['default_values']['font_color'] = 'FFFFFF';
  $plugin_instances['header_title_font']['default_values']['font_size'] = '35px';
  // Header-Logo Subtitle.
  $plugin_instances['header_subtitle_font']['default_values']['font_color'] = 'FFFFFF';
  $plugin_instances['header_subtitle_font']['default_values']['font_size'] = '25px';
  // Page title.
  $plugin_instances['page_title_font']['default_values']['font_size'] = '35px';
  // Landing Page Description.
  $plugin_instances['landingpage_description_font']['default_values']['font_size'] = '25px';
  // Landing page Listing Panes title font.
  $plugin_instances['landingpage_pane_listing_teaser_title']['default_values']['font_size'] = '15px';
  // Breadcrumb font.
  $plugin_instances['breadcrumb_font']['default_values']['font_size'] = '14px';
  // h2 font.
  $plugin_instances['h2_font']['default_values']['font_size'] = '30px';
  // h3 font.
  $plugin_instances['h3_font']['default_values']['font_size'] = '20px';
  // h4 font.
  $plugin_instances['h4_font']['default_values']['font_size'] = '18px';
  // h5 font.
  $plugin_instances['h5_font']['default_values']['font_size'] = '15px';
  // Paragraph font.
  $plugin_instances['paragraph_font']['default_values']['font_size'] = '15px';
  // List font.
  $plugin_instances['list_font']['default_values']['font_size'] = '15px';
  // Link in BP font.
  $plugin_instances['link_bp_font']['default_values']['font_size'] = '15px';
  // Link in BP font - visited state.
  $plugin_instances['link_bp_font_visited']['default_values']['font_size'] = '15px';
  // Link in BP font - hover state.
  $plugin_instances['link_bp_font_hover']['default_values']['font_size'] = '15px';
  // Blockquote font.
  $plugin_instances['blockquote_font']['default_values']['font_size'] = '25px';
  // Footer link font level 1.
  $plugin_instances['footer_level_1_menu_items_font']['default_values']['font_size'] = '18px';
  $plugin_instances['footer_level_1_menu_items_font']['default_values']['font_color'] = 'ededed';
  // Footer link font level 2.
  $plugin_instances['footer_level_2_menu_items_font']['default_values']['font_size'] = '14px';
  $plugin_instances['footer_level_2_menu_items_font']['default_values']['font_color'] = 'ededed';
  // Disclaimer link font.
  $plugin_instances['disclaimer_link_font']['default_values']['font_size'] = '15px';
  // Paragraph in panes font.
  $plugin_instances['paragraph_pane_font']['default_values']['font_size'] = '15px';
  // Links in panes font.
  $plugin_instances['link_pane_font']['default_values']['font_size'] = '15px';
  // Lists in panes font.
  $plugin_instances['list_pane_font']['default_values']['font_size'] = '15px';

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
 * Implements hook_paddle_themer_styles_edit_wizard_form_attachments().
 */
function paddle_theme_paddle_themer_styles_edit_wizard_form_attachments() {
  return array(
    'css' => array(
      drupal_get_path('theme',
        'paddle_theme') . '/css/paddle_themer_styles_edit_wizard_form.css',
    ),
  );
}

/**
 * Implements hook_paddle_color_palettes_color_selectors().
 */
function paddle_theme_paddle_color_palettes_color_selectors() {
  return array(
    array(
      'background-color' => array(
        'header',
        'footer',
        '.panel-pane{} .pane-section-top',
        '.panel-pane{} .pane-section-top h2',
        '.agenda-search-pane-title.pane-section-top',
        '.agenda-search-pane-title.pane-section-top h2',
        '.pane-facetapi .pane-title',
        '.menuslider-controls button',
        'header::before',
      ),
      'color' => array(
        '.region-content .panel-pane{} .pane-section-body blockquote',
        '#menu-display-top-menu a:hover',
      ),
      'border-color' => array(
        'div.pane-listing .paddle-landing-page-listing-teaser .node',
        '#block-paddle-menu-display-first-level .level-1 > .menu-item.active-trail',
        '#block-paddle-menu-display-first-level .level-1 > .menu-item:hover',
      ),
    ),
    array(
      'background-color' => array(
        '.pane-google-custom-search .form-submit',
        '.form-submit-container .form-submit',
        '.form-submit',
        '#block-paddle-menu-display-first-level .level-1 > .active-trail',
        '.menuslider-controls button:hover',
        'figcaption.image-pane-caption:before',
      ),
      'color' => array(
        '.region-content a:hover',
        '#block-paddle-menu-display-first-level .level-1 > .menu-item:hover > a',
        '#block-paddle-menu-display-first-level .level-1 > .menu-item.active-trail:hover > a',
        '#block-paddle-menu-display-first-level .level-1 > .menu-item > a.megadropdown-expanded',
      ),
      'border-top-color' => array(
        '#block-paddle-menu-display-first-level .level-1 > .menu-item > a.megadropdown-expanded',
      ),
    ),
    array(
      'background-color' => array(
        '.panel-pane{} .pane-section-body',
        '.panel-pane{} .pane-section-bottom',
      ),
    ),
    array(
      'color' => array(
        '.panel-pane{} .pane-section-body p',
        '.panel-pane{} .pane-section-body',
        '.panel-pane{} .pane-section-body a',
        '.panel-pane{} .menu',
        '.panel-pane{} .menu a',
        '.search-pop-up:hover'
      ),
    ),
    array(
      'color' => array(
        '.panel-pane{} .pane-section-bottom a',
        '.region-content .panel-pane{} .pane-section-body h2',
        '.region-content .panel-pane{} .pane-section-body h3',
        '.pane-content .pane-section-body .paddle-cultuurnet-spotlight-details h3',
        '.region-content .panel-pane{} .pane-section-body h4',
        '.region-content .panel-pane{} .pane-section-body h4 a',
        '.region-content .panel-pane{} .pane-section-body h5',
        '.region-content .panel-pane{} table th',
        '.pane-incoming-rss .entity.paddle-incoming-rss-feed-item--magazine > h2 > a',
      ),
      'border-color' => array(
        '.region-content .panel-pane{} table tr',
      ),
    ),
    array(
      'color' => array(
        '.header-title',
        '.header-subtitle',
        '#block-paddle-menu-display-first-level .level-1 > .menu-item > a',
        '#block-paddle-menu-display-footer-menu a',
        '#menu-display-disclaimer-menu .menu-item a',
        '.panel-pane{} .pane-section-top a',
        '.panel-pane{} .pane-section-top a h2',
        '.agenda-search-pane-title.pane-section-top',
        '.agenda-search-pane-title.pane-section-top h2',
        '.mobile-menu a',
      ),
    ),
    array(
      'color' => array(
        '#block-paddle-menu-display-current-level-plus-one .menu-item a:hover',
        '#block-paddle-menu-display-first-level .level-2 > .menu-item > a:hover',
        '.region-content blockquote',
        '.region-content h3',
        '.region-content a',
        '.search-api-page-results h3 a',
      ),
      'background-color' => array(
        '#main-nav',
        '#fake-menu-bg',
        '.pane-google-custom-search .form-submit:hover',
        '.form-submit-container .form-submit:hover',
        '.form-submit:hover',
        'hr',
        'a.label-link.active-facet.active',
        '#menu-display-current-level-plus-one .menuslider-controls button:hover',
        '.mobile-menu',
        '.block-search-api-page',

      ),
      'border-color' => array(
        '.region-content table tr',
        '#block-paddle-menu-display-first-level .level-1 > .menu-item > a',
        '#block-paddle-menu-display-first-level .level-1 > .menu-item',
      ),
    ),
  );
}

/**
 * Defines all color palettes to be used by the themer.
 *
 * @return array
 *   An associative array containing all color palettes.
 */
function paddle_theme_define_color_palettes() {
  return array(
    'palette_a_light' => array(
      'palette_a_light' => array(
        'title' => 'Nile new',
        'colors' => array(
          '#39b9be',
          '#2b979d',
          '#ffffff',
          '#5e5e5e',
          '#39b9be',
          '#ffffff',
          '#1c7074',
          '#d5d5d5',
        ),
      ),
      'palette_a_light1' => array(
        'title' => 'First Darker tone Palette',
        'colors' => array(
          '#1C6F73',
          '#735781',
          '#ffffff',
          '#5e5e5e',
          '#1C6F73',
        ),
      ),
      'palette_a_light2' => array(
        'title' => 'Second White Sub Palette',
        'colors' => array(
          '#735781',
          '#926da5',
          '#ffffff',
          '#5e5e5e',
          '#735781',
        ),
      ),
      'palette_a_light3' => array(
        'title' => 'Third White Sub Palette',
        'colors' => array(
          '#2b92be',
          '#735781',
          '#ffffff',
          '#5e5e5e',
          '#2b92be',
        ),
      ),
      'palette_a_light4' => array(
        'title' => 'Featured Palette',
        'colors' => array(
          '#4ba144',
          '#5dbe55',
          '#5dbe55',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_a_light5' => array(
        'title' => 'Featured Palette 2',
        'colors' => array(
          '#735781',
          '#926da5',
          '#926da5',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_a_light6' => array(
        'title' => 'Featured Palette 3',
        'colors' => array(
          '#23789c',
          '#2b92be',
          '#2b92be',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_b_light' => array(
      'palette_b_light' => array(
        'title' => 'Amazon new',
        'colors' => array(
          '#E63120',
          '#c63131',
          '#ffffff',
          '#5e5e5e',
          '#E63120',
          '#ffffff',
          '#912d2d',
          '#d5d5d5',
        ),
      ),
      'palette_b_light1' => array(
        'title' => 'First Darker tone Palette',
        'colors' => array(
          '#C73132',
          '#7f3aa2',
          '#ffffff',
          '#5e5e5e',
          '#C73132',
        ),
      ),
      'palette_b_light2' => array(
        'title' => 'Second White sub-palette',
        'colors' => array(
          '#6b5a43',
          '#6b5a43',
          '#ffffff',
          '#5e5e5e',
          '#6b5a43',
        ),
      ),
      'palette_b_light3' => array(
        'title' => 'Third White sub-palette',
        'colors' => array(
          '#4ba144',
          '#4ba144',
          '#ffffff',
          '#5e5e5e',
          '#4ba144',
        ),
      ),
      'palette_b_light4' => array(
        'title' => 'Featured palette',
        'colors' => array(
          '#4ba144',
          '#5dbe55',
          '#5dbe55',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_b_light5' => array(
        'title' => 'Featured palette 2',
        'colors' => array(
          '#968c19',
          '#b7ab1f',
          '#b7ab1f',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_b_light6' => array(
        'title' => 'Featured palette 3',
        'colors' => array(
          '#7f3aa2',
          '#9a50be',
          '#9a50be',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_c_light' => array(
      'palette_c_light' => array(
        'title' => 'Yangtze new',
        'colors' => array(
          '#e69b1f',
          '#c68031',
          '#ffffff',
          '#5e5e5e',
          '#e69b1f',
          '#ffffff',
          '#91622d',
          '#d5d5d5',
        ),
      ),
      'palette_c_light1' => array(
        'title' => 'First Darker tone Palette',
        'colors' => array(
          '#c68031',
          '#c68031',
          '#ffffff',
          '#5e5e5e',
          '#c68031',
        ),
      ),
      'palette_c_light2' => array(
        'title' => 'Second Even Darker tone Palette',
        'colors' => array(
          '#6b5a43',
          '#a67c44',
          '#ffffff',
          '#5e5e5e',
          '#6b5a43',
        ),
      ),
      'palette_c_light3' => array(
        'title' => 'Third White sub-palette',
        'colors' => array(
          '#a23a8d',
          '#a67c44',
          '#ffffff',
          '#5e5e5e',
          '#a23a8d',
        ),
      ),
      'palette_c_light4' => array(
        'title' => 'Featured palette',
        'colors' => array(
          '#91622d',
          '#c68031',
          '#c68031',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_c_light5' => array(
        'title' => 'Featured palette 2',
        'colors' => array(
          '#5d9619',
          '#72b71f',
          '#72b71f',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_c_light6' => array(
        'title' => 'Featured palette 3',
        'colors' => array(
          '#a23a8d',
          '#be50a7',
          '#be50a7',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_d_light' => array(
      'palette_d_light' => array(
        'title' => 'Mississippi new',
        'colors' => array(
          '#e43891',
          '#c04384',
          '#ffffff',
          '#5e5e5e',
          '#e43891',
          '#ffffff',
          '#843860',
          '#d5d5d5',
        ),
      ),
      'palette_d_light1' => array(
        'title' => 'Darker Tone Palette',
        'colors' => array(
          '#c04384',
          '#c04384',
          '#ffffff',
          '#5e5e5e',
          '#c04384',
        ),
      ),
      'palette_d_light2' => array(
        'title' => 'Even Darker Tone Palette',
        'colors' => array(
          '#843860',
          '#e43891',
          '#ffffff',
          '#5e5e5e',
          '#843860',
        ),
      ),
      'palette_d_light3' => array(
        'title' => 'Third white sub-palette',
        'colors' => array(
          '#4ba144',
          '#e43891',
          '#ffffff',
          '#5e5e5e',
          '#4ba144',
        ),
      ),
      'palette_d_light4' => array(
        'title' => 'Featured palette',
        'colors' => array(
          '#4ba144',
          '#5dbe55',
          '#5dbe55',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_d_light5' => array(
        'title' => 'Featured palette 2',
        'colors' => array(
          '#417ac0',
          '#428eec',
          '#428eec',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_d_light6' => array(
        'title' => 'Featured palette 3',
        'colors' => array(
          '#a5a73c',
          '#c2c444',
          '#c2c444',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_e_light' => array(
      'palette_e_light' => array(
        'title' => 'Yenisei new',
        'colors' => array(
          '#c9d010',
          '#999e0e',
          '#ffffff',
          '#5e5e5e',
          '#c9d010',
          '#ffffff',
          '#5d6009',
          '#d5d5d5',
        ),
      ),
      'palette_e_light1' => array(
        'title' => 'First Darker Tone Palette',
        'colors' => array(
          '#999e0e',
          '#999e0e',
          '#ffffff',
          '#5e5e5e',
          '#999e0e',
        ),
      ),
      'palette_e_light2' => array(
        'title' => 'Second Even Darker Tone Palette',
        'colors' => array(
          '#5d6009',
          '#5d6009',
          '#ffffff',
          '#5e5e5e',
          '#5d6009',
        ),
      ),
      'palette_e_light3' => array(
        'title' => 'Third White sub-Palette',
        'colors' => array(
          '#4ba144',
          '#5d6009',
          '#ffffff',
          '#5e5e5e',
          '#4ba144',
        ),
      ),
      'palette_e_light4' => array(
        'title' => 'Featured palette',
        'colors' => array(
          '#5d6009',
          '#999e0e',
          '#999e0e',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_e_light5' => array(
        'title' => 'Featured palette 2',
        'colors' => array(
          '#4ba144',
          '#5dbe55',
          '#5dbe55',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_e_light6' => array(
        'title' => 'Featured palette 3',
        'colors' => array(
          '#23789c',
          '#2b92be',
          '#2b92be',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_f_light' => array(
      'palette_f_light' => array(
        'title' => 'Rhine new',
        'colors' => array(
          '#16b8ee',
          '#1491bb',
          '#ffffff',
          '#5e5e5e',
          '#16b8ee',
          '#ffffff',
          '#176d8a',
          '#d5d5d5',
        ),
      ),
      'palette_f_light1' => array(
        'title' => 'First Darker Tone Palette',
        'colors' => array(
          '#186D8A',
          '#186D8A',
          '#ffffff',
          '#5e5e5e',
          '#186D8A',
        ),
      ),
      'palette_f_light2' => array(
        'title' => 'Second Even Darker Tone Palette',
        'colors' => array(
          '#1491bb',
          '#1491bb',
          '#ffffff',
          '#5e5e5e',
          '#1491bb',
        ),
      ),
      'palette_f_light3' => array(
        'title' => 'Third white sub-Palette',
        'colors' => array(
          '#37876d',
          '#37876d',
          '#ffffff',
          '#5e5e5e',
          '#37876d',
        ),
      ),
      'palette_f_light4' => array(
        'title' => 'Featured palette',
        'colors' => array(
          '#37876d',
          '#48b290',
          '#48b290',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_f_light5' => array(
        'title' => 'Featured palette 2',
        'colors' => array(
          '#ad9e4b',
          '#c7b551',
          '#c7b551',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_f_light6' => array(
        'title' => 'Featured palette 3',
        'colors' => array(
          '#b35e20',
          '#d26e25',
          '#d26e25',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_g_light' => array(
      'palette_g_light' => array(
        'title' => 'Ob-Irtysh new',
        'colors' => array(
          '#a3cc00',
          '#8bae00',
          '#ffffff',
          '#5e5e5e',
          '#a3cc00',
          '#ffffff',
          '#6f8b00',
          '#d5d5d5',
        ),
      ),
      'palette_g_light1' => array(
        'title' => 'First Dark Tone',
        'colors' => array(
          '#6F8C00',
          '#6F8C00',
          '#ffffff',
          '#5e5e5e',
          '#6F8C00',
        ),
      ),
      'palette_g_light2' => array(
        'title' => 'Second Darker Tone',
        'colors' => array(
          '#32b2e9',
          '#32b2e9',
          '#ffffff',
          '#5e5e5e',
          '#32b2e9',
        ),
      ),
      'palette_g_light3' => array(
        'title' => 'Third white sub-palette',
        'colors' => array(
          '#d53e5e',
          '#d53e5e',
          '#ffffff',
          '#5e5e5e',
          '#d53e5e',
        ),
      ),
      'palette_g_light4' => array(
        'title' => 'Featured Palette',
        'colors' => array(
          '#b2324d',
          '#d53e5e',
          '#d53e5e',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_g_light5' => array(
        'title' => 'Featured Palette 2',
        'colors' => array(
          '#b35e20',
          '#d26e25',
          '#d26e25',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_g_light6' => array(
        'title' => 'Featured Palette 3',
        'colors' => array(
          '#b2324d',
          '#d53e5e',
          '#d53e5e',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_h_light' => array(
      'palette_h_light' => array(
        'title' => 'Parana new',
        'colors' => array(
          '#b05dbf',
          '#944ea1',
          '#ffffff',
          '#5e5e5e',
          '#b05dbf',
          '#ffffff',
          '#753f7f',
          '#d5d5d5',
        ),
      ),
      'palette_h_light1' => array(
        'title' => 'First Dark Tone',
        'colors' => array(
          '#763F80',
          '#763F80',
          '#ffffff',
          '#5e5e5e',
          '#763F80',
        ),
      ),
      'palette_h_light2' => array(
        'title' => 'Second Darker Tone',
        'colors' => array(
          '#36A9D8',
          '#36A9D8',
          '#ffffff',
          '#5e5e5e',
          '#36A9D8',
        ),
      ),
      'palette_h_light3' => array(
        'title' => 'Third white sub-palette',
        'colors' => array(
          '#b35e20',
          '#b35e20',
          '#ffffff',
          '#5e5e5e',
          '#b35e20',
        ),
      ),
      'palette_h_light4' => array(
        'title' => 'Featured Palette',
        'colors' => array(
          '#37876d',
          '#49B291',
          '#49B291',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_h_light5' => array(
        'title' => 'Featured Palette 2',
        'colors' => array(
          '#b35e20',
          '#d26e25',
          '#d26e25',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_h_light6' => array(
        'title' => 'Featured Palette 3',
        'colors' => array(
          '#ad9e4b',
          '#c7b551',
          '#c7b551',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_i_light' => array(
      'palette_i_light' => array(
        'title' => 'Chambeshi new',
        'colors' => array(
          '#d53e5e',
          '#b2324d',
          '#ffffff',
          '#5e5e5e',
          '#d53e5e',
          '#ffffff',
          '#86263b',
          '#d5d5d5',
        ),
      ),
      'palette_i_light1' => array(
        'title' => 'First Dark Tone',
        'colors' => array(
          '#86263b',
          '#86263b',
          '#ffffff',
          '#5e5e5e',
          '#86263b',
        ),
      ),
      'palette_i_light2' => array(
        'title' => 'Second Darker Tone',
        'colors' => array(
          '#35ABDD',
          '#35ABDD',
          '#ffffff',
          '#5e5e5e',
          '#35ABDD',
        ),
      ),
      'palette_i_light3' => array(
        'title' => 'Third Darker Tone',
        'colors' => array(
          '#968c19',
          '#968c19',
          '#ffffff',
          '#5e5e5e',
          '#968c19',
        ),
      ),
      'palette_i_light4' => array(
        'title' => 'Featured Palette',
        'colors' => array(
          '#968c19',
          '#b7ab1f',
          '#b7ab1f',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_i_light5' => array(
        'title' => 'Featured Palette 2',
        'colors' => array(
          '#7f3aa2',
          '#9a50be',
          '#9a50be',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_i_light6' => array(
        'title' => 'Featured Palette 3',
        'colors' => array(
          '#a67c44',
          '#c88b39',
          '#c88b39',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_j_light' => array(
      'palette_j_light' => array(
        'title' => 'Amur new',
        'colors' => array(
          '#3ecbab',
          '#4da692',
          '#ffffff',
          '#5e5e5e',
          '#3ecbab',
          '#ffffff',
          '#42796d',
          '#d5d5d5',
        ),
      ),
      'palette_j_light1' => array(
        'title' => 'Dark Tone',
        'colors' => array(
          '#42796d',
          '#42796d',
          '#ffffff',
          '#5e5e5e',
          '#42796d',
        ),
      ),
      'palette_j_light2' => array(
        'title' => 'Darker Tone',
        'colors' => array(
          '#35ABDD',
          '#35ABDD',
          '#ffffff',
          '#5e5e5e',
          '#35ABDD',
        ),
      ),
      'palette_j_light3' => array(
        'title' => 'Third white sub-palette',
        'colors' => array(
          '#15465b',
          '#15465b',
          '#ffffff',
          '#5e5e5e',
          '#15465b',
        ),
      ),
      'palette_j_light4' => array(
        'title' => 'Featured Palette',
        'colors' => array(
          '#15465b',
          '#23789c',
          '#23789c',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_j_light5' => array(
        'title' => 'Featured Palette 2',
        'colors' => array(
          '#ad9e4b',
          '#c7b551',
          '#c7b551',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_j_light6' => array(
        'title' => 'Featured Palette 3',
        'colors' => array(
          '#b2324d',
          '#d53e5e',
          '#d53e5e',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_k_light' => array(
      'palette_k_light' => array(
        'title' => 'Lena new',
        'colors' => array(
          '#8ed2ee',
          '#71a3b8',
          '#ffffff',
          '#5e5e5e',
          '#8ed2ee',
          '#ffffff',
          '#4b6c7a',
          '#d5d5d5',
        ),
      ),
      'palette_k_light1' => array(
        'title' => 'First Dark Tone',
        'colors' => array(
          '#4b6c7a',
          '#4b6c7a',
          '#ffffff',
          '#5e5e5e',
          '#4b6c7a',
        ),
      ),
      'palette_k_light2' => array(
        'title' => 'Second Darker Tone',
        'colors' => array(
          '#35ABDD',
          '#35ABDD',
          '#ffffff',
          '#5e5e5e',
          '#35ABDD',
        ),
      ),
      'palette_k_light3' => array(
        'title' => 'Third white sub-palette',
        'colors' => array(
          '#795032',
          '#795032',
          '#ffffff',
          '#5e5e5e',
          '#795032',
        ),
      ),
      'palette_k_light4' => array(
        'title' => 'Featured Palette',
        'colors' => array(
          '#795032',
          '#d18955',
          '#d18955',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_k_light5' => array(
        'title' => 'Featured Palette 2',
        'colors' => array(
          '#4e7e71',
          '#75c4af',
          '#75c4af',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_k_light6' => array(
        'title' => 'Featured Palette 3',
        'colors' => array(
          '#767344',
          '#a5a059',
          '#a5a059',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_l_light' => array(
      'palette_l_light' => array(
        'title' => 'Mekong new',
        'colors' => array(
          '#abd580',
          '#95b871',
          '#ffffff',
          '#5e5e5e',
          '#abd580',
          '#ffffff',
          '#627a4b',
          '#d5d5d5',
        ),
      ),
      'palette_l_light1' => array(
        'title' => 'First Dark Tone',
        'colors' => array(
          '#627a4b',
          '#627a4b',
          '#ffffff',
          '#5e5e5e',
          '#627a4b',
        ),
      ),
      'palette_l_light2' => array(
        'title' => 'Second Darker Tone',
        'colors' => array(
          '#35ABDD',
          '#35ABDD',
          '#ffffff',
          '#5e5e5e',
          '#35ABDD',
        ),
      ),
      'palette_l_light3' => array(
        'title' => 'Third white sub-palette',
        'colors' => array(
          '#a66e45',
          '#a66e45',
          '#ffffff',
          '#5e5e5e',
          '#a66e45',
        ),
      ),
      'palette_l_light4' => array(
        'title' => 'Featured Palette',
        'colors' => array(
          '#6759a5',
          '#8371d0',
          '#8371d0',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_l_light5' => array(
        'title' => 'Featured Palette 2',
        'colors' => array(
          '#a66e45',
          '#d18955',
          '#d18955',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_l_light6' => array(
        'title' => 'Featured Palette 3',
        'colors' => array(
          '#a5599b',
          '#d071c3',
          '#d071c3',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_m_light' => array(
      'palette_m_light' => array(
        'title' => 'Mackenzie new',
        'colors' => array(
          '#eec28e',
          '#b89871',
          '#ffffff',
          '#5e5e5e',
          '#eec28e',
          '#ffffff',
          '#7a654b',
          '#d5d5d5',
        ),
      ),
      'palette_m_light1' => array(
        'title' => 'First Dark Tone',
        'colors' => array(
          '#795032',
          '#a66e45',
          '#ffffff',
          '#5e5e5e',
          '#795032',
        ),
      ),
      'palette_m_light2' => array(
        'title' => 'Second Darker Tone',
        'colors' => array(
          '#35ABDD',
          '#35ABDD',
          '#ffffff',
          '#5e5e5e',
          '#35ABDD',
        ),
      ),
      'palette_m_light3' => array(
        'title' => 'Third white sub-palette',
        'colors' => array(
          '#7e4e4f',
          '#7e4e4f',
          '#ffffff',
          '#5e5e5e',
          '#7e4e4f',
        ),
      ),
      'palette_m_light4' => array(
        'title' => 'Featured Palette',
        'colors' => array(
          '#7e4e4f',
          '#c47576',
          '#c47576',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_m_light5' => array(
        'title' => 'Featured Palette 2',
        'colors' => array(
          '#c47576',
          '#ee8e90',
          '#ee8e90',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_m_light6' => array(
        'title' => 'Featured Palette 3',
        'colors' => array(
          '#6759a5',
          '#8371d0',
          '#8371d0',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_n_light' => array(
      'palette_n_light' => array(
        'title' => 'OVAM new',
        'colors' => array(
          '#b8c400',
          '#6F8042',
          '#ffffff',
          '#5e5e5e',
          '#b8c400',
          '#ffffff',
          '#515D30',
          '#d5d5d5',
        ),
      ),
      'palette_n_light1' => array(
        'title' => 'First Darker Tone palette',
        'colors' => array(
          '#99411E',
          '#C65327',
          '#ffffff',
          '#5e5e5e',
          '#99411E',
        ),
      ),
      'palette_n_light2' => array(
        'title' => 'Second white sub-Palette',
        'colors' => array(
          '#0078B9',
          '#0098EB',
          '#ffffff',
          '#5e5e5e',
          '#0078B9',
        ),
      ),
      'palette_n_light3' => array(
        'title' => 'Third white Sub Palette',
        'colors' => array(
          '#C64479',
          '#C64479',
          '#ffffff',
          '#5e5e5e',
          '#C64479',
        ),
      ),
      'palette_n_light4' => array(
        'title' => 'Ovam Featured palette',
        'colors' => array(
          '#C64479',
          '#CB5786',
          '#D77EA2',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_n_light5' => array(
        'title' => 'Ovam Featured palette 2',
        'colors' => array(
          '#99411E',
          '#DA6E44',
          '#DA6E44',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_n_light6' => array(
        'title' => 'Ovam Featured palette 3',
        'colors' => array(
          '#0098EB',
          '#1FB0FF',
          '#1FB0FF',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_o_light' => array(
      'palette_o_light' => array(
        'title' => 'VIA new',
        'colors' => array(
          '#31789F',
          '#055B8A',
          '#ffffff',
          '#5e5e5e',
          '#31789F',
          '#ffffff',
          '#108fd4',
          '#d5d5d5',
        ),
      ),
      'palette_o_light1' => array(
        'title' => 'Dark VIA Sub 2',
        'colors' => array(
          '#FFBB06',
          '#BF9933',
          '#ffffff',
          '#5e5e5e',
          '#FFBB06',
        ),
      ),
      'palette_o_light2' => array(
        'title' => 'Darker VIA Sub 3',
        'colors' => array(
          '#FF3E06',
          '#BF5333',
          '#ffffff',
          '#5e5e5e',
          '#FF3E06',
        ),
      ),
      'palette_o_light3' => array(
        'title' => 'Third white sub-palette',
        'colors' => array(
          '#508A0D',
          '#BF5333',
          '#ffffff',
          '#5e5e5e',
          '#508A0D',
        ),
      ),
      'palette_o_light4' => array(
        'title' => 'Featured VIA Sub 1',
        'colors' => array(
          '#508A0D',
          '#476721',
          '#325A04',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_o_light5' => array(
        'title' => 'Featured VIA Sub 2',
        'colors' => array(
          '#FFBB06',
          '#BF9933',
          '#BF9933',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_o_light6' => array(
        'title' => 'Featured VIA Sub 3',
        'colors' => array(
          '#FF3E06',
          '#A62702',
          '#A62702',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_p_light' => array(
      'palette_p_light' => array(
        'title' => 'Corporate',
        'colors' => array(
          '#FFE615',
          '#B5A51E',
          '#ffffff',
          '#5e5e5e',
          '#FFE615',
          '#ffffff',
          '#FFE615',
          '#d5d5d5',
        ),
      ),
      'palette_p_light1' => array(
        'title' => 'First Dark Corporate',
        'colors' => array(
          '#FFBB06',
          '#BF9933',
          '#ffffff',
          '#5e5e5e',
          '#FFBB06',
        ),
      ),
      'palette_p_light2' => array(
        'title' => 'Second Darker Corporate',
        'colors' => array(
          '#FF3E06',
          '#BF5333',
          '#ffffff',
          '#5e5e5e',
          '#FF3E06',
        ),
      ),
      'palette_p_light3' => array(
        'title' => 'Third Darker Corporate',
        'colors' => array(
          '#6B6B6B',
          '#BF5333',
          '#ffffff',
          '#5e5e5e',
          '#6B6B6B',
        ),
      ),
      'palette_p_light4' => array(
        'title' => 'Featured Palette',
        'colors' => array(
          '#6B6B6B',
          '#989898',
          '#989898',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_p_light5' => array(
        'title' => 'Featured Palette 2',
        'colors' => array(
          '#BF5333',
          '#A62702',
          '#A62702',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_p_light6' => array(
        'title' => 'Featured Palette 3',
        'colors' => array(
          '#BF9933',
          '#A67902',
          '#A67902',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_q_light' => array(
      'palette_q_light' => array(
        'title' => 'VEA Energiesparen',
        'colors' => array(
          '#105269',
          '#4b7284',
          '#ffffff',
          '#6b6c6d',
          '#105269',
          '#ffffff',
          '#728f9b',
          '#c2c3c4',
        ),
      ),
      'palette_q_light1' => array(
        'title' => 'First Dark Corporate',
        'colors' => array(
          '#075e8d',
          '#075e8d',
          '#ffffff',
          '#6b6c6d',
          '#075e8d',
        ),
      ),
      'palette_q_light2' => array(
        'title' => 'Second Darker Corporate',
        'colors' => array(
          '#c83c22',
          '#c83c22',
          '#ffffff',
          '#6b6c6d',
          '#c83c22',
        ),
      ),
      'palette_q_light3' => array(
        'title' => 'Third Darker Corporate',
        'colors' => array(
          '#4aa832',
          '#4aa832',
          '#ffffff',
          '#6b6c6d',
          '#1A5924',
        ),
      ),
      'palette_q_light4' => array(
        'title' => 'Featured Palette',
        'colors' => array(
          '#c83c22',
          '#eba285',
          '#eba285',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_q_light5' => array(
        'title' => 'Featured Palette 2',
        'colors' => array(
          '#BF5333',
          '#A62702',
          '#A62702',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_q_light6' => array(
        'title' => 'Featured Palette 3',
        'colors' => array(
          '#4aa832',
          '#4aa832',
          '#4aa832',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_r_light' => array(
      'palette_r_light' => array(
        'title' => 'Onderwijs',
        'colors' => array(
          '#15465b',
          '#2D94BF',
          '#ffffff',
          '#5e5e5e',
          '#14475C',
          '#ffffff',
          '#2A5C71',
          '#d5d5d5',
        ),
      ),
      'palette_r_light1' => array(
        'title' => 'First Dark Corporate',
        'colors' => array(
          '#4ba144',
          '#4ba144',
          '#ffffff',
          '#5e5e5e',
          '#25582F',
        ),
      ),
      'palette_r_light2' => array(
        'title' => 'Second Darker Corporate',
        'colors' => array(
          '#543F5E',
          '#6F5B80',
          '#ffffff',
          '#5e5e5e',
          '#50405D',
        ),
      ),
      'palette_r_light3' => array(
        'title' => 'Third Darker Corporate',
        'colors' => array(
          '#d26e25',
          '#AE602F',
          '#ffffff',
          '#5e5e5e',
          '#BF9933',
        ),
      ),
      'palette_r_light4' => array(
        'title' => 'Featured Palette',
        'colors' => array(
          '#15465b',
          '#2b92be',
          '#2b92be',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_r_light5' => array(
        'title' => 'Featured Palette 2',
        'colors' => array(
          '#4ba144',
          '#5dbe55',
          '#5dbe55',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_r_light6' => array(
        'title' => 'Featured Palette 3',
        'colors' => array(
          '#543F5E',
          '#926da5',
          '#926da5',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'palette_s_light' => array(
      'palette_s_light' => array(
        'title' => 'Kañooh',
        'colors' => array(
          '#005384',
          '#2e8fcb',
          '#ffffff',
          '#5f5f5f',
          '#005384',
          '#ffffff',
          '#08abe1',
          '#d5d5d5',
        ),
      ),
      'palette_s_light1' => array(
        'title' => 'First Dark Corporate',
        'colors' => array(
          '#2e8fcb',
          '#2370a0',
          '#ffffff',
          '#5f5f5f',
          '#2370a0',
        ),
      ),
      'palette_s_light2' => array(
        'title' => 'Second Darker Corporate',
        'colors' => array(
          '#50bbac',
          '#248d7e',
          '#ffffff',
          '#5f5f5f',
          '#248d7e',
        ),
      ),
      'palette_s_light3' => array(
        'title' => 'Third Darker Corporate',
        'colors' => array(
          '#9570a4',
          '#7c5d88',
          '#ffffff',
          '#5f5f5f',
          '#7c5d88',
        ),
      ),
      'palette_s_light4' => array(
        'title' => 'Featured Palette',
        'colors' => array(
          '#2e8fcb',
          '#2370a0',
          '#2370a0',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_s_light5' => array(
        'title' => 'Featured Palette 2',
        'colors' => array(
          '#50bbac',
          '#248d7e',
          '#248d7e',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'palette_s_light6' => array(
        'title' => 'Featured Palette 3',
        'colors' => array(
          '#9570a4',
          '#7c5d88',
          '#7c5d88',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'expoo' => array(
      'expoo' => array(
        'title' => 'EXPOO',
        'colors' => array(
          '#a22621',
          '#7E7E7E',
          '#ffffff',
          '#000000',
          '#a22621',
          '#ffffff',
          '#a22621',
          '#d5d5d5',
        ),
      ),
      'expoo_1' => array(
        'title' => 'Expoo Blue White',
        'colors' => array(
          '#6794b1',
          '#FFFFFF',
          '#FFFFFF',
          '#000000',
          '#6794b1',
        ),
      ),
      'expoo_2' => array(
        'title' => 'Expoo Orange White',
        'colors' => array(
          '#d9861d',
          '#FFFFFF',
          '#FFFFFF',
          '#000000',
          '#d9861d',
        ),
      ),
      'expoo_3' => array(
        'title' => 'Expoo Green White',
        'colors' => array(
          '#d7da28',
          '#FFFFFF',
          '#FFFFFF',
          '#000000',
          '#d7da28',
        ),
      ),
      'expoo_4' => array(
        'title' => 'Expoo Green Grey',
        'colors' => array(
          '#d7da2b',
          '#ffffff',
          '#7E7E7E',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'expoo_5' => array(
        'title' => 'Expoo Grey Blue',
        'colors' => array(
          '#7E7E7E',
          '#ffffff',
          '#6794b1',
          '#ffffff',
          '#ffffff',
        ),
      ),
      'expoo_6' => array(
        'title' => 'Expoo Orange Grey',
        'colors' => array(
          '#d9861d',
          '#ffffff',
          '#7E7E7E',
          '#ffffff',
          '#ffffff',
        ),
      ),
    ),
    'vlabel' => array(
      'vlabel' => array(
        'title' => 'VLABEL',
        'colors' => array(
          '#6b6c6d',
          '#afa700',
          '#ffffff',
          '#5e5e5e',
          '#f6e900',
          '#ffffff',
          '#f6e900',
          '#d5d5d5',
        ),
      ),
      'vlabel_1' => array(
        'title' => 'Vlabel Yellow White',
        'colors' => array(
          '#dec32c',
          '#FFFFFF',
          '#FFFFFF',
          '#6b6c6d',
          '#6b6c6d',
        ),
      ),
      'vlabel_2' => array(
        'title' => 'Vlabel Red White',
        'colors' => array(
          '#c03b50',
          '#FFFFFF',
          '#FFFFFF',
          '#6b6c6d',
          '#6b6c6d',
        ),
      ),
      'vlabel_3' => array(
        'title' => 'Vlabel Purple White',
        'colors' => array(
          '#402d99',
          '#FFFFFF',
          '#FFFFFF',
          '#6b6c6d',
          '#6b6c6d',
        ),
      ),
      'vlabel_4' => array(
        'title' => 'Vlabel Blue White',
        'colors' => array(
          '#3d9fc6',
          '#ffffff',
          '#ffffff',
          '#6b6c6d',
          '#6b6c6d',
        ),
      ),
      'vlabel_5' => array(
        'title' => 'Vlabel Orange White',
        'colors' => array(
          '#e0762c',
          '#ffffff',
          '#ffffff',
          '#6b6c6d',
          '#6b6c6d',
        ),
      ),
      'vlabel_6' => array(
        'title' => 'Vlabel Green White',
        'colors' => array(
          '#35a501',
          '#ffffff',
          '#ffffff',
          '#6b6c6d',
          '#6b6c6d',
        ),
      ),
    ),
    'top_limburg' => array(
      'top_limburg' => array(
        'title' => 'T.OP Limburg',
        'colors' => array(
          '#000000',
          '#646464',
          '#ffffff',
          '#000000',
          '#646464',
          '#ffffff',
          '#333333',
          '#ffffff',
        ),
      ),
      'top_limburg_1' => array(
        'title' => 'T.OP Limburg Black White',
        'colors' => array(
          '#000000',
          '#FFFFFF',
          '#FFFFFF',
          '#000000',
          '#000000',
        ),
      ),
      'top_limburg_2' => array(
        'title' => 'T.OP Limburg White Black',
        'colors' => array(
          '#000000',
          '#646464',
          '#ffffff',
          '#000000',
          '#646464',
        ),
      ),
      'top_limburg_3' => array(
        'title' => 'T.OP Limburg White Black',
        'colors' => array(
          '#000000',
          '#646464',
          '#ffffff',
          '#000000',
          '#646464',
        ),
      ),
      'top_limburg_4' => array(
        'title' => 'T.OP Limburg White Black',
        'colors' => array(
          '#000000',
          '#646464',
          '#ffffff',
          '#000000',
          '#646464',
        ),
      ),
      'top_limburg_5' => array(
        'title' => 'T.OP Limburg White Black',
        'colors' => array(
          '#000000',
          '#646464',
          '#ffffff',
          '#000000',
          '#646464',
        ),
      ),
      'top_limburg_6' => array(
        'title' => 'T.OP Limburg White Black',
        'colors' => array(
          '#000000',
          '#646464',
          '#ffffff',
          '#000000',
          '#646464',
        ),
      ),
    ),
  );
}
