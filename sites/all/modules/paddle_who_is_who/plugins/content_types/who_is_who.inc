<?php
/**
 * @file
 * CTools content type plugin to embed a who is who pane.
 */

$plugin = array(
  'title' => t('Who is who'),
  'no title override' => TRUE,
  'description' => t('Renders the who is who pane.'),
  'category' => t('Paddle who is who'),
  'defaults' => array(
    'view_mode' => 'team_members',
  ),
  'paddle_panes sections' => TRUE,
  'reference tracker' => array(
    'node' => array(
      'value_type' => 'node_autocomplete',
    ),
  ),
);

/**
 * Render callback.
 */
function paddle_who_is_who_who_is_who_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  $callback = 'paddle_who_is_who_who_is_who_content_type_get_content_' . $conf['view_mode'];
  $output = $callback($conf);

  // Allow the pane sections to contribute output.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  $block->content = $output;
  return $block;
}

/**
 * Retrieves the pane content when team members has been selected.
 *
 * @param array $conf
 *   The configuration of the pane.
 *
 * @return string
 *   The rendered team members block.
 */
function paddle_who_is_who_who_is_who_content_type_get_content_team_members($conf) {
  $variables = array();

  if (!empty($conf['node']) && preg_match('/node\/(\d+)/', $conf['node'], $matches)) {
    $nid = $matches[1];
    $node = node_load($nid);

    $contact_persons = array();
    if (!user_access('view all unpublished content') && !empty($node->workbench_moderation['published']) ||
         user_access('view all unpublished content')) {
      $results = paddle_who_is_who_get_contact_persons_by_organizational_unit($nid);

      foreach ($results as $result) {
        if ($result->state === 'published') {
          // Loading a published node can be retrieved from cache, when already
          // loaded before.
          $node = node_load($result->nid);
        }
        else {
          // Non-published nodes can not because they load a specific revision.
          // So we try to minimise those calls.
          $node = node_load($result->nid, $result->vid);
        }

        $contact_persons[$result->nid] = array(
          'node' => $node,
          'company_info' => field_collection_item_revision_load($result->field_paddle_cp_company_info_revision_id),
        );
      }
    }

    $rendered_contact_persons = array();

    foreach ($contact_persons as $contact_person) {
      $params = array();
      $rendered_contact_persons[$contact_person['node']->nid] = theme('paddle_who_is_who_team_member', array(
        'node' => $contact_person['node'],
        'company_info' => $contact_person['company_info'],
      ));

      // Add the field collection ID as a parameter so the Contact person
      // linked will have the correct address from the referenced company.
      if (isset($contact_person['company_info']->item_id)) {
        $params['referenced_organization'] = $contact_person['company_info']->item_id;
      }

      $rendered_contact_persons[$contact_person['node']->nid]['link'] = url('node/' . $contact_person['node']->nid, array('query' => $params));
    }

    $variables['rendered'] = $rendered_contact_persons;
  }

  return theme('team_members', $variables);
}

/**
 * Retrieves the pane content when organization has been selected.
 *
 * @param array $conf
 *   The configuration of the pane.
 *
 * @return string
 *   The rendered organization block.
 */
function paddle_who_is_who_who_is_who_content_type_get_content_organization($conf) {
  $variables = array();

  if (!empty($conf['node']) && preg_match('/node\/(\d+)/', $conf['node'], $matches)) {
    $nid = $matches[1];
    $node = node_load($nid);
    $team_members = array();
    $contact_persons = array();

    // First we retrieve the children of the Organizational Unit if they exist.
    if (!empty($conf['children']) && (!user_access('view all unpublished content') && !empty($node->workbench_moderation['published']) ||
        (user_access('view all unpublished content')))) {
      $children = paddle_who_is_who_organizational_units_by_parent($nid);
      $children_array = array();
      foreach ($children as $child) {
        if (user_access('view all unpublished content')) {
          // Get the most recent revision when logged in, but not when archived.
          if ($child->workbench_moderation['current']->state != "published") {
            $child = node_load($child->workbench_moderation['current']->nid, $child->workbench_moderation['current']->vid);
          }
          $child_wrapper = entity_metadata_wrapper('node', $child);
          $children_array[] = l($child_wrapper->title->value(), 'node/' . $child->nid);
        }
        elseif (!empty($child->workbench_moderation['published'])) {
          $child_wrapper = entity_metadata_wrapper('node', $child);
          $children_array[] = l($child_wrapper->title->value(), 'node/' . $child->nid);
        }
      }
      $variables['children'] = $children_array;
    }

    // If team members are asked and the current user is allowed to view the current organisation.
    // Then we retrieve the contact persons who have the Organizational Unit
    // added in their company info field collection.
    if (!empty($conf['team_members']) && ($node->workbench_moderation['published'] || user_access('view all unpublished content'))) {
      $results = paddle_who_is_who_get_contact_persons_by_organizational_unit($nid);

      $revision_ids = array();
      foreach ($results as $result) {
        $revision_ids[] = $result->vid;
      }

      if (count($revision_ids)) {
        $query = db_select('node_revision', 'nr');
        $query->join('field_revision_field_paddle_cp_last_name', 'cpln', 'cpln.revision_id = nr.vid');
        $query->join('field_revision_field_paddle_cp_company_info', 'ci', 'ci.revision_id = nr.vid');
        $query->join('field_revision_field_cp_organisation', 'co', 'co.revision_id = ci.field_paddle_cp_company_info_revision_id');
        // Not all contact persons have a 'function' filled out, so make the
        // join optional by joining left.
        $query->leftJoin('field_revision_field_cp_function', 'cpf', 'cpf.revision_id = ci.field_paddle_cp_company_info_revision_id');
        $query->condition('nr.vid', $revision_ids, 'IN');
        $query->condition('co.field_cp_organisation_target_id', $nid, '=');
        $query->fields('nr', array('nid', 'title'))
          ->fields('cpf', array('field_cp_function_value'))
          ->fields('ci', array('field_paddle_cp_company_info_value'));
        $query->orderBy('cpln.field_paddle_cp_last_name_value', 'ASC');
        $contact_persons = $query->execute()->fetchAll();

        foreach ($contact_persons as $contact_person) {
          $cp_nid = $contact_person->nid;
          $team_members[$cp_nid]['function'] = check_plain($contact_person->field_cp_function_value);

          $params = array();
          // Add the field collection ID as a parameter so the Contact person
          // linked will have the correct address from the referenced company.
          if (isset($contact_person->field_paddle_cp_company_info_value)) {
            $params['referenced_organization'] = $contact_person->field_paddle_cp_company_info_value;
          }
          $team_members[$cp_nid]['title'] = l($contact_person->title, 'node/' . $cp_nid, array('query' => $params));
        }
      }
    }

    // Then we will retrieve the head of unit of the Organizational Unit.
    // We will have to check first if it exists already as a team member which
    // we already retrieved, if not we add it to the array. At the end we mark
    // the team member as a responsible person.
    if (!empty($conf['responsible']) && $conf['responsible']) {
      $node = node_load($nid);
      $ou_wrapper = entity_metadata_wrapper('node', $node);
      $responsible_person = $ou_wrapper->field_paddle_ou_cp_head_unit->value();

      if (!empty($responsible_person) && node_access('view', $responsible_person)) {
        $rp_nid = $responsible_person->nid;

        if (!array_key_exists($rp_nid, $team_members)) {
          $params = array();

          // Add the field collection ID as a parameter so the Contact person
          // linked will have the correct address from the referenced company.
          if (isset($responsible_person->field_paddle_cp_company_info[LANGUAGE_NONE][0]['value'])) {
            $params['referenced_organization'] = $responsible_person->field_paddle_cp_company_info[LANGUAGE_NONE][0]['value'];
          }

          $rp_wrapper = entity_metadata_wrapper('node', $responsible_person);
          $team_members[$rp_nid]['title'] = l($rp_wrapper->title->value(), 'node/' . $rp_nid, array('query' => $params));

          if (!empty($rp_wrapper->field_paddle_cp_company_info->value())) {
            $rp_organization = paddle_who_is_who_retrieve_organization_from_company_info_by_ou_id($rp_wrapper->field_paddle_cp_company_info, $nid);

            if (!empty($rp_organization->field_cp_function->value())) {
              $team_members[$rp_nid]['function'] = $rp_organization->field_cp_function->value();
            }
          }
        }
        $team_members[$rp_nid]['responsible'] = t('(responsible person)');

        // Move the responsible person to the front of the array.
        $team_members = array($rp_nid => $team_members[$rp_nid]) + $team_members;
      }
    }
    $variables['team_members'] = $team_members;
  }

  return theme('who_is_who_organization', $variables);
}

/**
 * Edit form callback for the content type.
 */
function paddle_who_is_who_who_is_who_content_type_edit_form($form, &$form_state) {
  $conf = $form_state['conf'];

  // Existing pane don't take into account pane defaults anymore.
  $plugin = $form_state['plugin'];
  $conf += $plugin['defaults'];

  $context_language = '';
  if (isset($form_state['contexts']['panelizer']->data->language)) {
    $context_language = $form_state['contexts']['panelizer']->data->language;
  }

  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'id' => drupal_html_id('pane-section-body'),
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  // Add an autocomplete field for selecting an organizational unit.
  $form['section_body']['node'] = array(
    '#type' => 'textfield',
    '#title' => t('Organizational unit'),
    '#required' => TRUE,
    '#default_value' => isset($conf['node']) ? $conf['node'] : '',
    '#size' => 60,
    '#autocomplete_path' => 'admin/paddle_panes/node_autocomplete_callback/' . $context_language . '/organizational_unit',
    '#attributes' => array('placeholder' => t('Search for page titles, or enter a node number directly: "node/123".')),
  );

  $form['section_body']['view_mode'] = array(
    '#type' => 'radios',
    '#title' => '',
    '#default_value' => $conf['view_mode'],
    '#options' => array(
      'team_members' => t('Team members'),
      'organization' => t('Organization'),
    ),
    '#ajax' => array(
      'callback' => 'paddle_who_is_who_who_is_who_content_type_edit_form_view_mode_ajax_callback',
      'wrapper' => $form['section_body']['#attributes']['id'],
    ),
  );

  $form['section_body']['children'] = array(
    '#type' => 'checkbox',
    '#title' => t('Include all underlying entities (as links)'),
    '#default_value' => isset($conf['children']) ? $conf['children'] : TRUE,
    '#states' => array(
      'visible' => array(
        ':input[name="view_mode"]' => array('value' => 'organization'),
      ),
    ),
  );

  $form['section_body']['responsible'] = array(
    '#type' => 'checkbox',
    '#title' => t('Responsible person'),
    '#default_value' => isset($conf['responsible']) ? $conf['responsible'] : TRUE,
    '#states' => array(
      'visible' => array(
        ':input[name="view_mode"]' => array('value' => 'organization'),
      ),
    ),
  );

  $form['section_body']['team_members'] = array(
    '#type' => 'checkbox',
    '#title' => t('All team members'),
    '#default_value' => isset($conf['team_members']) ? $conf['team_members'] : TRUE,
    '#states' => array(
      'visible' => array(
        ':input[name="view_mode"]' => array('value' => 'organization'),
      ),
    ),
  );

  // Include the form elements for the pane sections in the form.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);
  }

  form_load_include($form_state, 'inc', 'paddle_who_is_who', 'plugins/content_types/who_is_who');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_who_is_who_who_is_who_content_type_edit_form_validate($form, &$form_state) {
  $conf = $form_state['conf'];

  // Only nodes which exist are allowed to be selected.
  $node = FALSE;
  if (!empty($form_state['values']['node'])) {
    if (preg_match('/node\/(\d+)/', $form_state['values']['node'], $matches)) {
      $nid = $matches[1];
      $node = node_load($nid);
    }
  }

  if (empty($node) || $node->type != 'organizational_unit') {
    form_set_error('node', t('Please enter a valid Organizational unit ID.'));
  }

  // Allow the form elements provided by pane sections to be validated.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');

    // Sets the top section pane text and URL if none is given by the user.
    if ((!isset($form_state['values']['top']['enable section']) || $form_state['values']['top']['enable section'] === FALSE) && $conf['view_mode'] == "organization") {
      $form_state['values']['top']['enable_section'] = TRUE;
      $form_state['values']['top']['section_wrapper']['section_text'] = $node->title;
      $form_state['values']['top']['section_wrapper']['section_url_type'] = 'internal';
      $form_state['values']['top']['section_wrapper']['section_internal_url'] = 'node/' . $node->nid;
    }
    paddle_panes_pane_sections_validate($form, $form_state);
  }
}

/**
 * Submit callback for the configuration form.
 */
function paddle_who_is_who_who_is_who_content_type_edit_form_submit($form, &$form_state) {
  foreach (element_children($form['pane_settings']['section_body']) as $key) {
    $form_state['conf'][$key] = $form_state['values'][$key];
  }

  // Allow the form elements provided by pane sections to be saved.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_submit($form, $form_state);
  }
}

/**
 * Ajax callback to render the form after changing the selection type.
 */
function paddle_who_is_who_who_is_who_content_type_edit_form_view_mode_ajax_callback($form, &$form_state) {
  return $form['pane_settings']['section_body'];
}
