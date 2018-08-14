<?php
/**
 * @file
 * paddle_contact_person.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_contact_person_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'contact_person_organizations';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Contact Person Organizations';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Relationship: Content: Organizations (field_paddle_cp_company_info) */
  $handler->display->display_options['relationships']['field_paddle_cp_company_info_value']['id'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['relationships']['field_paddle_cp_company_info_value']['table'] = 'field_data_field_paddle_cp_company_info';
  $handler->display->display_options['relationships']['field_paddle_cp_company_info_value']['field'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['relationships']['field_paddle_cp_company_info_value']['delta'] = '-1';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Sort criterion: Content: Post date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'node';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'organizational_unit' => 'organizational_unit',
  );

  /* Display: Entity Reference */
  $handler = $view->new_display('entityreference', 'Entity Reference', 'entityreference_1');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'entityreference_style';
  $handler->display->display_options['style_options']['search_fields'] = array(
    'title' => 'title',
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'entityreference_fields';
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $translatables['contact_person_organizations'] = array(
    t('Master'),
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
    t('field collection item from field_paddle_cp_company_info'),
    t('Entity Reference'),
  );
  $export['contact_person_organizations'] = $view;

  $view = new view();
  $view->name = 'contact_person_overview';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Contact Person overview';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'manage paddle_apps';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '20';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['row_class'] = 'node-[nid]';
  $handler->display->display_options['style_options']['columns'] = array(
    'nid' => 'nid',
    'title' => 'title',
    'field_paddle_cp_function' => 'field_paddle_cp_function',
    'field_paddle_cp_ou_level_3' => 'field_paddle_cp_ou_level_3',
    'state' => 'state',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'nid' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'title' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_paddle_cp_function' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_paddle_cp_ou_level_3' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'state' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* Relationship: Workbench Moderation: Node */
  $handler->display->display_options['relationships']['nid']['id'] = 'nid';
  $handler->display->display_options['relationships']['nid']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['relationships']['nid']['field'] = 'nid';
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['relationship'] = 'nid';
  $handler->display->display_options['fields']['nid']['label'] = '';
  $handler->display->display_options['fields']['nid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['nid']['element_label_colon'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['relationship'] = 'nid';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Function */
  $handler->display->display_options['fields']['field_paddle_cp_function']['id'] = 'field_paddle_cp_function';
  $handler->display->display_options['fields']['field_paddle_cp_function']['table'] = 'field_data_field_paddle_cp_function';
  $handler->display->display_options['fields']['field_paddle_cp_function']['field'] = 'field_paddle_cp_function';
  $handler->display->display_options['fields']['field_paddle_cp_function']['relationship'] = 'nid';
  /* Field: Content: Organisational unit level 3 */
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_3']['id'] = 'field_paddle_cp_ou_level_3';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_3']['table'] = 'field_data_field_paddle_cp_ou_level_3';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_3']['field'] = 'field_paddle_cp_ou_level_3';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_3']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_3']['label'] = 'Organisation';
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  /* Sort criterion: Content: Post date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'node';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'contact_person' => 'contact_person',
  );
  /* Filter criterion: Workbench Moderation: Current */
  $handler->display->display_options['filters']['is_current']['id'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['is_current']['field'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['value'] = '1';

  /* Display: Contact person overview */
  $handler = $view->new_display('page', 'Contact person overview', 'contact_person_overview');
  $handler->display->display_options['path'] = 'admin/paddlet_store/app/paddle_contact_person/export';

  /* Display: Paddle Contact Person Overview CSV Export */
  $handler = $view->new_display('views_data_export', 'Paddle Contact Person Overview CSV Export', 'paddle_contact_person_overview_csv_export');
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'views_data_export_csv';
  $handler->display->display_options['style_options']['provide_file'] = 1;
  $handler->display->display_options['style_options']['filename'] = 'contact_person_overview.csv';
  $handler->display->display_options['style_options']['parent_sort'] = 0;
  $handler->display->display_options['style_options']['quote'] = 1;
  $handler->display->display_options['style_options']['trim'] = 0;
  $handler->display->display_options['style_options']['replace_newlines'] = 0;
  $handler->display->display_options['style_options']['header'] = 1;
  $handler->display->display_options['style_options']['keep_html'] = 0;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['relationship'] = 'nid';
  $handler->display->display_options['fields']['nid']['label'] = '';
  $handler->display->display_options['fields']['nid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['nid']['element_label_colon'] = FALSE;
  /* Field: Content: First name */
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['id'] = 'field_paddle_cp_first_name';
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['table'] = 'field_data_field_paddle_cp_first_name';
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['field'] = 'field_paddle_cp_first_name';
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['relationship'] = 'nid';
  /* Field: Content: Last name */
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['id'] = 'field_paddle_cp_last_name';
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['table'] = 'field_data_field_paddle_cp_last_name';
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['field'] = 'field_paddle_cp_last_name';
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['relationship'] = 'nid';
  /* Field: Content: Function */
  $handler->display->display_options['fields']['field_paddle_cp_function']['id'] = 'field_paddle_cp_function';
  $handler->display->display_options['fields']['field_paddle_cp_function']['table'] = 'field_data_field_paddle_cp_function';
  $handler->display->display_options['fields']['field_paddle_cp_function']['field'] = 'field_paddle_cp_function';
  $handler->display->display_options['fields']['field_paddle_cp_function']['relationship'] = 'nid';
  /* Field: Content: Manager */
  $handler->display->display_options['fields']['field_paddle_cp_manager']['id'] = 'field_paddle_cp_manager';
  $handler->display->display_options['fields']['field_paddle_cp_manager']['table'] = 'field_data_field_paddle_cp_manager';
  $handler->display->display_options['fields']['field_paddle_cp_manager']['field'] = 'field_paddle_cp_manager';
  $handler->display->display_options['fields']['field_paddle_cp_manager']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_manager']['settings'] = array(
    'link' => 0,
  );
  /* Field: Content: Featured image */
  $handler->display->display_options['fields']['field_paddle_featured_image']['id'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['table'] = 'field_data_field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['field'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['click_sort_column'] = 'sid';
  $handler->display->display_options['fields']['field_paddle_featured_image']['type'] = 'paddle_scald_atom_path';
  /* Field: Content: Organisational unit level 1 */
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_1']['id'] = 'field_paddle_cp_ou_level_1';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_1']['table'] = 'field_data_field_paddle_cp_ou_level_1';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_1']['field'] = 'field_paddle_cp_ou_level_1';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_1']['relationship'] = 'nid';
  /* Field: Content: Organisational unit level 2 */
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_2']['id'] = 'field_paddle_cp_ou_level_2';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_2']['table'] = 'field_data_field_paddle_cp_ou_level_2';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_2']['field'] = 'field_paddle_cp_ou_level_2';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_2']['relationship'] = 'nid';
  /* Field: Content: Organisational unit level 3 */
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_3']['id'] = 'field_paddle_cp_ou_level_3';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_3']['table'] = 'field_data_field_paddle_cp_ou_level_3';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_3']['field'] = 'field_paddle_cp_ou_level_3';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_3']['relationship'] = 'nid';
  /* Field: Content: Address - Full name */
  $handler->display->display_options['fields']['field_paddle_cp_address_name_line']['id'] = 'field_paddle_cp_address_name_line';
  $handler->display->display_options['fields']['field_paddle_cp_address_name_line']['table'] = 'field_data_field_paddle_cp_address';
  $handler->display->display_options['fields']['field_paddle_cp_address_name_line']['field'] = 'field_paddle_cp_address_name_line';
  $handler->display->display_options['fields']['field_paddle_cp_address_name_line']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_address_name_line']['label'] = 'Full name';
  /* Field: Content: Address - Thoroughfare (i.e. Street address) */
  $handler->display->display_options['fields']['field_paddle_cp_address_thoroughfare']['id'] = 'field_paddle_cp_address_thoroughfare';
  $handler->display->display_options['fields']['field_paddle_cp_address_thoroughfare']['table'] = 'field_data_field_paddle_cp_address';
  $handler->display->display_options['fields']['field_paddle_cp_address_thoroughfare']['field'] = 'field_paddle_cp_address_thoroughfare';
  $handler->display->display_options['fields']['field_paddle_cp_address_thoroughfare']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_address_thoroughfare']['label'] = 'Address 1';
  /* Field: Content: Address - Premise (i.e. Apartment / Suite number) */
  $handler->display->display_options['fields']['field_paddle_cp_address_premise']['id'] = 'field_paddle_cp_address_premise';
  $handler->display->display_options['fields']['field_paddle_cp_address_premise']['table'] = 'field_data_field_paddle_cp_address';
  $handler->display->display_options['fields']['field_paddle_cp_address_premise']['field'] = 'field_paddle_cp_address_premise';
  $handler->display->display_options['fields']['field_paddle_cp_address_premise']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_address_premise']['label'] = 'Address 2';
  /* Field: Content: Address - Postal code */
  $handler->display->display_options['fields']['field_paddle_cp_address_postal_code']['id'] = 'field_paddle_cp_address_postal_code';
  $handler->display->display_options['fields']['field_paddle_cp_address_postal_code']['table'] = 'field_data_field_paddle_cp_address';
  $handler->display->display_options['fields']['field_paddle_cp_address_postal_code']['field'] = 'field_paddle_cp_address_postal_code';
  $handler->display->display_options['fields']['field_paddle_cp_address_postal_code']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_address_postal_code']['label'] = 'Postal code';
  /* Field: Content: Address - Locality (i.e. City) */
  $handler->display->display_options['fields']['field_paddle_cp_address_locality']['id'] = 'field_paddle_cp_address_locality';
  $handler->display->display_options['fields']['field_paddle_cp_address_locality']['table'] = 'field_data_field_paddle_cp_address';
  $handler->display->display_options['fields']['field_paddle_cp_address_locality']['field'] = 'field_paddle_cp_address_locality';
  $handler->display->display_options['fields']['field_paddle_cp_address_locality']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_address_locality']['label'] = 'City';
  /* Field: Content: Mobile office */
  $handler->display->display_options['fields']['field_paddle_cp_mobile_office']['id'] = 'field_paddle_cp_mobile_office';
  $handler->display->display_options['fields']['field_paddle_cp_mobile_office']['table'] = 'field_data_field_paddle_cp_mobile_office';
  $handler->display->display_options['fields']['field_paddle_cp_mobile_office']['field'] = 'field_paddle_cp_mobile_office';
  $handler->display->display_options['fields']['field_paddle_cp_mobile_office']['relationship'] = 'nid';
  /* Field: Content: Phone office */
  $handler->display->display_options['fields']['field_paddle_cp_phone_office']['id'] = 'field_paddle_cp_phone_office';
  $handler->display->display_options['fields']['field_paddle_cp_phone_office']['table'] = 'field_data_field_paddle_cp_phone_office';
  $handler->display->display_options['fields']['field_paddle_cp_phone_office']['field'] = 'field_paddle_cp_phone_office';
  $handler->display->display_options['fields']['field_paddle_cp_phone_office']['relationship'] = 'nid';
  /* Field: Content: Fax */
  $handler->display->display_options['fields']['field_paddle_cp_fax']['id'] = 'field_paddle_cp_fax';
  $handler->display->display_options['fields']['field_paddle_cp_fax']['table'] = 'field_data_field_paddle_cp_fax';
  $handler->display->display_options['fields']['field_paddle_cp_fax']['field'] = 'field_paddle_cp_fax';
  $handler->display->display_options['fields']['field_paddle_cp_fax']['relationship'] = 'nid';
  /* Field: Content: E-mail */
  $handler->display->display_options['fields']['field_paddle_cp_email']['id'] = 'field_paddle_cp_email';
  $handler->display->display_options['fields']['field_paddle_cp_email']['table'] = 'field_data_field_paddle_cp_email';
  $handler->display->display_options['fields']['field_paddle_cp_email']['field'] = 'field_paddle_cp_email';
  $handler->display->display_options['fields']['field_paddle_cp_email']['relationship'] = 'nid';
  /* Field: Content: Website */
  $handler->display->display_options['fields']['field_paddle_cp_website']['id'] = 'field_paddle_cp_website';
  $handler->display->display_options['fields']['field_paddle_cp_website']['table'] = 'field_data_field_paddle_cp_website';
  $handler->display->display_options['fields']['field_paddle_cp_website']['field'] = 'field_paddle_cp_website';
  $handler->display->display_options['fields']['field_paddle_cp_website']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_website']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Content: Linkedin */
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['id'] = 'field_paddle_cp_linkedin';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['table'] = 'field_data_field_paddle_cp_linkedin';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['field'] = 'field_paddle_cp_linkedin';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Content: Twitter */
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['id'] = 'field_paddle_cp_twitter';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['table'] = 'field_data_field_paddle_cp_twitter';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['field'] = 'field_paddle_cp_twitter';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Content: Yammer */
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['id'] = 'field_paddle_cp_yammer';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['table'] = 'field_data_field_paddle_cp_yammer';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['field'] = 'field_paddle_cp_yammer';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Content: Skype */
  $handler->display->display_options['fields']['field_paddle_cp_skype']['id'] = 'field_paddle_cp_skype';
  $handler->display->display_options['fields']['field_paddle_cp_skype']['table'] = 'field_data_field_paddle_cp_skype';
  $handler->display->display_options['fields']['field_paddle_cp_skype']['field'] = 'field_paddle_cp_skype';
  $handler->display->display_options['fields']['field_paddle_cp_skype']['relationship'] = 'nid';
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['relationship'] = 'nid';
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  $handler->display->display_options['path'] = 'admin/paddlet_store/app/paddle_contact_person/export/export/csv';
  $handler->display->display_options['displays'] = array(
    'contact_person_overview' => 'contact_person_overview',
    'default' => 0,
  );
  $handler->display->display_options['use_batch'] = 'batch';
  $handler->display->display_options['return_path'] = 'admin/paddlet_store/app/paddle_contact_person/configure';
  $handler->display->display_options['segment_size'] = '100';

  /* Display: Paddle Contact Person Overview XLS Export */
  $handler = $view->new_display('views_data_export', 'Paddle Contact Person Overview XLS Export', 'paddle_contact_person_overview_xls_export');
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'views_data_export_xls';
  $handler->display->display_options['style_options']['provide_file'] = 1;
  $handler->display->display_options['style_options']['filename'] = 'contact_person_overview.xls';
  $handler->display->display_options['style_options']['parent_sort'] = 0;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['relationship'] = 'nid';
  $handler->display->display_options['fields']['nid']['label'] = '';
  $handler->display->display_options['fields']['nid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['nid']['element_label_colon'] = FALSE;
  /* Field: Content: First name */
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['id'] = 'field_paddle_cp_first_name';
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['table'] = 'field_data_field_paddle_cp_first_name';
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['field'] = 'field_paddle_cp_first_name';
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['relationship'] = 'nid';
  /* Field: Content: Last name */
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['id'] = 'field_paddle_cp_last_name';
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['table'] = 'field_data_field_paddle_cp_last_name';
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['field'] = 'field_paddle_cp_last_name';
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['relationship'] = 'nid';
  /* Field: Content: Function */
  $handler->display->display_options['fields']['field_paddle_cp_function']['id'] = 'field_paddle_cp_function';
  $handler->display->display_options['fields']['field_paddle_cp_function']['table'] = 'field_data_field_paddle_cp_function';
  $handler->display->display_options['fields']['field_paddle_cp_function']['field'] = 'field_paddle_cp_function';
  $handler->display->display_options['fields']['field_paddle_cp_function']['relationship'] = 'nid';
  /* Field: Content: Manager */
  $handler->display->display_options['fields']['field_paddle_cp_manager']['id'] = 'field_paddle_cp_manager';
  $handler->display->display_options['fields']['field_paddle_cp_manager']['table'] = 'field_data_field_paddle_cp_manager';
  $handler->display->display_options['fields']['field_paddle_cp_manager']['field'] = 'field_paddle_cp_manager';
  $handler->display->display_options['fields']['field_paddle_cp_manager']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_manager']['settings'] = array(
    'link' => 0,
  );
  /* Field: Content: Featured image */
  $handler->display->display_options['fields']['field_paddle_featured_image']['id'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['table'] = 'field_data_field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['field'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_featured_image']['click_sort_column'] = 'sid';
  $handler->display->display_options['fields']['field_paddle_featured_image']['type'] = 'paddle_scald_atom_path';
  $handler->display->display_options['fields']['field_paddle_featured_image']['settings'] = array(
    'file_view_mode' => 'default',
  );
  /* Field: Content: Organisational unit level 1 */
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_1']['id'] = 'field_paddle_cp_ou_level_1';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_1']['table'] = 'field_data_field_paddle_cp_ou_level_1';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_1']['field'] = 'field_paddle_cp_ou_level_1';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_1']['relationship'] = 'nid';
  /* Field: Content: Organisational unit level 2 */
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_2']['id'] = 'field_paddle_cp_ou_level_2';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_2']['table'] = 'field_data_field_paddle_cp_ou_level_2';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_2']['field'] = 'field_paddle_cp_ou_level_2';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_2']['relationship'] = 'nid';
  /* Field: Content: Organisational unit level 3 */
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_3']['id'] = 'field_paddle_cp_ou_level_3';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_3']['table'] = 'field_data_field_paddle_cp_ou_level_3';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_3']['field'] = 'field_paddle_cp_ou_level_3';
  $handler->display->display_options['fields']['field_paddle_cp_ou_level_3']['relationship'] = 'nid';
  /* Field: Content: Address - Full name */
  $handler->display->display_options['fields']['field_paddle_cp_address_name_line']['id'] = 'field_paddle_cp_address_name_line';
  $handler->display->display_options['fields']['field_paddle_cp_address_name_line']['table'] = 'field_data_field_paddle_cp_address';
  $handler->display->display_options['fields']['field_paddle_cp_address_name_line']['field'] = 'field_paddle_cp_address_name_line';
  $handler->display->display_options['fields']['field_paddle_cp_address_name_line']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_address_name_line']['label'] = 'Full name';
  /* Field: Content: Address - Thoroughfare (i.e. Street address) */
  $handler->display->display_options['fields']['field_paddle_cp_address_thoroughfare']['id'] = 'field_paddle_cp_address_thoroughfare';
  $handler->display->display_options['fields']['field_paddle_cp_address_thoroughfare']['table'] = 'field_data_field_paddle_cp_address';
  $handler->display->display_options['fields']['field_paddle_cp_address_thoroughfare']['field'] = 'field_paddle_cp_address_thoroughfare';
  $handler->display->display_options['fields']['field_paddle_cp_address_thoroughfare']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_address_thoroughfare']['label'] = 'Address 1';
  /* Field: Content: Address - Premise (i.e. Apartment / Suite number) */
  $handler->display->display_options['fields']['field_paddle_cp_address_premise']['id'] = 'field_paddle_cp_address_premise';
  $handler->display->display_options['fields']['field_paddle_cp_address_premise']['table'] = 'field_data_field_paddle_cp_address';
  $handler->display->display_options['fields']['field_paddle_cp_address_premise']['field'] = 'field_paddle_cp_address_premise';
  $handler->display->display_options['fields']['field_paddle_cp_address_premise']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_address_premise']['label'] = 'Address 2';
  /* Field: Content: Address - Postal code */
  $handler->display->display_options['fields']['field_paddle_cp_address_postal_code']['id'] = 'field_paddle_cp_address_postal_code';
  $handler->display->display_options['fields']['field_paddle_cp_address_postal_code']['table'] = 'field_data_field_paddle_cp_address';
  $handler->display->display_options['fields']['field_paddle_cp_address_postal_code']['field'] = 'field_paddle_cp_address_postal_code';
  $handler->display->display_options['fields']['field_paddle_cp_address_postal_code']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_address_postal_code']['label'] = 'Postal code';
  /* Field: Content: Address - Locality (i.e. City) */
  $handler->display->display_options['fields']['field_paddle_cp_address_locality']['id'] = 'field_paddle_cp_address_locality';
  $handler->display->display_options['fields']['field_paddle_cp_address_locality']['table'] = 'field_data_field_paddle_cp_address';
  $handler->display->display_options['fields']['field_paddle_cp_address_locality']['field'] = 'field_paddle_cp_address_locality';
  $handler->display->display_options['fields']['field_paddle_cp_address_locality']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_address_locality']['label'] = 'City';
  /* Field: Content: Mobile office */
  $handler->display->display_options['fields']['field_paddle_cp_mobile_office']['id'] = 'field_paddle_cp_mobile_office';
  $handler->display->display_options['fields']['field_paddle_cp_mobile_office']['table'] = 'field_data_field_paddle_cp_mobile_office';
  $handler->display->display_options['fields']['field_paddle_cp_mobile_office']['field'] = 'field_paddle_cp_mobile_office';
  $handler->display->display_options['fields']['field_paddle_cp_mobile_office']['relationship'] = 'nid';
  /* Field: Content: Phone office */
  $handler->display->display_options['fields']['field_paddle_cp_phone_office']['id'] = 'field_paddle_cp_phone_office';
  $handler->display->display_options['fields']['field_paddle_cp_phone_office']['table'] = 'field_data_field_paddle_cp_phone_office';
  $handler->display->display_options['fields']['field_paddle_cp_phone_office']['field'] = 'field_paddle_cp_phone_office';
  $handler->display->display_options['fields']['field_paddle_cp_phone_office']['relationship'] = 'nid';
  /* Field: Content: Fax */
  $handler->display->display_options['fields']['field_paddle_cp_fax']['id'] = 'field_paddle_cp_fax';
  $handler->display->display_options['fields']['field_paddle_cp_fax']['table'] = 'field_data_field_paddle_cp_fax';
  $handler->display->display_options['fields']['field_paddle_cp_fax']['field'] = 'field_paddle_cp_fax';
  $handler->display->display_options['fields']['field_paddle_cp_fax']['relationship'] = 'nid';
  /* Field: Content: E-mail */
  $handler->display->display_options['fields']['field_paddle_cp_email']['id'] = 'field_paddle_cp_email';
  $handler->display->display_options['fields']['field_paddle_cp_email']['table'] = 'field_data_field_paddle_cp_email';
  $handler->display->display_options['fields']['field_paddle_cp_email']['field'] = 'field_paddle_cp_email';
  $handler->display->display_options['fields']['field_paddle_cp_email']['relationship'] = 'nid';
  /* Field: Content: Website */
  $handler->display->display_options['fields']['field_paddle_cp_website']['id'] = 'field_paddle_cp_website';
  $handler->display->display_options['fields']['field_paddle_cp_website']['table'] = 'field_data_field_paddle_cp_website';
  $handler->display->display_options['fields']['field_paddle_cp_website']['field'] = 'field_paddle_cp_website';
  $handler->display->display_options['fields']['field_paddle_cp_website']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_website']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Content: Linkedin */
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['id'] = 'field_paddle_cp_linkedin';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['table'] = 'field_data_field_paddle_cp_linkedin';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['field'] = 'field_paddle_cp_linkedin';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Content: Twitter */
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['id'] = 'field_paddle_cp_twitter';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['table'] = 'field_data_field_paddle_cp_twitter';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['field'] = 'field_paddle_cp_twitter';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Content: Yammer */
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['id'] = 'field_paddle_cp_yammer';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['table'] = 'field_data_field_paddle_cp_yammer';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['field'] = 'field_paddle_cp_yammer';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Content: Skype */
  $handler->display->display_options['fields']['field_paddle_cp_skype']['id'] = 'field_paddle_cp_skype';
  $handler->display->display_options['fields']['field_paddle_cp_skype']['table'] = 'field_data_field_paddle_cp_skype';
  $handler->display->display_options['fields']['field_paddle_cp_skype']['field'] = 'field_paddle_cp_skype';
  $handler->display->display_options['fields']['field_paddle_cp_skype']['relationship'] = 'nid';
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['relationship'] = 'nid';
  $handler->display->display_options['fields']['body']['alter']['strip_tags'] = TRUE;
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  $handler->display->display_options['path'] = 'admin/paddlet_store/app/paddle_contact_person/export/export/xls';
  $handler->display->display_options['displays'] = array(
    'contact_person_overview' => 'contact_person_overview',
    'default' => 0,
  );
  $handler->display->display_options['use_batch'] = 'batch';
  $handler->display->display_options['return_path'] = 'admin/paddlet_store/app/paddle_contact_person/configure';
  $handler->display->display_options['segment_size'] = '100';
  $translatables['contact_person_overview'] = array(
    t('Master'),
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
    t('Node'),
    t('Title'),
    t('Function'),
    t('Organisation'),
    t('State'),
    t('Contact person overview'),
    t('Paddle Contact Person Overview CSV Export'),
    t('First name'),
    t('Last name'),
    t('Manager'),
    t('Featured image'),
    t('Organisational unit level 1'),
    t('Organisational unit level 2'),
    t('Organisational unit level 3'),
    t('Full name'),
    t('Address 1'),
    t('Address 2'),
    t('Postal code'),
    t('City'),
    t('Mobile office'),
    t('Phone office'),
    t('Fax'),
    t('E-mail'),
    t('Website'),
    t('Linkedin'),
    t('Twitter'),
    t('Yammer'),
    t('Skype'),
    t('Inhoud'),
    t('Paddle Contact Person Overview XLS Export'),
  );
  $export['contact_person_overview'] = $view;

  $view = new view();
  $view->name = 'organisations_view';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'field_collection_item';
  $view->human_name = 'Organisations view';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['use_more_text'] = 'meer';
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['exposed_form']['options']['submit_button'] = 'Zoeken';
  $handler->display->display_options['exposed_form']['options']['reset_button_label'] = 'Herstellen';
  $handler->display->display_options['exposed_form']['options']['exposed_sorts_label'] = 'Sorteren op';
  $handler->display->display_options['exposed_form']['options']['sort_asc_label'] = 'Oplopend';
  $handler->display->display_options['exposed_form']['options']['sort_desc_label'] = 'Aflopend';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_label'] = 'Items per pagina';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options_all_label'] = '- Alle -';
  $handler->display->display_options['pager']['options']['expose']['offset_label'] = 'Startpunt';
  $handler->display->display_options['pager']['options']['tags']['first'] = '<< eerste';
  $handler->display->display_options['pager']['options']['tags']['previous'] = '< vorige';
  $handler->display->display_options['pager']['options']['tags']['next'] = 'volgende >';
  $handler->display->display_options['pager']['options']['tags']['last'] = 'laatste >>';
  $handler->display->display_options['style_plugin'] = 'list';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Veld: Field collection item: Address - Thoroughfare (i.e. Street address) */
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['id'] = 'field_cp_ou_address_thoroughfare';
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['table'] = 'field_data_field_cp_ou_address';
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['field'] = 'field_cp_ou_address_thoroughfare';
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['label'] = '';
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['element_label_colon'] = FALSE;
  /* Veld: Field collection item: Address - Postcode */
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['id'] = 'field_cp_ou_address_postal_code';
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['table'] = 'field_data_field_cp_ou_address';
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['field'] = 'field_cp_ou_address_postal_code';
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['label'] = '';
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['element_label_colon'] = FALSE;
  /* Veld: Field collection item: Address - Plaats (bijv stad) */
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['id'] = 'field_cp_ou_address_locality';
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['table'] = 'field_data_field_cp_ou_address';
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['field'] = 'field_cp_ou_address_locality';
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['label'] = '';
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['alter']['alter_text'] = TRUE;
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['alter']['text'] = '[field_cp_ou_address_postal_code] [field_cp_ou_address_locality]';
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['element_label_colon'] = FALSE;
  /* Veld: Field collection item: Phone */
  $handler->display->display_options['fields']['field_cp_phone']['id'] = 'field_cp_phone';
  $handler->display->display_options['fields']['field_cp_phone']['table'] = 'field_data_field_cp_phone';
  $handler->display->display_options['fields']['field_cp_phone']['field'] = 'field_cp_phone';
  $handler->display->display_options['fields']['field_cp_phone']['label'] = '';
  $handler->display->display_options['fields']['field_cp_phone']['element_label_colon'] = FALSE;
  /* Veld: Field collection item: Url */
  $handler->display->display_options['fields']['field_cp_url']['id'] = 'field_cp_url';
  $handler->display->display_options['fields']['field_cp_url']['table'] = 'field_data_field_cp_url';
  $handler->display->display_options['fields']['field_cp_url']['field'] = 'field_cp_url';
  $handler->display->display_options['fields']['field_cp_url']['label'] = '';
  $handler->display->display_options['fields']['field_cp_url']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_cp_url']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 1,
  );
  /* Veld: Veld: Geofield */
  $handler->display->display_options['fields']['field_paddle_address_geocode']['id'] = 'field_paddle_address_geocode';
  $handler->display->display_options['fields']['field_paddle_address_geocode']['table'] = 'field_data_field_paddle_address_geocode';
  $handler->display->display_options['fields']['field_paddle_address_geocode']['field'] = 'field_paddle_address_geocode';
  $handler->display->display_options['fields']['field_paddle_address_geocode']['label'] = 'Geofield latitude';
  $handler->display->display_options['fields']['field_paddle_address_geocode']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_paddle_address_geocode']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_address_geocode']['click_sort_column'] = 'geom';
  $handler->display->display_options['fields']['field_paddle_address_geocode']['type'] = 'geofield_lat';
  $handler->display->display_options['fields']['field_paddle_address_geocode']['settings'] = array(
    'data' => 'full',
    'format' => 'decimal_degrees',
  );
  /* Veld: Veld: Geofield */
  $handler->display->display_options['fields']['field_paddle_address_geocode_1']['id'] = 'field_paddle_address_geocode_1';
  $handler->display->display_options['fields']['field_paddle_address_geocode_1']['table'] = 'field_data_field_paddle_address_geocode';
  $handler->display->display_options['fields']['field_paddle_address_geocode_1']['field'] = 'field_paddle_address_geocode';
  $handler->display->display_options['fields']['field_paddle_address_geocode_1']['label'] = 'Geofield longitude';
  $handler->display->display_options['fields']['field_paddle_address_geocode_1']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_paddle_address_geocode_1']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_address_geocode_1']['click_sort_column'] = 'geom';
  $handler->display->display_options['fields']['field_paddle_address_geocode_1']['type'] = 'geofield_lon';
  $handler->display->display_options['fields']['field_paddle_address_geocode_1']['settings'] = array(
    'data' => 'full',
    'format' => 'decimal_degrees',
  );
  /* Contextueel filter: Field collection item: Field collection item-ID */
  $handler->display->display_options['arguments']['item_id']['id'] = 'item_id';
  $handler->display->display_options['arguments']['item_id']['table'] = 'field_collection_item';
  $handler->display->display_options['arguments']['item_id']['field'] = 'item_id';
  $handler->display->display_options['arguments']['item_id']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['item_id']['exception']['title'] = 'Alle';
  $handler->display->display_options['arguments']['item_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['item_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['item_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['item_id']['summary_options']['items_per_page'] = '25';
  /* Filtercriterium: Field collection item: Veldnaam */
  $handler->display->display_options['filters']['field_name']['id'] = 'field_name';
  $handler->display->display_options['filters']['field_name']['table'] = 'field_collection_item';
  $handler->display->display_options['filters']['field_name']['field'] = 'field_name';
  $handler->display->display_options['filters']['field_name']['value'] = array(
    'field_paddle_cp_company_info' => 'field_paddle_cp_company_info',
  );
  $translatables['organisations_view'] = array(
    t('Master'),
    t('meer'),
    t('Zoeken'),
    t('Herstellen'),
    t('Sorteren op'),
    t('Oplopend'),
    t('Aflopend'),
    t('Items per pagina'),
    t('- Alle -'),
    t('Startpunt'),
    t('<< eerste'),
    t('< vorige'),
    t('volgende >'),
    t('laatste >>'),
    t('[field_cp_ou_address_postal_code] [field_cp_ou_address_locality]'),
    t('Geofield latitude'),
    t('Geofield longitude'),
    t('Alle'),
  );
  $export['organisations_view'] = $view;

  $view = new view();
  $view->name = 'ou_contact_person_overview';
  $view->description = 'contact Person overview export when OU is enabled';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'OU contact Person overview';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'manage paddle_apps';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '20';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['row_class'] = 'node-[nid]';
  $handler->display->display_options['style_options']['columns'] = array(
    'title' => 'title',
    'field_cp_function' => 'field_cp_function',
    'field_cp_organisation' => 'field_cp_organisation',
    'state' => 'state',
    'nid' => 'nid',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'title' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_cp_function' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_cp_organisation' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'state' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'nid' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* Relationship: Content: Organizations (field_paddle_cp_company_info) */
  $handler->display->display_options['relationships']['field_paddle_cp_company_info_value']['id'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['relationships']['field_paddle_cp_company_info_value']['table'] = 'field_data_field_paddle_cp_company_info';
  $handler->display->display_options['relationships']['field_paddle_cp_company_info_value']['field'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['relationships']['field_paddle_cp_company_info_value']['delta'] = '-1';
  /* Relationship: Workbench Moderation: Node */
  $handler->display->display_options['relationships']['nid']['id'] = 'nid';
  $handler->display->display_options['relationships']['nid']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['relationships']['nid']['field'] = 'nid';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['relationship'] = 'nid';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Field collection item: Function */
  $handler->display->display_options['fields']['field_cp_function']['id'] = 'field_cp_function';
  $handler->display->display_options['fields']['field_cp_function']['table'] = 'field_data_field_cp_function';
  $handler->display->display_options['fields']['field_cp_function']['field'] = 'field_cp_function';
  $handler->display->display_options['fields']['field_cp_function']['relationship'] = 'field_paddle_cp_company_info_value';
  /* Field: Field collection item: Organization */
  $handler->display->display_options['fields']['field_cp_organisation']['id'] = 'field_cp_organisation';
  $handler->display->display_options['fields']['field_cp_organisation']['table'] = 'field_data_field_cp_organisation';
  $handler->display->display_options['fields']['field_cp_organisation']['field'] = 'field_cp_organisation';
  $handler->display->display_options['fields']['field_cp_organisation']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_organisation']['settings'] = array(
    'link' => 0,
  );
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  /* Sort criterion: Content: Post date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'node';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'contact_person' => 'contact_person',
  );
  /* Filter criterion: Workbench Moderation: Current */
  $handler->display->display_options['filters']['is_current']['id'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['is_current']['field'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['value'] = '1';

  /* Display: OU contact Person overview */
  $handler = $view->new_display('page', 'OU contact Person overview', 'ou_contact_person_overview');
  $handler->display->display_options['display_description'] = 'OU contact Person overview';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['relationship'] = 'nid';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Field collection item: Function */
  $handler->display->display_options['fields']['field_cp_function']['id'] = 'field_cp_function';
  $handler->display->display_options['fields']['field_cp_function']['table'] = 'field_data_field_cp_function';
  $handler->display->display_options['fields']['field_cp_function']['field'] = 'field_cp_function';
  $handler->display->display_options['fields']['field_cp_function']['relationship'] = 'field_paddle_cp_company_info_value';
  /* Field: Field collection item: Organization */
  $handler->display->display_options['fields']['field_cp_organisation']['id'] = 'field_cp_organisation';
  $handler->display->display_options['fields']['field_cp_organisation']['table'] = 'field_data_field_cp_organisation';
  $handler->display->display_options['fields']['field_cp_organisation']['field'] = 'field_cp_organisation';
  $handler->display->display_options['fields']['field_cp_organisation']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_organisation']['settings'] = array(
    'link' => 0,
  );
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['exclude'] = TRUE;
  $handler->display->display_options['path'] = 'admin/paddlet_store/app/paddle_contact_person/cp-ou-export';

  /* Display: OU contact Person overview csv export */
  $handler = $view->new_display('views_data_export', 'OU contact Person overview csv export', 'ou_contact_person_overview_csv_export');
  $handler->display->display_options['display_description'] = 'OU contact Person overview csv export';
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'views_data_export_csv';
  $handler->display->display_options['style_options']['provide_file'] = 1;
  $handler->display->display_options['style_options']['filename'] = 'contact_person_overview.csv';
  $handler->display->display_options['style_options']['parent_sort'] = 0;
  $handler->display->display_options['style_options']['quote'] = 1;
  $handler->display->display_options['style_options']['trim'] = 0;
  $handler->display->display_options['style_options']['replace_newlines'] = 0;
  $handler->display->display_options['style_options']['newline_token'] = '1';
  $handler->display->display_options['style_options']['header'] = 1;
  $handler->display->display_options['style_options']['keep_html'] = 0;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: First name */
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['id'] = 'field_paddle_cp_first_name';
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['table'] = 'field_data_field_paddle_cp_first_name';
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['field'] = 'field_paddle_cp_first_name';
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['relationship'] = 'nid';
  /* Field: Content: Last name */
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['id'] = 'field_paddle_cp_last_name';
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['table'] = 'field_data_field_paddle_cp_last_name';
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['field'] = 'field_paddle_cp_last_name';
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['relationship'] = 'nid';
  /* Field: Content: Featured image */
  $handler->display->display_options['fields']['field_paddle_featured_image']['id'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['table'] = 'field_data_field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['field'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['click_sort_column'] = 'sid';
  $handler->display->display_options['fields']['field_paddle_featured_image']['type'] = 'paddle_scald_atom_path';
  /* Field: Field collection item: Function */
  $handler->display->display_options['fields']['field_cp_function']['id'] = 'field_cp_function';
  $handler->display->display_options['fields']['field_cp_function']['table'] = 'field_data_field_cp_function';
  $handler->display->display_options['fields']['field_cp_function']['field'] = 'field_cp_function';
  $handler->display->display_options['fields']['field_cp_function']['relationship'] = 'field_paddle_cp_company_info_value';
  /* Field: Field collection item: Organization */
  $handler->display->display_options['fields']['field_cp_organisation']['id'] = 'field_cp_organisation';
  $handler->display->display_options['fields']['field_cp_organisation']['table'] = 'field_data_field_cp_organisation';
  $handler->display->display_options['fields']['field_cp_organisation']['field'] = 'field_cp_organisation';
  $handler->display->display_options['fields']['field_cp_organisation']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_organisation']['settings'] = array(
    'link' => 0,
  );
  /* Field: Field collection item: Manager */
  $handler->display->display_options['fields']['field_cp_manager']['id'] = 'field_cp_manager';
  $handler->display->display_options['fields']['field_cp_manager']['table'] = 'field_data_field_cp_manager';
  $handler->display->display_options['fields']['field_cp_manager']['field'] = 'field_cp_manager';
  $handler->display->display_options['fields']['field_cp_manager']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_manager']['settings'] = array(
    'link' => 0,
  );
  /* Field: Field collection item: Address - Full name */
  $handler->display->display_options['fields']['field_cp_ou_address_name_line']['id'] = 'field_cp_ou_address_name_line';
  $handler->display->display_options['fields']['field_cp_ou_address_name_line']['table'] = 'field_data_field_cp_ou_address';
  $handler->display->display_options['fields']['field_cp_ou_address_name_line']['field'] = 'field_cp_ou_address_name_line';
  $handler->display->display_options['fields']['field_cp_ou_address_name_line']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_ou_address_name_line']['label'] = ' Full name';
  /* Field: Field collection item: Address - Thoroughfare (i.e. Street address) */
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['id'] = 'field_cp_ou_address_thoroughfare';
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['table'] = 'field_data_field_cp_ou_address';
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['field'] = 'field_cp_ou_address_thoroughfare';
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['label'] = 'Address 1';
  /* Field: Field collection item: Address - Premise (i.e. Apartment / Suite number) */
  $handler->display->display_options['fields']['field_cp_ou_address_premise']['id'] = 'field_cp_ou_address_premise';
  $handler->display->display_options['fields']['field_cp_ou_address_premise']['table'] = 'field_data_field_cp_ou_address';
  $handler->display->display_options['fields']['field_cp_ou_address_premise']['field'] = 'field_cp_ou_address_premise';
  $handler->display->display_options['fields']['field_cp_ou_address_premise']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_ou_address_premise']['label'] = 'Address 2';
  /* Field: Field collection item: Address - Postal code */
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['id'] = 'field_cp_ou_address_postal_code';
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['table'] = 'field_data_field_cp_ou_address';
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['field'] = 'field_cp_ou_address_postal_code';
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['label'] = ' Postal code';
  /* Field: Field collection item: Address - Locality (i.e. City) */
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['id'] = 'field_cp_ou_address_locality';
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['table'] = 'field_data_field_cp_ou_address';
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['field'] = 'field_cp_ou_address_locality';
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['label'] = 'City';
  /* Field: Field collection item: Mobile */
  $handler->display->display_options['fields']['field_cp_mobile']['id'] = 'field_cp_mobile';
  $handler->display->display_options['fields']['field_cp_mobile']['table'] = 'field_data_field_cp_mobile';
  $handler->display->display_options['fields']['field_cp_mobile']['field'] = 'field_cp_mobile';
  $handler->display->display_options['fields']['field_cp_mobile']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_mobile']['label'] = 'Mobile office';
  /* Field: Field collection item: Phone */
  $handler->display->display_options['fields']['field_cp_phone']['id'] = 'field_cp_phone';
  $handler->display->display_options['fields']['field_cp_phone']['table'] = 'field_data_field_cp_phone';
  $handler->display->display_options['fields']['field_cp_phone']['field'] = 'field_cp_phone';
  $handler->display->display_options['fields']['field_cp_phone']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_phone']['label'] = 'Phone office';
  /* Field: Field collection item: E-mail */
  $handler->display->display_options['fields']['field_cp_email']['id'] = 'field_cp_email';
  $handler->display->display_options['fields']['field_cp_email']['table'] = 'field_data_field_cp_email';
  $handler->display->display_options['fields']['field_cp_email']['field'] = 'field_cp_email';
  $handler->display->display_options['fields']['field_cp_email']['relationship'] = 'field_paddle_cp_company_info_value';
  /* Field: Field collection item: Url */
  $handler->display->display_options['fields']['field_cp_url']['id'] = 'field_cp_url';
  $handler->display->display_options['fields']['field_cp_url']['table'] = 'field_data_field_cp_url';
  $handler->display->display_options['fields']['field_cp_url']['field'] = 'field_cp_url';
  $handler->display->display_options['fields']['field_cp_url']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_url']['label'] = 'Website';
  $handler->display->display_options['fields']['field_cp_url']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Content: Twitter */
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['id'] = 'field_paddle_cp_twitter';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['table'] = 'field_data_field_paddle_cp_twitter';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['field'] = 'field_paddle_cp_twitter';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Content: Skype */
  $handler->display->display_options['fields']['field_paddle_cp_skype']['id'] = 'field_paddle_cp_skype';
  $handler->display->display_options['fields']['field_paddle_cp_skype']['table'] = 'field_data_field_paddle_cp_skype';
  $handler->display->display_options['fields']['field_paddle_cp_skype']['field'] = 'field_paddle_cp_skype';
  $handler->display->display_options['fields']['field_paddle_cp_skype']['relationship'] = 'nid';
  /* Field: Content: Linkedin */
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['id'] = 'field_paddle_cp_linkedin';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['table'] = 'field_data_field_paddle_cp_linkedin';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['field'] = 'field_paddle_cp_linkedin';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Content: Yammer */
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['id'] = 'field_paddle_cp_yammer';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['table'] = 'field_data_field_paddle_cp_yammer';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['field'] = 'field_paddle_cp_yammer';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Field: Office */
  $handler->display->display_options['fields']['field_cp_office']['id'] = 'field_cp_office';
  $handler->display->display_options['fields']['field_cp_office']['table'] = 'field_data_field_cp_office';
  $handler->display->display_options['fields']['field_cp_office']['field'] = 'field_cp_office';
  $handler->display->display_options['fields']['field_cp_office']['relationship'] = 'field_paddle_cp_company_info_value';
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  $handler->display->display_options['path'] = 'admin/paddlet_store/app/paddle_contact_person/cp-ou-export/export/csv';
  $handler->display->display_options['displays'] = array(
    'ou_contact_person_overview' => 'ou_contact_person_overview',
    'default' => 0,
  );
  $handler->display->display_options['use_batch'] = 'batch';
  $handler->display->display_options['return_path'] = 'admin/paddlet_store/app/paddle_contact_person/configure';
  $handler->display->display_options['segment_size'] = '100';

  /* Display: OU Contact person overview xls export */
  $handler = $view->new_display('views_data_export', 'OU Contact person overview xls export', 'ou_contact_person_overview_xls_export');
  $handler->display->display_options['display_description'] = 'OU Contact person overview xls export';
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'views_data_export_xls';
  $handler->display->display_options['style_options']['provide_file'] = 1;
  $handler->display->display_options['style_options']['filename'] = 'contact_person_overview.xls';
  $handler->display->display_options['style_options']['parent_sort'] = 0;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: First name */
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['id'] = 'field_paddle_cp_first_name';
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['table'] = 'field_data_field_paddle_cp_first_name';
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['field'] = 'field_paddle_cp_first_name';
  $handler->display->display_options['fields']['field_paddle_cp_first_name']['relationship'] = 'nid';
  /* Field: Content: Last name */
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['id'] = 'field_paddle_cp_last_name';
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['table'] = 'field_data_field_paddle_cp_last_name';
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['field'] = 'field_paddle_cp_last_name';
  $handler->display->display_options['fields']['field_paddle_cp_last_name']['relationship'] = 'nid';
  /* Field: Content: Featured image */
  $handler->display->display_options['fields']['field_paddle_featured_image']['id'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['table'] = 'field_data_field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['field'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['click_sort_column'] = 'sid';
  $handler->display->display_options['fields']['field_paddle_featured_image']['type'] = 'paddle_scald_atom_path';
  /* Field: Field collection item: Function */
  $handler->display->display_options['fields']['field_cp_function']['id'] = 'field_cp_function';
  $handler->display->display_options['fields']['field_cp_function']['table'] = 'field_data_field_cp_function';
  $handler->display->display_options['fields']['field_cp_function']['field'] = 'field_cp_function';
  $handler->display->display_options['fields']['field_cp_function']['relationship'] = 'field_paddle_cp_company_info_value';
  /* Field: Field collection item: Organization */
  $handler->display->display_options['fields']['field_cp_organisation']['id'] = 'field_cp_organisation';
  $handler->display->display_options['fields']['field_cp_organisation']['table'] = 'field_data_field_cp_organisation';
  $handler->display->display_options['fields']['field_cp_organisation']['field'] = 'field_cp_organisation';
  $handler->display->display_options['fields']['field_cp_organisation']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_organisation']['settings'] = array(
    'link' => 0,
  );
  /* Field: Field collection item: Manager */
  $handler->display->display_options['fields']['field_cp_manager']['id'] = 'field_cp_manager';
  $handler->display->display_options['fields']['field_cp_manager']['table'] = 'field_data_field_cp_manager';
  $handler->display->display_options['fields']['field_cp_manager']['field'] = 'field_cp_manager';
  $handler->display->display_options['fields']['field_cp_manager']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_manager']['settings'] = array(
    'link' => 0,
  );
  /* Field: Field collection item: Address - Full name */
  $handler->display->display_options['fields']['field_cp_ou_address_name_line']['id'] = 'field_cp_ou_address_name_line';
  $handler->display->display_options['fields']['field_cp_ou_address_name_line']['table'] = 'field_data_field_cp_ou_address';
  $handler->display->display_options['fields']['field_cp_ou_address_name_line']['field'] = 'field_cp_ou_address_name_line';
  $handler->display->display_options['fields']['field_cp_ou_address_name_line']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_ou_address_name_line']['label'] = ' Full name';
  /* Field: Field collection item: Address - Thoroughfare (i.e. Street address) */
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['id'] = 'field_cp_ou_address_thoroughfare';
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['table'] = 'field_data_field_cp_ou_address';
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['field'] = 'field_cp_ou_address_thoroughfare';
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_ou_address_thoroughfare']['label'] = 'Address 1';
  /* Field: Field collection item: Address - Premise (i.e. Apartment / Suite number) */
  $handler->display->display_options['fields']['field_cp_ou_address_premise']['id'] = 'field_cp_ou_address_premise';
  $handler->display->display_options['fields']['field_cp_ou_address_premise']['table'] = 'field_data_field_cp_ou_address';
  $handler->display->display_options['fields']['field_cp_ou_address_premise']['field'] = 'field_cp_ou_address_premise';
  $handler->display->display_options['fields']['field_cp_ou_address_premise']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_ou_address_premise']['label'] = 'Address 2';
  /* Field: Field collection item: Address - Postal code */
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['id'] = 'field_cp_ou_address_postal_code';
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['table'] = 'field_data_field_cp_ou_address';
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['field'] = 'field_cp_ou_address_postal_code';
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_ou_address_postal_code']['label'] = ' Postal code';
  /* Field: Field collection item: Address - Locality (i.e. City) */
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['id'] = 'field_cp_ou_address_locality';
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['table'] = 'field_data_field_cp_ou_address';
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['field'] = 'field_cp_ou_address_locality';
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_ou_address_locality']['label'] = 'City';
  /* Field: Field collection item: Mobile */
  $handler->display->display_options['fields']['field_cp_mobile']['id'] = 'field_cp_mobile';
  $handler->display->display_options['fields']['field_cp_mobile']['table'] = 'field_data_field_cp_mobile';
  $handler->display->display_options['fields']['field_cp_mobile']['field'] = 'field_cp_mobile';
  $handler->display->display_options['fields']['field_cp_mobile']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_mobile']['label'] = 'Mobile office';
  /* Field: Field collection item: Phone */
  $handler->display->display_options['fields']['field_cp_phone']['id'] = 'field_cp_phone';
  $handler->display->display_options['fields']['field_cp_phone']['table'] = 'field_data_field_cp_phone';
  $handler->display->display_options['fields']['field_cp_phone']['field'] = 'field_cp_phone';
  $handler->display->display_options['fields']['field_cp_phone']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_phone']['label'] = 'Phone office';
  /* Field: Field collection item: E-mail */
  $handler->display->display_options['fields']['field_cp_email']['id'] = 'field_cp_email';
  $handler->display->display_options['fields']['field_cp_email']['table'] = 'field_data_field_cp_email';
  $handler->display->display_options['fields']['field_cp_email']['field'] = 'field_cp_email';
  $handler->display->display_options['fields']['field_cp_email']['relationship'] = 'field_paddle_cp_company_info_value';
  /* Field: Field collection item: Url */
  $handler->display->display_options['fields']['field_cp_url']['id'] = 'field_cp_url';
  $handler->display->display_options['fields']['field_cp_url']['table'] = 'field_data_field_cp_url';
  $handler->display->display_options['fields']['field_cp_url']['field'] = 'field_cp_url';
  $handler->display->display_options['fields']['field_cp_url']['relationship'] = 'field_paddle_cp_company_info_value';
  $handler->display->display_options['fields']['field_cp_url']['label'] = 'Website';
  $handler->display->display_options['fields']['field_cp_url']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Content: Twitter */
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['id'] = 'field_paddle_cp_twitter';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['table'] = 'field_data_field_paddle_cp_twitter';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['field'] = 'field_paddle_cp_twitter';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_twitter']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Content: Skype */
  $handler->display->display_options['fields']['field_paddle_cp_skype']['id'] = 'field_paddle_cp_skype';
  $handler->display->display_options['fields']['field_paddle_cp_skype']['table'] = 'field_data_field_paddle_cp_skype';
  $handler->display->display_options['fields']['field_paddle_cp_skype']['field'] = 'field_paddle_cp_skype';
  $handler->display->display_options['fields']['field_paddle_cp_skype']['relationship'] = 'nid';
  /* Field: Content: Linkedin */
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['id'] = 'field_paddle_cp_linkedin';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['table'] = 'field_data_field_paddle_cp_linkedin';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['field'] = 'field_paddle_cp_linkedin';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_linkedin']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Content: Yammer */
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['id'] = 'field_paddle_cp_yammer';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['table'] = 'field_data_field_paddle_cp_yammer';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['field'] = 'field_paddle_cp_yammer';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_cp_yammer']['settings'] = array(
    'trim_length' => '80',
    'nofollow' => 0,
  );
  /* Field: Field: Office */
  $handler->display->display_options['fields']['field_cp_office']['id'] = 'field_cp_office';
  $handler->display->display_options['fields']['field_cp_office']['table'] = 'field_data_field_cp_office';
  $handler->display->display_options['fields']['field_cp_office']['field'] = 'field_cp_office';
  $handler->display->display_options['fields']['field_cp_office']['relationship'] = 'field_paddle_cp_company_info_value';
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  $handler->display->display_options['path'] = 'admin/paddlet_store/app/paddle_contact_person/cp-ou-export/export/xls';
  $handler->display->display_options['displays'] = array(
    'ou_contact_person_overview' => 'ou_contact_person_overview',
    'default' => 0,
  );
  $handler->display->display_options['use_batch'] = 'batch';
  $handler->display->display_options['return_path'] = 'admin/paddlet_store/app/paddle_contact_person/configure';
  $handler->display->display_options['segment_size'] = '100';
  $translatables['ou_contact_person_overview'] = array(
    t('Master'),
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
    t('field collection item from field_paddle_cp_company_info'),
    t('Node'),
    t('Title'),
    t('Function'),
    t('Organization'),
    t('State'),
    t('OU contact Person overview'),
    t('Nid'),
    t('OU contact Person overview csv export'),
    t('First name'),
    t('Last name'),
    t('Featured image'),
    t('Manager'),
    t(' Full name'),
    t('Address 1'),
    t('Address 2'),
    t(' Postal code'),
    t('City'),
    t('Mobile office'),
    t('Phone office'),
    t('E-mail'),
    t('Website'),
    t('Twitter'),
    t('Skype'),
    t('Linkedin'),
    t('Yammer'),
    t('Office'),
    t('OU Contact person overview xls export'),
  );
  $export['ou_contact_person_overview'] = $view;

  return $export;
}
