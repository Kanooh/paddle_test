<?php
/**
 * @file
 * paddle_glossary.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_glossary_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'paddle_glossary';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'paddle_glossary_definition';
  $view->human_name = 'Glossary';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Glossary';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '36';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['pager']['options']['tags']['first'] = 'first';
  $handler->display->display_options['pager']['options']['tags']['previous'] = 'previous';
  $handler->display->display_options['pager']['options']['tags']['next'] = 'next';
  $handler->display->display_options['pager']['options']['tags']['last'] = 'last';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['row_options']['inline'] = array(
    'definition' => 'definition',
    'field_glossary_description' => 'field_glossary_description',
  );
  /* Field: Glossary definition: Label */
  $handler->display->display_options['fields']['definition']['id'] = 'definition';
  $handler->display->display_options['fields']['definition']['table'] = 'paddle_glossary_definition';
  $handler->display->display_options['fields']['definition']['field'] = 'definition';
  $handler->display->display_options['fields']['definition']['label'] = '';
  $handler->display->display_options['fields']['definition']['element_label_colon'] = FALSE;
  /* Field: Glossary definition: Description */
  $handler->display->display_options['fields']['field_glossary_description']['id'] = 'field_glossary_description';
  $handler->display->display_options['fields']['field_glossary_description']['table'] = 'field_data_field_glossary_description';
  $handler->display->display_options['fields']['field_glossary_description']['field'] = 'field_glossary_description';
  $handler->display->display_options['fields']['field_glossary_description']['label'] = '';
  $handler->display->display_options['fields']['field_glossary_description']['element_label_colon'] = FALSE;
  /* Sort criterion: Glossary definition: Label */
  $handler->display->display_options['sorts']['definition']['id'] = 'definition';
  $handler->display->display_options['sorts']['definition']['table'] = 'paddle_glossary_definition';
  $handler->display->display_options['sorts']['definition']['field'] = 'definition';
  /* Contextual filter: Glossary definition: Label */
  $handler->display->display_options['arguments']['definition']['id'] = 'definition';
  $handler->display->display_options['arguments']['definition']['table'] = 'paddle_glossary_definition';
  $handler->display->display_options['arguments']['definition']['field'] = 'definition';
  $handler->display->display_options['arguments']['definition']['default_action'] = 'default';
  $handler->display->display_options['arguments']['definition']['exception']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['definition']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['definition']['default_argument_options']['argument'] = 'a';
  $handler->display->display_options['arguments']['definition']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['definition']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['definition']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['definition']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['definition']['glossary'] = TRUE;
  $handler->display->display_options['arguments']['definition']['limit'] = '1';
  $handler->display->display_options['arguments']['definition']['case'] = 'upper';
  $handler->display->display_options['arguments']['definition']['path_case'] = 'lower';

  /* Display: Paddle Glossary Page */
  $handler = $view->new_display('page', 'Paddle Glossary Page', 'paddle_glossary_page');
  $handler->display->display_options['defaults']['access'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'see frontend glossary views page';
  $handler->display->display_options['path'] = 'views-glossary';

  /* Display: Paddle Glossary Attachment */
  $handler = $view->new_display('attachment', 'Paddle Glossary Attachment', 'paddle_glossary_attachment');
  $handler->display->display_options['defaults']['css_class'] = FALSE;
  $handler->display->display_options['css_class'] = 'paddle-glossary-pager';
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Glossary definition: Label */
  $handler->display->display_options['arguments']['definition']['id'] = 'definition';
  $handler->display->display_options['arguments']['definition']['table'] = 'paddle_glossary_definition';
  $handler->display->display_options['arguments']['definition']['field'] = 'definition';
  $handler->display->display_options['arguments']['definition']['default_action'] = 'summary';
  $handler->display->display_options['arguments']['definition']['exception']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['definition']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['definition']['default_argument_options']['argument'] = 'a';
  $handler->display->display_options['arguments']['definition']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['definition']['summary']['format'] = 'unformatted_summary';
  $handler->display->display_options['arguments']['definition']['summary_options']['count'] = FALSE;
  $handler->display->display_options['arguments']['definition']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['definition']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['definition']['validate']['fail'] = 'empty';
  $handler->display->display_options['arguments']['definition']['glossary'] = TRUE;
  $handler->display->display_options['arguments']['definition']['limit'] = '1';
  $handler->display->display_options['arguments']['definition']['case'] = 'upper';
  $handler->display->display_options['arguments']['definition']['path_case'] = 'lower';
  $handler->display->display_options['displays'] = array(
    'default' => 'default',
    'paddle_glossary_page' => 'paddle_glossary_page',
    'paddle_glossary_block' => 'paddle_glossary_block',
    'glossary_overview_pane' => 'glossary_overview_pane',
  );
  $handler->display->display_options['inherit_arguments'] = FALSE;

  /* Display: Paddle Glossary Block */
  $handler = $view->new_display('block', 'Paddle Glossary Block', 'paddle_glossary_block');
  $handler->display->display_options['defaults']['use_ajax'] = FALSE;
  $handler->display->display_options['use_ajax'] = TRUE;
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'gdid' => 'gdid',
    'definition' => 'definition',
    'edit_link' => 'edit_link',
    'delete_link' => 'edit_link',
  );
  $handler->display->display_options['style_options']['default'] = 'definition';
  $handler->display->display_options['style_options']['info'] = array(
    'gdid' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'definition' => array(
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
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Glossary definition: Glossary definition ID */
  $handler->display->display_options['fields']['gdid']['id'] = 'gdid';
  $handler->display->display_options['fields']['gdid']['table'] = 'paddle_glossary_definition';
  $handler->display->display_options['fields']['gdid']['field'] = 'gdid';
  $handler->display->display_options['fields']['gdid']['label'] = '';
  $handler->display->display_options['fields']['gdid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['gdid']['element_label_colon'] = FALSE;
  /* Field: Glossary definition: Label */
  $handler->display->display_options['fields']['definition']['id'] = 'definition';
  $handler->display->display_options['fields']['definition']['table'] = 'paddle_glossary_definition';
  $handler->display->display_options['fields']['definition']['field'] = 'definition';
  $handler->display->display_options['fields']['definition']['label'] = 'Title';
  $handler->display->display_options['fields']['definition']['element_label_colon'] = FALSE;
  /* Field: Glossary definition: Edit link */
  $handler->display->display_options['fields']['edit_link']['id'] = 'edit_link';
  $handler->display->display_options['fields']['edit_link']['table'] = 'paddle_glossary_definition';
  $handler->display->display_options['fields']['edit_link']['field'] = 'edit_link';
  $handler->display->display_options['fields']['edit_link']['label'] = 'Actions';
  $handler->display->display_options['fields']['edit_link']['element_label_colon'] = FALSE;
  /* Field: Glossary definition: Delete link */
  $handler->display->display_options['fields']['delete_link']['id'] = 'delete_link';
  $handler->display->display_options['fields']['delete_link']['table'] = 'paddle_glossary_definition';
  $handler->display->display_options['fields']['delete_link']['field'] = 'delete_link';
  $handler->display->display_options['fields']['delete_link']['label'] = '';
  $handler->display->display_options['fields']['delete_link']['element_label_colon'] = FALSE;
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Glossary definition: Label */
  $handler->display->display_options['arguments']['definition']['id'] = 'definition';
  $handler->display->display_options['arguments']['definition']['table'] = 'paddle_glossary_definition';
  $handler->display->display_options['arguments']['definition']['field'] = 'definition';
  $handler->display->display_options['arguments']['definition']['default_action'] = 'default';
  $handler->display->display_options['arguments']['definition']['exception']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['definition']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['definition']['default_argument_options']['argument'] = 'a';
  $handler->display->display_options['arguments']['definition']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['definition']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['definition']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['definition']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['definition']['validate']['fail'] = 'empty';
  $handler->display->display_options['arguments']['definition']['glossary'] = TRUE;
  $handler->display->display_options['arguments']['definition']['limit'] = '1';
  $handler->display->display_options['arguments']['definition']['case'] = 'upper';
  $handler->display->display_options['arguments']['definition']['path_case'] = 'lower';

  /* Display: Glossary overview */
  $handler = $view->new_display('panel_pane', 'Glossary overview', 'glossary_overview_pane');
  $handler->display->display_options['defaults']['use_ajax'] = FALSE;
  $handler->display->display_options['use_ajax'] = TRUE;
  $handler->display->display_options['defaults']['link_display'] = FALSE;
  $handler->display->display_options['link_display'] = 'paddle_glossary_page';
  $handler->display->display_options['defaults']['access'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['style_options']['row_class'] = 'glossary-definition';
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['row_options']['inline'] = array(
    'definition' => 'definition',
    'field_glossary_description' => 'field_glossary_description',
  );
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Glossary definition: Label */
  $handler->display->display_options['fields']['definition']['id'] = 'definition';
  $handler->display->display_options['fields']['definition']['table'] = 'paddle_glossary_definition';
  $handler->display->display_options['fields']['definition']['field'] = 'definition';
  $handler->display->display_options['fields']['definition']['label'] = '';
  $handler->display->display_options['fields']['definition']['element_type'] = '0';
  $handler->display->display_options['fields']['definition']['element_label_type'] = '0';
  $handler->display->display_options['fields']['definition']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['definition']['element_wrapper_type'] = 'div';
  $handler->display->display_options['fields']['definition']['element_wrapper_class'] = 'col-sm-3';
  /* Field: Glossary definition: Description */
  $handler->display->display_options['fields']['field_glossary_description']['id'] = 'field_glossary_description';
  $handler->display->display_options['fields']['field_glossary_description']['table'] = 'field_data_field_glossary_description';
  $handler->display->display_options['fields']['field_glossary_description']['field'] = 'field_glossary_description';
  $handler->display->display_options['fields']['field_glossary_description']['label'] = '';
  $handler->display->display_options['fields']['field_glossary_description']['element_type'] = '0';
  $handler->display->display_options['fields']['field_glossary_description']['element_label_type'] = '0';
  $handler->display->display_options['fields']['field_glossary_description']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_glossary_description']['element_wrapper_type'] = 'div';
  $handler->display->display_options['fields']['field_glossary_description']['element_wrapper_class'] = 'col-sm-9';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Glossary definition: Label */
  $handler->display->display_options['arguments']['definition']['id'] = 'definition';
  $handler->display->display_options['arguments']['definition']['table'] = 'paddle_glossary_definition';
  $handler->display->display_options['arguments']['definition']['field'] = 'definition';
  $handler->display->display_options['arguments']['definition']['exception']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['definition']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['definition']['default_argument_options']['argument'] = 'a';
  $handler->display->display_options['arguments']['definition']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['definition']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['definition']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['definition']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['definition']['glossary'] = TRUE;
  $handler->display->display_options['arguments']['definition']['limit'] = '1';
  $handler->display->display_options['arguments']['definition']['case'] = 'upper';
  $handler->display->display_options['arguments']['definition']['path_case'] = 'lower';
  $translatables['paddle_glossary'] = array(
    t('Master'),
    t('Glossary'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('first'),
    t('previous'),
    t('next'),
    t('last'),
    t('All'),
    t('Paddle Glossary Page'),
    t('Paddle Glossary Attachment'),
    t('Paddle Glossary Block'),
    t('.'),
    t(','),
    t('Title'),
    t('Actions'),
    t('Glossary overview'),
    t('View panes'),
  );
  $export['paddle_glossary'] = $view;

  return $export;
}
