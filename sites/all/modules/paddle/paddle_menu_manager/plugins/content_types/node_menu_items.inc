<?php

/**
 * @file
 * Plugin to provide a content type for the paddle menu manager node menu items.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Node Menu Items'),
  'description' => t('Renders a list of menu items linking to a specific node.'),
  'category' => t('Paddle Menu Manager'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render callback.
 */
function paddle_menu_manager_node_menu_items_content_type_render($subtype, $conf, $args, $context) {
  global $language_content;
  ctools_include('ajax');
  ctools_include('modal');

  $block = new stdClass();
  $block->title = t('Navigation');
  $block->content = '';

  // Stop if no node has been set.
  if (empty($context->data) || empty($context->data->nid)) {
    return $block;
  }

  $block->content = ctools_modal_text_button(t('Add to menu'), 'admin/structure/menu_manager/nojs/node/' . $context->data->nid . '/add_menu_link', t('Create a menu link to this page.'), 'button add-button');

  $node = $context->data;

  $menu_links = paddle_menu_manager_get_menu_links_by_nid($node->nid);

  $items = array();

  $highest_mlid = 0;

  foreach ($menu_links as $mlid => $menu_link) {
    $menu = menu_load($menu_link['menu_name']);
    $breadcrumb_titles = paddle_menu_manager_breadcrumb_titles($menu_link);
    $items[] = array(
      'data' => theme('paddle_menu_manager_node_menu_item', array(
        'menu_link' => $menu_link,
        'menu' => $menu,
        'breadcrumb' => $breadcrumb_titles,
        'node' => $node,
      )),
      'class' => array('mlid-' . $mlid),
    );

    // Searches the lastly added menu item from the main menu
    // (which is the dominant breadcrumb).
    if ($mlid > $highest_mlid && $menu_link['menu_name'] == 'main_menu_' . $language_content->language) {
      $highest_mlid = $mlid;
    }
  }

  // Had to use a replace instead of concatenating the strings.
  // The theme function item_list sees the content outside
  // the breadcrumb span as a new list item.
  foreach ($items as $index => $item) {
    if ($item['class'][0] == 'mlid-' . $highest_mlid) {
      $dominant = '</span> <span class="dominant-breadcrumb">';
      $dominant .= l(t('(dominant breadcrumb)'), 'http://support.kanooh.be/support/solutions/articles/3000065792-welke-broodkruimel-is-dominant-', array('attributes' => array('class' => 'breadcrumb-faq', 'target' => '_blank')));
      $dominant .= '</span></span>';

      $items[$index]['data'] = str_replace('</span></span>', $dominant, $item['data']);
    }
  }

  $variables = array(
    'items' => $items,
    'attributes' => array('class' => 'node-menu-links'),
  );
  $block->content .= theme('item_list', $variables);

  return $block;
}

/**
 * Settings form builder callback.
 */
function paddle_menu_manager_node_menu_items_content_type_edit_form($form, &$form_state) {
  return $form;
}

/**
 * Displays the administrative title for a panel pane in the drag & drop UI.
 */
function paddle_menu_manager_node_menu_items_content_type_admin_title($subtype, $conf, $context) {
  return t('Node Menu Items');
}
