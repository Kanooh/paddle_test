<?php

/**
 * @file
 * CTools content type plugin allowing to create a new landing page.
 */

$plugin = array(
  'title' => t('Add new landing page'),
  'description' => t('Shows a list of layouts linking to the wizard for creating a new landing page.'),
  'single' => TRUE,
  'content_types' => array('add_new_landing_page'),
  'render callback' => 'add_new_landing_page_render',
  'defaults' => array(),
  'edit form' => 'add_new_landing_page_edit_form',
  'category' => array(t('Paddle Content Manager'), -9),
);

/**
 * Render the content type.
 */
function add_new_landing_page_render($subtype, $conf, $args, $context) {
  ctools_include('common', 'panels');
  $allowed_layouts = panels_common_get_allowed_layouts('panelizer_node:landing_page');

  // Don't render the pane if the user doesn't have permission to create
  // landing pages or if no layouts have been enabled for landing pages.
  if (!node_access('create', 'landing_page') || empty($allowed_layouts)) {
    return FALSE;
  }

  ctools_include('display-layout', 'panels');
  ctools_include('plugins', 'panels');
  ctools_include('cleanstring');
  ctools_include('modal');
  ctools_modal_add_js();

  // Format the enabled layouts as a collection of links.
  $items = array();
  foreach ($allowed_layouts as $layout_id => $layout) {
    // Only allow layouts categorized as Paddle Layouts.
    if ($layout['category'] != 'Paddle Layouts') {
      continue;
    }

    $options = array(
      'attributes' => array(
        'class' => array('ctools-use-modal'),
        'title' => check_plain($layout_id),
      ),
      'html' => TRUE,
      'query' => array(
        'layout' => check_plain('node:landing_page:' . $layout_id),
      ),
    );
    $layout_icon = panels_print_layout_icon($layout_id, $layout, $title = strip_tags($layout['title']));
    $items[] = l($layout_icon, 'admin/content_manager/nojs/landing_page/add', $options);
  }

  // Put them in an item list.
  $output = array(
    '#type' => 'container',
    '#attributes' => array(
      'id' => 'add-new-landing-page',
    ),
    'layouts' => array(
      '#theme' => 'item_list',
      '#type' => 'ul',
      '#attributes' => array('class' => array('layout')),
      '#items' => $items,
    ),
  );

  // Return the output as a block.
  $block = new stdClass();
  $block->title = check_plain(t('Add new landing page'));
  $block->content = render($output);

  return $block;
}

/**
 * Edit form callback for the content type.
 */
function add_new_landing_page_edit_form($form, &$form_state) {
  return $form;
}
