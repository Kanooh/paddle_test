<?php
/**
 * @file
 * Views hook implementations of Paddle Dashboard.
 */

/**
 * Implements hook_views_data().
 */
function paddle_dashboard_views_data() {
  $data = array();

  $data['paddle_dashboard_global']['table']['group'] = t('Paddle Dashboard');
  $data['paddle_dashboard_global']['table']['join'] = array('#global' => array());

  $data['paddle_dashboard_global']['more'] = array(
    'title' => t('More link'),
    'help' => t('Displays a link to another page.'),
    'area' => array(
      'handler' => 'paddle_dashboard_handler_more',
    ),
  );

  $data['node']['dashboard_title'] = array(
    'group' => t('Paddle Dashboard'),
    'title' => t('Title'),
    'help' => t('The title with the title attribute set.'),
    'field' => array(
      'field' => 'title',
      'handler' => 'paddle_dashboard_handler_title',
    ),
  );

  return $data;
}
