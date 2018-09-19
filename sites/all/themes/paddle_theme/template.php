<?php
/**
 * @file
 * Theme implementation to add custom templating
 */

/**
 * Implements css_alter().
 */
function paddle_theme_css_alter(&$css) {
  unset($css[drupal_get_path('module', 'node') . '/node.css']);
  unset($css[drupal_get_path('module', 'system') . '/system.menus.css']);
  // If paddle theme branded is enabled then add branded fonts.
  // We need Klavika fonts for the toolbar of vlaanderen.be.
  $themes = list_themes();
  if (!empty($themes['paddle_theme_branded']) && $themes['paddle_theme_branded']->status == 1) {
    $css_fonts = drupal_get_path('theme', 'paddle_theme_branded') . '/css/branded-fonts.css';
    drupal_add_css($css_fonts, array('every_page' => FALSE, 'group' => 100));
  }

  // Remove the default styles from the calendar module.
  if (module_exists('paddle_calendar')) {
    unset($css[drupal_get_path('module', 'calendar') . '/css/calendar_multiday.css']);
  }

  // Remove the default tooltip style from the glossary module.
  if (module_exists('paddle_glossary')) {
    unset($css[drupal_get_path('module', 'paddle_glossary') . '/css/tooltip.css']);
  }

  // Remove the default css from the poll module.
  if (module_exists('poll')) {
    unset($css[drupal_get_path('module', 'poll') . '/poll.css']);
  }
}

/**
 * Implements js_alter().
 */
function paddle_theme_js_alter(&$javascript) {
  unset($javascript[drupal_get_path('module', 'ctools') . '/js/modal.js']);
  unset($javascript['misc/jquery.js']);
}

/**
 * Implements hook_library_alter().
 */
function paddle_theme_library_alter(&$javascript, $module) {
  // Replace jQuery Form plugin.
  // @see jquery_update module.
  $path = drupal_get_path('theme', 'paddle_theme');
  $javascript['jquery.form']['js']['misc/jquery.form.js']['data'] = $path . '/scripts/libs/jquery.form.min.js';
  $javascript['jquery.form']['version'] = '2.69';
}

/**
 * Implements theme_preprocess_html().
 */
function paddle_theme_preprocess_html(&$variables) {
  // Drupal core always adds no-sidebars class.
  // @see template_preprocess_html()
  $key = array_search('no-sidebars', $variables['classes_array']);
  unset($variables['classes_array'][$key]);

  drupal_add_js(drupal_get_path('theme', 'paddle_theme') . '/scripts/libs/jquery.1.7.2.min.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'group' => JS_LIBRARY,
      'every_page' => TRUE,
      'weight' => -20,
    ));

  $js_settings = array(
    'type' => 'file',
    'scope' => 'header',
    'group' => JS_THEME,
    'every_page' => TRUE,
  );

  drupal_add_js(drupal_get_path('theme', 'paddle_theme') . '/scripts/jquery.easing.1.3.js', $js_settings);
  drupal_add_js(drupal_get_path('theme', 'paddle_theme') . '/scripts/jquery.vo_menuslider.js', $js_settings);
  drupal_add_js(drupal_get_path('theme', 'paddle_theme') . '/scripts/menus.js', $js_settings);
  drupal_add_js(drupal_get_path('theme', 'paddle_theme') . '/scripts/search.js', $js_settings);
  drupal_add_js(drupal_get_path('theme', 'paddle_theme') . '/scripts/jquery.details.min.js', $js_settings);
  drupal_add_js(drupal_get_path('theme', 'paddle_theme') . '/scripts/details.js', $js_settings);
  drupal_add_js(drupal_get_path('theme', 'paddle_theme') . '/scripts/accessibility.js', $js_settings);
  // Strip entire left column on front page.
  if ($variables['is_front'] === TRUE) {
    if (isset($variables['page']['left_column'])) {
      unset($variables['page']['left_column']);
    }
  }

  // Add information about the left column.
  if (isset($variables['page']['left_column']) && $children = element_children($variables['page']['left_column'])) {
    $variables['classes_array'][] = 'left-column';
  }
  else {
    $variables['classes_array'][] = 'no-left-column';
  }

  $variables['paddle_custom_javascript'] = variable_get('paddle_custom_javascript', '');
}

/**
 * Implements theme_breadcrumb().
 */
function paddle_theme_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  $breadcrumb = array_unique($breadcrumb);

  if (!empty($breadcrumb)) {
    // Output of the breadcrumb.
    $crumbs = '';
    for ($i = 0; $i < count($breadcrumb); $i++) {
      $last_item_class = ($i == count($breadcrumb) - 1) ? ' breadcrumb-item-last' : '';
      // Adds a span to the Home breadcrumb to be able to style it as a icon.
      if ($i == 0 && preg_match("@<a ([^>]+)>(.+)</a>@i", $breadcrumb[$i], $matches)) {
        $crumbs .= '<li class="breadcrumb-item breadcrumb-item-' . $i . ' breadcrumb-home' . $last_item_class . '"><a ' . $matches[1] . '><span>' . $matches[2] . '</span></a></li>';
      }
      elseif ($i == count($breadcrumb) - 1) {
        $breadcrumb = '<span class="breadcrumb-title">' . strip_tags($breadcrumb[$i]) . '</span>';
        $crumbs .= '<li class="breadcrumb-item breadcrumb-item-' . $i . $last_item_class . '">' . $breadcrumb . '</li>';
      }
      else {
        $crumbs .= '<li class="breadcrumb-item breadcrumb-item-' . $i . '">' . $breadcrumb[$i] . '</li>';
      }
    }
    $accessibility_string = t('You are here');

    return '<ul aria-label="' . $accessibility_string . '">' . $crumbs . '</ul>';
  }
}

/**
 * Implements theme_preprocess_page().
 */
function paddle_theme_preprocess_page(&$variables) {
  global $language;

  // Check if the disclaimer menu is disabled globally.
  $variables['show_disclaimer_menu'] = variable_get('paddle_style_show_disclaimer', TRUE);
  // Check if breadcrumbs are enabled for other pages in case this would
  // not be a node. If the page would be a node the value would be
  // overwritten later anyways.
  $variables['show_breadcrumb'] = variable_get('show_breadcrumbs_for_other_pages', TRUE);

  // Check for nodes if the breadcrumb needs to show on the page.
  if (!empty($variables['node'])) {
    // Check if the breadcrumbs are disabled globally.
    $variables['show_breadcrumb'] = variable_get('paddle_style_show_breadcrumbs_for_' . $variables['node']->type, TRUE);
    $show_breadcrumb = field_get_items('node', $variables['node'], 'field_show_breadcrumb');
    if ($show_breadcrumb !== FALSE) {
      // If the node has a field_show_breadcrumb value, use that instead of the
      // global one.
      $variables['show_breadcrumb'] = $show_breadcrumb[0]['value'];
    }
  }

  // Set the header title.
  if (variable_get('paddle_core_header_title', '')) {
    $variables['header_title'] = variable_get('paddle_core_header_title', '');
  }

  // Set the header subtitle.
  if (variable_get('paddle_core_header_subtitle', '')) {
    $variables['header_title_prefix'] = variable_get('paddle_core_header_subtitle', '');
  }

  // Set the logo alt tag.
  if (variable_get('paddle_core_logo_alt', '')) {
    $variables['logo_alt'] = variable_get('paddle_core_logo_alt', t('Home'));
  }

  // For the following content types we need to render the comments sections
  // separately because they do not got rendered by node.tpl.php because
  // they are fully panelized.
  if (
    module_exists('paddle_comment') &&
    !empty($variables['node']) &&
    !paddle_content_manager_panelized_node_uses_entity_view($variables['node']) &&
    variable_get('comment_' . $variables['node']->type, COMMENT_NODE_HIDDEN) != COMMENT_NODE_HIDDEN
  ) {
    comment_node_view($variables['node'], 'full');
    if (!empty($variables['node']->content['comments'])) {
      $variables['page']['content']['comments'] = $variables['node']->content['comments'] + array('#weight' => 99);
    }
  }

  $footer_style = variable_get('paddle_core_footer_footer_style', 'fat_footer');
  if ($footer_style == 'no_footer') {
    // Empty the footer when 'No footer' was chosen.
    $variables['page']['footer'] = '';
  }
  elseif ($footer_style == 'thin_footer') {
    // Set the footer title.
    if (variable_get('paddle_core_footer_footer_title', '')) {
      $variables['footer_title'] = variable_get('paddle_core_footer_footer_title', '');
    }

    // Set the footer subtitle.
    if (variable_get('paddle_core_footer_footer_subtitle', '')) {
      $variables['footer_subtitle'] = variable_get('paddle_core_footer_footer_subtitle', '');
    }
  }
  elseif ($footer_style == 'rich_footer') {
    $footer_id = variable_get('paddle_rich_footer_id');
    $footer = entity_load_single('paddle_rich_footer', $footer_id);
    $display = $footer->panelizer['page_manager']->display;
    $variables['page']['footer'] = panels_render_display($display);
  }

  // Set the footer style in a variable to use it as a class later.
  $variables['footer_style'] = $footer_style;

  // Check if the search_box needs to be set.
  $search_box = variable_get('paddle_style_show_search_box', TRUE);
  $search_popup = variable_get('paddle_style_search_placeholder_popup_checkbox', FALSE);
  $header_search = '';

  if ($search_box) {
    $header_search .= 'show-search';
    if ($search_popup) {
      $header_search .= ' pop-up-search';
    }
  }
  else {
    unset($variables['page']['service_links']['search_api_page_search']);
  }

  // Set the header search in a variable to use it as a class later.
  $variables['header_search'] = $header_search;

  // If branding was checked.
  if (variable_get('include_vo_branding_elements', FALSE)) {
    $vo_global_header_render_array = variable_get('vo_global_header', array());
    $variables['vo_global_header'] = render($vo_global_header_render_array);
    $vo_global_footer_render_array = variable_get('vo_global_footer', array());
    $variables['vo_global_footer'] = render($vo_global_footer_render_array);
  }

  $variables['federal_branding'] = variable_get('federal_branding', FALSE);

  if (!empty($variables['federal_branding'])) {
    if (!empty($variables['page']['service_links']['locale_language'])) {
      $variables['federal_header']['language_switcher'] = $variables['page']['service_links']['locale_language'];

      // We need to hide the original language switcher.
      unset($variables['page']['service_links']['locale_language']);
    }

    $variables['federal_header']['more_info'] = array(
      '#type' => 'container',
      '#attributes' => array(
        'class' => array('federal-header-more-info'),
      ),
      'more-info-text' => array(
        '#type' => 'markup',
        '#prefix' => '<div class="more-info-text">' . t('Other official information and services') . ': ',
        '#markup' => '<a href="http://www.belgium.be/' . $language->language . '">www.belgium.be</a>',
        '#suffix' => '</div>',
      ),
      'more-info-image' => array(
        '#type' => 'markup',
        '#prefix' => '<div class="more-info-image">',
        '#markup' => '<img src="/sites/all/themes/paddle_theme/images/blgm_beLogo.gif" alt="Logo.be" />',
        '#suffix' => '</div>',
      ),
    );
  }

  // The Paddle Section Theming module might provide us with a background image.
  if (!empty($variables['paddle_section_theming_background_image_url'])) {
    drupal_add_css(
      'header > div.header-background-canvas { background-image: url(' . $variables['paddle_section_theming_background_image_url'] . '); }',
      array(
        'group' => CSS_THEME,
        'type' => 'inline',
        'preprocess' => FALSE,
        'weight' => '50',
      )
    );
  }
}

/**
 * Override or insert variables into the maintenance page template.
 */
function paddle_theme_process_maintenance_page(&$variables) {
  // Default maintenance template.
  if (variable_get('maintenance_mode', 0)) {
    $variables['content'] = variable_get('maintenance_mode_message', '');
  }
  elseif (variable_get('paddle_maintenance_mode', 0)) {
    $message = variable_get('paddle_maintenance_mode_message', t('Behind the scenes we are building a brand new website.<br>Check back soon! Need more info? Paddle to <a href="http://kanooh.be">kanooh.be</a>!'));
    $variables['content'] = filter_xss($message, array('a', 'br'));

    // Add a class to the body to indicate that we are on our maintenance page.
    $variables['classes_array'][] = 'paddle-maintenance-mode';
    if (strlen($variables['classes'])) {
      $variables['classes'] .= ' ';
    }
    $variables['classes'] .= 'paddle-maintenance-mode';

    // Change the title of the page as well.
    $variables['head_title_array']['title'] = 'Kañooh';
  }
  $variables['title'] = variable_get('paddle_maintenance_title', $variables['title']);
  $variables['theme_hook_suggestions'][] = 'page__maintenance';

  // Remove some JavaScript.
  $variables['scripts'] = '';
}

/**
 * Implements hook_block_info_alter().
 */
function paddle_theme_block_info_alter(&$blocks, $theme, $code_blocks) {
  if (isset($blocks['paddle_social_identities']['paddle_social_identities']) && ($blocks['paddle_social_identities']['paddle_social_identities']['theme'] == 'paddle_theme' || $blocks['paddle_social_identities']['paddle_social_identities']['theme'] == 'paddle_theme_branded')) {
    $blocks['paddle_social_identities']['paddle_social_identities']['region'] = 'sidebar_second';
    $blocks['paddle_social_identities']['paddle_social_identities']['status'] = 1;
  }
}

/**
 * Implements paddle_menu_display_menu_items().
 */
function paddle_theme_paddle_menu_display_menu_items($variables) {
  $items = $variables['items'];
  $menu_display = $variables['menu_display'];

  $output = '';
  $item_list = array();
  $level_depth = '';
  $div_level_class = '';

  foreach ($variables['items'] as $item) {
    $attributes = array();
    if ($item['a_class']) {
      $attributes = array('attributes' => array('class' => array($item['a_class'])));
    }

    if (in_array($menu_display->name, array('first_level', 'current_level_plus_one'))) {
      // Add an empty span to which the 'greater than' symbol can be set on
      // menu links in the mobile menu.
      $list_icon = '<span aria-hidden="true" class="list-icon"></span>';
      // Add span tags to the link text so the menu slider is able to determine
      // the actual text width of the menu.
      $item['text'] = $list_icon . '<span class="menu-link-text">' . $item['text'] . '</span>';
      $attributes['html'] = TRUE;
    }
    $content = isset($item['content']) ? $item['content'] : '';
    if ($item['depth'] >= $menu_display->fromLevel() && $item['depth'] <= $menu_display->toLevel()) {
      // Extra class to display actual level in the wrapper div.
      $level_depth = $item['depth'];
      $div_level_class = ' level-' . $level_depth;
      // Adds additional classes to provide columnar menus.
      if ($menu_display->name == 'footer_menu' && $level_depth == 1) {
        // With more than 6 footer-menu top-level items we always divide in six
        // columns. Otherwise we can have 2,3 and 4 columns.
        $grid = 12;
        $grid_division_class = 'col-md-12';
        if (count($items) == 2) {
          $grid_division_class = 'col-md-6';
        }
        elseif (count($items) == 3) {
          $grid_division_class = 'col-md-4';
        }
        elseif (count($items) == 4) {
          $grid_division_class = 'col-md-3';
        }
        // Five menu items or more are displayed in 6 columns per row.
        elseif (count($items) >= 5) {
          $grid_division_class = (count($item_list) > 1 && count($item_list) % 6 == 0) ? 'col-md-2 first-of-row' : 'col-md-2';
        }
        $item_list[] = array(
          'data' => l($item['text'], $item['link'], $attributes) . $content,
          'class' => array($item['li_class'], $grid_division_class),
        );
      }
      elseif ($menu_display->name == 'first_level' && $level_depth == 2 && $menu_display->toLevel() == 3) {
        // With more than 3 first level menu top-level items we always divide
        // in three columns. Otherwise we can have 1 or 2 columns.
        $grid = 12;
        // One menu item we add the default.
        $grid_division_class = 'col-md-12';
        // Two columns.
        if (count($items) == 2) {
          $grid_division_class = 'col-md-6';
        }
        // Three or more menu items, we have three columns per row.
        elseif (count($items) > 2) {
          $grid_division_class = (count($item_list) > 1 && count($item_list) % 3 == 0) ? 'col-md-4 first-of-row' : 'col-md-4';
        }
        $item_list[$item['menu_item']['mlid']] = array(
          'data' => l($item['text'], $item['link'], $attributes) . $content,
          'class' => array($item['li_class'], $grid_division_class),
        );
      }
      else {
        $item_list[$item['menu_item']['mlid']] = array(
          'data' => l($item['text'], $item['link'], $attributes) . $content,
          'class' => array($item['li_class']),
        );
      }
    }
    elseif (strlen($content)) {
      $output .= $content;
    }

    // Add the remaining attributes to the item.
    if (!empty($item['#attributes'])) {
      foreach ($item['#attributes'] as $attribute => $value) {
        $item_list[$item['menu_item']['mlid']][$attribute] = $value;
      }
    }
  }
  if (strlen($output)) {
    return $output;
  }

  if (count($item_list)) {
    $output = theme_item_list(
      array(
        'items' => $item_list,
        'title' => '',
        'type' => 'ul',
        'attributes' => array(
          'class' => $variables['ul_class'],
        ),
      )
    );
  }

  if ($menu_display->name == 'first_level' && $level_depth == 2) {
    // Additional wrapper for columnar layouts.
    $output = str_replace('<div class="item-list">', '<ul class="paddle-sub-nav">', $output);

    return '<div class="' . $menu_display->div_class . $div_level_class . '">' . $output;
  }
  elseif ($menu_display->name == 'footer_menu') {
    switch ($level_depth) {
      case 1:
        $menu_display->div_class = trim('row ' . $menu_display->div_class);
        $output = str_replace('<div class="item-list">', '<div class="col-md-12">', $output);
        return '<div class="' . $menu_display->div_class . $div_level_class . '">' . $output . '</div>';

      case 2:
        $menu_name_id = 'menu-display-' . str_replace('_', '-', $menu_display->name);
        return str_replace('<div class="item-list">', '<div class="' . $menu_name_id . ' ' . $menu_display->div_class . $div_level_class . '">', $output);
    }
  }
  $menu_name_id = 'menu-display-' . str_replace('_', '-', $menu_display->name);

  return str_replace('<div class="item-list">', '<div id="' . $menu_name_id . '" class="' . $menu_display->div_class . $div_level_class . '">', $output);
}

/**
 * Implements hook_page_alter().
 */
function paddle_theme_page_alter(&$page) {
  unset($page['page_bottom']['panels_ipe']);
  if (isset($page['content']['system_main']['form']) && $page['content']['system_main']['form']['#form_id'] == 'search_api_page_search_form') {
    unset($page['content']['system_main']['form']);
  }
  // Custom search.
  if (arg(0) == 'search') {
    drupal_set_title(t('Search results'));
    // Checks if key 'keys' exists in array.
    // If true hide the label.
    if (isset($page['content']['system_main']['form']['form'])) {
      foreach (array_keys($page['content']['system_main']['form']['form']) as $label) {
        if (preg_match('/^keys/', $label, $matches)) {
          $page['content']['system_main']['form']['form'][$label]['#title_display'] = 'invisible';
        }
      }
    }
  }
}

/**
 * Returns HTML for a query pager.
 *
 * Menu callbacks that display paged query results should call theme('pager') to
 * retrieve a pager control so that users can view other results. Format a list
 * of nearby pages with additional query results.
 *
 * @param array $variables
 *   An associative array containing:
 *   - tags: An array of labels for the controls in the pager.
 *   - element: An optional integer to distinguish between multiple pagers on
 *     one page.
 *   - parameters: An associative array of query string parameters to append to
 *     the pager links.
 *   - quantity: The number of pages in the list.
 *
 * @ingroup themeable
 */
function paddle_theme_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // Curent is the page we are currently paged to.
  $pager_current = $pager_page_array[$element] + 1;
  // Frst is the first page listed by this pager piece (re quantity).
  $pager_first = $pager_current - $pager_middle + 1;
  // Last is the last page listed by this pager piece (re quantity).
  $pager_last = $pager_current + $quantity - $pager_middle;
  // Max is the maximum page number.
  $pager_max = $pager_total[$element];

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.
  $li_first = theme('pager_first', array(
    'text' => (isset($tags[0]) ? $tags[0] : t('first')),
    'element' => $element,
    'parameters' => $parameters,
  ));
  $li_previous = theme('pager_previous', array(
    'text' => (isset($tags[1]) ? $tags[1] : t('previous')),
    'element' => $element,
    'interval' => 1,
    'parameters' => $parameters,
  ));
  $li_next = theme('pager_next', array(
    'text' => (isset($tags[3]) ? $tags[3] : t('next')),
    'element' => $element,
    'interval' => 1,
    'parameters' => $parameters,
  ));
  $li_last = theme('pager_last', array(
    'text' => (isset($tags[4]) ? $tags[4] : t('last')),
    'element' => $element,
    'parameters' => $parameters,
  ));

  if ($pager_total[$element] > 1) {
    if ($li_first) {
      $items[] = array(
        'class' => array('pager-first'),
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_previous', array(
              'text' => $i,
              'element' => $element,
              'interval' => ($pager_current - $i),
              'parameters' => $parameters,
            )),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('pager-current'),
            'data' => $i,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_next', array(
              'text' => $i,
              'element' => $element,
              'interval' => ($i - $pager_current),
              'parameters' => $parameters,
            )),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next'),
        'data' => $li_next,
      );
    }
    if ($li_last) {
      $items[] = array(
        'class' => array('pager-last'),
        'data' => $li_last,
      );
    }

    return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager')),
    ));
  }
}

/**
 * Implements theme_pager_link().
 */
function paddle_theme_pager_link($variables) {
  $text = $variables['text'];
  $page_new = $variables['page_new'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $attributes = $variables['attributes'];

  $page = isset($_GET['page']) ? $_GET['page'] : '';
  if ($new_page = implode(',', pager_load_array($page_new[$element], $element, explode(',', $page)))) {
    $parameters['page'] = $new_page;
  }

  $query = array();
  if (count($parameters)) {
    $query = drupal_get_query_parameters($parameters, array());
  }
  if ($query_pager = pager_get_query_parameters()) {
    $query = array_merge($query, $query_pager);
  }

  // Set each pager link title.
  if (!isset($attributes['title'])) {
    static $titles = NULL;
    if (!isset($titles)) {
      $titles = array(
        t('first') => t('Go to first page'),
        t('previous') => t('Go to previous page'),
        t('next') => t('Go to next page'),
        t('last') => t('Go to last page'),
      );
    }
    if (isset($titles[$text])) {
      $attributes['title'] = $titles[$text];
      // Set additional class.
      $attributes['class'] = 'hidden-text';
    }
    elseif (is_numeric($text)) {
      $attributes['title'] = t('Go to page @number', array('@number' => $text));
    }
  }

  // @todo l() cannot be used here, since it adds an 'active' class based on the
  //   path only (which is always the current path for pager links). Apparently,
  //   none of the pager links is active at any time - but it should still be
  //   possible to use l() here.
  // @see http://drupal.org/node/1410574
  $attributes['href'] = url($_GET['q'], array('query' => $query));
  if (isset($titles[$text])) {
    return '<a' . drupal_attributes($attributes) . '><span>' . check_plain($text) . '</span></a>';
  }
  else {
    return '<a' . drupal_attributes($attributes) . '>' . check_plain($text) . '</a>';
  }
}

/**
 * Returns HTML for the "last page" link in query pager.
 *
 * @param array $variables
 *   An associative array containing:
 *   - text: The name (or image) of the link.
 *   - element: An optional integer to distinguish between multiple pagers on
 *     one page.
 *   - parameters: An associative array of query string parameters to append to
 *     the pager links.
 *
 * @ingroup themeable
 */
function paddle_theme_pager_last($variables) {
  $text = $variables['text'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  global $pager_page_array, $pager_total;
  $output = '';

  // If we are anywhere but the last page.
  if ($pager_page_array[$element] < ($pager_total[$element] - 1)) {
    $output = theme('pager_link', array(
      'text' => $text,
      'page_new' => pager_load_array($pager_total[$element] - 1, $element, $pager_page_array),
      'element' => $element,
      'parameters' => $parameters
    ));
  }

  return $output;
}

/**
 * Implements form_alter() for the search form API search block.
 */
function paddle_theme_form_search_api_page_search_form_alter(&$form, &$form_state, $form_id) {
  // We are altering two forms that use the same base form but have a different
  // setup.
  // The 'search_api_page_search_form' has its elements in the 'form' element.
  if ($form_id == 'search_api_page_search_form') {
    $elements = &$form['form'];
  }
  // The 'search_api_page_search_form_search' has its elements in the root.
  else {
    $elements = &$form;
  }

  foreach (array_keys($elements) as $key) {
    if (preg_match('/^keys/', $key, $matches)) {
      $elements[$key]['#attributes']['placeholder'] = variable_get('search_placeholder_text', t('Looking for what?'));
      $elements[$key]['#title'] = t('search');
      $elements[$key]['#title_display'] = 'invisible';
    }
    if (preg_match('/^submit/', $key, $matches)) {
      $elements[$key]['#prefix'] = '<div class="form-submit-container">';
      $elements[$key]['#value'] = variable_get('search_placeholder_button_label', t('Search'));
      $elements[$key]['#suffix'] = '</div>';
    }
  }
}

/**
 * Implements theme_facetapi_link_active() for term links on the search page.
 */
function paddle_theme_facetapi_link_active($variables) {

  // Sanitizes the link text if necessary.
  $sanitize = empty($variables['options']['html']);
  $link_text = ($sanitize) ? check_plain($variables['text']) : $variables['text'];

  // Adds count to link if one was passed.
  if (isset($variables['count'])) {
    $link_text .= ' ' . theme('facetapi_count', $variables);
  }

  // Theme function variables fro accessible markup.
  // @see http://drupal.org/node/1316580
  $accessible_vars = array(
    'text' => $variables['text'],
    'active' => TRUE,
  );

  // Builds link, passes through t() which gives us the ability to change the
  // position of the widget on a per-language basis.
  $replacements = array(
    '!facetapi_deactivate_widget' => theme('facetapi_deactivate_widget', $variables),
    '!facetapi_accessible_markup' => theme('facetapi_accessible_markup', $accessible_vars),
  );
  $variables['text'] = t('!facetapi_deactivate_widget !facetapi_accessible_markup', $replacements);
  $variables['options']['html'] = TRUE;

  return theme_link($variables) . $link_text;
}

/**
 * Implements theme_facetapi_title().
 */
function paddle_theme_facetapi_title($variables) {
  return $variables['title'];
}

/**
 * Implements hook_preprocess_entity().
 */
function paddle_theme_preprocess_entity(&$variables) {
  // Add a view mode class to the entity.
  $variables['classes_array'][] = drupal_html_class($variables['entity_type'] . '--' . $variables['view_mode']);
  $variables['classes_array'] = array_unique($variables['classes_array']);
}

/**
 * Implements hook_preprocess_date_views_pager().
 */
function paddle_theme_preprocess_date_views_pager(&$variables) {
  $plugin = $variables['plugin'];

  if (!empty($plugin->view)) {
    $variables['theme_hook_suggestions'][] = 'date_views_pager__' . $plugin->view->name;
  }
}

/**
 * Override default theme_date_nav_title().
 *
 * If no link is provided, returns the flat title.
 */
function paddle_theme_date_nav_title($params) {
  $granularity = $params['granularity'];
  $view = $params['view'];
  $date_info = $view->date_info;
  $link = !empty($params['link']) ? $params['link'] : FALSE;
  $format = !empty($params['format']) ? $params['format'] : NULL;
  $format_with_year = variable_get('date_views_' . $granularity . 'format_with_year', 'l, F j, Y');
  $format_without_year = variable_get('date_views_' . $granularity . 'format_without_year', 'l, F j');
  switch ($granularity) {
    case 'year':
      $title = $date_info->year;
      $date_arg = $date_info->year;
      break;

    case 'month':
      $format = !empty($format) ? $format : (empty($date_info->mini) ? $format_with_year : $format_without_year);
      $title = date_format_date($date_info->min_date, 'custom', $format);
      $date_arg = $date_info->year . '-' . date_pad($date_info->month);
      break;

    case 'day':
      $format = !empty($format) ? $format : (empty($date_info->mini) ? $format_with_year : $format_without_year);
      $title = date_format_date($date_info->min_date, 'custom', $format);
      $date_arg = $date_info->year . '-' . date_pad($date_info->month) . '-' . date_pad($date_info->day);
      break;

    case 'week':
      // Take a clone, otherwise the week end date gets calculated woefully.
      $week_end_date = clone $date_info->max_date;
      $week_end_date = $week_end_date->modify('-1day');

      $title = t('@date1 - @date2', array(
        '@date1' => date_format_date($date_info->min_date, 'custom', 'd F'),
        '@date2' => date_format_date($week_end_date, 'custom', 'd F Y'),
      ));
      $date_arg = $date_info->year . '-W' . date_pad($date_info->week);
      break;
  }

  if ($link) {
    // Month navigation titles are used as links in the mini view.
    $attributes = array('title' => t('View full page month'));
    $url = date_pager_url($view, $granularity, $date_arg, TRUE);

    return l($title, $url, array('attributes' => $attributes));
  }

  return $title;
}

/**
 * Implements hook_preprocess_calendar_mini().
 */
function paddle_theme_preprocess_calendar_mini(&$variables) {
  if (!empty($variables['day_names'])) {
    $day_names = $variables['day_names'];

    foreach ($day_names as $key => &$info) {
      // Do not wrap if only the first letter is shown.
      if (drupal_strlen($info['data']) < 3) {
        continue;
      }

      // Wrap the first letters and the remaining string in a span,
      // to allow responsive handling.
      $first_letters = '<span class="first-letter">';
      $first_letters .= drupal_substr($info['data'], 0, 2);
      $first_letters .= '</span>';

      $remaining = '<span class="remaining-letters">';
      $remaining .= drupal_substr($info['data'], 2);
      $remaining .= '</span>';

      $info['data'] = $first_letters . $remaining;
    }

    $variables['day_names'] = $day_names;
  }
}

/**
 * Theme function for the ical icon used by attached iCal feed.
 *
 * Available variables are:
 * $variables['tooltip'] - The tooltip to be used for the ican feed icon.
 * $variables['url'] - The url to the actual iCal feed.
 * $variables['view'] - The view object from which the iCal feed is being
 *   built (useful for contextual information).
 */
function paddle_theme_date_ical_icon($variables) {
  $text = t('Add to personal calendar', array(), array('context' => 'paddle-calendar'));

  return l($text, $variables['url'], array(
    'external' => TRUE,
    'attributes' => array(
      'class' => array('ical-feed'),
    ),
  ));
}

/**
 * Implements hook_preprocess_view().
 */
function paddle_theme_preprocess_views_view(&$variables) {
  $view = $variables['view'];

  // Append the glossary pager script only when rendering the related view.
  if ($view->name == 'paddle_glossary' && $view->current_display == 'paddle_glossary_attachment') {
    drupal_add_js(
      drupal_get_path('theme', 'paddle_theme') . '/scripts/glossary-pager.js',
      array(
        'type' => 'file',
        'scope' => 'header',
        'group' => JS_THEME,
        'every_page' => FALSE,
      )
    );
  }

  if ($view->name == 'holiday_participation_filter_pages') {
    // A list of the displays and their machine names, titles and maps.
    $displays = array(
      'DayTrips' => array(
        'title' => t('Day trips'),
        'url' => 'offer/daytrips',
        'machine-name' => 'day_trips',
        'map-url' => 'offer/daytrips/map',
      ),
      'GroupAccommodations' => array(
        'title' => t('Group accommodations'),
        'url' => 'offer/groupaccommodations',
        'machine-name' => 'group_accommodations',
        'map-url' => 'offer/groupaccommodations/map',
      ),
      'HolidayAccommodations' => array(
        'title' => t('Holiday accommodations'),
        'url' => 'offer/holidayaccommodations',
        'machine-name' => 'holiday_accommodations',
        'map-url' => 'offer/holidayaccommodations/map',
      ),
      'OrganisedHolidays' => array(
        'title' => t('Organised holidays'),
        'url' => 'offer/organisedholidays',
        'machine-name' => 'organised_holidays',
        'map-url' => 'offer/organisedholidays/map',
      ),
    );

    // Wrap the links in a div and add it to the view header.
    $links = '';
    switch ($variables['display_id']) {
      case 'day_trips':
      case 'holiday_accommodations':
      case 'organised_holidays':
      case 'group_accommodations':
        foreach ($displays as $display) {
          $links .= paddle_holiday_participation_get_views_display_link($display['title'], $display['url'], $display['machine-name']);
        }
        break;

      case 'day_trips_map':
      case 'holiday_accommodations_map':
      case 'organised_holidays_map':
      case 'group_accommodations_map':
        foreach ($displays as $display) {
          $links .= paddle_holiday_participation_get_views_display_link($display['title'], $display['map-url'], $display['machine-name']);
        }
        break;
    }
    // Wrap the links in a div and add it to the view header.
    $variables['header'] = "<div class='hp-categories-links col-md-12'>" . $links . "</div>";
    // Adds a display switch to the header of a view.
    switch ($variables['display_id']) {
      case 'day_trips':
      case 'day_trips_map':
        $variables['footer'] .= paddle_holiday_participation_get_views_display_switch($displays['DayTrips']);
        break;

      case 'holiday_accommodations':
      case 'holiday_accommodations_map':
        $variables['footer'] .= paddle_holiday_participation_get_views_display_switch($displays['HolidayAccommodations']);
        break;

      case 'organised_holidays' :
      case 'organised_holidays_map' :
        $variables['footer'] .= paddle_holiday_participation_get_views_display_switch($displays['OrganisedHolidays']);
        break;

      case 'group_accommodations':
      case 'group_accommodations_map':
        $variables['footer'] .= paddle_holiday_participation_get_views_display_switch($displays['GroupAccommodations']);
        break;
    }
  }
}

/**
 * Implements theme_form_required_marker().
 */
function paddle_theme_form_required_marker($variables) {
  // This is also used in the installer, pre-database setup.
  $t = get_t();
  $attributes = array(
    'class' => 'form-required',
    'title' => $t('This field is required.'),
  );

  return '<span' . drupal_attributes($attributes) . '>(' . $t('required') . ')</span>';
}

/**
 * Markup for the language switcher block.
 */
function paddle_theme_links__locale_block($variables) {
  global $language;
  $output = '';

  // Extract the current language.
  $current = $variables['links'][$language->language];

  $federal = variable_get('federal_branding', FALSE);
  if (!$federal) {
    unset($variables['links'][$language->language]);
  }

  // Render the active language as button.
  $id = drupal_html_id('language-switcher-btn');
  $output .= theme('html_tag', array(
    'element' => array(
      '#tag' => 'button',
      '#value' => $current['title'],
      '#value_suffix' => '<i class="fa fa-caret-down"></i>',
      '#attributes' => array(
        'id' => $id,
        'class' => array('language-switcher-btn'),
        'aria-haspopup' => 'true',
        'aria-expanded' => 'false',
      ),
    ),
  ));

  // Include the javascript to handle the toggling of the dropdown.
  drupal_add_js(drupal_get_path('theme', 'paddle_theme') . '/scripts/language-switcher.js');

  foreach ($variables['links'] as &$link) {
    if ($federal && empty($link['href'])) {
      $link['attributes']['class'][] = 'locale-untranslated';
    }
  }

  if ($federal) {
    unset($variables['links'][$language->language]['href']);
  }

  $output .= theme('links', $variables);

  return $output;
}

/**
 * Implements hook_preprocess().
 */
function paddle_theme_preprocess_field(&$variables, $hook) {
  $menu_object = menu_get_object('node');

  if (!empty($menu_object) && $menu_object->type == 'paddle_product') {
    $element = $variables['element'];

    if (
      in_array($element['#field_name'], paddle_product_get_common_product_fields()) ||
      $element['#field_name'] == 'body'
    ) {
      if ($element['#field_name'] == 'body') {
        $variables['label_hidden'] = FALSE;
        $variables['label'] = t('Description');
      }

      $field_value = field_get_items('node', $element['#object'], $element['#field_name']);

      // Check if there is a value for the field. If not, do not render the
      // label.
      if (!empty($field_value[0]['value'])) {
        $variables['label_as_title'] = TRUE;
        $variables['classes_array'][] = 'field-name-body';
      }
      else {
        $variables['label_hidden'] = TRUE;
      }
    }
  }
}

/**
 * Preprocess txt output template.
 */
function paddle_theme_preprocess_views_data_export_txt_body(&$vars) {
  // Here we alter the output of multi-value fields to print each value on a new line.
  $vars['multi_value_fields'] = array(
    'field_paddle_kce_keywords',
    'field_paddle_kce_authors',
    'field_paddle_kce_related_docs',
    'field_paddle_kce_related_links',
  );

  // Publication types values from field_publication_type as array keys
  // and Bibliographic software values as array values.
  $vars['publications_types'] = array(
    'Book' => 'BOOK',
    'Journal' => 'JFULL',
    'Article' => 'JOUR',
    'Conference Paper' => 'CPAPER',
    'Presentation' => 'SLIDE',
    'Report' => 'RPRT',
  );
}

/**
 * Process variables for search-result.tpl.php.
 *
 * @param array $variables
 *   An associative array containing the following arguments:
 *     - $result .
 *
 * @see search_api_page-result.tpl.php
 */
function paddle_theme_preprocess_search_api_page_result(&$variables) {
  if (!empty($variables['item']->field_paddle_featured_image)) {
    $atom = scald_atom_load($variables['item']->field_paddle_featured_image[LANGUAGE_NONE][0]['sid']);
    if (!empty($atom)) {
      $alt = field_get_items('scald_atom', $atom, 'field_scald_alt_tag');
      $alt = !empty($alt) ? $alt[0]['value'] : '';
      $image = theme('image_style', array(
        'style_name' => 'paddle_search_featured_image',
        'path' => $atom->file_source,
        'alt' => $alt,
      ));
      $variables['paddle_featured_image'] = render($image);
    }

  }

}
