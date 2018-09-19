<?php

/**
 * @file
 * Administrative page callbacks for Paddle Menu Manager module.
 */

/**
 * Page callback for the overview of the menus.
 */
function paddle_menu_manager_overview() {
  global $language_content;

  // Include CTools modal dialog support.
  ctools_include('ajax');
  ctools_include('modal');
  ctools_modal_add_js();

  if ($menus = paddle_menu_manager_get_menus($language_content->language)) {
    $header = array(
      t('Title'),
      array(
        'data' => t('Operations'),
        'colspan' => '4',
      ),
    );
    $rows = array();
    foreach ($menus as $menu) {
      $row = array(
        theme('menu_admin_overview', array(
          'title' => $menu['title'],
          'name' => $menu['menu_name'],
          'description' => $menu['description'],
        )),
      );
      $row[] = array('data' => l(t('list links'), 'admin/structure/menu_manager/' . $menu['i18n_tsid']));
      $row[] = array('data' => l(t('edit menu'), 'admin/structure/menu_manager/' . $menu['i18n_tsid'] . '/edit'));
      $row[] = array('data' => l(t('add link'), 'admin/structure/menu_manager/' . $menu['i18n_tsid'] . '/add'));
      $row[] = array(
        'data' => ctools_modal_text_button(t('delete menu'), 'admin/structure/menu_manager/nojs/' . $menu['menu_name'] . '/delete', t('Delete menu @name', array('@name' => $menu['title']))),
      );
      $rows[] = $row;
    }

    return theme('table', array('header' => $header, 'rows' => $rows));
  }
  $link = ctools_modal_text_button(t('Create menu'), 'admin/structure/menu_manager/nojs/add', t('Create a new menu.'));
  return t('No menus have been created yet. !link', array('!link' => $link));
}

/**
 * Returns HTML for the paddle_menu_manager overview page.
 *
 * @param array $variables
 *   An associative array containing:
 *   - title: The menu's title.
 *   - description: The menu's description.
 *
 * @ingroup themeable
 */
function theme_paddle_menu_manager_overview($variables) {
  $output = check_plain($variables['title']);
  $output .= '<div class="description">' . filter_xss_admin($variables['description']) . '</div>';

  return $output;
}

/**
 * Form for editing an entire menu tree at once.
 *
 * Shows for one menu the menu links accessible to the current user and
 * relevant operations.
 *
 * This is based on menu_overview_form(), but filters the results on
 * $language_content.
 * @see menu_overview_form()
 */
function paddle_menu_manager_menu_overview_form($form, &$form_state, $tsid) {
  global $menu_admin;
  global $language_content;

  if (module_exists('paddle_contextual_toolbar')) {
    $form['#after_build'] = array(
      'paddle_menu_manager_add_contextual_actions_menu_items_overview',
    );
  }
  module_load_include('inc', 'menu', 'menu.admin');

  $translation_set = i18n_translation_set_load($tsid);
  $translations = $translation_set->get_translations();
  $menu = $translations[$language_content->language];

  $form['#attached']['css'] = array(drupal_get_path('module', 'menu') . '/menu.css');
  $form['#attached']['js'] = array(drupal_get_path('module', 'paddle_menu_manager') . '/js/paddle_menu_manager_big_menus.js');
  $tree = paddle_menu_manager_generate_renderable_menu($menu['menu_name'], 1, 1, -1);
  $form = array_merge($form, _paddle_menu_manager_menu_overview_form($tree, $menu));
  $form['#menu'] = $menu;

  if (element_children($form)) {
    $form['actions'] = array('#type' => 'actions');
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save configuration'),
      '#submit' => array('paddle_menu_manager_overview_form_submit'),
    );
  }
  else {
    $form['#empty_text'] = t('There are no menu links yet.');
  }

  // Add the menu language as a data attribute of the form.
  $form['#attributes']['data-menu-language'] = $form['#menu']['language'];
  return $form;
}

/**
 * Submit handler for the Paddle Menu Manager overview form.
 *
 * This is based on menu_overview_form_submit() changing the source of the
 * data read - instead from the form it's from the form_state.
 *
 * This function takes great care in saving parent items first, then items
 * underneath them. Saving items in the incorrect order can break the menu tree.
 *
 * @see menu_overview_form_submit()
 */
function paddle_menu_manager_overview_form_submit($form, &$form_state) {
  // Get the $_POST order.
  $order = array_flip(array_keys($form_state['input']));
  // Update our original form with the new order.
  $form = array_merge($order, $form);
  $updated_items = array();
  $fields = array('weight', 'plid');
  foreach (element_children($form) as $mlid) {
    if (isset($form[$mlid]['#item'])) {
      $element = $form[$mlid];
      // Update any fields that have changed in this menu item.
      foreach ($fields as $field) {
        if ($form_state['input'][$mlid][$field] != $element[$field]['#default_value']) {
          $element['#item'][$field] = $form_state['input'][$mlid][$field];
          // If the parent item itself has changed, make sure the original
          // parent item will be updated before the child item. This prevents
          // any changes to be overwritten by the original values of the
          // parent item.
          if ($field == 'plid') {
            $original_plid = "mlid:{$element[$field]['#default_value']}";
            if (!array_key_exists($original_plid, $updated_items) && array_key_exists($original_plid, $form)) {
              $updated_items = array($original_plid => $form[$original_plid]['#item']) + $updated_items;
            }
          }
          $updated_items[$mlid] = $element['#item'];
        }
      }

      // Hidden is a special case, the value needs to be reversed.
      if (isset($form_state['input'][$mlid]['hidden']) && $form_state['input'][$mlid]['hidden'] != $element['hidden']['#default_value']) {
        // Convert to integer rather than boolean due to PDO cast to string.
        $element['#item']['hidden'] = $form_state['input'][$mlid]['hidden'] ? 0 : 1;
        $updated_items[$mlid] = $element['#item'];
      }
    }
  }

  // Save all our changed items to the database.
  foreach ($updated_items as $item) {
    $item['customized'] = 1;
    menu_link_save($item);
    // Clear the page cache of menu structure panes containing this item.
    paddle_landing_page_clear_page_cache_of_menu_structure_panes($item);
  }
  drupal_set_message(t('Your configuration has been saved.'));
}

/**
 * Recursive helper function for menu_overview_form().
 *
 * This is based on _menu_overview_tree_form(), adding a number of additional
 * column to the table.
 * @see _menu_overview_tree_form()
 *
 * @param array $tree
 *   The menu_tree retrieved by menu_tree_data.
 * @param array $menu
 *   The menu for which we are on the overview page.
 */
function _paddle_menu_manager_menu_overview_form($tree, $menu) {
  ctools_include('ajax');
  ctools_include('modal');
  ctools_modal_add_js();
  $form = &drupal_static(__FUNCTION__, array('#tree' => TRUE));
  foreach ($tree as $data) {
    $item = $data['link'];

    // Don't show callbacks; these have $item['hidden'] < 0.
    if ($item && $item['hidden'] >= 0) {
      $mlid = 'mlid:' . $item['mlid'];
      $form[$mlid]['#item'] = $item;
      $form[$mlid]['#attributes'] = $item['hidden'] ? array('class' => array('menu-disabled')) : array('class' => array('menu-enabled'));
      $form[$mlid]['#attributes']['class'][] = 'mlid-' . $item['mlid'];

      // Add the expanding link for big menus if the item has_children.
      $form[$mlid]['expandable']['#markup'] = '';
      if ($item['has_children']) {
        $form[$mlid]['expandable']['#markup'] = l(
          // HTML entity for a squared plus sign.
          '&#8862;',
          'admin/structure/menu_manager/get_submenu/' . $menu['menu_name'] . '/' . $item['mlid'],
          array(
            'attributes' => array(
              'class' => 'paddle-big-menu-expandable',
              'rel' => $item['mlid'],
            ),
            'html' => TRUE,
          )
        );
      }
      // In the menu overview, we show the menu link title as a tooltip, instead
      // of the description.
      $item['localized_options']['attributes']['title'] = $item['title'];

      // Add the menu link.
      $form[$mlid]['title']['#markup'] = l($item['title'], $item['href'], $item['localized_options']);

      if ($item['hidden']) {
        $form[$mlid]['title']['#markup'] .= ' (' . t('disabled') . ')';
      }
      elseif ($item['link_path'] == 'user' && $item['module'] == 'system') {
        $form[$mlid]['title']['#markup'] .= ' (' . t('logged in users only') . ')';
      }

      // Determine the url and link type to show in the table. We support these
      // types:
      // - frontpage: used for items that link to <front>.
      // - [node type]: for nodes.
      // All other paths will be left blank for the moment.
      // @todo: Replace this with a hook that can be implemented to provide
      // additional menu types.
      $url = url_is_external($item['link_path']) ? $item['link_path'] : '/' . drupal_get_path_alias($item['link_path']);
      $type = '';

      $link_path = ($item['link_path'] == '<front>') ? variable_get('site_frontpage', 'node') : $item['link_path'];

      if (url_is_external($item['link_path'])) {
        $type = t('External');
      }
      else {
        $link_path = (array) explode('/', $item['link_path']);

        switch ($link_path[0]) {
          case '<front>':
            $url = '/';
            $type = t('Frontpage');
            break;

          case 'node':
            if (count($link_path) == 2 && is_numeric($link_path[1])) {
              $node = node_load($link_path[1]);
              $type = node_type_get_name($node);
            }
            break;
        }
      }

      // In the menu overview, we show the menu path as a tooltip.
      $form[$mlid]['url'] = array(
        '#value' => $url,
        '#theme_wrappers' => array('html_tag'),
        '#tag' => 'span',
        '#attributes' => array('title' => check_plain($url)),
      );

      $form[$mlid]['type'] = array(
        '#markup' => $type,
      );

      if (!empty($node) && is_array($link_path)) {
        $form[$mlid]['node_status'] = array(
          '#markup' => $node->status == 1 ? t('Published') : paddle_content_manager_get_node_status_label($node),
        );
      }

      $form[$mlid]['hidden'] = array(
        '#type' => 'checkbox',
        '#title' => t('Enable @title menu link', array('@title' => $item['title'])),
        '#title_display' => 'invisible',
        '#default_value' => !$item['hidden'],
        '#access' => FALSE,
      );

      $form[$mlid]['weight'] = array(
        '#type' => 'weight',
        '#delta' => count($tree) < 50 ? 50 : count($tree),
        '#default_value' => !empty($item['weight']) ? $item['weight'] : 0,
        '#title_display' => 'invisible',
        '#title' => t('Weight for @title', array('@title' => $item['title'])),
      );

      $form[$mlid]['mlid'] = array(
        '#type' => 'hidden',
        '#value' => $item['mlid'],
      );

      $form[$mlid]['plid'] = array(
        '#type' => 'hidden',
        '#default_value' => $item['plid'],
      );

      // Build a list of operations.
      $operations = array();
      if (!empty($node) && $node->status == 1 && !user_access('manage_menu_link_to_published_nodes')) {
        $form[$mlid]['hidden']['#access'] = FALSE;

        $operations['edit'] = array();

        $operations['visit'] = array(
          '#type' => 'link',
          '#title' => t('Visit'),
          '#href' => $item['link_path'],
          '#attributes' => array(
            'class' => array(
              'ui-icon',
              'ui-icon-hiddentext',
              'ui-icon-visit',
            ),
          ),
        );

        if ($item['module'] == 'menu' || $item['updated'] == 1) {
          $operations['delete'] = array();
        }
        // Set the reset column.
        elseif ($item['module'] == 'system' && $item['customized']) {
          $operations['reset'] = array();
        }
      }
      else {
        $operations['visit'] = array(
          '#type' => 'link',
          '#title' => t('Visit'),
          '#href' => $item['link_path'],
          '#attributes' => array(
            'class' => array(
              'ui-icon',
              'ui-icon-hiddentext',
              'ui-icon-visit',
            ),
          ),
        );

        $operations['edit'] = array(
          '#markup' => ctools_modal_text_button(t('Edit'), 'admin/structure/menu_manager/nojs/' . $menu['menu_name'] . '/' . $item['mlid'] . '/edit', t('Edit this menu item.'), 'ui-icon ui-icon-hiddentext ui-icon-edit ctools-modal-overlay-persist'),
        );

        // Only items created by the menu module can be deleted.
        if ($item['module'] == 'menu' || $item['updated'] == 1) {
          $operations['delete'] = array(
            '#markup' => ctools_modal_text_button(t('Delete'), 'admin/structure/menu_manager/nojs/' . $menu['menu_name'] . '/' . $item['mlid'] . '/delete', t('Delete this menu item.'), 'ui-icon ui-icon-hiddentext ui-icon-delete'),
          );
        }
        // Set the reset column.
        elseif ($item['module'] == 'system' && $item['customized']) {
          $operations['reset'] = array(
            '#type' => 'link',
            '#title' => t('reset'),
            '#href' => 'admin/structure/menu/item/' . $item['mlid'] . '/reset',
            '#attributes' => array(
              'class' => array(
                'ui-icon',
                'ui-icon-hiddentext',
                'ui-icon-reset',
              ),
            ),
          );
        }
      }
      $form[$mlid]['operations'] = $operations;
    }

    if ($data['below']) {
      _paddle_menu_manager_menu_overview_form($data['below'], $menu);
    }
  }

  return $form;
}

/**
 * Returns HTML for the menu overview form into a table.
 *
 * This is based on theme_menu_overview_form(), adding a number of additional
 * columns to the table.
 * @see theme_menu_overview_form()
 *
 * @param array $variables
 *   An associative array containing:
 *   - form: A render element representing the form.
 *
 * @ingroup themeable
 */
function theme_paddle_menu_manager_menu_overview_form($variables) {
  $form = $variables['form'];
  drupal_add_tabledrag('menu-overview', 'match', 'parent', 'menu-plid', 'menu-plid', 'menu-mlid', TRUE, MENU_MAX_DEPTH - 1);
  drupal_add_tabledrag('menu-overview', 'order', 'sibling', 'menu-weight');

  $header = array(
    array('data' => t('Title'), 'class' => array('title')),
    t('Weight'),
    // The active checkbox has been disabled for users because it is not being
    // used at the moment. We keep it in code for possible future use.
    // array('data' => t('Active'), 'class' => array('checkbox')),
    t('URL'),
    t('Type'),
    array('data' => t('Status'), 'class' => array('status')),
    array('data' => t('Actions'), 'class' => array('actions')),
  );

  // Only render the url, type, and actions column if we are not rendered in a
  // modal.
  if (!empty($form['#modal_render'])) {
    unset($header[2]);
    unset($header[3]);
    unset($header[4]);
    array_pop($header);
  }

  $rows = array();
  foreach (element_children($form) as $mlid) {
    if (isset($form[$mlid]['hidden'])) {
      $element = &$form[$mlid];

      // Build a list of operations. Only do this if we are not being rendered
      // in a modal.
      $operations = array();
      $operations['data'] = '';
      if (!empty($form['#modal_render'])) {
        $element['url']['#access'] = FALSE;
        $element['type']['#access'] = FALSE;
        $element['operations']['#access'] = FALSE;
        $element['node_status']['#access'] = FALSE;
      }
      else {
        foreach (element_children($element['operations']) as $op) {
          $operations['data'] .= drupal_render($element['operations'][$op]);
        }
      }

      // Add special classes to be used for tabledrag.js.
      $element['plid']['#attributes']['class'] = array('menu-plid');
      $element['mlid']['#attributes']['class'] = array('menu-mlid');
      $element['weight']['#attributes']['class'] = array('menu-weight');

      // Change the parent field to a hidden. This allows any value but hides
      // the field.
      $element['plid']['#type'] = 'hidden';

      $row = array();
      $row[] = theme('indentation', array('size' => $element['#item']['depth'] - 1)) . drupal_render($element['expandable']) . drupal_render($element['title']);
      $row[] = drupal_render($element['weight']) . drupal_render($element['plid']) . drupal_render($element['mlid']);
      if (empty($form['#modal_render'])) {
        $row[] = drupal_render($element['url']);
        $row[] = drupal_render($element['type']);
        $row[] = drupal_render($element['node_status']);
      }
      if ($operations['data']) {
        $row = array_merge($row, $operations);
      }
      $row = array_merge(array('data' => $row), $element['#attributes']);
      $row['class'][] = 'draggable';
      $rows[] = $row;
    }
  }

  // Provide an empty text if there are no results.
  if (empty($rows)) {
    $rows[] = array(array('data' => $form['#empty_text'], 'colspan' => '6'));
  }

  $output = theme('table', array(
    'header' => $header,
    'rows' => $rows,
    'attributes' => array('id' => 'menu-overview'),
  ));

  // If we are being rendered in a modal, wrap the body in a container to aid in
  // theming.
  if (!empty($form['#modal_render'])) {
    $output = '<div class="form-body">' . $output . '</div>';
  }

  // Render the remaining elements.
  $output .= drupal_render_children($form);

  return $output;
}

/**
 * Replaces menu_tree_check_access() to show unpublished nodes as well.
 *
 * @param array $tree
 *   The menu tree you wish to operate on.
 * @param array $node_links
 *   A collection of node link references generated from $tree by
 *   menu_tree_collect_node_links().
 */
function paddle_menu_manager_tree_check_access(&$tree, $node_links = array()) {
  if ($node_links) {
    $nids = array_keys($node_links);
    $select = db_select('node', 'n');
    $select->addField('n', 'nid');
    $select->condition('n.nid', $nids, 'IN');
    $select->addTag('node_access');
    $nids = $select->execute()->fetchCol();
    foreach ($nids as $nid) {
      foreach ($node_links[$nid] as $mlid => $link) {
        $node_links[$nid][$mlid]['access'] = TRUE;
      }
    }
  }
  _menu_tree_check_access($tree);
}

/**
 * Generates the menu to be rendered.
 *
 * @param string $menu_name
 *   The name of the menu to be rendered.
 * @param int $min_depth
 *   The depth from which to start getting items.
 * @param int $max_depth
 *   The depth at which to end getting items.
 * @param int $parent
 *   The mlid of the parent of the items which should be returned.
 *
 * @return array
 *   The generated menu tree.
 */
function paddle_menu_manager_generate_renderable_menu($menu_name, $min_depth = 1, $max_depth = 1, $parent = 0) {
  $mr_fields = 'load_functions|to_arg_functions|access_callback|access_arguments|page_callback|';
  $mr_fields .= 'page_arguments|delivery_callback|title|title_callback|title_arguments|type|description';
  $query = db_select('menu_links', 'ml');
  $query->leftjoin('menu_router', 'mr', 'mr.path = ml.router_path');
  $query->fields('ml')
    ->fields('mr', explode('|', $mr_fields))
    ->condition('depth', range($min_depth, ($max_depth > MENU_MAX_DEPTH ? MENU_MAX_DEPTH : $max_depth)), 'IN')
    ->condition('ml.menu_name', $menu_name);

  if ($parent >= 0) {
    $query->condition('plid', $parent);
  }
  $result = $query->orderBy('p1, p2, p3, p4, p5, p6, p7, p8, p9', 'ASC')
    ->execute()
    ->fetchAll();
  // Convert each record to array.
  $links = array();
  foreach ($result as $record) {
    $links[] = (array) $record;
  }
  if ($parent <= 0) {
    $tree = menu_tree_data($links);
  }
  else {
    $tree = menu_tree_data($links, array($parent), $min_depth);
  }
  $node_links = array();
  menu_tree_collect_node_links($tree, $node_links);
  // We indicate that a menu administrator is running the menu access check.
  // We have overwritten the access check with our own implementation because we
  // need to show items linked to unpublished nodes.
  paddle_menu_manager_tree_check_access($tree, $node_links);

  return $tree;
}

/**
 * AJAX callback generating > L1 sub-menus.
 *
 * @param array $menu
 *   The entire menu object which will be used.
 * @param int $parent
 *   The mlid of the parent of the items which should be returned.
 * @param string $form_id
 *   The id of the form which we are editing.
 * @param string $form_build_id
 *   The build id of the form which we are editing.
 *
 * Prints the TRs of the sub-menu. The function will cache the form in order to
 * get the already generated menu items from previous calls.
 */
function paddle_menu_manager_get_submenu($menu, $parent, $form_id, $form_build_id) {
  $tree = paddle_menu_manager_generate_renderable_menu($menu['menu_name'], ++$parent['depth'], ++$parent['depth'], $parent['mlid']);

  // Set up an initial form state. It will be amended by drupal_rebuild_form().
  $form_state = form_state_defaults();
  $form_state['build_info']['args'] = array($menu['i18n_tsid']);
  $form_state['tsid'] = $menu['i18n_tsid'];
  $form_state['values'] = array();
  $form_state['rebuild_info']['copy']['#build_id'] = $form_build_id;

  // If the form is in the cache take it, otherwise rebuild it. If it is cached
  // it means we have already generated it in previous calls and it contains the
  // menu items already fetched with AJAX.
  $form = cache_get('form_' . $form_build_id, 'cache_form') ? form_get_cache($form_build_id, $form_state) : drupal_rebuild_form($form_id, $form_state, array('#build_id' => $form_build_id));

  // Get all generated menu items to remove them later - we only for the render.
  $old_mlids = array();
  foreach (element_children($form) as $mlid) {
    if (isset($form[$mlid]['#item'])) {
      $old_mlids[$form[$mlid]['#item']['mlid']] = $mlid;
    }
  }
  $form = array_merge($form, _paddle_menu_manager_menu_overview_form($tree, $menu));
  drupal_prepare_form($form_id, $form, $form_state);
  form_builder($form_id, $form, $form_state);
  // Remove the automatically generated menu items for the rendered form.
  $render_form = $form;
  foreach ($old_mlids as $mlid => $form_element_id) {
    unset($render_form[$form_element_id]);
  }
  $output = drupal_render($render_form);

  // Cache the form.
  form_set_cache($form_build_id, $form, $form_state);

  // Filter the HTML - we need the TRs only.
  $matches = array();
  $pattern = '/<tbody>(.*?)<\/tbody>/si';
  preg_match($pattern, $output, $matches);
  if ($matches[1]) {
    echo $matches[1];
  }

  die();
}
