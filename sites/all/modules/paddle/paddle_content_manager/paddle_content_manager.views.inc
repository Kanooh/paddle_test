<?php

/**
 * @file
 * This file handles views api definitions.
 * We describe the table to Views module
 * as well as other necessary stuff to integrate
 */


/**
 * Implements hook_views_data_alter().
 */
function paddle_content_manager_views_data_alter(&$data) {
  $data['workbench_moderation_node_history']['state']['field']['handler'] = 'paddle_content_manager_field_current_state';
  $data['workbench_moderation_node_history']['state']['filter']['handler'] = 'paddle_content_manager_handler_filter_state';

  $data['node']['admin_view_link'] = array(
    'group' => t('Paddle Content Manager'),
    'real field' => 'nid',
    'title' => t('Admin view link'),
    'help' => t('Link to the admin view node screen.'),
    'field' => array(
      'handler' => 'paddle_content_manager_handler_field_node_link_admin_view',
    ),
  );

  $data['node']['page_layout_link'] = array(
    'group' => t('Paddle Content Manager'),
    'real field' => 'nid',
    'title' => t('Page layout link'),
    'help' => t('Link to the node page layout screen.'),
    'field' => array(
      'handler' => 'paddle_content_manager_handler_field_node_link_page_layout',
    ),
  );

  $data['node']['archive_node'] = array(
    'group' => t('Paddle Content Manager'),
    'real field' => 'nid',
    'title' => t('Archive node link'),
    'help' => t('Link to the archive node confirmation page.'),
    'field' => array(
      'handler' => 'paddle_content_manager_handler_field_archive_node_link',
    ),
  );

  $data['node']['restore_node'] = array(
    'group' => t('Paddle Content Manager'),
    'real field' => 'nid',
    'title' => t('Restore node link'),
    'help' => t('Link to the restore node confirmation page.'),
    'field' => array(
      'handler' => 'paddle_content_manager_handler_field_restore_node_link',
    ),
  );

  $data['node']['clone_node'] = array(
    'group' => t('Paddle Content Manager'),
    'title' => t('Clone link'),
    'help' => t('Provide a simple link to clone the node.'),
    'field' => array(
      'handler' => 'paddle_content_manager_handler_field_node_link_clone',
    ),
  );

  return $data;
}

/**
 * Implements hook_views_data().
 */
function paddle_content_manager_views_data() {
  $data = array();

  $data['node']['creation_uid'] = array(
    'group' => t('Paddle Content Manager'),
    'real field' => 'uid',
    'title' => t('Creation author'),
    'help' => t('Which is the user who created'),
    'field' => array(
      'handler' => 'paddle_content_manager_field_creation_uid',
      'click sortable' => TRUE,
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_in_operator',
      'options callback' => 'paddle_content_manager_creation_user_options',
    ),
  );

  $data['node']['url'] = array(
    'group' => t('Paddle Content Manager'),
    'real field' => 'nid',
    'title' => t('Aliased url'),
    'help' => t('Full url to the node, aliased if an alias exists.'),
    'field' => array(
      'handler' => 'paddle_content_manager_field_url',
      'click sortable' => TRUE,
    ),
  );

  $data['workbench_moderation_node_history']['last_modified_uid'] = array(
    'group' => t('Paddle Content Manager'),
    'real field' => 'uid',
    'title' => t('Last modified author'),
    'help' => t('Which is the user who last modified'),
    'field' => array(
      'handler' => 'paddle_content_manager_field_modified_uid',
      'click sortable' => TRUE,
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_in_operator',
      'options callback' => 'paddle_content_manager_last_modified_user_options',
    ),
  );

  $data['paddle_content_manager_revision_assignee']['table']['group'] = t('Paddle Content Manager');

  $data['paddle_content_manager_revision_assignee']['table']['join'] = array(
    'node_revision' => array(
      'left_table' => 'workbench_moderation_node_history',
      'left_field' => 'hid',
      'field' => 'workbench_hid',
    ),
  );

  // Our Handler.
  $data['paddle_content_manager_revision_assignee']['uid'] = array(
    'title' => t('Assigned author'),
    'help' => t('Which is the assigned author'),
    'field' => array(
      'handler' => 'paddle_content_manager_field_assigned_uid',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_in_operator',
      'options callback' => 'paddle_content_manager_assignee_options',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_user_uid',
      'name field' => 'name',
    ),
  );

  foreach (workbench_moderation_states() as $state) {
    $data['paddle_content_manager_revision_assignee']['uid_' . $state->name] = array(
      'title' => t('Assigned author in state: @state', array('@state' => $state->name)),
      'help' => t('Which is the assigned author in state @state', array('@state' => $state->name)),
      'real field' => 'uid',
      'filter' => array(
        'handler' => 'views_handler_filter_in_operator',
        'options callback' => 'paddle_content_manager_assignee_options',
        'options arguments' => array($state->name),
      ),
    );
  }

  $data['paddle_content_manager_validate_user']['table']['group'] = t('Paddle Content Manager');

  $data['paddle_content_manager_validate_user']['table']['join'] = array(
    'node_revision' => array(
      'left_table' => 'workbench_moderation_node_history',
      'left_field' => 'nid',
      'field' => 'nid',
    ),
  );

  $data['paddle_content_manager_validate_user']['uid'] = array(
    'title' => t('Validation author'),
    'help' => t('Which is the user who validated'),
    'field' => array(
      'handler' => 'paddle_content_manager_field_validation_uid',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_in_operator',
      'options callback' => 'paddle_content_manager_validate_user_options',
    ),
  );

  return $data;
}
