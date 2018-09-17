<?php
/**
 * @file
 * Theme implementation to add custom templating
 */

/**
 * Implements hook_css_alter().
 */
function web_universe_theme_css_alter(&$css) {
  // Remove default Drupal styling so it doesn't conflict with the clean Web
  // Universe styling.
  // @see https://www.drupal.org/node/992058
  $exclude = array(
    'misc/vertical-tabs.css' => FALSE,
    'modules/aggregator/aggregator.css' => FALSE,
    'modules/block/block.css' => FALSE,
    'modules/book/book.css' => FALSE,
    'modules/comment/comment.css' => FALSE,
    'modules/dblog/dblog.css' => FALSE,
    'modules/file/file.css' => FALSE,
    'modules/filter/filter.css' => FALSE,
    'modules/forum/forum.css' => FALSE,
    'modules/help/help.css' => FALSE,
    'modules/menu/menu.css' => FALSE,
    'modules/node/node.css' => FALSE,
    'modules/openid/openid.css' => FALSE,
    'modules/poll/poll.css' => FALSE,
    'modules/profile/profile.css' => FALSE,
    'modules/search/search.css' => FALSE,
    'modules/statistics/statistics.css' => FALSE,
    'modules/syslog/syslog.css' => FALSE,
    'modules/system/admin.css' => FALSE,
    'modules/system/maintenance.css' => FALSE,
    'modules/system/system.css' => FALSE,
    'modules/system/system.admin.css' => FALSE,
    'modules/system/system.base.css' => FALSE,
    'modules/system/system.maintenance.css' => FALSE,
    'modules/system/system.menus.css' => FALSE,
    'modules/system/system.messages.css' => FALSE,
    'modules/system/system.theme.css' => FALSE,
    'modules/taxonomy/taxonomy.css' => FALSE,
    'modules/tracker/tracker.css' => FALSE,
    'modules/update/update.css' => FALSE,
    'modules/user/user.css' => FALSE,

  );
  $css = array_diff_key($css, $exclude);
}

/**
 * Implements theme_preprocess_html().
 */
function web_universe_theme_preprocess_html(&$variables) {
  // Add the remote files from the Web Universe project.
  drupal_add_css('http://dij151upo6vad.cloudfront.net/2.latest/css/vlaanderen-ui.css', array('type' => 'external'));
  $color_scheme = variable_get('paddle_web_universe_color_scheme', 'corporate');
  drupal_add_css('http://dij151upo6vad.cloudfront.net/2.latest/css/vlaanderen-ui-' . $color_scheme . '.css', array('type' => 'external'));
  drupal_add_js('http://dij151upo6vad.cloudfront.net/2.latest/js/vlaanderen-ui.js', array('type' => 'external', 'scope' => 'footer'));
}

/**
 * Implements theme_preprocess_page().
 */
function web_universe_theme_preprocess_page(&$variables) {
  // Add the VO Header and Footer.
  if (variable_get('include_vo_branding_elements', FALSE)) {
    $vo_global_header_render_array = variable_get('vo_global_header', array());
    $variables['vo_global_header'] = render($vo_global_header_render_array);
    $vo_global_footer_render_array = variable_get('vo_global_footer', array());
    $variables['vo_global_footer'] = render($vo_global_footer_render_array);
  }

  if (variable_get('paddle_style_header_title_default', FALSE)) {
    $header_title = variable_get('site_name', '');
  }
  else {
    $header_title = variable_get('paddle_web_universe_header_title', '');
  }
  $header_title_prefix = variable_get('paddle_web_universe_header_title_prefix', '');
  $header_type = variable_get('paddle_web_universe_header_type', 'naked_header');

  // Render the selected header type. We prefer to avoid a big if/else structure
  // in the template files.)
  if ($header_type == 'naked_header') {
    $header_logo_tag_line = variable_get('paddle_web_universe_header_logo_tag_line', '');

    $variables['header'] = theme('paddle_web_universe_theme_naked_header',
       array(
         'header_title' => $header_title,
         'header_title_prefix' => $header_title_prefix,
         'header_logo_tag_line' => $header_logo_tag_line,
       )
     );
  }
  else {
    // Set the header image.
    // The Paddle Section Theming module might provide us with a background image.
    if (!empty($variables['paddle_section_theming_background_image_url'])) {
      $header_image = $variables['paddle_section_theming_background_image_url'];
    }
    // If there is none specified, we pick the default header set in the theme
    // settings.
    else {
      $header_image = variable_get('paddle_web_universe_header_image', '');
    }

    if (drupal_is_front_page()) {
      $variables['header'] = theme('paddle_web_universe_theme_image_header_full_width',
        array(
          'header_title' => $header_title,
          'header_title_prefix' => $header_title_prefix,
          'header_image' => $header_image,
        )
      );
    }
    // Check if we are on a node.
    elseif (!empty($variables['node'])) {
      $variables['show_title'] = FALSE;
      $header_title = '';
      $level_1_item = array();
      $level_2_item = array();
      $node = $variables['node'];
      $node_wrapper = entity_metadata_wrapper('node', $node);

      if (!empty($node_wrapper->field_display_title_in_header) && !empty($node_wrapper->field_display_title_in_header->value())) {
        $header_title = $node->title;
      }
      else {
        $variables['show_title'] = TRUE;
      }

      $active_trail = menu_get_active_trail();
      $last_item = end($active_trail);

      // We check how deep in the menu our current page is. There can only be
      // a parent item if the current item actually has parents.
      if (!empty($last_item) && $last_item['depth'] > 1) {
        // We want to get the 2nd element of the trail since the first element
        // is in our Paddle environment always 'Home'.
        $first_parent = array_slice($active_trail, 1, 1);
        $level_1_item['text'] = $first_parent[0]['link_title'];
        $level_1_item['url'] = url($first_parent[0]['link_path']);
        if ($last_item['depth'] > 2 && empty($header_title)) {
          $second_parent = array_slice($active_trail, 2, 1);
          $level_2_item['text'] = $second_parent[0]['link_title'];
          $level_2_item['url'] = url($second_parent[0]['link_path']);
        }
      }

      $variables['header'] = theme('paddle_web_universe_theme_image_header_page_title',
        array(
          'header_title' => $header_title,
          'level_1_item' => $level_1_item,
          'level_2_item' => $level_2_item,
          'header_image' => $header_image,
        )
      );
    }
  }

  // We render the breadcrumb through JS, from which we add it to the global VO
  // header.
  drupal_add_js(
    drupal_get_path('theme', 'web_universe_theme') . '/assets/scripts/display-breadcrumb.js',
    array(
      'type' => 'file',
      'scope' => 'footer',
      'group' => JS_LIBRARY,
      'every_page' => TRUE,
    )
  );
  drupal_add_js(
    array(
      'web_universe_theme' => array(
        'breadcrumb_trail' => web_universe_theme_extract_data_from_formatted_links(drupal_get_breadcrumb()),
      ),
    ),
    'setting'
  );
}

/**
 * Extracts link data from formatted links.
 *
 * @param string[] $formatted_links
 *   An array containing links as A tags.
 * @return array
 *   Each array item is an array with 'text' and 'url' keys.
 */
function web_universe_theme_extract_data_from_formatted_links($formatted_links) {
  $link_data = array();

  if (!empty($formatted_links)) {
    foreach ($formatted_links as $formatted_link) {
      $dom_document = new domdocument();
      $dom_document->loadHTML($formatted_link);
      $hyperlink = $dom_document->getElementsByTagName("a")->item(0);

      if (!empty($hyperlink)) {
        $link_data[] = array(
          'text' => $hyperlink->textContent,
          'url' => $hyperlink->getAttribute("href"),
        );
      }
    }
  }

  return $link_data;
}

/**
 * @inheritdoc
 */
function web_universe_theme_paddle_menu_display_menu_items($variables) {
  $menu_display = $variables['menu_display'];

  if ($menu_display->name != 'footer_menu') {
    // Only "mess" with the footer menu. Rely on the default for the other
    // menus. Call that function directly instead of via theme() to avoid an
    // infinite loop.
    return theme_paddle_menu_display_menu_items($variables);
  }

  // Adapt the HTML to conform with the Web Universe content footer.
  $output = '';
  $item_list = array();
  foreach ($variables['items'] as $item) {
    $attributes = array();
    if ($item['a_class']) {
      $attributes = array('attributes' => array('class' => array($item['a_class'])));
    }
    $content = isset($item['content']) ? $item['content'] : '';
    if ($item['depth'] >= $menu_display->fromLevel() && $item['depth'] <= $menu_display->toLevel()) {
      $classes = explode(' ', $item['li_class']);
      if ($item['depth'] == '1') {
        // Add col width to 1st level menu items.
        // @todo Fix the case when there are more than 12 top level menu items.
        $classes[] = 'col--1-' . count($variables['items']);
        $classes[] = 'col--1-1--s';
      }
      $item_list[$item['menu_item']['mlid']] = array(
        'data' => l($item['text'], $item['link'], $attributes) . $content,
        'class' => $classes,
      );
    }
    elseif (strlen($content)) {
      $output .= $content;
    }

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
    $classes = explode(' ', $variables['ul_class']);
    if ($item['depth'] == '1') {
      // Only add the grid on the container of the 1st level of the menu.
      $classes[] = 'grid';
      $classes[] = 'grid--is-stacked';
    }
    elseif ($item['depth'] == '2') {
      // Only add the smaller text styling to the 2nd level of the menu.
      $classes[] = 'link-list';
      $classes[] = 'link-list--small';
    }

    $output = theme(
      'item_list',
      array(
        'items' => $item_list,
        'title' => '',
        'type' => 'ul',
        'attributes' => array('class' => $classes),
      )
    );
  }

  return str_replace('<div class="item-list">', '<div class="' . $menu_display->div_class . '">', $output);
}

/**
 * Implements theme_HOOK().
 */
function web_universe_theme_menu_tree($variables) {
  return '<ul class="menu js-equal-height-container">' . $variables['tree'] . '</ul>';
}
