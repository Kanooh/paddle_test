<?php

/**
 * @file
 * Administrative page callbacks for Paddle Menu Display module.
 */

/**
 * Page callback for the overview of the menu display interface.
 */
function paddle_menu_display_main_interface() {
  $result = db_query("SELECT mdid, title, name, description FROM {menu_displays} ORDER BY title", array(), array('fetch' => PDO::FETCH_ASSOC));
  $header = array(t('Title'), array(
    'data' => t('Operations'),
    'colspan' => '2',
    ));
  $rows = array();
  foreach ($result as $menu) {
    $row = array($menu['title'] . '<div class="description">' . check_plain($menu['description']) . '</div>');
    $row[] = array('data' => l(t('edit menu display'), 'admin/structure/menu_displays/' . $menu['mdid'] . '/edit'));
    $row[] = array('data' => l(t('delete menu display'), 'admin/structure/menu_displays/' . $menu['mdid'] . '/delete'));
    $rows[] = $row;
  }
  return theme('table', array('header' => $header, 'rows' => $rows));
}

/**
 * Page callback for the edit menu display interface.
 */
function paddle_menu_display_edit_page() {
  drupal_add_css(drupal_get_path('module', 'paddle_menu_display') . '/misc/paddle_menu_display.css', array('every_page' => FALSE, 'group' => CSS_THEME));
  drupal_add_js(drupal_get_path('module', 'paddle_menu_display') . '/misc/paddle_menu_display_edit.js', array('every_page' => FALSE, 'group' => JS_THEME));
  $form = array();
  $mdid = arg(3);
  $result = db_query('SELECT * FROM {menu_displays} WHERE mdid = :mdid', array(':mdid' => $mdid));
  $record = $result->fetchObject();

  // If the record can not be loaded we are creating a new menu display.
  if (!$record) {
    $record = new stdClass();
    $record->title = '';
    $record->description = '';
    $record->menu_name = 'main-menu';
    $record->follow_active_trail = 0;
    $record->root_path = '';
    $record->{'404_root_path'} = 'admin';
    $record->access_denied_root_path = 'admin';
    $record->from_level = '1';
    $record->to_level = '1';
    $record->add_active_class = TRUE;
    $record->add_first_last = TRUE;
    $record->add_odd_even = FALSE;
    $record->add_active_trail = TRUE;
    $record->div_class = '';
    $record->ul_class = '';
    $record->li_class = '';
    $record->a_class = '';
    $record->cont_types_roots = array();
    $record->path_roots = array();

    drupal_set_title('Add menu display');
  }
  else {
    $record->cont_types_roots = $record->cont_types_roots ? unserialize($record->cont_types_roots) : array();
    $record->path_roots = $record->path_roots ? unserialize($record->path_roots) : array();
    $tag_classes = unserialize($record->tag_classes);
    $record->div_class = $tag_classes['div_class'];
    $record->ul_class = $tag_classes['ul_class'];
    $record->li_class = $tag_classes['li_class'];
    $record->a_class = $tag_classes['a_class'];
  }

  $levels = drupal_map_assoc(range(1, 9));
  $levels['curr_lvl'] = 'Current level';
  $levels['curr_lvl_plus_1'] = 'Current level + 1';

  $form['title'] = array(
    '#type' => 'textfield',
    '#default_value' => $record->title,
    '#title' => t('Title'),
    '#description' => t('This name will apear in the list of the menu displays. It will be also used as title of the block generated for this menu.'),
    '#maxlength' => 255,
    '#required' => TRUE,
  );

  $form['description'] = array(
    '#type' => 'textarea',
    '#title' => t('Description'),
    '#default_value' => $record->description,
    '#description' => t('Short description which will appear in the list of the menu displays.'),
  );

  $form['menu_name'] = array(
    '#type' => 'select',
    '#title' => t('Menus'),
    '#options' => menu_get_menus(),
    '#default_value' => $record->menu_name,
    '#description' => t('Select the menu of which you want the menu display.'),
    '#required' => TRUE,
  );

  $form['from_level'] = array(
    '#type' => 'select',
    '#default_value' => 1,
    '#title' => t('From level'),
    '#default_value' => $record->from_level,
    '#options' => $levels,
    '#description' => t('From which level of the menu the rendering should start.'),
  );

  $form['to_level'] = array(
    '#type' => 'select',
    '#default_value' => 1,
    '#title' => t('To level'),
    '#default_value' => $record->to_level,
    '#options' => $levels,
    '#description' => t('On which level of the menu the rendering should end.'),
  );

  $form['add_active_class'] = array(
    '#type' => 'checkbox',
    '#default_value' => TRUE,
    '#title' => t('Add active class'),
    '#default_value' => $record->add_active_class,
    '#description' => t('Check to have class "active" on the <em>li</em> of the active menu item.'),
  );

  $form['add_first_last'] = array(
    '#type' => 'checkbox',
    '#default_value' => TRUE,
    '#title' => t('Add first/last classes'),
    '#default_value' => $record->add_first_last,
    '#description' => t('Check to have classes "first" and "last" on the <em>li</em> of the first and last menu items accordingly.'),
  );

  $form['add_odd_even'] = array(
    '#type' => 'checkbox',
    '#default_value' => FALSE,
    '#title' => t('Add odd/even class'),
    '#default_value' => $record->add_odd_even,
    '#description' => t('Check to have class "odd" and "even" on the <em>li</em> of the menu.'),
  );

  $form['add_active_trail'] = array(
    '#type' => 'checkbox',
    '#default_value' => FALSE,
    '#title' => t('Add active trail class'),
    '#default_value' => $record->add_active_trail,
    '#description' => t('Check to have class "active_trail"on the <em>li</em> of the menu.'),
  );

  $form['div_class'] = array(
    '#type' => 'textfield',
    '#default_value' => $record->div_class,
    '#title' => t('Class of <em>div</em>'),
    '#description' => t('An additional class to add to each <em>div</em> wrapping an <em>ul</em>.'),
    '#maxlength' => 255,
  );

  $form['ul_class'] = array(
    '#type' => 'textfield',
    '#default_value' => $record->ul_class,
    '#title' => t('Class of <em>ul</em>'),
    '#description' => t('An additional class to add to each <em>ul</em>.'),
    '#maxlength' => 255,
  );

  $form['li_class'] = array(
    '#type' => 'textfield',
    '#default_value' => $record->li_class,
    '#title' => t('Class of <em>li</em>'),
    '#description' => t('An additional class to add to each <em>li</em>.'),
    '#maxlength' => 255,
  );

  $form['a_class'] = array(
    '#type' => 'textfield',
    '#default_value' => $record->a_class,
    '#title' => t('Class of <em>a</em>'),
    '#description' => t('An additional class to add to each <em>a</em>.'),
    '#maxlength' => 255,
  );

  $form['root_items'] = array(
    '#type' => 'fieldset',
    '#title' => 'Root Items',
    '#collapsable' => TRUE,
  );

  $form['root_items']['follow_active_trail'] = array(
    '#type' => 'checkbox',
    '#default_value' => $record->follow_active_trail,
    '#title' => t('Follow active trail'),
    '#description' => t('Whether or not the menu display will follow the active trail.'),
  );

  $form['root_items']['root_path'] = array(
    '#type' => 'textfield',
    '#default_value' => $record->root_path,
    '#title' => t('Global root item'),
    '#description' => t('The menu item from which the rendering will be reckoned. If you leave it blank it will use the current path as root item.'),
    '#maxlength' => 255,
  );

  $form['root_items']['404_root_path'] = array(
    '#type' => 'textfield',
    '#default_value' => $record->{'404_root_path'},
    '#title' => t('"Page not found" root item'),
    '#description' => t('The root item in case of 404.'),
    '#maxlength' => 255,
  );

  $form['root_items']['access_denied_root_path'] = array(
    '#type' => 'textfield',
    '#default_value' => $record->{'access_denied_root_path'},
    '#title' => t('"Access denied" root item'),
    '#description' => t('The root item in case of 403 (access denied).'),
    '#maxlength' => 255,
  );

  $form['root_items']['content_types'] = array(
    '#type' => 'fieldset',
    '#title' => t('Root items for nodes'),
    '#collapsable' => TRUE,
  );

  $content_types = _node_types_build()->types;

  foreach ($content_types as $type) {
    $form['root_items']['content_types']['cont_type_' . $type->type] = array(
      '#type' => 'textfield',
      '#default_value' => isset($record->cont_types_roots[$type->type]) ? $record->cont_types_roots[$type->type] : '',
      '#title' => $type->name,
      '#description' => t('The root item for this content type.'),
      '#maxlength' => 255,
    );
  }

  $form['root_items']['path_roots'] = array(
    '#type' => 'fieldset',
    '#title' => t('Root items for paths'),
    '#collapsable' => TRUE,
  );

  foreach ($record->path_roots as $i => $path_root) {
    $form['root_items']['path_roots']['group_' . $i] = array(
      '#type' => 'fieldset',
      '#title' => t('Path/root item pair'),
      '#collapsable' => TRUE,
      '#attributes' => array('class' => array('path_root_pair')),
    );

    $form['root_items']['path_roots']['group_' . $i]['path_roots_path_' . $i] = array(
      '#type' => 'textfield',
      '#default_value' => isset($record->path_roots[$i]['path']) ? $record->path_roots[$i]['path'] : '',
      '#title' => t('Path'),
      '#description' => t('The path for which the manu display block will appear.'),
      '#attributes' => array('class' => array('path_roots_path')),
      '#maxlength' => 255,
    );

    $form['root_items']['path_roots']['group_' . $i]['path_roots_root_item_' . $i] = array(
      '#type' => 'textfield',
      '#default_value' => isset($record->path_roots[$i]['root_item']) ? $record->path_roots[$i]['root_item'] : '',
      '#title' => t('Root item'),
      '#description' => t('The root item for this path.'),
      '#maxlength' => 255,
      '#attributes' => array('class' => array('path_roots_root_item')),
    );
  }

  $cnt = count($record->path_roots);

  $form['root_items']['path_roots']['group_' . $cnt] = array(
    '#type' => 'fieldset',
    '#title' => t('Path/root item pair'),
    '#collapsable' => TRUE,
    '#attributes' => array('class' => array('first_empty_path_root_item', 'path_root_pair')),
  );

  $form['root_items']['path_roots']['group_' . $cnt]['path_roots_path_' . $cnt] = array(
    '#type' => 'textfield',
    '#default_value' => '',
    '#title' => t('Path'),
    '#description' => t('The path for which the manu display block will appear.'),
    '#maxlength' => 255,
    '#attributes' => array('class' => array('path_roots_path')),
  );

  $form['root_items']['path_roots']['group_' . $cnt]['path_roots_root_item_' . $cnt] = array(
    '#type' => 'textfield',
    '#default_value' => '',
    '#title' => t('Root item'),
    '#description' => t('The root item for this path.'),
    '#maxlength' => 255,
    '#attributes' => array('class' => array('path_roots_root_item')),
  );

  $form['root_items']['path_roots']['add_path_root'] = array(
    '#markup' => theme_render_template(drupal_get_path('module', 'paddle_menu_display') . '/templates/btn.tpl.php', array()),
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#submit' => array('paddle_menu_display_edit_submit'),
  );

  $form['cancel'] = array(
    '#type' => 'submit',
    '#value' => t('Cancel'),
    '#submit' => array('paddle_menu_display_edit_cancel'),
  );

  $form['mdid'] = array(
    '#type' => 'hidden',
    '#value' => $mdid,
  );

  return $form;
}

/**
 * Submit callback for the cancel button of the edit menu display form.
 */
function paddle_menu_display_edit_cancel() {
  drupal_goto('admin/structure/menu_displays');
}

/**
 * Submit callback for the cancel button of the edit menu display form.
 */
function paddle_menu_display_edit_submit($form, &$form_state) {
  $menu = $form_state['values'];
  $post_values = $_POST;
  $cont_types_roots = array();
  $path_roots = array();

  $content_types = _node_types_build()->types;
  foreach ($content_types as $type) {
    if ($menu['cont_type_' . $type->type]) {
      $cont_types_roots[$type->type] = $menu['cont_type_' . $type->type];
    }
  }
  foreach ($post_values as $key => $value) {
    if (strpos($key, 'path_roots_path_') === 0) {
      $id = substr($key, strlen('path_roots_path_'));
      if ($value && $post_values['path_roots_root_item_' . $id]) {
        $path_roots[$id] = array('path' => $value, 'root_item' => $post_values['path_roots_root_item_' . $id]);
      }
    }
  }
  $tag_classes = array(
    'div_class' => $menu['div_class'],
    'ul_class' => $menu['ul_class'],
    'li_class' => $menu['li_class'],
    'a_class' => $menu['a_class'],
  );

  $menu_display = array(
    'title' => $menu['title'],
    'name' => str_replace(' ', '_', strtolower($menu['title'])),
    'description' => $menu['description'],
    'menu_name' => $menu['menu_name'],
    'follow_active_trail' => $menu['follow_active_trail'],
    'root_path' => $menu['root_path'],
    '404_root_path' => $menu['404_root_path'],
    'from_level' => $menu['from_level'],
    'to_level' => $menu['to_level'],
    'add_active_class' => $menu['add_active_class'],
    'add_first_last' => $menu['add_first_last'],
    'add_odd_even' => $menu['add_odd_even'],
    'add_active_trail' => $menu['add_active_trail'],
    'tag_classes' => serialize($tag_classes),
    'cont_types_roots' => serialize($cont_types_roots),
    'path_roots' => serialize($path_roots),
  );
  if ($menu['mdid'] > 0) {
    $menu_display['mdid'] = $menu['mdid'];
    drupal_write_record('menu_displays', $menu_display, 'mdid');
    drupal_set_message('The menu display has been updated successfully!', 'status');
    // Clear the cache records for this menu display.
    module_load_include('inc', 'paddle_menu_display', 'paddle_menu_display_main');
    paddle_menu_display_cache_clear_by_cid($menu_display['mdid'] . ':%', FALSE);
  }
  else {
    drupal_write_record('menu_displays', $menu_display);
    drupal_set_message('The menu display has been created successfully!', 'status');
  }
  drupal_goto('admin/structure/menu_displays');
}

/**
 * Page callback for the delete operation of menu display.
 */
function paddle_menu_display_delete($menu_display) {
  $mdid = arg(3);
  $result = db_query('SELECT * FROM {menu_displays} WHERE mdid = :mdid', array(':mdid' => $mdid));
  $record = $result->fetchObject();

  return drupal_get_form('paddle_menu_display_delete_confirm', $record);
}

/**
 * Delete confirm for a menu display.
 */
function paddle_menu_display_delete_confirm($form, &$form_state, $record) {
  $form['#menu_display'] = $record;

  $caption = '<p>' . t('This action cannot be undone.') . '</p>';
  return
    confirm_form($form, t('Are you sure you want to delete the custom menu display %title?', array('%title' => $record->title)), 'admin/structure/menu_displays', $caption, t('Delete'));
}

/**
 * Delete callback.
 */
function paddle_menu_display_delete_confirm_submit($form, &$form_state) {
  $menu_display = $form['#menu_display'];
  $form_state['redirect'] = 'admin/structure/menu_displays';

  db_delete('block')->condition('delta', $menu_display->name)->condition('module', 'paddle_menu_display')->execute();
  db_delete('menu_displays')->condition('mdid', $menu_display->mdid)->execute();

  $t_args = array('%title' => $menu_display->title);
  drupal_set_message(t('The custom menu display %title has been deleted.', $t_args));
  watchdog('paddle_menu_display', 'Deleted custom menu %title and all its menu links.', $t_args, WATCHDOG_NOTICE);
}

/**
 * Add new menu display page callback.
 */
function paddle_menu_display_add() {
  return drupal_get_form('paddle_menu_display_edit_page');
}
