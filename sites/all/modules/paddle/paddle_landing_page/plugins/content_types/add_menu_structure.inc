<?php

/**
 * @file
 * CTools content type plugin to embed a menu structure.
 */

$plugin = array(
  'title' => t('Add new menu structure'),
  'description' => t('Add a new menu structure.'),
  'single' => TRUE,
  'render callback' => 'add_menu_structure_render',
  'no title override' => TRUE,
  'defaults' => array(),
  'edit form' => 'add_menu_structure_edit_form',
  'category' => array(t('Paddle Landing Page'), -9),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function add_menu_structure_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $output = '';
  $view_mode = 'default';

  // Only output the menu structure if the user has access to this menu or menu
  // item. If the menu item is empty we are rendering the root of the menu.
  $menu_item = menu_link_load($conf['menu_items']);
  if (empty($menu_item) || !empty($menu_item['access'])) {
    if (!empty($conf['view_mode'])) {
      $view_mode = $conf['view_mode'];
    }

    if ($output = paddle_landing_page_generate_menu_structure($conf['menus'], $conf['menu_items'], $conf['item_levels'], $view_mode, array($conf['list_type']))) {
      // Transform the customized pane section configuration into something that
      // paddle_panes_pane_sections_render() understands.
      // @see paddle_panes_pane_sections_render()
      if (!empty($conf['top']['section_content_type']) && $conf['top']['section_content_type'] == 'title') {
        $conf['top']['section_content_type'] = 'text';
        $conf['top']['section_text'] = check_plain($menu_item['title']);
      }
      if (!empty($conf['top']['section_url_type']) && $conf['top']['section_url_type'] == 'menu_link') {
        // The front page system token is treated differently.
        if ($menu_item['link_path'] == '<front>') {
          $conf['top']['section_url_type'] = 'external';
          $conf['top']['section_external_url'] = url('<front>');
        }
        elseif ($menu_item['external']) {
          $conf['top']['section_url_type'] = 'external';
          $conf['top']['section_external_url'] = $menu_item['link_path'];
        }
        else {
          $conf['top']['section_url_type'] = 'internal';
          $conf['top']['section_internal_url'] = $menu_item['link_path'];
        }
      }

      module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
      paddle_panes_pane_sections_render($output, $conf, $context);
    }
  }

  // Render the menu.
  $block->content = $output;

  return $block;
}

/**
 * Edit form callback for the content type.
 */
function add_menu_structure_edit_form($form, &$form_state) {
  global $language_content;
  $menu_options = array();

  $conf = $form_state['conf'];

  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  // Remove ctools default title override field.
  $form['section_body']['override_title']['#access'] = FALSE;
  $form['section_body']['override_title_text']['#access'] = FALSE;
  $form['section_body']['override_title_markup']['#access'] = FALSE;

  $language = !empty($form_state['contexts']['panelizer']->data->language) ? $form_state['contexts']['panelizer']->data->language : $language_content->language;
  if ($menus = paddle_menu_manager_get_menus($language)) {
    $main_menu = 'main_menu_' . $language;
    $menus = array($main_menu => $menus[$main_menu]) + $menus;
    // Create our array of menus for the form selection.
    foreach ($menus as $index => $menu) {
      $menu_options[$index] = $menu['title'];
    }

    // Make sure that if we have a menu item in the settings it's own menu is
    // used.
    if (!empty($conf['menu_items'])) {
      $menu_item = menu_link_load($conf['menu_items']);
      if (!empty($menu_item)) {
        $conf['menus'] = $menu_item['menu_name'];
      }
    }

    // Select for menu's.
    $form['section_body']['menus'] = array(
      '#title' => t('Select a navigation menu'),
      '#type' => 'select',
      '#options' => $menu_options,
      '#default_value' => isset($conf['menus']) ? $conf['menus'] : '',
      '#ajax' => array(
        'path' => 'paddle_landing_page_get_menu_items',
      ),
    );

    // Build our menu tree from the selected menu.
    $m_option = isset($conf['menus']) ? $conf['menus'] : key($menu_options);
    // Recursively get menu structure in the correct order.
    $m_tree = user_access('view all unpublished content') ? paddle_landing_page_menu_build_tree($m_option) : menu_build_tree($m_option);

    // Define our first option within the select.
    $first_option = $menu_options[$m_option] . t('-items');
    // Build our custom menu item list array.
    $menu_items = paddle_landing_page_build_menu_structure($m_tree, $first_option);

    // Select for menu items.
    $form['section_body']['menu_items'] = array(
      '#type' => 'select',
      '#validated' => TRUE,
      '#title' => t('Select a menu item'),
      '#options' => $menu_items,
      '#default_value' => isset($conf['menu_items']) ? $conf['menu_items'] : '',
      '#ajax' => array(
        'path' => 'paddle_landing_page_get_menu_levels',
      ),
    );

    // Attempt to traverse up the given menu items parents to calculate levels.
    $m_item = isset($conf['menu_items']) ? $conf['menu_items'] : key($menu_items);
    $menu_item_levels = paddle_landing_page_menu_item_get_levels($m_item, 0, $m_option);

    // Select for menu item level(s).
    $form['section_body']['item_levels'] = array(
      '#type' => 'select',
      '#validated' => TRUE,
      '#title' => t('Levels deep'),
      '#options' => $menu_item_levels,
      '#default_value' => isset($conf['item_levels']) ? $conf['item_levels'] : '',
    );

    // Support the use of multiple view modes.
    $view_modes = array(
      'default' => t('List'),
    );

    // Other modules can add additional view modes.
    $view_modes += module_invoke_all('paddle_landing_page_menu_structure_view_modes');

    $form['section_body']['view_mode'] = array(
      '#type' => 'radios',
      '#validated' => TRUE,
      '#title' => t('View Mode'),
      '#options' => $view_modes,
      '#default_value' => isset($conf['view_mode']) ? $conf['view_mode'] : 'default' ,
    );

    // Add an unordered list type when using the 'List' view mode.
    $form['section_body']['list_type'] = array(
      '#type' => 'radios',
      '#validated' => TRUE,
      '#title' => t('List type'),
      '#options' => array(
        'bullet-list' => t('Bullet list'),
        'regular-list' => t('Regular list'),
      ),
      '#default_value' => isset($conf['list_type']) ? $conf['list_type'] : 'bullet-list',
      '#states' => array(
        'visible' => array(
          'input[name="view_mode"]' => array('value' => 'default'),
        ),
      ),
    );
  }

  // Include the form elements for the pane sections in the form.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_build_edit_form($form, $form_state);

  // Add the option to show the menu title in the top pane section.
  // Hide the text input field if this option is selected.
  $form['pane_settings']['top']['section_wrapper']['section_content_type']['#options'] = array('title' => t('Use the menu title')) + $form['pane_settings']['top']['section_wrapper']['section_content_type']['#options'];

  $form['pane_settings']['top']['section_wrapper']['section_text']['#states']['visible'][':input[name="top[section_wrapper][section_content_type]"]'] = array(
    '!value' => 'title',
  );
  $form['pane_settings']['top']['section_wrapper']['section_text']['#states']['required'][':input[name="top[section_wrapper][section_content_type]"]'] = array(
    '!value' => 'title',
  );

  // Provide an option to link the title to the href of the menu item.
  $form['pane_settings']['top']['section_wrapper']['section_url_type']['#options']['menu_link'] = t('Link to the menu page');

  form_load_include($form_state, 'inc', 'paddle_landing_page', 'plugins/content_types/add_menu_structure');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function add_menu_structure_edit_form_validate($form, &$form_state) {
  // Allow the form elements provided by pane sections to be validated.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_validate($form, $form_state);
}

/**
 * Submit callback for the configuration form.
 */
function add_menu_structure_edit_form_submit($form, &$form_state) {
  foreach (element_children($form['pane_settings']['section_body']) as $key) {
    if (!empty($form_state['values'][$key])) {
      $form_state['conf'][$key] = $form_state['values'][$key];
    }
    else {
      $form_state['conf'][$key] = NULL;
    }
  }

  // Hash the menu config so that we can clear the cache of the menu when the
  // pane has been changed.
  $starting_menu_item = $form_state['conf']['menu_items'] == FALSE ? NULL : menu_link_load($form_state['conf']['menu_items']);
  $config = paddle_landing_page_generate_menu_structure_pane_cache_config($starting_menu_item, $form_state['conf']['item_levels']);
  $config_key = md5(serialize($config));

  cache_clear_all('links:' . $form_state['conf']['menus'] . ':menu-structure:' . $config_key . ':', 'cache_menu', TRUE);
  // Allow the form elements provided by pane sections to be saved.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_submit($form, $form_state);
}

/**
 * Generates a menu structure from given parameters.
 *
 * @param string $menu_name
 *   Machine name of the menu.
 * @param string|bool $mlid
 *   Menu link id (mlid).
 * @param string|bool $max_depth
 *   Used for limiting the levels within the menu tree.
 * @param string $view_mode
 *   The way the menu tree will be displayed as.
 * @param array $classes
 *   Adds additional classes to the wrapper.
 *
 * @return string
 *   HTML rendered menu tree.
 */
function paddle_landing_page_generate_menu_structure($menu_name, $mlid = FALSE, $max_depth = FALSE, $view_mode = 'default', $classes = array()) {
  // Load our menu item which we use as the root element in our tree.
  $starting_menu_item = $mlid == FALSE ? NULL : menu_link_load($mlid);

  // Make sure that if we have a menu item in the settings it's own menu is
  // used.
  if ($starting_menu_item && $starting_menu_item['menu_name'] != $menu_name) {
    $menu_name = $starting_menu_item['menu_name'];
  }

  $config = paddle_landing_page_generate_menu_structure_pane_cache_config($starting_menu_item, $max_depth);

  // Build our menu tree with some config.
  global $user;
  $roles_key = md5(serialize(array_keys($user->roles)));
  $config_key = md5(serialize($config));
  // We use the same cache id prefix 'links:' followed by the menu name, and
  // the same cache bin as the menu module, to ensure our cache gets cleared as
  // well whenever menu_cache_clear() is called.

  $suffix = "";
  $cache_menu_panes_per_page = variable_get("cache_menu_panes_per_page", FALSE);
  if($cache_menu_panes_per_page != FALSE) {
    $suffix = ':' . md5($_GET['q']);
  }

  $cid = 'links:' . $menu_name . ':menu-structure:' . $config_key . ':' . $roles_key . $suffix;

  $cache = cache_get($cid, 'cache_menu');

  if ($cache && $cache->expire > 0 && $cache->expire < REQUEST_TIME) {
    $cache = FALSE;
  }

  // Don't cache the pane for logged in users.
  if ($cache) {
    $output = $cache->data;
  }
  else {
    $tree = user_access('view all unpublished content') ? paddle_landing_page_menu_build_tree($menu_name, $config) : menu_build_tree($menu_name, $config);

    // Knock off the siblings of our root menu item.
    if (is_array($starting_menu_item)) {
      $keys = array_keys($tree);
      $keyword = preg_quote($starting_menu_item['link_title'] . ' ' . $starting_menu_item['mlid'], '/');
      $key = array_values(preg_grep("/{$keyword}/i", $keys));
      $new_tree[$key[0]] = $tree[$key[0]];
      // Knock off the parent item, if items exist below.
      if (!empty($tree[$key[0]]['below'])) {
        $new_tree = $tree[$key[0]]['below'];
      }
    }
    else {
      $new_tree = $tree;
    }

    $output = _paddle_landing_page_menu_structure_tree_output($new_tree, $view_mode);
    $output = drupal_render($output);
    cache_set($cid, $output, 'cache_menu', CACHE_TEMPORARY);
  }

  $classes[] = 'ul-menu-items';

  $attributes = array(
    'class' => $classes,
    'data-cache' => $cache ? 'hit' : 'miss',
  );
  return $output ? '<div ' . drupal_attributes($attributes) . '>' . $output . '</div>' : '';
}

/**
 * Returns a rendered menu tree.
 *
 * This is taken from menu_tree_output() but allows to display unpublished items
 * to users with the 'view all unpublished content' permission.
 *
 * The menu item's LI element is given one of the following classes:
 * - expanded: The menu item is showing its submenu.
 * - collapsed: The menu item has a submenu which is not shown.
 * - leaf: The menu item has no submenu.
 *
 * @param array $tree
 *   A data structure representing the tree as returned from menu_tree_data.
 * @param string $view_mode
 *   The way the menu tree will be displayed as.
 *
 * @return array
 *   A structured array to be rendered by drupal_render().
 *
 * @see menu_tree_output()
 */
function _paddle_landing_page_menu_structure_tree_output($tree, $view_mode) {
  $build = array();
  $items = array();
  global $language_url;

  // Filter out hidden links and unpublished links if the user does not have
  // access to them.
  foreach ($tree as $data) {
    if ((user_access('view all unpublished content') || $data['link']['access']) && !$data['link']['hidden']) {
      $items[] = $data;
    }
  }

  if (user_access('view all unpublished content')) {
    $unpublished_mlids = paddle_landing_page_get_mlids_leading_to_unpublished_nodes($items);
  }
  else {
    $unpublished_mlids = array();
  }

  $router_item = menu_get_item();
  $num_items = count($items);
  $nids = array();
  foreach ($items as $i => $data) {
    // The default view mode renders the menu structure tree as links.
    if ($view_mode == 'default') {
      $class = array();
      if ($i == 0) {
        $class[] = 'first';
      }
      if ($i == $num_items - 1) {
        $class[] = 'last';
      }
      // Set a class for the <li>-tag. Since $data['below'] may contain local
      // tasks, only set 'expanded' class if the link also has children within
      // the current menu.
      if ($data['link']['has_children'] && $data['below']) {
        $class[] = 'expanded';
      }
      elseif ($data['link']['has_children']) {
        $class[] = 'collapsed';
      }
      else {
        $class[] = 'leaf';
      }
      // Set a class if the link is in the active trail.
      if ($data['link']['in_active_trail']) {
        $class[] = 'active-trail';
        $data['link']['localized_options']['attributes']['class'][] = 'active-trail';
      }
      // Normally, l() compares the href of every link with $_GET['q'] and sets
      // the active class accordingly. But local tasks do not appear in menu
      // trees, so if the current path is a local task, and this link is its
      // tab root, then we have to set the class manually.
      if ($data['link']['href'] == $router_item['tab_root_href'] && $data['link']['href'] != $_GET['q']) {
        $data['link']['localized_options']['attributes']['class'][] = 'active';
      }

      $backend_paths = array(
        'admin/content_manager/' . $data['link']['link_path'] . '/view',
        $data['link']['link_path'] . '/draft',
      );

      $paddle_path_by_menu_possible_paths = array(
        paddle_path_by_menu_get_original_path(),
        drupal_get_path_alias(),
      );

      // Check if the current page is shown on the back-end or a draft.
      if (in_array($_GET['q'], $backend_paths)) {
        $data['link']['localized_options']['attributes']['class'][] = 'active';
      }
      // In the paddle_path_by_menu module the active menu item has been modified.
      // Which means that the comparison made in the l() function will never work.
      // The $_GET['q'] is not what drupal core intends it to be. Currently it is
      // stored in the node/% pattern instead of the path alias. We need to redo
      // the check here to correctly assess the active item. We do not check the
      // $_GET['q'] value here since otherwise we would have a double active class
      // .
      elseif ((in_array($data['link']['href'], $paddle_path_by_menu_possible_paths) && $data['link']['href'] != $_GET['q'])) {
        $data['link']['localized_options']['attributes']['class'][] = 'active';
      }
      // Allow menu-specific theme overrides.
      $element['#theme'] = 'menu_link__' . strtr($data['link']['menu_name'], '-', '_');
      $element['#attributes']['class'] = $class;
      $element['#title'] = $data['link']['title'];
      if (in_array($data['link']['mlid'], $unpublished_mlids)) {
        $element['#title'] .= ' (' . t('unpublished') . ')';
      }
      $element['#href'] = $data['link']['href'];
      $element['#localized_options'] = !empty($data['link']['localized_options']) ? $data['link']['localized_options'] : array();
      $element['#below'] = $data['below'] ? _paddle_landing_page_menu_structure_tree_output($data['below'], $view_mode) : $data['below'];
      $element['#original_link'] = $data['link'];
      // Index using the link's unique mlid.
      $build[$data['link']['mlid']] = $element;
    }
    // Retrieve the node ID of menu items linked to a node.
    // TODO: Handle external links to be viewed in a node based view mode.
    elseif (!empty($data['link']['router_path']) && $data['link']['router_path'] == 'node/%') {
      // The fifth character is the first character behind 'node/'.
      $nid = drupal_substr($data['link']['link_path'], 5);
      $nids[] = $nid;
    }
    // Support the <front> internal link when it links to a node.
    elseif (!empty($data['link']['link_path']) && $data['link']['link_path'] == '<front>') {
      $node_link_path = drupal_get_normal_path(variable_get('site_frontpage', 'node'));
      $nid = drupal_substr($node_link_path, 5);
      $nids[] = $nid;
    }
  }

  if ($view_mode != 'default' && !empty($nids)) {
    $nodes = node_load_multiple($nids);
    $build = node_view_multiple($nodes, $view_mode);
  }
  if ($build) {
    // Make sure drupal_render() does not re-order the links.
    $build['#sorted'] = TRUE;
    // Add the theme wrapper for outer markup.
    // Allow menu-specific theme overrides.
    $build['#theme_wrappers'][] = 'menu_tree__' . strtr($data['link']['menu_name'], '-', '_');
  }

  return $build;
}
