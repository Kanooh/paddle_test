<?php

/**
 * @file
 * Custom theme functions and overrides.
 */

/**
 * Implements hook_theme().
 *
 * Provides custom template for user, user/login page.
 */
function paddle_admin_theme_theme() {
  $items = array();
  $items['user_login'] = array(
    'render element' => 'form',
    'path' => drupal_get_path('theme', 'paddle_admin_theme') . '/templates',
    'template' => 'user-login',
    'preprocess functions' => array(
      'paddle_admin_theme_preprocess_user_login',
    ),
  );
  $items['user_pass'] = array(
    'render element' => 'form',
    'path' => drupal_get_path('theme', 'paddle_admin_theme') . '/templates',
    'template' => 'user-pass',
    'preprocess functions' => array(
      'paddle_admin_theme_preprocess_user_login',
    ),
  );
  return $items;
}

/**
 * Implements hook_theme().
 *
 * Theme_link to enclose all links text in a span element.
 */
function paddle_admin_theme_link($variables) {
  // Escape the link text.
  $text = !empty($variables['options']['html']) ? $variables['text'] : check_plain($variables['text']);

  // Formbuilder specific links should not be placed inside a span. This will
  // conflict with the javascript to add elements to the build form page.
  if (
    !empty($variables['options']) &&
    !empty($variables['options']['query']) &&
    !empty($variables['options']['query']['destination']) &&
    substr($variables['options']['query']['destination'], -strlen('build_form')) === 'build_form'
  ) {
    return '<a href="' . check_plain(url($variables['path'], $variables['options'])) . '"' . drupal_attributes($variables['options']['attributes']) . '>' . $text . '</a>';
  }

  // Wrap the text in a span if it is not already wrapped in one.
  $text = preg_match('/^<span[^>]*>.*<\/span>$/', $text) ? $text : '<span>' . $text . '</span>';

  return '<a href="' . check_plain(url($variables['path'], $variables['options'])) . '"' . drupal_attributes($variables['options']['attributes']) . '>' . $text . '</a>';
}

/**
 * Implements theme_panels_layout_icon().
 */
function paddle_admin_theme_panels_layout_icon($vars) {
  $id = $vars['id'];
  $image = $vars['image'];
  $title = $vars['title'];

  // Add custom class based on landingspage layouts.
  $output = '<div class="layout-icon ' .  strtolower($title) . '">';
  $output .= '<div class="thumb-wrapper">';
  $output .= $image;
  if ($title) {
    $output .= '<div class="caption">' . $title . '</div>';
  }
  $output .= '</div></div>';
  return $output;
}

/**
 * Implements theme_panels_layout_link().
 */
function paddle_admin_theme_panels_layout_link($vars) {
  preg_match('/\<span>(.*)<\/span><\/a>/', $vars['title'], $matches);
  if (is_array($matches) && isset($matches[1])) {
    $layout_name = $matches[1];
    // Add layout name as CSS class so we can target it for our thumbnail
    // sprite.
    $vars['class'][] = strtolower($layout_name);
  }

  // Continue as the original theming function would do.
  return theme_panels_layout_link($vars);
}

/**
 * Theme a feed link.
 *
 * This theme function uses the theme pattern system to allow it to be
 * overidden in a more specific manner. The options for overiding this include
 * providing per display id; per type; per display id and per type.
 *
 * This has been copied verbatim from theme_views_data_export_feed_icon()
 * with the following changes:
 * - The html => TRUE option has been removed.
 * - The button class has been added.
 * - The image has been changed to standard text.
 */
function paddle_admin_theme_views_data_export_feed_icon($variables) {
  extract($variables, EXTR_SKIP);
  $url_options = array(
    'attributes' => array('class' => 'button'),
  );
  if (is_array($query)) {
    $url_options['query'] = $query;
  }
  return l($text, $url, $url_options);
}

/**
 * Themes some exposed form elements in a collapsible fieldset.
 */
function paddle_admin_theme_secondary_exposed_elements($vars) {
  $element = $vars['element'];

  $output = '<div class="bef-secondary-options">';
  foreach (element_children($element) as $id) {
    $el = $element[$id];
    switch ($id) {
      case 'search_body':
        $el['#prefix'] = '<div class="col-md-6">';
        break;

      case 'changed':
        $el['#suffix'] = '</div>';
        break;

      case 'field_page_responsible_author_target_id':
        $el['#prefix'] = '<div class="col-md-6">';
        break;

      case 'creation_uid':
        $el['#suffix'] = '</div>';
        break;
    }
    $output .= drupal_render($el);
  }
  $output .= '</div>';

  return $output;
}

/**
 * Returns HTML for the paddle menu display.
 *
 * @param array $variables
 *   An associative array containing:
 *   - items: Menu items to render. Array with the following elements:
 *     * li_class - String with classes separated by space to add to the "li"
 *                  tag of the current menu item.
 *     * a_class - String with classes separated by space to add to the "a" tag.
 *     * content - The HTML of the items below.
 *     * depth - The depth of the current menu item in the menu structure.
 *     * link - The menu item URL.
 *     * text - The menu item title.
 *   - menu_display: The menu display object we are rendering.
 *   - ul_class: String with classes separated by space to add to the "ul".
 *
 * @return string
 *   The generated html.
 *
 * @ingroup themeable
 */
function paddle_admin_theme_paddle_menu_display_menu_items($variables) {
  $menu_display = $variables['menu_display'];

  $output = '';
  $item_list = array();
  foreach ($variables['items'] as $item) {
    $attributes = array();
    if ($item['a_class']) {
      $attributes = array('attributes' => array('class' => array($item['a_class'])));
    }
    $content = isset($item['content']) ? $item['content'] : '';
    if ($item['depth'] >= $menu_display->fromLevel() && $item['depth'] <= $menu_display->toLevel()) {
      $item_list[$item['menu_item']['mlid']] = array(
        'data' => l($item['text'], $item['link'], $attributes) . $content,
        'class' => array($item['li_class']),
      );
    }
    elseif (strlen($content)) {
      $output .= $content;
    }

    if (!empty($item['content'])) {
      // When there's content, it means that we have children, so mark it as
      // expanded.
      paddle_menu_display_add_class($variables['ul_class'], 'is-expanded');
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
    $output = theme_item_list(array(
        'items' => $item_list,
        'title' => '',
        'type' => 'ul',
        'attributes' => array('class' => $variables['ul_class']),
      )
    );
  }

  return str_replace('<div class="item-list">', '<div class="' . $menu_display->div_class . '">', $output);
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
function paddle_admin_theme_date_ical_icon($variables) {
  $text = t('Add to personal calendar', array(), array('context' => 'paddle-calendar'));

  return l($text, $variables['url'], array(
    'external' => TRUE,
    'attributes' => array(
      'class' => array('ical-feed'),
    ),
  ));
}
