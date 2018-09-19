<?php
/**
 * @file
 * paddle_protected_content.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_protected_content_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'roles';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'role';
  $view->human_name = 'Roles';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Roles';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'administer user roles';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '10';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'rid' => 'rid',
    'name' => 'name',
    'edit_link' => 'edit_link',
    'delete_link' => 'edit_link',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'rid' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'name' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'edit_link' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'delete_link' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* Field: Role: Rid */
  $handler->display->display_options['fields']['rid']['id'] = 'rid';
  $handler->display->display_options['fields']['rid']['table'] = 'role';
  $handler->display->display_options['fields']['rid']['field'] = 'rid';
  $handler->display->display_options['fields']['rid']['exclude'] = TRUE;
  /* Field: Role: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'role';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  /* Field: Paddle Protected Content: Edit link */
  $handler->display->display_options['fields']['edit_link']['id'] = 'edit_link';
  $handler->display->display_options['fields']['edit_link']['table'] = 'role';
  $handler->display->display_options['fields']['edit_link']['field'] = 'edit_link';
  $handler->display->display_options['fields']['edit_link']['label'] = 'Actions';
  $handler->display->display_options['fields']['edit_link']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['edit_link']['alter']['link_class'] = 'ctools-use-modal ui-icon ui-icon-hiddentext ui-icon-edit';
  /* Field: Paddle Protected Content: Delete link */
  $handler->display->display_options['fields']['delete_link']['id'] = 'delete_link';
  $handler->display->display_options['fields']['delete_link']['table'] = 'role';
  $handler->display->display_options['fields']['delete_link']['field'] = 'delete_link';
  $handler->display->display_options['fields']['delete_link']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['delete_link']['alter']['link_class'] = 'ctools-use-modal ui-icon ui-icon-hiddentext ui-icon-delete';
  $handler->display->display_options['fields']['delete_link']['element_label_colon'] = FALSE;

  /* Display: Roles overview */
  $handler = $view->new_display('page', 'Roles overview', 'page_roles_overview');
  $handler->display->display_options['path'] = 'admin/users/roles';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Roles';
  $handler->display->display_options['menu']['name'] = 'management';
  $translatables['roles'] = array(
    t('Master'),
    t('Roles'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('« first'),
    t('‹ previous'),
    t('next ›'),
    t('last »'),
    t('Rid'),
    t('Name'),
    t('Actions'),
    t('Delete link'),
    t('Roles overview'),
  );
  $export['roles'] = $view;

  return $export;
}
