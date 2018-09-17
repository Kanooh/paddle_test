<?php
/**
 * @file
 * paddle_holiday_participation.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_holiday_participation_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'holiday_participation_filter_pages';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Holiday participation filter pages';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'better_exposed_filters';
  $handler->display->display_options['exposed_form']['options']['submit_button'] = 'Search';
  $handler->display->display_options['exposed_form']['options']['bef'] = array(
    'general' => array(
      'input_required' => 0,
      'text_input_required' => array(
        'text_input_required' => array(
          'value' => 'Select any filter and click on Apply to see results',
          'format' => 'full_html',
        ),
      ),
      'allow_secondary' => 0,
      'secondary_label' => 'Advanced options',
      'secondary_collapse_override' => '0',
    ),
    'field_geofield_distance' => array(
      'bef_format' => 'default',
      'slider_options' => array(
        'bef_slider_min' => '0',
        'bef_slider_max' => '99999',
        'bef_slider_step' => '1',
        'bef_slider_animate' => '',
        'bef_slider_orientation' => 'horizontal',
      ),
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Search by city/postal code',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_formula_oh_value' => array(
      'bef_format' => 'bef',
      'more_options' => array(
        'bef_select_all_none' => 0,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'contract_year' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Which year',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'month' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Which month',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
  );
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '10';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['pager']['options']['tags']['first'] = '';
  $handler->display->display_options['pager']['options']['tags']['previous'] = '';
  $handler->display->display_options['pager']['options']['tags']['next'] = '';
  $handler->display->display_options['pager']['options']['tags']['last'] = '';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Field: Content: Featured image */
  $handler->display->display_options['fields']['field_paddle_featured_image']['id'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['table'] = 'field_data_field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['field'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_featured_image']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_featured_image']['click_sort_column'] = 'sid';
  $handler->display->display_options['fields']['field_paddle_featured_image']['type'] = 'paddle_holiday_participation_featured_image';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['label'] = '';
  $handler->display->display_options['fields']['body']['element_label_colon'] = FALSE;
  /* Sort criterion: Content: Title */
  $handler->display->display_options['sorts']['title']['id'] = 'title';
  $handler->display->display_options['sorts']['title']['table'] = 'node';
  $handler->display->display_options['sorts']['title']['field'] = 'title';
  $handler->display->display_options['filter_groups']['groups'] = array(
    1 => 'AND',
    2 => 'AND',
  );
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'offer' => 'offer',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Content: Category (field_hp_category) */
  $handler->display->display_options['filters']['field_hp_category_value']['id'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['table'] = 'field_data_field_hp_category';
  $handler->display->display_options['filters']['field_hp_category_value']['field'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['value'] = array(
    'organised holidays' => 'organised holidays',
  );
  $handler->display->display_options['filters']['field_hp_category_value']['group'] = 1;
  /* Filter criterion: Content: Province (field_hp_province) */
  $handler->display->display_options['filters']['field_hp_province_value']['id'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['table'] = 'field_data_field_hp_province';
  $handler->display->display_options['filters']['field_hp_province_value']['field'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['value'] = array(
    'antwerpen' => 'antwerpen',
    'limburg' => 'limburg',
    'west-vlaanderen' => 'west-vlaanderen',
    'oost-vlaanderen' => 'oost-vlaanderen',
    'vlaams-brabant' => 'vlaams-brabant',
    'brussel' => 'brussel',
    'waals-brabant' => 'waals-brabant',
    'luik' => 'luik',
    'namen' => 'namen',
    'henegouwen' => 'henegouwen',
    'luxemburg' => 'luxemburg',
  );
  $handler->display->display_options['filters']['field_hp_province_value']['group'] = 2;
  $handler->display->display_options['filters']['field_hp_province_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['operator_id'] = 'field_hp_province_value_op';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['operator'] = 'field_hp_province_value_op';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['identifier'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Content: Formula organised holidays (field_hp_formula_oh) */
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['id'] = 'field_hp_formula_oh_value';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['table'] = 'field_data_field_hp_formula_oh';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['field'] = 'field_hp_formula_oh_value';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['value'] = array(
    0 => '0',
    1 => '1',
  );
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['operator_id'] = 'field_hp_formula_oh_value_op';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['label'] = 'Formula';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['operator'] = 'field_hp_formula_oh_value_op';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['identifier'] = 'field_hp_formula_oh_value';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['reduce'] = TRUE;

  /* Display: Holiday accommodations */
  $handler = $view->new_display('page', 'Holiday accommodations', 'holiday_accommodations');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Offer';
  $handler->display->display_options['display_description'] = 'Holiday accommodations';
  $handler->display->display_options['defaults']['exposed_form'] = FALSE;
  $handler->display->display_options['exposed_form']['type'] = 'better_exposed_filters';
  $handler->display->display_options['exposed_form']['options']['submit_button'] = 'Search';
  $handler->display->display_options['exposed_form']['options']['bef'] = array(
    'general' => array(
      'input_required' => 0,
      'text_input_required' => array(
        'text_input_required' => array(
          'value' => 'Select any filter and click on Apply to see results',
          'format' => 'full_html',
        ),
      ),
      'allow_secondary' => 0,
      'secondary_label' => 'Advanced options',
      'secondary_collapse_override' => '0',
    ),
    'field_geofield_distance' => array(
      'bef_format' => 'default',
      'slider_options' => array(
        'bef_slider_min' => '0',
        'bef_slider_max' => '99999',
        'bef_slider_step' => '1',
        'bef_slider_animate' => '',
        'bef_slider_orientation' => 'horizontal',
      ),
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Search by city/postal code',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_province_value' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => 'All provinces',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_facilities_value' => array(
      'bef_format' => 'bef',
      'more_options' => array(
        'bef_select_all_none' => 0,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => 'All',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_room_and_board_value' => array(
      'bef_format' => 'bef',
      'more_options' => array(
        'bef_select_all_none' => 0,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_labels_value' => array(
      'bef_format' => 'bef',
      'more_options' => array(
        'bef_select_all_none' => 0,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => 'All',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'contract_year' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Which year',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'month' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Which month',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
  );
  $handler->display->display_options['defaults']['footer'] = FALSE;
  /* Footer: Global: Result summary */
  $handler->display->display_options['footer']['result']['id'] = 'result';
  $handler->display->display_options['footer']['result']['table'] = 'views';
  $handler->display->display_options['footer']['result']['field'] = 'result';
  $handler->display->display_options['footer']['result']['content'] = '@total results';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Facilities */
  $handler->display->display_options['fields']['field_hp_facilities']['id'] = 'field_hp_facilities';
  $handler->display->display_options['fields']['field_hp_facilities']['table'] = 'field_data_field_hp_facilities';
  $handler->display->display_options['fields']['field_hp_facilities']['field'] = 'field_hp_facilities';
  $handler->display->display_options['fields']['field_hp_facilities']['label'] = '';
  $handler->display->display_options['fields']['field_hp_facilities']['alter']['alter_text'] = TRUE;
  $handler->display->display_options['fields']['field_hp_facilities']['alter']['text'] = '<li class="[field_hp_facilities-value] hp_icon"> </li>';
  $handler->display->display_options['fields']['field_hp_facilities']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_hp_facilities']['hide_empty'] = TRUE;
  $handler->display->display_options['fields']['field_hp_facilities']['empty_zero'] = TRUE;
  $handler->display->display_options['fields']['field_hp_facilities']['delta_offset'] = '0';
  $handler->display->display_options['fields']['field_hp_facilities']['multi_type'] = 'ul';
  $handler->display->display_options['fields']['field_hp_facilities']['separator'] = '';
  /* Field: Content: Featured image */
  $handler->display->display_options['fields']['field_paddle_featured_image']['id'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['table'] = 'field_data_field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['field'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_featured_image']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_featured_image']['click_sort_column'] = 'sid';
  $handler->display->display_options['fields']['field_paddle_featured_image']['type'] = 'paddle_holiday_participation_featured_image';
  /* Field: Content: Province */
  $handler->display->display_options['fields']['field_hp_province']['id'] = 'field_hp_province';
  $handler->display->display_options['fields']['field_hp_province']['table'] = 'field_data_field_hp_province';
  $handler->display->display_options['fields']['field_hp_province']['field'] = 'field_hp_province';
  $handler->display->display_options['fields']['field_hp_province']['label'] = '';
  $handler->display->display_options['fields']['field_hp_province']['element_label_colon'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['label'] = '';
  $handler->display->display_options['fields']['body']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['body']['type'] = 'paddle_landing_page_summary_or_trimmed_plain_text';
  /* Field: Content: Contract start year */
  $handler->display->display_options['fields']['field_hp_contract_start_year']['id'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['table'] = 'field_data_field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['field'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_start_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Contract end year */
  $handler->display->display_options['fields']['field_hp_contract_end_year']['id'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['table'] = 'field_data_field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['field'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_end_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['filter_groups']['groups'] = array(
    1 => 'AND',
    2 => 'AND',
  );
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'offer' => 'offer',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Content: Category (field_hp_category) */
  $handler->display->display_options['filters']['field_hp_category_value']['id'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['table'] = 'field_data_field_hp_category';
  $handler->display->display_options['filters']['field_hp_category_value']['field'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['value'] = array(
    'holiday accommodations' => 'holiday accommodations',
  );
  $handler->display->display_options['filters']['field_hp_category_value']['group'] = 1;
  /* Filter criterion: Holiday participation: Proximity */
  $handler->display->display_options['filters']['field_geofield_distance']['id'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['table'] = 'field_data_field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['field'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['operator'] = '<';
  $handler->display->display_options['filters']['field_geofield_distance']['value'] = array(
    'distance' => '25',
    'distance2' => 200,
    'unit' => '6371',
    'origin' => '',
  );
  $handler->display->display_options['filters']['field_geofield_distance']['group'] = 2;
  $handler->display->display_options['filters']['field_geofield_distance']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator_id'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['label'] = 'Region';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['description'] = 'Search by city or postal code';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['identifier'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_geofield_distance']['source'] = 'geocoder';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_entity_url_field'] = 'field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_current_user_field'] = 'field_paddle_geofield';
  /* Filter criterion: Content: Province (field_hp_province) */
  $handler->display->display_options['filters']['field_hp_province_value']['id'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['table'] = 'field_data_field_hp_province';
  $handler->display->display_options['filters']['field_hp_province_value']['field'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['value'] = array(
    'antwerpen' => 'antwerpen',
    'limburg' => 'limburg',
    'west-vlaanderen' => 'west-vlaanderen',
    'oost-vlaanderen' => 'oost-vlaanderen',
    'vlaams-brabant' => 'vlaams-brabant',
    'brussel' => 'brussel',
    'waals-brabant' => 'waals-brabant',
    'luik' => 'luik',
    'namen' => 'namen',
    'henegouwen' => 'henegouwen',
    'luxemburg' => 'luxemburg',
  );
  $handler->display->display_options['filters']['field_hp_province_value']['group'] = 2;
  $handler->display->display_options['filters']['field_hp_province_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['operator_id'] = 'field_hp_province_value_op';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['operator'] = 'field_hp_province_value_op';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['identifier'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Content: Facilities (field_hp_facilities) */
  $handler->display->display_options['filters']['field_hp_facilities_value']['id'] = 'field_hp_facilities_value';
  $handler->display->display_options['filters']['field_hp_facilities_value']['table'] = 'field_data_field_hp_facilities';
  $handler->display->display_options['filters']['field_hp_facilities_value']['field'] = 'field_hp_facilities_value';
  $handler->display->display_options['filters']['field_hp_facilities_value']['value'] = array(
    'hp_swimming' => 'hp_swimming',
    'hp_pets_allowed' => 'hp_pets_allowed',
  );
  $handler->display->display_options['filters']['field_hp_facilities_value']['group'] = 2;
  $handler->display->display_options['filters']['field_hp_facilities_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['operator_id'] = 'field_hp_facilities_value_op';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['label'] = 'Facilities';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['operator'] = 'field_hp_facilities_value_op';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['identifier'] = 'field_hp_facilities_value';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Content: Room and board (field_hp_room_and_board) */
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['id'] = 'field_hp_room_and_board_value';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['table'] = 'field_data_field_hp_room_and_board';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['field'] = 'field_hp_room_and_board_value';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['value'] = array(
    'hp_self_cooking' => 'hp_self_cooking',
    'hp_bed_and_breakfast' => 'hp_bed_and_breakfast',
    'hp_half_board' => 'hp_half_board',
    'hp_full_board' => 'hp_full_board',
  );
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['group'] = 2;
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['operator_id'] = 'field_hp_room_and_board_value_op';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['label'] = 'Formula';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['operator'] = 'field_hp_room_and_board_value_op';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['identifier'] = 'field_hp_room_and_board_value';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Content: Labels (field_hp_labels) */
  $handler->display->display_options['filters']['field_hp_labels_value']['id'] = 'field_hp_labels_value';
  $handler->display->display_options['filters']['field_hp_labels_value']['table'] = 'field_data_field_hp_labels';
  $handler->display->display_options['filters']['field_hp_labels_value']['field'] = 'field_hp_labels_value';
  $handler->display->display_options['filters']['field_hp_labels_value']['value'] = array(
    'hp_green_key_label' => 'hp_green_key_label',
    'hp_cycling_label' => 'hp_cycling_label',
    'hp_accessibility_label_a' => 'hp_accessibility_label_a',
    'hp_accessibility_label_a_plus' => 'hp_accessibility_label_a_plus',
  );
  $handler->display->display_options['filters']['field_hp_labels_value']['group'] = 2;
  $handler->display->display_options['filters']['field_hp_labels_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['operator_id'] = 'field_hp_labels_value_op';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['label'] = 'Labels';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['operator'] = 'field_hp_labels_value_op';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['identifier'] = 'field_hp_labels_value';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Holiday participation: Contract year */
  $handler->display->display_options['filters']['contract_year']['id'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['table'] = 'holiday_participation_contract_year';
  $handler->display->display_options['filters']['contract_year']['field'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['value'] = '2016';
  $handler->display->display_options['filters']['contract_year']['exposed'] = TRUE;
  $handler->display->display_options['filters']['contract_year']['expose']['operator_id'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['label'] = 'Which year?';
  $handler->display->display_options['filters']['contract_year']['expose']['operator'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['identifier'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['contract_year']['contract_start_year'] = 'field_hp_contract_start_year';
  $handler->display->display_options['filters']['contract_year']['contract_end_year'] = 'field_hp_contract_end_year';
  /* Filter criterion: Holiday participation: Month */
  $handler->display->display_options['filters']['month']['id'] = 'month';
  $handler->display->display_options['filters']['month']['table'] = 'field_data_field_hp_validity_period';
  $handler->display->display_options['filters']['month']['field'] = 'month';
  $handler->display->display_options['filters']['month']['value'] = array(
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10',
    11 => '11',
    12 => '12',
  );
  $handler->display->display_options['filters']['month']['exposed'] = TRUE;
  $handler->display->display_options['filters']['month']['expose']['operator_id'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['label'] = 'Month';
  $handler->display->display_options['filters']['month']['expose']['operator'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['identifier'] = 'month';
  $handler->display->display_options['filters']['month']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  /* Filter criterion: Content: Title */
  $handler->display->display_options['filters']['title']['id'] = 'title';
  $handler->display->display_options['filters']['title']['table'] = 'node';
  $handler->display->display_options['filters']['title']['field'] = 'title';
  $handler->display->display_options['filters']['title']['operator'] = 'contains';
  $handler->display->display_options['filters']['title']['exposed'] = TRUE;
  $handler->display->display_options['filters']['title']['expose']['operator_id'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['label'] = 'search by title';
  $handler->display->display_options['filters']['title']['expose']['operator'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['identifier'] = 'title';
  $handler->display->display_options['filters']['title']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['month']['expose']['reduce'] = TRUE;
  $handler->display->display_options['path'] = 'offer/holidayaccommodations';

  /* Display: Group accommodations */
  $handler = $view->new_display('page', 'Group accommodations', 'group_accommodations');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Offer';
  $handler->display->display_options['display_description'] = 'Group accommodations';
  $handler->display->display_options['defaults']['exposed_form'] = FALSE;
  $handler->display->display_options['exposed_form']['type'] = 'better_exposed_filters';
  $handler->display->display_options['exposed_form']['options']['submit_button'] = 'Search';
  $handler->display->display_options['exposed_form']['options']['bef'] = array(
    'general' => array(
      'input_required' => 0,
      'text_input_required' => array(
        'text_input_required' => array(
          'value' => 'Select any filter and click on Apply to see results',
          'format' => 'full_html',
        ),
      ),
      'allow_secondary' => 0,
      'secondary_label' => 'Advanced options',
      'secondary_collapse_override' => '0',
    ),
    'field_geofield_distance' => array(
      'bef_format' => 'default',
      'slider_options' => array(
        'bef_slider_min' => '0',
        'bef_slider_max' => '99999',
        'bef_slider_step' => '1',
        'bef_slider_animate' => '',
        'bef_slider_orientation' => 'horizontal',
      ),
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Search by city/postal code',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_province_value' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => 'All provinces',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'capacity_range' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_facilities_value' => array(
      'bef_format' => 'bef',
      'more_options' => array(
        'bef_select_all_none' => 0,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => 'All',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_room_and_board_value' => array(
      'bef_format' => 'bef',
      'more_options' => array(
        'bef_select_all_none' => 0,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_labels_value' => array(
      'bef_format' => 'bef',
      'more_options' => array(
        'bef_select_all_none' => 0,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => 'All',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'contract_year' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Which year',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'month' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Which month',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
  );
  $handler->display->display_options['defaults']['footer'] = FALSE;
  /* Footer: Global: Result summary */
  $handler->display->display_options['footer']['result']['id'] = 'result';
  $handler->display->display_options['footer']['result']['table'] = 'views';
  $handler->display->display_options['footer']['result']['field'] = 'result';
  $handler->display->display_options['footer']['result']['content'] = '@total results';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Facilities */
  $handler->display->display_options['fields']['field_hp_facilities']['id'] = 'field_hp_facilities';
  $handler->display->display_options['fields']['field_hp_facilities']['table'] = 'field_data_field_hp_facilities';
  $handler->display->display_options['fields']['field_hp_facilities']['field'] = 'field_hp_facilities';
  $handler->display->display_options['fields']['field_hp_facilities']['label'] = '';
  $handler->display->display_options['fields']['field_hp_facilities']['alter']['alter_text'] = TRUE;
  $handler->display->display_options['fields']['field_hp_facilities']['alter']['text'] = '<li class="[field_hp_facilities-value] hp_icon"> </li>';
  $handler->display->display_options['fields']['field_hp_facilities']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_hp_facilities']['empty'] = '<li class="[field_hp_facilities-value]"> </li>';
  $handler->display->display_options['fields']['field_hp_facilities']['hide_empty'] = TRUE;
  $handler->display->display_options['fields']['field_hp_facilities']['empty_zero'] = TRUE;
  $handler->display->display_options['fields']['field_hp_facilities']['delta_offset'] = '0';
  $handler->display->display_options['fields']['field_hp_facilities']['multi_type'] = 'ul';
  /* Field: Content: Featured image */
  $handler->display->display_options['fields']['field_paddle_featured_image']['id'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['table'] = 'field_data_field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['field'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_featured_image']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_featured_image']['click_sort_column'] = 'sid';
  $handler->display->display_options['fields']['field_paddle_featured_image']['type'] = 'paddle_holiday_participation_featured_image';
  /* Field: Content: Province */
  $handler->display->display_options['fields']['field_hp_province']['id'] = 'field_hp_province';
  $handler->display->display_options['fields']['field_hp_province']['table'] = 'field_data_field_hp_province';
  $handler->display->display_options['fields']['field_hp_province']['field'] = 'field_hp_province';
  $handler->display->display_options['fields']['field_hp_province']['label'] = '';
  $handler->display->display_options['fields']['field_hp_province']['element_label_colon'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['label'] = '';
  $handler->display->display_options['fields']['body']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['body']['type'] = 'paddle_landing_page_summary_or_trimmed_plain_text';
  /* Field: Content: Min capacity */
  $handler->display->display_options['fields']['field_hp_min_capacity']['id'] = 'field_hp_min_capacity';
  $handler->display->display_options['fields']['field_hp_min_capacity']['table'] = 'field_data_field_hp_min_capacity';
  $handler->display->display_options['fields']['field_hp_min_capacity']['field'] = 'field_hp_min_capacity';
  $handler->display->display_options['fields']['field_hp_min_capacity']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_min_capacity']['settings'] = array(
    'thousand_separator' => '',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Max capacity */
  $handler->display->display_options['fields']['field_hp_max_capacity']['id'] = 'field_hp_max_capacity';
  $handler->display->display_options['fields']['field_hp_max_capacity']['table'] = 'field_data_field_hp_max_capacity';
  $handler->display->display_options['fields']['field_hp_max_capacity']['field'] = 'field_hp_max_capacity';
  $handler->display->display_options['fields']['field_hp_max_capacity']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_max_capacity']['settings'] = array(
    'thousand_separator' => '',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Contract start year */
  $handler->display->display_options['fields']['field_hp_contract_start_year']['id'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['table'] = 'field_data_field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['field'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_start_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Contract end year */
  $handler->display->display_options['fields']['field_hp_contract_end_year']['id'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['table'] = 'field_data_field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['field'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_end_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['filter_groups']['groups'] = array(
    1 => 'AND',
    2 => 'AND',
  );
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'offer' => 'offer',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Content: Category (field_hp_category) */
  $handler->display->display_options['filters']['field_hp_category_value']['id'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['table'] = 'field_data_field_hp_category';
  $handler->display->display_options['filters']['field_hp_category_value']['field'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['value'] = array(
    'group accommodations' => 'group accommodations',
  );
  $handler->display->display_options['filters']['field_hp_category_value']['group'] = 1;
  /* Filter criterion: Holiday participation: Proximity */
  $handler->display->display_options['filters']['field_geofield_distance']['id'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['table'] = 'field_data_field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['field'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['operator'] = '<';
  $handler->display->display_options['filters']['field_geofield_distance']['value'] = array(
    'distance' => '25',
    'distance2' => 200,
    'unit' => '6371',
    'origin' => '',
  );
  $handler->display->display_options['filters']['field_geofield_distance']['group'] = 2;
  $handler->display->display_options['filters']['field_geofield_distance']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator_id'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['label'] = 'Region';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['description'] = 'Search by city or postal code';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['identifier'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_geofield_distance']['source'] = 'geocoder';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_entity_url_field'] = 'field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_current_user_field'] = 'field_paddle_geofield';
  /* Filter criterion: Content: Province (field_hp_province) */
  $handler->display->display_options['filters']['field_hp_province_value']['id'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['table'] = 'field_data_field_hp_province';
  $handler->display->display_options['filters']['field_hp_province_value']['field'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['value'] = array(
    'antwerpen' => 'antwerpen',
    'limburg' => 'limburg',
    'west-vlaanderen' => 'west-vlaanderen',
    'oost-vlaanderen' => 'oost-vlaanderen',
    'vlaams-brabant' => 'vlaams-brabant',
    'brussel' => 'brussel',
    'waals-brabant' => 'waals-brabant',
    'luik' => 'luik',
    'namen' => 'namen',
    'henegouwen' => 'henegouwen',
    'luxemburg' => 'luxemburg',
  );
  $handler->display->display_options['filters']['field_hp_province_value']['group'] = 2;
  $handler->display->display_options['filters']['field_hp_province_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['operator_id'] = 'field_hp_province_value_op';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['operator'] = 'field_hp_province_value_op';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['identifier'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Holiday participation: Capacity range */
  $handler->display->display_options['filters']['capacity_range']['id'] = 'capacity_range';
  $handler->display->display_options['filters']['capacity_range']['table'] = 'holiday_participation_capacity_range';
  $handler->display->display_options['filters']['capacity_range']['field'] = 'capacity_range';
  $handler->display->display_options['filters']['capacity_range']['group'] = 2;
  $handler->display->display_options['filters']['capacity_range']['exposed'] = TRUE;
  $handler->display->display_options['filters']['capacity_range']['expose']['operator_id'] = 'capacity_range_op';
  $handler->display->display_options['filters']['capacity_range']['expose']['operator'] = 'capacity_range_op';
  $handler->display->display_options['filters']['capacity_range']['expose']['identifier'] = 'capacity_range';
  $handler->display->display_options['filters']['capacity_range']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['capacity_range']['min_capacity'] = 'field_hp_min_capacity';
  $handler->display->display_options['filters']['capacity_range']['max_capacity'] = 'field_hp_max_capacity';
  $handler->display->display_options['filters']['capacity_range']['include_endpoints'] = FALSE;
  /* Filter criterion: Content: Facilities (field_hp_facilities) */
  $handler->display->display_options['filters']['field_hp_facilities_value']['id'] = 'field_hp_facilities_value';
  $handler->display->display_options['filters']['field_hp_facilities_value']['table'] = 'field_data_field_hp_facilities';
  $handler->display->display_options['filters']['field_hp_facilities_value']['field'] = 'field_hp_facilities_value';
  $handler->display->display_options['filters']['field_hp_facilities_value']['value'] = array(
    'hp_swimming' => 'hp_swimming',
    'hp_pets_allowed' => 'hp_pets_allowed',
  );
  $handler->display->display_options['filters']['field_hp_facilities_value']['group'] = 2;
  $handler->display->display_options['filters']['field_hp_facilities_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['operator_id'] = 'field_hp_facilities_value_op';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['label'] = 'Facilities';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['operator'] = 'field_hp_facilities_value_op';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['identifier'] = 'field_hp_facilities_value';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Content: Room and board (field_hp_room_and_board) */
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['id'] = 'field_hp_room_and_board_value';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['table'] = 'field_data_field_hp_room_and_board';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['field'] = 'field_hp_room_and_board_value';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['value'] = array(
    'hp_self_cooking' => 'hp_self_cooking',
    'hp_bed_and_breakfast' => 'hp_bed_and_breakfast',
    'hp_half_board' => 'hp_half_board',
    'hp_full_board' => 'hp_full_board',
  );
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['group'] = 2;
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['operator_id'] = 'field_hp_room_and_board_value_op';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['label'] = 'Formula';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['operator'] = 'field_hp_room_and_board_value_op';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['identifier'] = 'field_hp_room_and_board_value';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Content: Labels (field_hp_labels) */
  $handler->display->display_options['filters']['field_hp_labels_value']['id'] = 'field_hp_labels_value';
  $handler->display->display_options['filters']['field_hp_labels_value']['table'] = 'field_data_field_hp_labels';
  $handler->display->display_options['filters']['field_hp_labels_value']['field'] = 'field_hp_labels_value';
  $handler->display->display_options['filters']['field_hp_labels_value']['value'] = array(
    'hp_green_key_label' => 'hp_green_key_label',
    'hp_cycling_label' => 'hp_cycling_label',
    'hp_accessibility_label_a' => 'hp_accessibility_label_a',
    'hp_accessibility_label_a_plus' => 'hp_accessibility_label_a_plus',
  );
  $handler->display->display_options['filters']['field_hp_labels_value']['group'] = 2;
  $handler->display->display_options['filters']['field_hp_labels_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['operator_id'] = 'field_hp_labels_value_op';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['label'] = 'Labels';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['operator'] = 'field_hp_labels_value_op';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['identifier'] = 'field_hp_labels_value';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Holiday participation: Contract year */
  $handler->display->display_options['filters']['contract_year']['id'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['table'] = 'holiday_participation_contract_year';
  $handler->display->display_options['filters']['contract_year']['field'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['value'] = '2016';
  $handler->display->display_options['filters']['contract_year']['exposed'] = TRUE;
  $handler->display->display_options['filters']['contract_year']['expose']['operator_id'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['label'] = 'Which year?';
  $handler->display->display_options['filters']['contract_year']['expose']['operator'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['identifier'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['contract_year']['contract_start_year'] = 'field_hp_contract_start_year';
  $handler->display->display_options['filters']['contract_year']['contract_end_year'] = 'field_hp_contract_end_year';
  /* Filter criterion: Holiday participation: Month */
  $handler->display->display_options['filters']['month']['id'] = 'month';
  $handler->display->display_options['filters']['month']['table'] = 'field_data_field_hp_validity_period';
  $handler->display->display_options['filters']['month']['field'] = 'month';
  $handler->display->display_options['filters']['month']['value'] = array(
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10',
    11 => '11',
    12 => '12',
  );
  $handler->display->display_options['filters']['month']['exposed'] = TRUE;
  $handler->display->display_options['filters']['month']['expose']['operator_id'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['label'] = 'Month';
  $handler->display->display_options['filters']['month']['expose']['operator'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['identifier'] = 'month';
  $handler->display->display_options['filters']['month']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );

  /* Filter criterion: Content: Title */
  $handler->display->display_options['filters']['title']['id'] = 'title';
  $handler->display->display_options['filters']['title']['table'] = 'node';
  $handler->display->display_options['filters']['title']['field'] = 'title';
  $handler->display->display_options['filters']['title']['operator'] = 'contains';
  $handler->display->display_options['filters']['title']['exposed'] = TRUE;
  $handler->display->display_options['filters']['title']['expose']['operator_id'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['label'] = 'search by title';
  $handler->display->display_options['filters']['title']['expose']['operator'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['identifier'] = 'title';
  $handler->display->display_options['filters']['title']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['month']['expose']['reduce'] = TRUE;
  $handler->display->display_options['path'] = 'offer/groupaccommodations';

  /* Display: Day trips */
  $handler = $view->new_display('page', 'Day trips', 'day_trips');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Offer';
  $handler->display->display_options['display_description'] = 'Day trips';
  $handler->display->display_options['defaults']['footer'] = FALSE;
  /* Footer: Global: Result summary */
  $handler->display->display_options['footer']['result']['id'] = 'result';
  $handler->display->display_options['footer']['result']['table'] = 'views';
  $handler->display->display_options['footer']['result']['field'] = 'result';
  $handler->display->display_options['footer']['result']['content'] = '@total results';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Facilities */
  $handler->display->display_options['fields']['field_hp_facilities']['id'] = 'field_hp_facilities';
  $handler->display->display_options['fields']['field_hp_facilities']['table'] = 'field_data_field_hp_facilities';
  $handler->display->display_options['fields']['field_hp_facilities']['field'] = 'field_hp_facilities';
  $handler->display->display_options['fields']['field_hp_facilities']['label'] = '';
  $handler->display->display_options['fields']['field_hp_facilities']['alter']['alter_text'] = TRUE;
  $handler->display->display_options['fields']['field_hp_facilities']['alter']['text'] = '<li class="[field_hp_facilities-value] hp_icon"> </li>';
  $handler->display->display_options['fields']['field_hp_facilities']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_hp_facilities']['hide_empty'] = TRUE;
  $handler->display->display_options['fields']['field_hp_facilities']['empty_zero'] = TRUE;
  $handler->display->display_options['fields']['field_hp_facilities']['delta_offset'] = '0';
  $handler->display->display_options['fields']['field_hp_facilities']['multi_type'] = 'ul';
  /* Field: Content: Featured image */
  $handler->display->display_options['fields']['field_paddle_featured_image']['id'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['table'] = 'field_data_field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['field'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_featured_image']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_featured_image']['click_sort_column'] = 'sid';
  $handler->display->display_options['fields']['field_paddle_featured_image']['type'] = 'paddle_holiday_participation_featured_image';
  /* Field: Content: Province */
  $handler->display->display_options['fields']['field_hp_province']['id'] = 'field_hp_province';
  $handler->display->display_options['fields']['field_hp_province']['table'] = 'field_data_field_hp_province';
  $handler->display->display_options['fields']['field_hp_province']['field'] = 'field_hp_province';
  $handler->display->display_options['fields']['field_hp_province']['label'] = '';
  $handler->display->display_options['fields']['field_hp_province']['alter']['text'] = '<span class="province-name" >[field_hp_province] </span>
[field_hp_facilities]
<span class="hp-featured-image" >[field_paddle_featured_image]</span>';
  $handler->display->display_options['fields']['field_hp_province']['element_label_colon'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['label'] = '';
  $handler->display->display_options['fields']['body']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['body']['type'] = 'paddle_landing_page_summary_or_trimmed_plain_text';
  /* Field: Content: Contract end year */
  $handler->display->display_options['fields']['field_hp_contract_end_year']['id'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['table'] = 'field_data_field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['field'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_end_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Contract start year */
  $handler->display->display_options['fields']['field_hp_contract_start_year']['id'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['table'] = 'field_data_field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['field'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_start_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['filter_groups']['groups'] = array(
    1 => 'AND',
    2 => 'AND',
  );
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'offer' => 'offer',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Content: Category (field_hp_category) */
  $handler->display->display_options['filters']['field_hp_category_value']['id'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['table'] = 'field_data_field_hp_category';
  $handler->display->display_options['filters']['field_hp_category_value']['field'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['value'] = array(
    'day trips' => 'day trips',
  );
  $handler->display->display_options['filters']['field_hp_category_value']['group'] = 1;
  /* Filter criterion: Holiday participation: Proximity */
  $handler->display->display_options['filters']['field_geofield_distance']['id'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['table'] = 'field_data_field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['field'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['operator'] = '<';
  $handler->display->display_options['filters']['field_geofield_distance']['value'] = array(
    'distance' => '25',
    'distance2' => 200,
    'unit' => '6371',
    'origin' => '',
  );
  $handler->display->display_options['filters']['field_geofield_distance']['group'] = 2;
  $handler->display->display_options['filters']['field_geofield_distance']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator_id'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['label'] = 'Region';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['description'] = 'Search by city or postal code';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['identifier'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_geofield_distance']['source'] = 'geocoder';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_entity_url_field'] = 'field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_current_user_field'] = 'field_paddle_geofield';
  /* Filter criterion: Holiday participation: Event state */
  $handler->display->display_options['filters']['field_hp_contract_type_value']['id'] = 'field_hp_contract_type_value';
  $handler->display->display_options['filters']['field_hp_contract_type_value']['table'] = 'field_data_field_hp_contract_type';
  $handler->display->display_options['filters']['field_hp_contract_type_value']['field'] = 'field_hp_contract_type_value';
  $handler->display->display_options['filters']['field_hp_contract_type_value']['value'] = array(
    'hp_evenement' => 'hp_evenement',
  );
  $handler->display->display_options['filters']['field_hp_contract_type_value']['group'] = 2;
  $handler->display->display_options['filters']['field_hp_contract_type_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_contract_type_value']['expose']['operator_id'] = 'field_hp_contract_type_value_op';
  $handler->display->display_options['filters']['field_hp_contract_type_value']['expose']['operator'] = 'field_hp_contract_type_value_op';
  $handler->display->display_options['filters']['field_hp_contract_type_value']['expose']['identifier'] = 'field_hp_contract_type_value';
  $handler->display->display_options['filters']['field_hp_contract_type_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_contract_type_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_contract_type_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Holiday participation: Contract year */
  $handler->display->display_options['filters']['contract_year']['id'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['table'] = 'holiday_participation_contract_year';
  $handler->display->display_options['filters']['contract_year']['field'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['value'] = '2016';
  $handler->display->display_options['filters']['contract_year']['exposed'] = TRUE;
  $handler->display->display_options['filters']['contract_year']['expose']['operator_id'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['label'] = 'Which year?';
  $handler->display->display_options['filters']['contract_year']['expose']['operator'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['identifier'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['contract_year']['contract_start_year'] = 'field_hp_contract_start_year';
  $handler->display->display_options['filters']['contract_year']['contract_end_year'] = 'field_hp_contract_end_year';
  /* Filter criterion: Holiday participation: Month */
  $handler->display->display_options['filters']['month']['id'] = 'month';
  $handler->display->display_options['filters']['month']['table'] = 'field_data_field_hp_validity_period';
  $handler->display->display_options['filters']['month']['field'] = 'month';
  $handler->display->display_options['filters']['month']['value'] = array(
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10',
    11 => '11',
    12 => '12',
  );
  $handler->display->display_options['filters']['month']['exposed'] = TRUE;
  $handler->display->display_options['filters']['month']['expose']['operator_id'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['label'] = 'Month';
  $handler->display->display_options['filters']['month']['expose']['operator'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['identifier'] = 'month';
  $handler->display->display_options['filters']['month']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  /* Filter criterion: Content: Title */
  $handler->display->display_options['filters']['title']['id'] = 'title';
  $handler->display->display_options['filters']['title']['table'] = 'node';
  $handler->display->display_options['filters']['title']['field'] = 'title';
  $handler->display->display_options['filters']['title']['operator'] = 'contains';
  $handler->display->display_options['filters']['title']['exposed'] = TRUE;
  $handler->display->display_options['filters']['title']['expose']['operator_id'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['label'] = 'search by title';
  $handler->display->display_options['filters']['title']['expose']['operator'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['identifier'] = 'title';
  $handler->display->display_options['filters']['title']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['month']['expose']['reduce'] = TRUE;
  $handler->display->display_options['path'] = 'offer/daytrips';

  /* Display: Organised holidays */
  $handler = $view->new_display('page', 'Organised holidays', 'organised_holidays');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Offer';
  $handler->display->display_options['display_description'] = 'Organised holidays';
  $handler->display->display_options['defaults']['footer'] = FALSE;
  /* Footer: Global: Result summary */
  $handler->display->display_options['footer']['result']['id'] = 'result';
  $handler->display->display_options['footer']['result']['table'] = 'views';
  $handler->display->display_options['footer']['result']['field'] = 'result';
  $handler->display->display_options['footer']['result']['content'] = '@total results';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Featured image */
  $handler->display->display_options['fields']['field_paddle_featured_image']['id'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['table'] = 'field_data_field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['field'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_featured_image']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_featured_image']['click_sort_column'] = 'sid';
  $handler->display->display_options['fields']['field_paddle_featured_image']['type'] = 'paddle_holiday_participation_featured_image';
  /* Field: Content: Province */
  $handler->display->display_options['fields']['field_hp_province']['id'] = 'field_hp_province';
  $handler->display->display_options['fields']['field_hp_province']['table'] = 'field_data_field_hp_province';
  $handler->display->display_options['fields']['field_hp_province']['field'] = 'field_hp_province';
  $handler->display->display_options['fields']['field_hp_province']['label'] = '';
  $handler->display->display_options['fields']['field_hp_province']['element_label_colon'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['label'] = '';
  $handler->display->display_options['fields']['body']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['body']['type'] = 'paddle_landing_page_summary_or_trimmed_plain_text';
  /* Field: Content: Contract end year */
  $handler->display->display_options['fields']['field_hp_contract_end_year']['id'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['table'] = 'field_data_field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['field'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_end_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Contract start year */
  $handler->display->display_options['fields']['field_hp_contract_start_year']['id'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['table'] = 'field_data_field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['field'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_start_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['filter_groups']['groups'] = array(
    1 => 'AND',
    2 => 'AND',
  );
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'offer' => 'offer',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Content: Category (field_hp_category) */
  $handler->display->display_options['filters']['field_hp_category_value']['id'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['table'] = 'field_data_field_hp_category';
  $handler->display->display_options['filters']['field_hp_category_value']['field'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['value'] = array(
    'organised holidays' => 'organised holidays',
  );
  $handler->display->display_options['filters']['field_hp_category_value']['group'] = 1;
  /* Filter criterion: Holiday participation: Proximity */
  $handler->display->display_options['filters']['field_geofield_distance']['id'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['table'] = 'field_data_field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['field'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['operator'] = '<';
  $handler->display->display_options['filters']['field_geofield_distance']['value'] = array(
    'distance' => '25',
    'distance2' => 200,
    'unit' => '6371',
    'origin' => '',
  );
  $handler->display->display_options['filters']['field_geofield_distance']['group'] = 2;
  $handler->display->display_options['filters']['field_geofield_distance']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator_id'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['label'] = 'Region';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['description'] = 'Search by city or postal code';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['identifier'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_geofield_distance']['source'] = 'geocoder';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_entity_url_field'] = 'field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_current_user_field'] = 'field_paddle_geofield';
  /* Filter criterion: Content: Formula organised holidays (field_hp_formula_oh) */
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['id'] = 'field_hp_formula_oh_value';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['table'] = 'field_data_field_hp_formula_oh';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['field'] = 'field_hp_formula_oh_value';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['value'] = array(
    0 => '0',
    1 => '1',
  );
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['group'] = 2;
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['operator_id'] = 'field_hp_formula_oh_value_op';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['label'] = 'Formula';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['operator'] = 'field_hp_formula_oh_value_op';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['identifier'] = 'field_hp_formula_oh_value';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Holiday participation: Contract year */
  $handler->display->display_options['filters']['contract_year']['id'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['table'] = 'holiday_participation_contract_year';
  $handler->display->display_options['filters']['contract_year']['field'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['value'] = '2016';
  $handler->display->display_options['filters']['contract_year']['exposed'] = TRUE;
  $handler->display->display_options['filters']['contract_year']['expose']['operator_id'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['label'] = 'Which year?';
  $handler->display->display_options['filters']['contract_year']['expose']['operator'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['identifier'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['contract_year']['contract_start_year'] = 'field_hp_contract_start_year';
  $handler->display->display_options['filters']['contract_year']['contract_end_year'] = 'field_hp_contract_end_year';
  /* Filter criterion: Holiday participation: Month */
  $handler->display->display_options['filters']['month']['id'] = 'month';
  $handler->display->display_options['filters']['month']['table'] = 'field_data_field_hp_validity_period';
  $handler->display->display_options['filters']['month']['field'] = 'month';
  $handler->display->display_options['filters']['month']['value'] = array(
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10',
    11 => '11',
    12 => '12',
  );
  $handler->display->display_options['filters']['month']['exposed'] = TRUE;
  $handler->display->display_options['filters']['month']['expose']['operator_id'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['label'] = 'Month';
  $handler->display->display_options['filters']['month']['expose']['operator'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['identifier'] = 'month';
  $handler->display->display_options['filters']['month']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  /* Filter criterion: Content: Title */
  $handler->display->display_options['filters']['title']['id'] = 'title';
  $handler->display->display_options['filters']['title']['table'] = 'node';
  $handler->display->display_options['filters']['title']['field'] = 'title';
  $handler->display->display_options['filters']['title']['operator'] = 'contains';
  $handler->display->display_options['filters']['title']['exposed'] = TRUE;
  $handler->display->display_options['filters']['title']['expose']['operator_id'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['label'] = 'search by title';
  $handler->display->display_options['filters']['title']['expose']['operator'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['identifier'] = 'title';
  $handler->display->display_options['filters']['title']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['month']['expose']['reduce'] = TRUE;
  $handler->display->display_options['path'] = 'offer/organisedholidays';

  /* Display: Day trips map */
  $handler = $view->new_display('page', 'Day trips map', 'day_trips_map');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Offer';
  $handler->display->display_options['display_description'] = 'Day trips map';
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'geofield_map_map';
  $handler->display->display_options['style_options']['data_source'] = 'field_paddle_geofield';
  $handler->display->display_options['style_options']['popup_source'] = 'title';
  $handler->display->display_options['style_options']['geofield_map_height'] = '400px';
  $handler->display->display_options['style_options']['geofield_map_min_zoom'] = '0';
  $handler->display->display_options['style_options']['geofield_map_max_zoom'] = '0';
  $handler->display->display_options['style_options']['geofield_map_draggable'] = 1;
  $handler->display->display_options['style_options']['geofield_map_center'] = array(
    'lat' => '50.8503',
    'lon' => '4.3517',
    'geocode' => 'Find my location',
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['footer'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Geofield */
  $handler->display->display_options['fields']['field_paddle_geofield']['id'] = 'field_paddle_geofield';
  $handler->display->display_options['fields']['field_paddle_geofield']['table'] = 'field_data_field_paddle_geofield';
  $handler->display->display_options['fields']['field_paddle_geofield']['field'] = 'field_paddle_geofield';
  $handler->display->display_options['fields']['field_paddle_geofield']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_geofield']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_geofield']['click_sort_column'] = 'geom';
  $handler->display->display_options['fields']['field_paddle_geofield']['settings'] = array(
    'data' => 'full',
  );
  /* Field: Content: Contract end year */
  $handler->display->display_options['fields']['field_hp_contract_end_year']['id'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['table'] = 'field_data_field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['field'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_end_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Contract start year */
  $handler->display->display_options['fields']['field_hp_contract_start_year']['id'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['table'] = 'field_data_field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['field'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_start_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'offer' => 'offer',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Content: Category (field_hp_category) */
  $handler->display->display_options['filters']['field_hp_category_value']['id'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['table'] = 'field_data_field_hp_category';
  $handler->display->display_options['filters']['field_hp_category_value']['field'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['value'] = array(
    'day trips' => 'day trips',
  );
  $handler->display->display_options['filters']['field_hp_category_value']['group'] = 1;
  /* Filter criterion: Holiday participation: Proximity */
  $handler->display->display_options['filters']['field_geofield_distance']['id'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['table'] = 'field_data_field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['field'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['operator'] = '<';
  $handler->display->display_options['filters']['field_geofield_distance']['value'] = array(
    'distance' => '25',
    'distance2' => 200,
    'unit' => '6371',
    'origin' => '',
  );
  $handler->display->display_options['filters']['field_geofield_distance']['group'] = 1;
  $handler->display->display_options['filters']['field_geofield_distance']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator_id'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['label'] = 'Region';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['description'] = 'Search by city or postal code';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['identifier'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_geofield_distance']['source'] = 'geocoder';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_entity_url_field'] = 'field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_current_user_field'] = 'field_paddle_geofield';
  /* Filter criterion: Holiday participation: Event state */
  $handler->display->display_options['filters']['field_hp_contract_type_value']['id'] = 'field_hp_contract_type_value';
  $handler->display->display_options['filters']['field_hp_contract_type_value']['table'] = 'field_data_field_hp_contract_type';
  $handler->display->display_options['filters']['field_hp_contract_type_value']['field'] = 'field_hp_contract_type_value';
  $handler->display->display_options['filters']['field_hp_contract_type_value']['value'] = array(
    'hp_evenement' => 'hp_evenement',
  );
  $handler->display->display_options['filters']['field_hp_contract_type_value']['group'] = 1;
  $handler->display->display_options['filters']['field_hp_contract_type_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_contract_type_value']['expose']['operator_id'] = 'field_hp_contract_type_value_op';
  $handler->display->display_options['filters']['field_hp_contract_type_value']['expose']['operator'] = 'field_hp_contract_type_value_op';
  $handler->display->display_options['filters']['field_hp_contract_type_value']['expose']['identifier'] = 'field_hp_contract_type_value';
  $handler->display->display_options['filters']['field_hp_contract_type_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_contract_type_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_contract_type_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Holiday participation: Contract year */
  $handler->display->display_options['filters']['contract_year']['id'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['table'] = 'holiday_participation_contract_year';
  $handler->display->display_options['filters']['contract_year']['field'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['value'] = '2016';
  $handler->display->display_options['filters']['contract_year']['exposed'] = TRUE;
  $handler->display->display_options['filters']['contract_year']['expose']['operator_id'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['label'] = 'Which year?';
  $handler->display->display_options['filters']['contract_year']['expose']['operator'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['identifier'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['contract_year']['contract_start_year'] = 'field_hp_contract_start_year';
  $handler->display->display_options['filters']['contract_year']['contract_end_year'] = 'field_hp_contract_end_year';
  /* Filter criterion: Holiday participation: Month */
  $handler->display->display_options['filters']['month']['id'] = 'month';
  $handler->display->display_options['filters']['month']['table'] = 'field_data_field_hp_validity_period';
  $handler->display->display_options['filters']['month']['field'] = 'month';
  $handler->display->display_options['filters']['month']['value'] = array(
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10',
    11 => '11',
    12 => '12',
  );
  $handler->display->display_options['filters']['month']['exposed'] = TRUE;
  $handler->display->display_options['filters']['month']['expose']['operator_id'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['label'] = 'Month';
  $handler->display->display_options['filters']['month']['expose']['operator'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['identifier'] = 'month';
  $handler->display->display_options['filters']['month']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  /* Filter criterion: Content: Title */
  $handler->display->display_options['filters']['title']['id'] = 'title';
  $handler->display->display_options['filters']['title']['table'] = 'node';
  $handler->display->display_options['filters']['title']['field'] = 'title';
  $handler->display->display_options['filters']['title']['operator'] = 'contains';
  $handler->display->display_options['filters']['title']['exposed'] = TRUE;
  $handler->display->display_options['filters']['title']['expose']['operator_id'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['label'] = 'search by title';
  $handler->display->display_options['filters']['title']['expose']['operator'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['identifier'] = 'title';
  $handler->display->display_options['filters']['title']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['month']['expose']['reduce'] = TRUE;
  $handler->display->display_options['path'] = 'offer/daytrips/map';

  /* Display: Organised holidays map */
  $handler = $view->new_display('page', 'Organised holidays map', 'organised_holidays_map');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Offer';
  $handler->display->display_options['display_description'] = 'Organised holidays map';
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'geofield_map_map';
  $handler->display->display_options['style_options']['data_source'] = 'field_paddle_geofield';
  $handler->display->display_options['style_options']['popup_source'] = 'title';
  $handler->display->display_options['style_options']['geofield_map_height'] = '400px';
  $handler->display->display_options['style_options']['geofield_map_min_zoom'] = '0';
  $handler->display->display_options['style_options']['geofield_map_max_zoom'] = '0';
  $handler->display->display_options['style_options']['geofield_map_draggable'] = 1;
  $handler->display->display_options['style_options']['geofield_map_center'] = array(
    'lat' => '50.8503',
    'lon' => '4.3517',
    'geocode' => 'Find my location',
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['footer'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Geofield */
  $handler->display->display_options['fields']['field_paddle_geofield']['id'] = 'field_paddle_geofield';
  $handler->display->display_options['fields']['field_paddle_geofield']['table'] = 'field_data_field_paddle_geofield';
  $handler->display->display_options['fields']['field_paddle_geofield']['field'] = 'field_paddle_geofield';
  $handler->display->display_options['fields']['field_paddle_geofield']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_geofield']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_geofield']['click_sort_column'] = 'geom';
  $handler->display->display_options['fields']['field_paddle_geofield']['settings'] = array(
    'data' => 'full',
  );
  /* Field: Content: Contract end year */
  $handler->display->display_options['fields']['field_hp_contract_end_year']['id'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['table'] = 'field_data_field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['field'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_end_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Contract start year */
  $handler->display->display_options['fields']['field_hp_contract_start_year']['id'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['table'] = 'field_data_field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['field'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_start_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['filter_groups']['groups'] = array(
    1 => 'AND',
    2 => 'AND',
  );
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'offer' => 'offer',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Content: Category (field_hp_category) */
  $handler->display->display_options['filters']['field_hp_category_value']['id'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['table'] = 'field_data_field_hp_category';
  $handler->display->display_options['filters']['field_hp_category_value']['field'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['value'] = array(
    'organised holidays' => 'organised holidays',
  );
  $handler->display->display_options['filters']['field_hp_category_value']['group'] = 1;
  /* Filter criterion: Content: Formula organised holidays (field_hp_formula_oh) */
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['id'] = 'field_hp_formula_oh_value';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['table'] = 'field_data_field_hp_formula_oh';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['field'] = 'field_hp_formula_oh_value';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['value'] = array(
    0 => '0',
    1 => '1',
  );
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['group'] = 2;
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['operator_id'] = 'field_hp_formula_oh_value_op';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['label'] = 'Formula';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['operator'] = 'field_hp_formula_oh_value_op';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['identifier'] = 'field_hp_formula_oh_value';
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_formula_oh_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Holiday participation: Proximity */
  $handler->display->display_options['filters']['field_geofield_distance']['id'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['table'] = 'field_data_field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['field'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['operator'] = '<';
  $handler->display->display_options['filters']['field_geofield_distance']['value'] = array(
    'distance' => '25',
    'distance2' => 200,
    'unit' => '6371',
    'origin' => '',
  );
  $handler->display->display_options['filters']['field_geofield_distance']['group'] = 2;
  $handler->display->display_options['filters']['field_geofield_distance']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator_id'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['label'] = 'Region';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['description'] = 'Search by city or postal code';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['identifier'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_geofield_distance']['source'] = 'geocoder';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_entity_url_field'] = 'field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_current_user_field'] = 'field_paddle_geofield';
  /* Filter criterion: Holiday participation: Contract year */
  $handler->display->display_options['filters']['contract_year']['id'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['table'] = 'holiday_participation_contract_year';
  $handler->display->display_options['filters']['contract_year']['field'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['value'] = '2016';
  $handler->display->display_options['filters']['contract_year']['exposed'] = TRUE;
  $handler->display->display_options['filters']['contract_year']['expose']['operator_id'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['label'] = 'Which year?';
  $handler->display->display_options['filters']['contract_year']['expose']['operator'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['identifier'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['contract_year']['contract_start_year'] = 'field_hp_contract_start_year';
  $handler->display->display_options['filters']['contract_year']['contract_end_year'] = 'field_hp_contract_end_year';
  /* Filter criterion: Holiday participation: Month */
  $handler->display->display_options['filters']['month']['id'] = 'month';
  $handler->display->display_options['filters']['month']['table'] = 'field_data_field_hp_validity_period';
  $handler->display->display_options['filters']['month']['field'] = 'month';
  $handler->display->display_options['filters']['month']['value'] = array(
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10',
    11 => '11',
    12 => '12',
  );
  $handler->display->display_options['filters']['month']['exposed'] = TRUE;
  $handler->display->display_options['filters']['month']['expose']['operator_id'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['label'] = 'Month';
  $handler->display->display_options['filters']['month']['expose']['operator'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['identifier'] = 'month';
  $handler->display->display_options['filters']['month']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['month']['expose']['reduce'] = TRUE;
  /* Filter criterion: Holiday participation: Month */
  $handler->display->display_options['filters']['month_1']['id'] = 'month_1';
  $handler->display->display_options['filters']['month_1']['table'] = 'field_data_field_hp_validity_period';
  $handler->display->display_options['filters']['month_1']['field'] = 'month';
  $handler->display->display_options['filters']['month_1']['value'] = array(
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10',
    11 => '11',
    12 => '12',
  );
  $handler->display->display_options['filters']['month_1']['exposed'] = TRUE;
  $handler->display->display_options['filters']['month_1']['expose']['operator_id'] = 'month_1_op';
  $handler->display->display_options['filters']['month_1']['expose']['label'] = 'Month';
  $handler->display->display_options['filters']['month_1']['expose']['operator'] = 'month_1_op';
  $handler->display->display_options['filters']['month_1']['expose']['identifier'] = 'month_1';
  $handler->display->display_options['filters']['month_1']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  /* Filter criterion: Content: Title */
  $handler->display->display_options['filters']['title']['id'] = 'title';
  $handler->display->display_options['filters']['title']['table'] = 'node';
  $handler->display->display_options['filters']['title']['field'] = 'title';
  $handler->display->display_options['filters']['title']['operator'] = 'contains';
  $handler->display->display_options['filters']['title']['exposed'] = TRUE;
  $handler->display->display_options['filters']['title']['expose']['operator_id'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['label'] = 'search by title';
  $handler->display->display_options['filters']['title']['expose']['operator'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['identifier'] = 'title';
  $handler->display->display_options['filters']['title']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['month_1']['expose']['reduce'] = TRUE;
  $handler->display->display_options['path'] = 'offer/organisedholidays/map';

  /* Display: Group accommodations map */
  $handler = $view->new_display('page', 'Group accommodations map', 'group_accommodations_map');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Offer';
  $handler->display->display_options['display_description'] = 'Group accommodations map';
  $handler->display->display_options['defaults']['exposed_form'] = FALSE;
  $handler->display->display_options['exposed_form']['type'] = 'better_exposed_filters';
  $handler->display->display_options['exposed_form']['options']['submit_button'] = 'Search';
  $handler->display->display_options['exposed_form']['options']['bef'] = array(
    'general' => array(
      'input_required' => 0,
      'text_input_required' => array(
        'text_input_required' => array(
          'value' => 'Select any filter and click on Apply to see results',
          'format' => 'full_html',
        ),
      ),
      'allow_secondary' => 0,
      'secondary_label' => 'Advanced options',
      'secondary_collapse_override' => '0',
    ),
    'field_geofield_distance' => array(
      'bef_format' => 'default',
      'slider_options' => array(
        'bef_slider_min' => '0',
        'bef_slider_max' => '99999',
        'bef_slider_step' => '1',
        'bef_slider_animate' => '',
        'bef_slider_orientation' => 'horizontal',
      ),
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Search by city/postal code',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_province_value' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => 'All provinces',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'capacity_range' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_facilities_value' => array(
      'bef_format' => 'bef',
      'more_options' => array(
        'bef_select_all_none' => 0,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => 'All',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_room_and_board_value' => array(
      'bef_format' => 'bef',
      'more_options' => array(
        'bef_select_all_none' => 0,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_labels_value' => array(
      'bef_format' => 'bef',
      'more_options' => array(
        'bef_select_all_none' => 0,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => 'All',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'contract_year' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Which year',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'month' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Which month',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
  );
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'geofield_map_map';
  $handler->display->display_options['style_options']['data_source'] = 'field_paddle_geofield';
  $handler->display->display_options['style_options']['popup_source'] = 'title';
  $handler->display->display_options['style_options']['geofield_map_height'] = '400px';
  $handler->display->display_options['style_options']['geofield_map_min_zoom'] = '0';
  $handler->display->display_options['style_options']['geofield_map_max_zoom'] = '0';
  $handler->display->display_options['style_options']['geofield_map_draggable'] = 1;
  $handler->display->display_options['style_options']['geofield_map_center'] = array(
    'lat' => '50.8503',
    'lon' => '4.3517',
    'geocode' => 'Find my location',
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['footer'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Geofield */
  $handler->display->display_options['fields']['field_paddle_geofield']['id'] = 'field_paddle_geofield';
  $handler->display->display_options['fields']['field_paddle_geofield']['table'] = 'field_data_field_paddle_geofield';
  $handler->display->display_options['fields']['field_paddle_geofield']['field'] = 'field_paddle_geofield';
  $handler->display->display_options['fields']['field_paddle_geofield']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_geofield']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_geofield']['click_sort_column'] = 'geom';
  $handler->display->display_options['fields']['field_paddle_geofield']['settings'] = array(
    'data' => 'full',
  );
  /* Field: Content: Min capacity */
  $handler->display->display_options['fields']['field_hp_min_capacity']['id'] = 'field_hp_min_capacity';
  $handler->display->display_options['fields']['field_hp_min_capacity']['table'] = 'field_data_field_hp_min_capacity';
  $handler->display->display_options['fields']['field_hp_min_capacity']['field'] = 'field_hp_min_capacity';
  $handler->display->display_options['fields']['field_hp_min_capacity']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_min_capacity']['settings'] = array(
    'thousand_separator' => '',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Max capacity */
  $handler->display->display_options['fields']['field_hp_max_capacity']['id'] = 'field_hp_max_capacity';
  $handler->display->display_options['fields']['field_hp_max_capacity']['table'] = 'field_data_field_hp_max_capacity';
  $handler->display->display_options['fields']['field_hp_max_capacity']['field'] = 'field_hp_max_capacity';
  $handler->display->display_options['fields']['field_hp_max_capacity']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_max_capacity']['settings'] = array(
    'thousand_separator' => '',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Contract end year */
  $handler->display->display_options['fields']['field_hp_contract_end_year']['id'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['table'] = 'field_data_field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['field'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_end_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Contract start year */
  $handler->display->display_options['fields']['field_hp_contract_start_year']['id'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['table'] = 'field_data_field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['field'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_start_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'offer' => 'offer',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Content: Category (field_hp_category) */
  $handler->display->display_options['filters']['field_hp_category_value']['id'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['table'] = 'field_data_field_hp_category';
  $handler->display->display_options['filters']['field_hp_category_value']['field'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['value'] = array(
    'group accommodations' => 'group accommodations',
  );
  $handler->display->display_options['filters']['field_hp_category_value']['group'] = 1;
  /* Filter criterion: Holiday participation: Proximity */
  $handler->display->display_options['filters']['field_geofield_distance']['id'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['table'] = 'field_data_field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['field'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['operator'] = '<';
  $handler->display->display_options['filters']['field_geofield_distance']['value'] = array(
    'distance' => '25',
    'distance2' => 200,
    'unit' => '6371',
    'origin' => '',
  );
  $handler->display->display_options['filters']['field_geofield_distance']['group'] = 1;
  $handler->display->display_options['filters']['field_geofield_distance']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator_id'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['label'] = 'Region';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['description'] = 'Search by city or postal code';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['identifier'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_geofield_distance']['source'] = 'geocoder';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_entity_url_field'] = 'field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_current_user_field'] = 'field_paddle_geofield';
  /* Filter criterion: Content: Province (field_hp_province) */
  $handler->display->display_options['filters']['field_hp_province_value']['id'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['table'] = 'field_data_field_hp_province';
  $handler->display->display_options['filters']['field_hp_province_value']['field'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['value'] = array(
    'antwerpen' => 'antwerpen',
    'limburg' => 'limburg',
    'west-vlaanderen' => 'west-vlaanderen',
    'oost-vlaanderen' => 'oost-vlaanderen',
    'vlaams-brabant' => 'vlaams-brabant',
    'brussel' => 'brussel',
    'waals-brabant' => 'waals-brabant',
    'luik' => 'luik',
    'namen' => 'namen',
    'henegouwen' => 'henegouwen',
    'luxemburg' => 'luxemburg',
  );
  $handler->display->display_options['filters']['field_hp_province_value']['group'] = 1;
  $handler->display->display_options['filters']['field_hp_province_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['operator_id'] = 'field_hp_province_value_op';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['operator'] = 'field_hp_province_value_op';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['identifier'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Holiday participation: Capacity range */
  $handler->display->display_options['filters']['capacity_range']['id'] = 'capacity_range';
  $handler->display->display_options['filters']['capacity_range']['table'] = 'holiday_participation_capacity_range';
  $handler->display->display_options['filters']['capacity_range']['field'] = 'capacity_range';
  $handler->display->display_options['filters']['capacity_range']['group'] = 1;
  $handler->display->display_options['filters']['capacity_range']['exposed'] = TRUE;
  $handler->display->display_options['filters']['capacity_range']['expose']['operator_id'] = 'capacity_range_op';
  $handler->display->display_options['filters']['capacity_range']['expose']['operator'] = 'capacity_range_op';
  $handler->display->display_options['filters']['capacity_range']['expose']['identifier'] = 'capacity_range';
  $handler->display->display_options['filters']['capacity_range']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['capacity_range']['min_capacity'] = 'field_hp_min_capacity';
  $handler->display->display_options['filters']['capacity_range']['max_capacity'] = 'field_hp_max_capacity';
  $handler->display->display_options['filters']['capacity_range']['include_endpoints'] = FALSE;
  /* Filter criterion: Content: Facilities (field_hp_facilities) */
  $handler->display->display_options['filters']['field_hp_facilities_value']['id'] = 'field_hp_facilities_value';
  $handler->display->display_options['filters']['field_hp_facilities_value']['table'] = 'field_data_field_hp_facilities';
  $handler->display->display_options['filters']['field_hp_facilities_value']['field'] = 'field_hp_facilities_value';
  $handler->display->display_options['filters']['field_hp_facilities_value']['value'] = array(
    'hp_swimming' => 'hp_swimming',
    'hp_pets_allowed' => 'hp_pets_allowed',
  );
  $handler->display->display_options['filters']['field_hp_facilities_value']['group'] = 1;
  $handler->display->display_options['filters']['field_hp_facilities_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['operator_id'] = 'field_hp_facilities_value_op';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['label'] = 'Facilities';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['operator'] = 'field_hp_facilities_value_op';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['identifier'] = 'field_hp_facilities_value';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Content: Room and board (field_hp_room_and_board) */
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['id'] = 'field_hp_room_and_board_value';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['table'] = 'field_data_field_hp_room_and_board';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['field'] = 'field_hp_room_and_board_value';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['value'] = array(
    'hp_self_cooking' => 'hp_self_cooking',
    'hp_bed_and_breakfast' => 'hp_bed_and_breakfast',
    'hp_half_board' => 'hp_half_board',
    'hp_full_board' => 'hp_full_board',
  );
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['group'] = 1;
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['operator_id'] = 'field_hp_room_and_board_value_op';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['label'] = 'Formula';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['operator'] = 'field_hp_room_and_board_value_op';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['identifier'] = 'field_hp_room_and_board_value';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Content: Labels (field_hp_labels) */
  $handler->display->display_options['filters']['field_hp_labels_value']['id'] = 'field_hp_labels_value';
  $handler->display->display_options['filters']['field_hp_labels_value']['table'] = 'field_data_field_hp_labels';
  $handler->display->display_options['filters']['field_hp_labels_value']['field'] = 'field_hp_labels_value';
  $handler->display->display_options['filters']['field_hp_labels_value']['value'] = array(
    'hp_green_key_label' => 'hp_green_key_label',
    'hp_cycling_label' => 'hp_cycling_label',
    'hp_accessibility_label_a' => 'hp_accessibility_label_a',
    'hp_accessibility_label_a_plus' => 'hp_accessibility_label_a_plus',
  );
  $handler->display->display_options['filters']['field_hp_labels_value']['group'] = 1;
  $handler->display->display_options['filters']['field_hp_labels_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['operator_id'] = 'field_hp_labels_value_op';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['label'] = 'Labels';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['operator'] = 'field_hp_labels_value_op';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['identifier'] = 'field_hp_labels_value';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Holiday participation: Contract year */
  $handler->display->display_options['filters']['contract_year']['id'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['table'] = 'holiday_participation_contract_year';
  $handler->display->display_options['filters']['contract_year']['field'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['value'] = '2016';
  $handler->display->display_options['filters']['contract_year']['exposed'] = TRUE;
  $handler->display->display_options['filters']['contract_year']['expose']['operator_id'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['label'] = 'Which year?';
  $handler->display->display_options['filters']['contract_year']['expose']['operator'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['identifier'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['contract_year']['contract_start_year'] = 'field_hp_contract_start_year';
  $handler->display->display_options['filters']['contract_year']['contract_end_year'] = 'field_hp_contract_end_year';
  /* Filter criterion: Holiday participation: Month */
  $handler->display->display_options['filters']['month']['id'] = 'month';
  $handler->display->display_options['filters']['month']['table'] = 'field_data_field_hp_validity_period';
  $handler->display->display_options['filters']['month']['field'] = 'month';
  $handler->display->display_options['filters']['month']['value'] = array(
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10',
    11 => '11',
    12 => '12',
  );
  $handler->display->display_options['filters']['month']['exposed'] = TRUE;
  $handler->display->display_options['filters']['month']['expose']['operator_id'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['label'] = 'Month';
  $handler->display->display_options['filters']['month']['expose']['operator'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['identifier'] = 'month';
  $handler->display->display_options['filters']['month']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  /* Filter criterion: Content: Title */
  $handler->display->display_options['filters']['title']['id'] = 'title';
  $handler->display->display_options['filters']['title']['table'] = 'node';
  $handler->display->display_options['filters']['title']['field'] = 'title';
  $handler->display->display_options['filters']['title']['operator'] = 'contains';
  $handler->display->display_options['filters']['title']['exposed'] = TRUE;
  $handler->display->display_options['filters']['title']['expose']['operator_id'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['label'] = 'search by title';
  $handler->display->display_options['filters']['title']['expose']['operator'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['identifier'] = 'title';
  $handler->display->display_options['filters']['title']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['month']['expose']['reduce'] = TRUE;
  $handler->display->display_options['path'] = 'offer/groupaccommodations/map';

  /* Display: Holiday accommodations map */
  $handler = $view->new_display('page', 'Holiday accommodations map', 'holiday_accommodations_map');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Offer';
  $handler->display->display_options['display_description'] = 'Holiday accommodations map';
  $handler->display->display_options['defaults']['exposed_form'] = FALSE;
  $handler->display->display_options['exposed_form']['type'] = 'better_exposed_filters';
  $handler->display->display_options['exposed_form']['options']['submit_button'] = 'Search';
  $handler->display->display_options['exposed_form']['options']['bef'] = array(
    'general' => array(
      'input_required' => 0,
      'text_input_required' => array(
        'text_input_required' => array(
          'value' => 'Select any filter and click on Apply to see results',
          'format' => 'full_html',
        ),
      ),
      'allow_secondary' => 0,
      'secondary_label' => 'Advanced options',
      'secondary_collapse_override' => '0',
    ),
    'field_geofield_distance' => array(
      'bef_format' => 'default',
      'slider_options' => array(
        'bef_slider_min' => '0',
        'bef_slider_max' => '99999',
        'bef_slider_step' => '1',
        'bef_slider_animate' => '',
        'bef_slider_orientation' => 'horizontal',
      ),
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Search by city/postal code',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_province_value' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => 'All provinces',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_facilities_value' => array(
      'bef_format' => 'bef',
      'more_options' => array(
        'bef_select_all_none' => 0,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => 'All',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_room_and_board_value' => array(
      'bef_format' => 'bef',
      'more_options' => array(
        'bef_select_all_none' => 0,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_hp_labels_value' => array(
      'bef_format' => 'bef',
      'more_options' => array(
        'bef_select_all_none' => 0,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => 'All',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'contract_year' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Which year',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'month' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => 'Which month',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
  );
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'geofield_map_map';
  $handler->display->display_options['style_options']['data_source'] = 'field_paddle_geofield';
  $handler->display->display_options['style_options']['popup_source'] = 'title';
  $handler->display->display_options['style_options']['geofield_map_height'] = '400px';
  $handler->display->display_options['style_options']['geofield_map_min_zoom'] = '0';
  $handler->display->display_options['style_options']['geofield_map_max_zoom'] = '0';
  $handler->display->display_options['style_options']['geofield_map_draggable'] = 1;
  $handler->display->display_options['style_options']['geofield_map_center'] = array(
    'lat' => '50.8503',
    'lon' => '4.3517',
    'geocode' => 'Find my location',
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Geofield */
  $handler->display->display_options['fields']['field_paddle_geofield']['id'] = 'field_paddle_geofield';
  $handler->display->display_options['fields']['field_paddle_geofield']['table'] = 'field_data_field_paddle_geofield';
  $handler->display->display_options['fields']['field_paddle_geofield']['field'] = 'field_paddle_geofield';
  $handler->display->display_options['fields']['field_paddle_geofield']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_geofield']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_geofield']['click_sort_column'] = 'geom';
  $handler->display->display_options['fields']['field_paddle_geofield']['settings'] = array(
    'data' => 'full',
  );
  /* Field: Content: Contract end year */
  $handler->display->display_options['fields']['field_hp_contract_end_year']['id'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['table'] = 'field_data_field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['field'] = 'field_hp_contract_end_year';
  $handler->display->display_options['fields']['field_hp_contract_end_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_end_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Contract start year */
  $handler->display->display_options['fields']['field_hp_contract_start_year']['id'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['table'] = 'field_data_field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['field'] = 'field_hp_contract_start_year';
  $handler->display->display_options['fields']['field_hp_contract_start_year']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_hp_contract_start_year']['settings'] = array(
    'thousand_separator' => '',
    'decimal_separator' => '.',
    'scale' => '2',
    'prefix_suffix' => 1,
  );
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'offer' => 'offer',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Content: Category (field_hp_category) */
  $handler->display->display_options['filters']['field_hp_category_value']['id'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['table'] = 'field_data_field_hp_category';
  $handler->display->display_options['filters']['field_hp_category_value']['field'] = 'field_hp_category_value';
  $handler->display->display_options['filters']['field_hp_category_value']['value'] = array(
    'holiday accommodations' => 'holiday accommodations',
  );
  $handler->display->display_options['filters']['field_hp_category_value']['group'] = 1;
  /* Filter criterion: Holiday participation: Proximity */
  $handler->display->display_options['filters']['field_geofield_distance']['id'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['table'] = 'field_data_field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['field'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['operator'] = '<';
  $handler->display->display_options['filters']['field_geofield_distance']['value'] = array(
    'distance' => '25',
    'distance2' => 200,
    'unit' => '6371',
    'origin' => '',
  );
  $handler->display->display_options['filters']['field_geofield_distance']['group'] = 1;
  $handler->display->display_options['filters']['field_geofield_distance']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator_id'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['label'] = 'Region';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['description'] = 'Search by city or postal code';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['operator'] = 'field_geofield_distance_op';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['identifier'] = 'field_geofield_distance';
  $handler->display->display_options['filters']['field_geofield_distance']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_geofield_distance']['source'] = 'geocoder';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_entity_url_field'] = 'field_paddle_geofield';
  $handler->display->display_options['filters']['field_geofield_distance']['geofield_proximity_current_user_field'] = 'field_paddle_geofield';
  /* Filter criterion: Content: Province (field_hp_province) */
  $handler->display->display_options['filters']['field_hp_province_value']['id'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['table'] = 'field_data_field_hp_province';
  $handler->display->display_options['filters']['field_hp_province_value']['field'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['value'] = array(
    'antwerpen' => 'antwerpen',
    'limburg' => 'limburg',
    'west-vlaanderen' => 'west-vlaanderen',
    'oost-vlaanderen' => 'oost-vlaanderen',
    'vlaams-brabant' => 'vlaams-brabant',
    'brussel' => 'brussel',
    'waals-brabant' => 'waals-brabant',
    'luik' => 'luik',
    'namen' => 'namen',
    'henegouwen' => 'henegouwen',
    'luxemburg' => 'luxemburg',
  );
  $handler->display->display_options['filters']['field_hp_province_value']['group'] = 1;
  $handler->display->display_options['filters']['field_hp_province_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['operator_id'] = 'field_hp_province_value_op';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['operator'] = 'field_hp_province_value_op';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['identifier'] = 'field_hp_province_value';
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_province_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Content: Facilities (field_hp_facilities) */
  $handler->display->display_options['filters']['field_hp_facilities_value']['id'] = 'field_hp_facilities_value';
  $handler->display->display_options['filters']['field_hp_facilities_value']['table'] = 'field_data_field_hp_facilities';
  $handler->display->display_options['filters']['field_hp_facilities_value']['field'] = 'field_hp_facilities_value';
  $handler->display->display_options['filters']['field_hp_facilities_value']['value'] = array(
    'hp_swimming' => 'hp_swimming',
    'hp_pets_allowed' => 'hp_pets_allowed',
  );
  $handler->display->display_options['filters']['field_hp_facilities_value']['group'] = 1;
  $handler->display->display_options['filters']['field_hp_facilities_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['operator_id'] = 'field_hp_facilities_value_op';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['label'] = 'Facilities';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['operator'] = 'field_hp_facilities_value_op';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['identifier'] = 'field_hp_facilities_value';
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_facilities_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Content: Room and board (field_hp_room_and_board) */
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['id'] = 'field_hp_room_and_board_value';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['table'] = 'field_data_field_hp_room_and_board';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['field'] = 'field_hp_room_and_board_value';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['value'] = array(
    'hp_self_cooking' => 'hp_self_cooking',
    'hp_bed_and_breakfast' => 'hp_bed_and_breakfast',
    'hp_half_board' => 'hp_half_board',
    'hp_full_board' => 'hp_full_board',
  );
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['group'] = 1;
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['operator_id'] = 'field_hp_room_and_board_value_op';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['label'] = 'Formula';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['operator'] = 'field_hp_room_and_board_value_op';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['identifier'] = 'field_hp_room_and_board_value';
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_room_and_board_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Content: Labels (field_hp_labels) */
  $handler->display->display_options['filters']['field_hp_labels_value']['id'] = 'field_hp_labels_value';
  $handler->display->display_options['filters']['field_hp_labels_value']['table'] = 'field_data_field_hp_labels';
  $handler->display->display_options['filters']['field_hp_labels_value']['field'] = 'field_hp_labels_value';
  $handler->display->display_options['filters']['field_hp_labels_value']['value'] = array(
    'hp_green_key_label' => 'hp_green_key_label',
    'hp_cycling_label' => 'hp_cycling_label',
    'hp_accessibility_label_a' => 'hp_accessibility_label_a',
    'hp_accessibility_label_a_plus' => 'hp_accessibility_label_a_plus',
  );
  $handler->display->display_options['filters']['field_hp_labels_value']['group'] = 1;
  $handler->display->display_options['filters']['field_hp_labels_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['operator_id'] = 'field_hp_labels_value_op';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['label'] = 'Labels';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['operator'] = 'field_hp_labels_value_op';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['identifier'] = 'field_hp_labels_value';
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['field_hp_labels_value']['expose']['reduce'] = TRUE;
  /* Filter criterion: Holiday participation: Contract year */
  $handler->display->display_options['filters']['contract_year']['id'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['table'] = 'holiday_participation_contract_year';
  $handler->display->display_options['filters']['contract_year']['field'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['value'] = '2016';
  $handler->display->display_options['filters']['contract_year']['exposed'] = TRUE;
  $handler->display->display_options['filters']['contract_year']['expose']['operator_id'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['label'] = 'Which year?';
  $handler->display->display_options['filters']['contract_year']['expose']['operator'] = 'contract_year_op';
  $handler->display->display_options['filters']['contract_year']['expose']['identifier'] = 'contract_year';
  $handler->display->display_options['filters']['contract_year']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['contract_year']['contract_start_year'] = 'field_hp_contract_start_year';
  $handler->display->display_options['filters']['contract_year']['contract_end_year'] = 'field_hp_contract_end_year';
  /* Filter criterion: Holiday participation: Month */
  $handler->display->display_options['filters']['month']['id'] = 'month';
  $handler->display->display_options['filters']['month']['table'] = 'field_data_field_hp_validity_period';
  $handler->display->display_options['filters']['month']['field'] = 'month';
  $handler->display->display_options['filters']['month']['value'] = array(
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10',
    11 => '11',
    12 => '12',
  );
  $handler->display->display_options['filters']['month']['exposed'] = TRUE;
  $handler->display->display_options['filters']['month']['expose']['operator_id'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['label'] = 'Month';
  $handler->display->display_options['filters']['month']['expose']['operator'] = 'month_op';
  $handler->display->display_options['filters']['month']['expose']['identifier'] = 'month';
  $handler->display->display_options['filters']['month']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  /* Filter criterion: Content: Title */
  $handler->display->display_options['filters']['title']['id'] = 'title';
  $handler->display->display_options['filters']['title']['table'] = 'node';
  $handler->display->display_options['filters']['title']['field'] = 'title';
  $handler->display->display_options['filters']['title']['operator'] = 'contains';
  $handler->display->display_options['filters']['title']['exposed'] = TRUE;
  $handler->display->display_options['filters']['title']['expose']['operator_id'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['label'] = 'search by title';
  $handler->display->display_options['filters']['title']['expose']['operator'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['identifier'] = 'title';
  $handler->display->display_options['filters']['title']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['month']['expose']['reduce'] = TRUE;
  $handler->display->display_options['path'] = 'offer/holidayaccommodations/map';
  $translatables['holiday_participation_filter_pages'] = array(
    t('Master'),
    t('more'),
    t('Search'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Select any filter and click on Apply to see results'),
    t('Advanced options'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('Formula'),
    t('Holiday accommodations'),
    t('Offer'),
    t('Search by city/postal code'),
    t('All provinces'),
    t('All'),
    t('Which year'),
    t('Which month'),
    t('results'),
    t('Contract start year'),
    t('Contract end year'),
    t('Region'),
    t('Search by city or postal code'),
    t('Facilities'),
    t('Labels'),
    t('Which year?'),
    t('Month'),
    t('Group accommodations'),
    t('Min capacity'),
    t('Max capacity'),
    t('Day trips'),
    t('Organised holidays'),
    t('Day trips map'),
    t('Organised holidays map'),
    t('Group accommodations map'),
    t('Holiday accommodations map'),
  );
  $export['holiday_participation_filter_pages'] = $view;

  return $export;
}
