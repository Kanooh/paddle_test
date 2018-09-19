<?php

/**
 * @file
 * Administrative page callbacks for Paddle Taxonomy Manager module.
 */

/**
 * Page callback for the overview of the taxonomy vocabularies.
 */
function paddle_taxonomy_manager_overview() {
  $vocabularies = taxonomy_get_vocabularies();
  drupal_goto('admin/structure/taxonomy_manager/' . $vocabularies[min(array_keys($vocabularies))]->vid);
}

/**
 * Form for listing the terms of a vocabulary.
 *
 * This is based on taxonomy_overview_terms().
 * @see taxonomy_overview_terms()
 */
function paddle_taxonomy_manager_vocabulary_overview_form($form, &$form_state, $vid) {
  global $pager_page_array, $pager_total, $pager_total_items, $language_content;

  ctools_include('ajax');
  ctools_include('modal');
  ctools_modal_add_js();

  $vocabulary = taxonomy_vocabulary_load($vid);

  // The hierarchy value is not a reliable marker to tell if the vocabulary is
  // really hierarchical so we set it here.
  $flat_vocab = variable_get('taxonomy_vocabulary_' . $vocabulary->machine_name . '_type', 'hierarchical');
  $vocabulary->hierarchy = $flat_vocab == 'flat' ? PADDLE_TAXONOMY_MANAGER_HIERARCHY_DISABLED : PADDLE_TAXONOMY_MANAGER_HIERARCHY_SINGLE;
  if (module_exists('paddle_contextual_toolbar')) {
    $form['#after_build'] = array(
      'paddle_taxonomy_manager_add_contextual_actions_term_overview',
    );
  }
  // Check for confirmation forms.
  if (isset($form_state['confirm_reset_alphabetical'])) {
    return taxonomy_vocabulary_confirm_reset_alphabetical($form, $form_state, $vocabulary->vid);
  }

  $form['#vocabulary'] = $vocabulary;
  $form['#tree'] = TRUE;

  $page = isset($_GET['page']) ? $_GET['page'] : 0;
  // Number of terms per page.
  $page_increment = variable_get('taxonomy_terms_per_page_admin', 100);
  // Elements shown on this page.
  $page_entries = 0;
  // Elements at the root level before this page.
  $before_entries = 0;
  // Elements at the root level after this page.
  $after_entries = 0;
  // Elements at the root level on this page.
  $root_entries = 0;

  // Terms from previous and next pages are shown if the term tree would have
  // been cut in the middle. Keep track of how many extra terms we show on each
  // page of terms.
  $back_step    = NULL;
  $forward_step = 0;

  // An array of the terms to be displayed on this page.
  $current_page = array();

  $delta = 0;
  $term_deltas = array();
  $first_level_items = taxonomy_get_tree($vocabulary->vid, 0, 1);
  $form['#attached']['js'] = array(drupal_get_path('module', 'paddle_taxonomy_manager') . '/js/paddle_taxonomy_manager_big_vocabularies.js');

  // Filter the terms for the current language only.
  $tree = array();
  foreach ($first_level_items as $index => $term) {
    if ($term && $term->language == $language_content->language) {
      $tree[$index] = $term;
    }
  }
  $term = current($tree);
  do {
    // In case this tree is completely empty.
    if (empty($term)) {
      break;
    }
    $delta++;
    // Count entries before the current page.
    if ($page && ($page * $page_increment) > $before_entries && !isset($back_step)) {
      $before_entries++;
      continue;
    }
    // Count entries after the current page.
    elseif ($page_entries > $page_increment && isset($complete_tree)) {
      $after_entries++;
      continue;
    }

    // Do not let a term start the page that is not at the root.
    if (isset($term->depth) && ($term->depth > 0) && !isset($back_step)) {
      $back_step = 0;
      while ($pterm = prev($tree)) {
        $before_entries--;
        $back_step++;
        if ($pterm->depth == 0) {
          prev($tree);
          // Jump back to the start of the root level parent.
          continue 2;
        }
      }
    }
    $back_step = isset($back_step) ? $back_step : 0;

    // Continue rendering the tree until we reach the a new root item.
    if ($page_entries >= $page_increment + $back_step + 1 && $term->depth == 0 && $root_entries > 1) {
      $complete_tree = TRUE;
      // This new item at the root level is the first item on the next page.
      $after_entries++;
      continue;
    }
    if ($page_entries >= $page_increment + $back_step) {
      $forward_step++;
    }

    // Finally, if we've gotten down this far, we're rendering a term on this
    // page.
    $page_entries++;
    $term_deltas[$term->tid] = isset($term_deltas[$term->tid]) ? $term_deltas[$term->tid] + 1 : 0;
    $key = 'tid:' . $term->tid . ':' . $term_deltas[$term->tid];

    // Keep track of the first term displayed on this page.
    if ($page_entries == 1) {
      $form['#first_tid'] = $term->tid;
    }
    // Keep a variable to make sure at least 2 root elements are displayed.
    if ($term->parents[0] == 0) {
      $root_entries++;
    }
    $current_page[$key] = $term;
  } while ($term = next($tree));

  // Because we didn't use a pager query, set the necessary pager variables.
  $total_entries = $before_entries + $page_entries + $after_entries;
  $pager_total_items[0] = $total_entries;
  $pager_page_array[0] = $page;
  $pager_total[0] = ceil($total_entries / $page_increment);

  // If this form was already submitted once, it's probably hit a validation
  // error. Ensure the form is rebuilt in the same order as the user submitted.
  if (!empty($form_state['input'])) {
    // Get the $_POST order.
    $order = array_flip(array_keys($form_state['input']));
    // Update our form with the new order.
    $current_page = array_merge($order, $current_page);
    foreach ($current_page as $key => $term) {
      // Verify this is a term for the current page and set at the current
      // depth.
      if (isset($form_state['input'][$key]) && is_array($form_state['input'][$key]) && is_numeric($form_state['input'][$key]['tid'])) {
        $current_page[$key]->depth = $form_state['input'][$key]['depth'];
      }
      else {
        unset($current_page[$key]);
      }
    }
  }

  $form = array_merge($form, _paddle_taxonomy_manager_vocabulary_overview_form($current_page, $vocabulary));

  $form['#total_entries'] = $total_entries;
  $form['#page_increment'] = $page_increment;
  $form['#page_entries'] = $page_entries;
  $form['#back_step'] = $back_step;
  $form['#forward_step'] = $forward_step;
  $form['#empty_text'] = t('No terms available.');

  // Only provide a submit button for hierarchical vocabularies that can be
  // reordered. Flat vocabularies are alphabetical and cannot be reordered.
  if ($vocabulary->hierarchy == PADDLE_TAXONOMY_MANAGER_HIERARCHY_SINGLE) {
    $form['actions'] = array('#type' => 'actions', '#tree' => FALSE);
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save'),
    );
    $form_state['redirect'] = array($_GET['q'], (isset($_GET['page']) ? array('query' => array('page' => $_GET['page'])) : array()));
  }

  $form['#submit'][] = 'paddle_taxonomy_manager_vocabulary_overview_form_submit';

  return $form;
}

/**
 * Submit handler for the Paddle Taxonomy Manager overview form.
 *
 * This function takes great care in saving parent terms first, then terms
 * underneath them. Saving terms in the incorrect order can break the
 * vocabulary.
 */
function paddle_taxonomy_manager_vocabulary_overview_form_submit($form, &$form_state) {
  // Get the $_POST order.
  $order = array_flip(array_keys($form_state['input']));
  // Update our original form with the new order.
  $form = array_merge($order, $form);
  $updated_terms = array();
  $fields = array('weight', 'parent');
  foreach (element_children($form) as $tid) {
    if (isset($form[$tid]['#term'])) {
      $element = $form[$tid];
      // Update any fields that have changed in this term.
      foreach ($fields as $field) {
        if ($form_state['input'][$tid][$field] != $element[$field]['#default_value']) {
          $element['#term'][$field] = $form_state['input'][$tid][$field];
          // If the parent term itself has changed, make sure the original
          // parent term will be updated before the child term. This prevents
          // any changes to be overwritten by the original values of the
          // parent term.
          if ($field == 'parent') {
            $original_parent = "tid:{$element[$field]['#default_value']}:0";
            if (!array_key_exists($original_parent, $updated_terms) && array_key_exists($original_parent, $form)) {
              $updated_terms = array($original_parent => $form[$original_parent]['#term']) + $updated_terms;
            }
          }
          $updated_terms[$tid] = $element['#term'];
        }
      }

      // Hidden is a special case, the value needs to be reversed.
      if (isset($form_state['input'][$tid]['hidden']) && $form_state['input'][$tid]['hidden'] != $element['hidden']['#default_value']) {
        // Convert to integer rather than boolean due to PDO cast to string.
        $element['#term']['hidden'] = $form_state['input'][$tid]['hidden'] ? 0 : 1;
        $updated_terms[$tid] = $element['#term'];
      }
    }
  }

  // Save all updated terms.
  foreach ($updated_terms as $term) {
    $term = (object) $term;
    // Update term_hierachy and term_data directly since we don't have a
    // fully populated term object to save.
    db_update('taxonomy_term_hierarchy')
      ->fields(array('parent' => $term->parent))
      ->condition('tid', $term->tid, '=')
      ->execute();

    db_update('taxonomy_term_data')
      ->fields(array('weight' => $term->weight))
      ->condition('tid', $term->tid, '=')
      ->execute();
  }

  // Be sure to reset taxonomy term cache.
  entity_get_controller('taxonomy_term')->resetCache();

  drupal_set_message(t('Your configuration has been saved.'));
}

/**
 * Returns HTML for a terms overview form as a sortable list of terms.
 *
 * @param array $variables
 *   An associative array containing:
 *   - form: A render element representing the form.
 *
 * @see taxonomy_overview_terms()
 * @ingroup themeable
 */
function theme_paddle_taxonomy_manager_vocabulary_overview_form($variables) {
  $form = $variables['form'];
  $vocabulary = $form['#vocabulary'];

  $page_increment = $form['#page_increment'];
  $page_entries = $form['#page_entries'];
  $back_step = $form['#back_step'];
  $forward_step = $form['#forward_step'];
  // Add drag and drop if parent fields are present in the form.
  if ($vocabulary->hierarchy != PADDLE_TAXONOMY_MANAGER_HIERARCHY_DISABLED) {
    drupal_add_tabledrag('taxonomy', 'match', 'parent', 'term-parent', 'term-parent', 'term-id', FALSE);
    drupal_add_tabledrag('taxonomy', 'depth', 'group', 'term-depth', NULL, NULL, FALSE);
    drupal_add_js(drupal_get_path('module', 'taxonomy') . '/taxonomy.js');
    drupal_add_js(array('taxonomy' => array('backStep' => $back_step, 'forwardStep' => $forward_step)), 'setting');
    drupal_add_css(drupal_get_path('module', 'taxonomy') . '/taxonomy.css');
    drupal_add_tabledrag('taxonomy', 'order', 'sibling', 'term-weight');
  }

  $errors = form_get_errors() != FALSE ? form_get_errors() : array();
  $rows = array();
  foreach (element_children($form) as $key) {
    if (isset($form[$key]['#term'])) {
      $term = &$form[$key];

      $row = array();
      $term['#term']['depth'] = count(taxonomy_get_parents_all($term['#term']['tid'])) - 1;
      $row[] = theme('indentation', array('size' => $term['#term']['depth'])) . drupal_render($term['expandable']) . $term['#term']['name'];
      if ($vocabulary->hierarchy != PADDLE_TAXONOMY_MANAGER_HIERARCHY_DISABLED) {
        $term['tid']['#attributes']['class'] = array('term-id');
        $term['parent']['#attributes']['class'] = array('term-parent');
        $term['depth']['#attributes']['class'] = array('term-depth');
        $row[0] .= drupal_render($term['parent']) . drupal_render($term['tid']) . drupal_render($term['depth']);
        $term['weight']['#attributes']['class'] = array('term-weight');
        $row[] = drupal_render($term['weight']);
      }
      $row[] = drupal_render($term['edit']);
      $row[] = drupal_render($term['delete']);
      $row = array('data' => $row, 'data-term-id' => $term['#term']['tid']);
      $rows[$key] = $row;
    }
  }

  // Add necessary classes to rows.
  $row_position = 0;
  foreach ($rows as $key => $row) {
    $rows[$key]['class'] = array();
    if ($vocabulary->hierarchy != PADDLE_TAXONOMY_MANAGER_HIERARCHY_DISABLED) {
      $rows[$key]['class'][] = 'draggable';
    }

    // Add classes that mark which terms belong to previous and next pages.
    if ($row_position < $back_step || $row_position >= $page_entries - $forward_step) {
      $rows[$key]['class'][] = 'taxonomy-term-preview';
    }

    if ($row_position !== 0 && $row_position !== count($rows) - 1) {
      if ($row_position == $back_step - 1 || $row_position == $page_entries - $forward_step - 1) {
        $rows[$key]['class'][] = 'taxonomy-term-divider-top';
      }
      elseif ($row_position == $back_step || $row_position == $page_entries - $forward_step) {
        $rows[$key]['class'][] = 'taxonomy-term-divider-bottom';
      }
    }

    // Add an error class if this row contains a form error.
    foreach ($errors as $error_key => $error) {
      if (strpos($error_key, $key) === 0) {
        $rows[$key]['class'][] = 'error';
      }
    }
    $row_position++;
  }

  $header = array(
    array('data' => t('Name'), 'class' => array('title')),
    array('data' => t('Weight'), 'class' => array('row-weight')),
    array(
      'data' => t('Operations'),
      'colspan' => '2',
      'class' => array('actions'),
    ),
  );
  // We remove the "Weight" column if the vocabulary is flat.
  if ($vocabulary->hierarchy == PADDLE_TAXONOMY_MANAGER_HIERARCHY_DISABLED) {
    unset($header[1]);
  }
  $output = theme('table', array(
    'header' => $header,
    'rows' => $rows,
    'attributes' => array('id' => 'taxonomy'),
    'empty' => $form['#empty_text'],
  ));

  // Use a copy of the form where the terms are removed - Drupal will render
  // them and they have already being rendered in the table.
  $render_form = $form;
  foreach (element_children($render_form) as $key) {
    if (isset($render_form[$key]['#term'])) {
      unset($render_form[$key]);
    }
  }
  $output .= drupal_render_children($render_form);
  $output .= theme('pager');

  return $output;
}

/**
 * Recursive helper function for vocabulary_overview_form().
 *
 * @param array $tree
 *   The vocabulary_tree retrieved by vocabulary_get_tree.
 * @param object $vocabulary
 *   The vocabulary for which we are on the overview page.
 */
function _paddle_taxonomy_manager_vocabulary_overview_form($tree, $vocabulary, $always_add_weights = FALSE) {
  ctools_include('ajax');
  ctools_include('modal');
  ctools_modal_add_js();
  $form = &drupal_static(__FUNCTION__, array('#tree' => TRUE));

  // Build the actual form.
  foreach ($tree as $key => $term) {
    // Save the term for the current page so we don't have to load it a second
    // time.
    $form[$key]['#term'] = (array) $term;

    // Add the expanding link for big terms if the term has children.
    $form[$key]['expandable']['#markup'] = '';
    if (taxonomy_get_children($form[$key]['#term']['tid'])) {
      // HTML entity for a squared plus sign.
      $text = '&#8862;';
      $path = 'admin/structure/taxonomy_manager/get_subterm/' . $vocabulary->vid . '/' . $form[$key]['#term']['tid'];
      $options = array(
        'attributes' => array(
          'class' => 'paddle-big-vocabulary-expandable',
          'rel' => $form[$key]['#term']['tid'],
        ),
        'html' => TRUE,
      );
      $form[$key]['expandable']['#markup'] = l($text, $path, $options);
    }

    if (isset($term->parents)) {
      $form[$key]['#term']['parent'] = $term->parent = $term->parents[0];
      unset($form[$key]['#term']['parents'], $term->parents);
    }

    $form[$key]['view'] = array(
      '#markup' => check_plain($term->name),
    );
    // Add weights and parents for hierarchical vocabularies.
    if ($vocabulary->hierarchy != PADDLE_TAXONOMY_MANAGER_HIERARCHY_DISABLED) {
      $form[$key]['tid'] = array(
        '#type' => 'hidden',
        '#value' => $term->tid,
      );
      $form[$key]['parent'] = array(
        '#type' => 'hidden',
        // Yes, default_value on a hidden. It needs to be changeable by the
        // javascript.
        '#default_value' => $term->parent,
      );
      $form[$key]['depth'] = array(
        '#type' => 'hidden',
        // Same as above, the depth is modified by javascript, so it's a
        // default_value.
        '#default_value' => $term->depth,
      );
      $form[$key]['weight'] = array(
        '#type' => 'weight',
        '#title_display' => 'invisible',
        '#title' => t('Weight for added term'),
        '#default_value' => $term->weight,
      );
    }

    $form[$key]['edit'] = array(
      '#markup' => ctools_modal_text_button(t('Edit'), 'admin/structure/taxonomy_manager/nojs/' . $vocabulary->machine_name . '/' . $term->tid . '/edit', t('Edit this taxonomy term.'), 'ui-icon ui-icon-edit ui-icon-hiddentext ctools-modal-overlay-persist'),
    );
    $form[$key]['delete'] = array(
      '#markup' => ctools_modal_text_button(t('Delete'), 'admin/structure/taxonomy_manager/nojs/' . $vocabulary->vid . '/' . $term->tid . '/delete', t('Delete this taxonomy term.'), 'ui-icon ui-icon-delete ui-icon-hiddentext'),
    );
  }

  return $form;
}

/**
 * AJAX callback generating > L1 sub-terms.
 *
 * @param array $vocabulary
 *   The entire vocabulary object which will be used.
 * @param int $parent
 *   The tid of the parent of the terms which should be returned.
 * @param string $form_id
 *   The id of the form which we are editing.
 * @param string $form_build_id
 *   The build id of the form which we are editing.
 *
 * Prints the TRs of the sub-term. The function will cache the form in order to
 * get the already generated terms from previous calls.
 */
function paddle_taxonomy_manager_get_subterm($vocabulary, $parent, $form_id, $form_build_id) {
  // The hierarchy value is not a reliable marker to tell if the vocabulary is
  // really hierarchical so we set it here.
  $vocabulary_type = variable_get('taxonomy_vocabulary_' . $vocabulary->machine_name . '_type', 'hierarchical');
  $vocabulary->hierarchy = $vocabulary_type == 'flat' ? PADDLE_TAXONOMY_MANAGER_HIERARCHY_DISABLED : PADDLE_TAXONOMY_MANAGER_HIERARCHY_SINGLE;
  $raw_tree = taxonomy_get_tree($vocabulary->vid, $parent->tid, 1);

  // Change the keys to be consistent with what we have as keys by default.
  $tree = array();
  $term_deltas = array();
  foreach ($raw_tree as $key => $term) {
    $term_deltas[$term->tid] = isset($term_deltas[$term->tid]) ? $term_deltas[$term->tid] + 1 : 0;
    $key = 'tid:' . $term->tid . ':' . $term_deltas[$term->tid];
    $tree[$key] = $term;
  }

  // Set up an initial form state. It will be amended by drupal_rebuild_form().
  $form_state = form_state_defaults();
  $form_state['build_info']['args'] = array($vocabulary->vid);
  $form_state['values'] = array();
  $form_state['rebuild_info']['copy']['#build_id'] = $form_build_id;

  // If the form is in the cache take it, otherwise rebuild it. If it is cached
  // it means we have already generated it in previous calls and it contains the
  // terms already fetched with AJAX.
  $form = cache_get('form_' . $form_build_id, 'cache_form') ? form_get_cache($form_build_id, $form_state) : drupal_rebuild_form($form_id, $form_state, array('#build_id' => $form_build_id));

  // We are working with the entire form hierarchy behind the scenes, so that if
  // the form is submitted, all terms are going to be processed instead of only
  // the ones that were loaded with AJAX.
  // We will make a copy of the form, and remove all original terms from it
  // before sending it to the browser, so that only the subtree that is
  // requested by AJAX is going to be returned.
  // Compile a list of all terms in the form so we can remove them from the
  // customized form that will be sent to the browser.
  $old_tids = array();
  foreach (element_children($form) as $tid) {
    if (isset($form[$tid]['#term'])) {
      $old_tids[$form[$tid]['#term']['tid']] = $tid;
    }
  }

  $form = array_merge($form, _paddle_taxonomy_manager_vocabulary_overview_form($tree, $vocabulary, TRUE));
  drupal_prepare_form($form_id, $form, $form_state);
  form_builder($form_id, $form, $form_state);

  // Cache the form.
  form_set_cache($form_build_id, $form, $form_state);

  // Remove the automatically generated terms for the rendered form.
  $render_form = $form;
  foreach ($old_tids as $tid => $form_element_id) {
    unset($render_form[$form_element_id]);
  }
  $output = drupal_render($render_form);

  // Filter the HTML - we need the TRs only.
  $matches = array();
  $pattern = '/<tbody>(.*?)<\/tbody>/si';
  preg_match($pattern, $output, $matches);
  if ($matches[1]) {
    echo $matches[1];
  }

  die();
}
