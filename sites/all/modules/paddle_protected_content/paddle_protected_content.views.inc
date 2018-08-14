<?php

/**
 * @file
 * Code for the Paddle Protected Content views.
 */

/**
 * Implements hook_views_data().
 */
function paddle_protected_content_views_data() {
  $data = array();

  // Define the base group of this table. Fields that don't
  // have a group defined will go into this field by default.
  $data['role']['table']['group'] = t('Role');

  // Advertise this table as a possible base table.
  $data['role']['table']['base'] = array(
    'field' => 'rid',
    'title' => t('Role'),
    'help' => t('Contains the Drupal user roles.'),
    'weight' => -10,
    'defaults' => array(
      'field' => 'name',
    ),
  );

  // Role ID is the identifier of our table.
  $data['role']['rid'] = array(
    'title' => t('Rid'),
    'help' => t('The role ID.'),
    'field' => array(
      'handler' => 'views_handler_field_numeric',
      'click sortable' => TRUE,
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_numeric',
      'name field' => 'title',
      'numeric' => TRUE,
      'validate type' => 'nid',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_numeric',
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
  );

  // Role name.
  $data['role']['name'] = array(
    'title' => t('Name'),
    'help' => t('The role name.'),
    'field' => array(
      'handler' => 'views_handler_field',
      'click sortable' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_string',
    ),
  );

  $data['role']['edit_link'] = array(
    'group' => t('Paddle Protected Content'),
    'real field' => 'rid',
    'title' => t('Edit link'),
    'help' => t('Link to the role edit screen.'),
    'field' => array(
      'handler' => 'paddle_protected_content_handler_field_role_edit',
    ),
  );

  $data['role']['delete_link'] = array(
    'group' => t('Paddle Protected Content'),
    'real field' => 'rid',
    'title' => t('Delete link'),
    'help' => t('Link to the role delete screen.'),
    'field' => array(
      'handler' => 'paddle_protected_content_handler_field_role_delete',
    ),
  );

  return $data;
}

/**
 * Implements hook_views_query_alter().
 */
function paddle_protected_content_views_query_alter(&$view, &$query) {
  if ($view->name === 'roles') {
    // Remove the anonymous and authenticated user roles from the view.
    $query->add_where(1, 'rid', array(DRUPAL_ANONYMOUS_RID, DRUPAL_AUTHENTICATED_RID), 'NOT IN');
  }
}

/**
 * Implements hook_preprocess_views_view().
 */
function paddle_protected_content_preprocess_views_view(&$variables) {
  $view = $variables['view'];
  if ($view->name == 'roles') {
    $add_text = t('Add role');
    $add_path = 'admin/users/roles/nojs/add';

    ctools_include('ajax');
    ctools_include('modal');
    ctools_modal_add_js();

    $add_link = l($add_text, $add_path, array(
      'attributes' => array(
        'class' => array(
          'ctools-use-modal',
          'ui-icon',
          'fa-plus',
        ),
        'id' => 'add-role-button',
      ),
    ));

    if (!module_exists('paddle_contextual_toolbar')) {
      $variables['header'] = $add_link;
    }
    else {
      $actions = array();
      $actions[] = array(
        'action' => $add_link,
        'class' => array('add'),
        'weight' => 0,
      );
      paddle_contextual_toolbar_actions($actions);
    }
  }
}
