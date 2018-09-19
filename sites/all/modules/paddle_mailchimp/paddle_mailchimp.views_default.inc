<?php
/**
 * @file
 * paddle_mailchimp.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_mailchimp_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'signup_forms';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'mailchimp_signup';
  $view->human_name = 'Signup forms';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_ajax'] = TRUE;
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '10';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'title' => 'title',
    'signup_lists' => 'signup_lists',
    'delete_link' => 'edit_link',
    'edit_link' => 'edit_link',
    'mcs_id' => 'mcs_id',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'title' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'signup_lists' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'delete_link' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'edit_link' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'mcs_id' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
    ),
  );
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No Signup forms created.';
  /* Field: MailChimp Signup: Internal, numeric mailchimp signup ID */
  $handler->display->display_options['fields']['mcs_id']['id'] = 'mcs_id';
  $handler->display->display_options['fields']['mcs_id']['table'] = 'mailchimp_signup';
  $handler->display->display_options['fields']['mcs_id']['field'] = 'mcs_id';
  $handler->display->display_options['fields']['mcs_id']['label'] = 'ID';
  $handler->display->display_options['fields']['mcs_id']['separator'] = '';
  /* Field: MailChimp Signup: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'mailchimp_signup';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  /* Field: MailChimp Signup: Mailchimp Lists */
  $handler->display->display_options['fields']['signup_lists']['id'] = 'signup_lists';
  $handler->display->display_options['fields']['signup_lists']['table'] = 'mailchimp_signup';
  $handler->display->display_options['fields']['signup_lists']['field'] = 'signup_lists';
  $handler->display->display_options['fields']['signup_lists']['label'] = 'Lists';
  /* Field: MailChimp Signup: Edit link */
  $handler->display->display_options['fields']['edit_link']['id'] = 'edit_link';
  $handler->display->display_options['fields']['edit_link']['table'] = 'mailchimp_signup';
  $handler->display->display_options['fields']['edit_link']['field'] = 'edit_link';
  $handler->display->display_options['fields']['edit_link']['label'] = 'Actions';
  /* Field: MailChimp Signup: Delete link */
  $handler->display->display_options['fields']['delete_link']['id'] = 'delete_link';
  $handler->display->display_options['fields']['delete_link']['table'] = 'mailchimp_signup';
  $handler->display->display_options['fields']['delete_link']['field'] = 'delete_link';
  $handler->display->display_options['fields']['delete_link']['label'] = '';
  $handler->display->display_options['fields']['delete_link']['element_label_colon'] = FALSE;
  /* Sort criterion: MailChimp Signup: Internal, numeric mailchimp signup ID */
  $handler->display->display_options['sorts']['mcs_id']['id'] = 'mcs_id';
  $handler->display->display_options['sorts']['mcs_id']['table'] = 'mailchimp_signup';
  $handler->display->display_options['sorts']['mcs_id']['field'] = 'mcs_id';
  $handler->display->display_options['sorts']['mcs_id']['order'] = 'DESC';
  $translatables['signup_forms'] = array(
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
    t('No Signup forms created.'),
    t('ID'),
    t('Title'),
    t('Lists'),
    t('Actions'),
  );
  $export['signup_forms'] = $view;

  return $export;
}
