<?php

/**
 * @file
 * CTools content type plugin to embed listings (based on Views) in panes.
 */

$plugin = array(
  'title' => t('Add listing'),
  'no title override' => TRUE,
  'description' => t('Add listing.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Paddle Landing Page'), -20),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_panes_listing_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $output = '';

  // Load the view.
  if ($view = views_get_view('paddle_panes_listing')) {
    // Set the display.
    $view->set_display('default');

    // The landing page we're on, should not be shown in the listings.
    $filter = $view->get_item($view->current_display, 'filter', 'nid');
    if (is_array($args) && !empty($args[0])) {
      $filter['value']['value'] = $args[0];
    }
    elseif (empty($args)) {
      $menu_item = menu_get_item();

      if ($menu_item['path'] == 'panels/ajax') {
        $parts = explode(':', $menu_item['page_arguments'][2]);
        $filter['value']['value'] = !empty($parts[2]) ? $parts[2] : NULL;
      }
    }

    if (!empty($filter['value']['value'])) {
      $view->set_item($view->current_display, 'filter', 'nid', $filter);
    }

    // Check if the "Terms" filter is used.
    if (!empty($conf['terms'])) {
      foreach ($conf['terms'] as $voc_name => $vocabulary) {
        $selected_terms = array();
        foreach ($vocabulary as $term) {
          $selected_terms[] = $term['tid'];
        }
        if (!empty($selected_terms)) {
          // Use the 'tid' filter as a prototype for all vocabulary filters.
          $filter = $view->get_item($view->current_display, 'filter', 'tid');
          $filter['value'] = drupal_map_assoc($selected_terms);
          $view->set_item($view->current_display, 'filter', $voc_name, $filter);
        }
      }
    }
    // Remove the prototype "Terms" filter.
    $view->set_item($view->current_display, 'filter', 'tid', NULL);

    $filter = $view->get_item($view->current_display, 'filter', 'type');
    // Remove the filter by default. If no items selected, shows all.
    $view->set_item($view->current_display, 'filter', 'type', NULL);
    // Check if the "Content types" filter is used.
    if (!empty($conf['content_types'])) {
      // Array keys are used: array('basic_page' => TRUE, 'news' => FALSE).
      $selected_content_types = array_keys(array_filter($conf['content_types']));
      if (count(array_keys(array_filter($conf['content_types'])))) {
        $filter['value'] = drupal_map_assoc($selected_content_types);
        $view->set_item($view->current_display, 'filter', 'type', $filter);
      }
    }

    // Respect the current node's language.
    if (is_array($args) && !empty($args[0]) && $node = node_load($args[0])) {
      $filter = $view->get_item($view->current_display, 'filter', 'language');
      $filter['value'] = array($node->language => $node->language);
      $view->set_item($view->current_display, 'filter', 'language', $filter);
    }

    if (module_exists('paddle_cirro') && !empty($conf['cirro_type'])) {
      // Rebuild the filter as it would have been exported in the view.
      $filter = array(
        'id' => 'field_paddle_cirro_methodology_value',
        'table' => 'field_data_field_paddle_cirro_methodology',
        'field' => 'field_paddle_cirro_methodology_value',
        'relationship' => 'none',
        'group_type' => 'group',
        'ui_name' => '',
        'operator' => 'or',
        'group' => '1',
        'exposed' => FALSE,
        'expose' =>
          array(
            'operator_id' => FALSE,
            'label' => '',
            'description' => '',
            'use_operator' => FALSE,
            'operator_label' => '',
            'operator' => '',
            'identifier' => '',
            'required' => FALSE,
            'remember' => FALSE,
            'multiple' => FALSE,
            'remember_roles' =>
              array(
                2 => 2,
              ),
            'reduce' => FALSE,
          ),
        'is_grouped' => FALSE,
        'group_info' =>
          array(
            'label' => '',
            'description' => '',
            'identifier' => '',
            'optional' => TRUE,
            'widget' => 'select',
            'multiple' => FALSE,
            'remember' => 0,
            'default_group' => 'All',
            'default_group_multiple' =>
              array(),
            'group_items' =>
              array(),
          ),
        'reduce_duplicates' => 0,
      );

      $value = array();

      if ($conf['cirro_type']['methodology'] === 'methodology') {
        $value['1'] = '1';
        if ($conf['cirro_type']['assistance_tool'] === 'assistance_tool') {
          $value['0'] = '0';
        }
      }

      $filter['value'] = $value;
      $view->set_item($view->current_display, 'filter', $filter['id'], $filter);
    }

    // Set the view mode.
    if (!empty($conf['view_mode'])) {
      $view->display_handler->options['row_options']['view_mode'] = $conf['view_mode'];
      // Render the 'listing_title' view mode as an unordered list. The other
      // view modes can be rendered in the default 'unformatted list'.
      if ($conf['view_mode'] == 'listing_title') {
        $view->display_handler->options['style_plugin'] = 'list';
      }
      // Add a CSS class indicating the view mode that is being used.
      $view->display_handler->options['css_class'] = 'paddle-landing-page-' . $conf['view_mode'];
    }

    // Set the number of items to display.
    $number_items = !empty($conf['number_items']) ? $conf['number_items'] : variable_get('pane_listing_number_items', 5);
    $view->set_items_per_page($number_items);

    // Set the sorting.
    if (!empty($conf['sorting_type'])) {
      // This is in the format {sorting_type}_{sorting_order}, eg. 'title_desc'.
      list($sorting_type, $sorting_order) = explode('_', $conf['sorting_type']);
      // We support three sorts: by title, publication date, and creation date.
      if (in_array($sorting_type, array('created', 'stamp', 'title', 'value'))) {
        // If Workbench Moderation is not enabled, fall back to sorting on node
        // creation date rather than publication date (aka 'stamp').
        $sorting_type = $sorting_type == 'stamp' && !module_exists('workbench_moderation') ? 'created' : $sorting_type;
        $view->display_handler->options['sorts'] = array(
          $sorting_type => array(
            'field' => $sorting_type,
            'id' => $sorting_type,
            'order' => strtoupper($sorting_order),
          ),
        );

        switch ($sorting_type) {
          case 'stamp':
            $table = module_exists('workbench_moderation') ? 'workbench_moderation_node_history' : 'node';
            break;

          case 'value':
            $table = 'votingapi_cache';
            $view->display_handler->options['sorts'][$sorting_type]['relationship'] = 'votingapi_cache_node_relationship';
            $view->display_handler->options['sorts'][$sorting_type]['coalesce'] = 1;
            $view->display_handler->options['relationships']['votingapi_cache_node_relationship']['id'] = 'votingapi_cache_node_relationship';
            $view->display_handler->options['relationships']['votingapi_cache_node_relationship']['table'] = 'node';
            $view->display_handler->options['relationships']['votingapi_cache_node_relationship']['field'] = 'votingapi_cache';
            $view->display_handler->options['relationships']['votingapi_cache_node_relationship']['votingapi'] = array(
              'value_type' => 'percent',
              'tag' => 'vote',
              'function' => 'average',
            );
            break;

          default:
            $table = 'node';
        }
        $view->display_handler->options['sorts'][$sorting_type]['table'] = $table;
      }
    }

    // Run the query.
    $view->pre_execute();
    $view->execute();

    // Render the view.
    $output = $view->render();

    // If the view has no results completely avoid output because Views will
    // still generate output.
    if (empty($view->result)) {
      $output = '';
    }

    // Allow the pane sections to contribute output.
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  $block->content = $output;
  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_panes_listing_content_type_edit_form($form, &$form_state) {
  $conf = $form_state['conf'];

  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  $form['section_body']['filters'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -5,
    '#title' => t('Filters'),
  );

  // Add a "Tags" selection widget for every available vocabulary.
  // @see taxonomy_field_widget_form()
  $form['section_body']['filters']['terms'] = array('#tree' => TRUE);
  foreach (taxonomy_vocabulary_load_multiple(FALSE) as $vocabulary) {
    $terms = array();
    if (isset($conf['terms'][$vocabulary->machine_name]) && is_array($conf['terms'][$vocabulary->machine_name])) {
      foreach ($conf['terms'][$vocabulary->machine_name] as $item) {
        $terms[$item['tid']] = isset($item['taxonomy_term']) ? $item['taxonomy_term'] : taxonomy_term_load($item['tid']);
      }
    }

    $context_node_id = 0;
    if (!is_null($form_state['contexts'])) {
      $context_node_id = $form_state['contexts']['panelizer']->data->nid;
    }
    $form['section_body']['filters']['terms'][$vocabulary->machine_name] = array(
      '#type' => 'textfield',
      '#title' => t('Filter by %vocabulary tags', array('%vocabulary' => $vocabulary->name)),
      '#description' => t('Use commas to separate multiple tags.'),
      '#default_value' => taxonomy_implode_tags($terms),
      '#autocomplete_path' => 'paddle_panes/taxonomy/autocomplete/' . $context_node_id . '/' . $vocabulary->vid,
      '#size' => 60,
      '#maxlength' => 1024,
      '#element_validate' => array('paddle_panes_taxonomy_autocomplete_validate'),
    );
  }

  // Only show moderateable node types if Workbench Moderation is enabled.
  if (module_exists('workbench_moderation')) {
    $options = array_intersect_key(node_type_get_names(), drupal_map_assoc(workbench_moderation_moderate_node_types()));
  }
  else {
    $options = node_type_get_names();
  }
  $form['section_body']['filters']['content_types'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Filter by content types'),
    '#description' => t('Show only content from the selected content types. If nothing is selected content from all types will be shown.'),
    '#options' => $options,
    '#default_value' => isset($conf['content_types']) ? $conf['content_types'] : array(),
  );

  $form['section_body']['display_settings'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => 0,
    '#title' => t('Display'),
  );

  $form['section_body']['display_settings']['view_mode'] = array(
    '#type' => 'container',
  );

  $view_mode_default_value = isset($conf['view_mode']) ? $conf['view_mode'] : 'listing_title';
  $form['section_body']['display_settings']['view_mode']['listing_title'] = array(
    '#type' => 'radio',
    '#title' => t('Titles'),
    '#return_value' => 'listing_title',
    '#parents' => array('view_mode'),
    '#default_value' => $view_mode_default_value,
    '#attributes' => array(
      'name' => 'view_mode',
    ),
  );

  // Add a tip for the "Teasers" radio button.
  $tip = theme('html_tag', array(
    'element' => array(
      '#tag' => 'span',
      '#attributes' => array(
        'class' => 'inline-tip',
      ),
      '#value' => ' (' . t("Featured image, name and body summary or first paragraph of the body if it doesn't exist") . ')',
    ),
  ));
  $form['section_body']['display_settings']['view_mode']['listing_teaser'] = array(
    '#type' => 'radio',
    '#title' => t('Teasers'),
    '#return_value' => 'listing_teaser',
    '#parents' => array('view_mode'),
    '#default_value' => $view_mode_default_value,
    '#attributes' => array(
      'name' => 'view_mode',
    ),
    '#suffix' => $tip,
  );

  $tip = theme('html_tag', array(
    'element' => array(
      '#tag' => 'span',
      '#attributes' => array(
        'class' => 'inline-tip',
      ),
      '#value' => ' (' . t('Spotlight representation of the list items') . ')',
    ),
  ));

  $form['section_body']['display_settings']['view_mode']['spotlight'] = array(
    '#type' => 'radio',
    '#title' => t('Spotlight view'),
    '#return_value' => 'spotlight',
    '#parents' => array('view_mode'),
    '#default_value' => $view_mode_default_value,
    '#attributes' => array(
      'name' => 'view_mode',
    ),
    '#suffix' => $tip,
  );

  $form['section_body']['display_settings']['number_items'] = array(
    '#type' => 'textfield',
    '#required' => TRUE,
    '#title' => t('Number of items to display') . ':',
    '#default_value' => isset($conf['number_items']) ? $conf['number_items'] : variable_get('pane_listing_number_items', 5),
  );

  $form['section_body']['sorting'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => 5,
    '#title' => t('Sorting'),
  );

  $sorting_options = array();
  if (module_exists('workbench_moderation')) {
    $sorting_options += array(
      'stamp_desc' => t('Chronological by latest publication date - newest first'),
      'stamp_asc' => t('Chronological by latest publication date - oldest first'),
    );
  }

  $sorting_options += array(
    'created_desc' => t('Chronological by creation date - newest first'),
    'created_asc' => t('Chronological by creation date - oldest first'),
    'title_asc' => t('Alphabetical - (a-z)'),
    'title_desc' => t('Alphabetical - (z-a)'),
  );

  if (module_exists('paddle_rate')) {
    $sorting_options += array(
      'value_desc' => t('By rating - highest first'),
      'value_asc' => t('By rating - lowest first'),
    );
  }

  $form['section_body']['sorting']['sorting_type'] = array(
    '#type' => 'radios',
    '#title' => '',
    '#options' => $sorting_options,
    '#default_value' => isset($conf['sorting_type']) ? $conf['sorting_type'] : (module_exists('workbench_moderation') ? 'stamp_desc' : 'created_desc'),
  );

  // Include the form elements for the pane sections in the form.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_build_edit_form($form, $form_state);

  form_load_include($form_state, 'inc', 'paddle_panes', 'plugins/content_types/listing');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_panes_listing_content_type_edit_form_validate($form, &$form_state) {
  $values = $form_state['values'];
  // Check that the "Number of items" field is a number bigger than zero.
  if (!filter_var($values['number_items'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)))) {
    form_set_error('number_items', t('The number of items must be a valid number bigger than zero.'));
  }

  // Allow the form elements provided by pane sections to be validated.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_validate($form, $form_state);
}

/**
 * Submit callback for the configuration form.
 */
function paddle_panes_listing_content_type_edit_form_submit($form, &$form_state) {
  // Store all values in the pane configuration.
  foreach (element_children($form['pane_settings']['section_body']) as $fieldset) {
    foreach (element_children($form['pane_settings']['section_body'][$fieldset]) as $element) {
      if (isset($form_state['values'][$element])) {
        $form_state['conf'][$element] = $form_state['values'][$element];
      }
    }
  }

  // Store only the term ids, not the entire term data array.
  if (!empty($form_state['conf']['terms'])) {
    foreach ($form_state['conf']['terms'] as $vocabulary_name => $terms) {
      foreach ($terms as $key => $term) {
        $form_state['conf']['terms'][$vocabulary_name][$key] = array('tid' => $term['tid']);
      }
    }
  }

  // Allow the form elements provided by pane sections to be saved.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_submit($form, $form_state);
}
