<?php

/**
 * @file
 * Contains the functions which have to do with the theming.
 */

/**
 * Implements hook_theme().
 *
 * Paddle Core provides various general templates.
 */
function paddle_core_theme() {
  return array(
    'paddle_core_icon' => array(
      'variables' => array(
        'type' => 'fa',
        'name' => '',
        'attributes' => array(),
      ),
    ),
    'paddle_core_listing_table' => array(
      'variables' => array(
        'items' => array(),
        'attributes' => array(),
      ),
    ),
    'paddle_core_listing_table_item' => array(
      'variables' => array(
        'content' => '',
        'icon' => '',
        'icon_alignment' => 'right',
      ),
    ),
    'paddle_core_titled_iconed_links_list' => array(
      'variables' => array(
        'items' => array(),
      ),
    ),
  );
}

/**
 * Implements hook_paddle_color_palettes_color_selectors_alter().
 */
function paddle_core_paddle_color_palettes_color_selectors_alter(&$data, $theme) {
  // @todo Get rid of this function when using SASS/LESS.
  if ($theme->name == 'paddle_theme_branded') {
    $data[4]['color'][] = '.panel-pane{} .listing.table-listing a';
  }
}

/**
 * Provides a template for icons.
 *
 * @param array $variables
 *   An associative array containing:
 *   - type: The type of icon, ie. 'fa' or 'file'.
 *   - name: The name of the icon. For font-awesome this is the class of the
 *     icon without the "fa-" prefix. For file icons it should be the file
 *     extension.
 *   - attributes: Any HTML attributes to add to the icon, in a keyed array.
 */
function theme_paddle_core_icon($variables) {
  $type = $variables['type'];
  $name = $variables['name'];
  $attributes = $variables['attributes'];

  // Set an empty class string as default.
  if (!isset($attributes['class'])) {
    $attributes['class'] = '';
  }
  // If the class string is not empty, add an extra space before we add our own
  // classes.
  elseif (!empty($attributes['class'])) {
    $attributes['class'] .= ' ';
  }

  // Add the classes to style the icon. Add a class for the type, and a class
  // that combines the type and name of the icon with a dash in between.
  // Ouput would be eg. "fa fa-chevron-right".
  $attributes['class'] .= $type . ' ' . $type . '-' . $name;

  $icon_html = '<i aria-hidden="true"' . drupal_attributes($attributes) . '></i>';

  return $icon_html;
}

/**
 * Provides a template for lists that look like tables.
 *
 * @param array $variables
 *   An associative array containing:
 *   - items: An array of items to be displayed in the list. See
 *     theme_item_list() for documentation.
 *   - attributes: The attributes applied to the list.
 */
function theme_paddle_core_listing_table($variables) {
  $attributes = $variables['attributes'];
  if (!isset($attributes['class'])) {
    $attributes['class'] = '';
  }
  $attributes['class'] .= ((empty($attributes['class'])) ? '' : ' ') . 'listing table-listing';

  return theme('item_list', array(
    'items' => $variables['items'],
    'attributes' => $attributes,
  ));
}

/**
 * Provides a template for items that fit inside listing tables.
 *
 * @todo Add support for thumbnails, like used in the extended news listings.
 *
 * @param array $variables
 *   An associative array containing:
 *   - content: Actual content of the item. This can be any HTML.
 *   - extension (optional): file extension of the item.
 *   - icon (optional): Icon to use, should be generated using the
 *     paddle_core_icon template.
 *   - icon_alignment (optional): 'left' or 'right'. Defaults to 'right'.
 *   - size (optional): file size of the item.
 *
 * @return string
 *  Themed HTML for the list table item.
 */
function theme_paddle_core_listing_table_item($variables) {
  $icon_alignment = isset($variables['icon_alignment']) ? $variables['icon_alignment'] : 'right';
  $icon = !empty($variables['icon']) ? '<span class="icon align-' . $icon_alignment . '">' . $variables['icon'] . '</span>' : '';

  $html = '';
  if (isset($variables['extension'])) {
    $html .= '<span class="visuallyhidden">' . check_plain($variables['extension']) . ' ' . t('file') . '</span>';
  }
  $html .= '<span class="item">';

  // Add the icon to the left.
  if (!empty($icon) && $icon_alignment == 'left') {
    $html .= $icon;
  }

  // Add the actual content.
  $html .= '<span class="content">' . $variables['content'] . (isset($variables['size']) ? ' (' . $variables['size'] . ')' : '') . '</span>';

  // Add the icon to the right.
  if (!empty($icon) && $icon_alignment == 'right') {
    $html .= $icon;
  }

  $html .= '</span>';
  $html .= '</span>';

  return $html;
}

/**
 * Templating function for generating a list.
 *
 * Provides a template for items that are listed with a title, icon and some
 * help text all wrapped in a link.
 * Similar to theme_paddle_content_manager_add_links() but adds icons themed
 * through theme_paddle_core_icon().
 *
 * @param array $variables
 *   An associative array containing:
 *   - items: An associative array with the items to render containing:
 *     - title: the title of the item which will be wrapped in <h4>
 *     - icon: the name of the FA icon to display inside the link.
 *     - help_text: the HTML on which the link is put.
 *     - link: An associative array containing:
 *       - url: the URL for the link.
 *       - query: an array, the query part of the URL. See l().
 *       - attributes: an array containing optional link attributes. See l().
 *
 * @return string
 *   Themed HTML for the links list.
 *
 * @see theme_paddle_core_icon()
 * @see theme_paddle_content_manager_add_links()
 */
function theme_paddle_core_titled_iconed_links_list($variables) {
  $items = array();

  foreach ($variables['items'] as $item) {
    // Generate the title.
    $title = theme('html_tag', array('element' => array('#tag' => 'h4', '#value' => $item['title'])));

    // Generate HTML for the icon.
    $icon = theme('paddle_core_icon', array('type' => 'fa', 'name' => $item['icon']));

    // Generate the link.
    $link = $item['link'];
    $options = array('html' => TRUE, 'attributes' => $link['attributes']);

    // Add URL query if there is one set.
    if (isset($link['query'])) {
      $options['query'] = $link['query'];
    }
    $items[] = l($title . $icon . $item['help_text'], $link['url'], $options);
  }

  return theme('item_list', array('items' => $items));
}
