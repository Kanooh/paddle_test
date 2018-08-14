<?php
/**
 * @file
 * template.php
 */

/**
 * Override or insert variables into the head of page
 */
global $_base_theme_path;
$_base_theme_path = drupal_get_path('theme', 'paddle_admin_theme');

// Hook preprocess.
include_once $_base_theme_path . '/inc/preprocess.inc';
// Hook_alters.
include_once $_base_theme_path . '/inc/alter.inc';
// All process functions.
include_once $_base_theme_path . '/inc/process.inc';
// Theme function overrides.
include_once $_base_theme_path . '/inc/theme.inc';

/**
 * Get the appropriate Ctools modal JavaScript settings.
 */
function paddle_admin_theme_ctools_modal_settings() {
  $base_theme_path = drupal_get_path('theme', 'paddle_admin_theme');

  $settings = array(
    'wide-modal' => array(
      'loadingText' => t('...'),
      'closeText' => '',
      'closeImage' => theme('image', array(
          'path' => $base_theme_path . '/images/icon-close-window.png',
          'title' => t('Close window'),
          'alt' => t('Close window'),
        )),
      'modalSize' => array(
        'type' => 'large',
      ),
      'id' => 'wide-modal',
    ),
    'medium-modal' => array(
      'loadingText' => t('...'),
      'closeText' => '',
      'closeImage' => theme('image', array(
        'path' => $base_theme_path . '/images/icon-close-window.png',
        'title' => t('Close window'),
        'alt' => t('Close window'),
      )),
      'modalSize' => array(
        'type' => 'medium',
      ),
      'id' => 'medium-modal',
    ),
    'CToolsModal' => array(
      'loadingText' => t('...'),
      'closeText' => '',
      'closeImage' => theme('image', array(
          'path' => $base_theme_path . '/images/icon-close-window.png',
          'title' => t('Close window'),
          'alt' => t('Close window'),
        )),
      'throbber' => theme('image', array(
          'path' => ctools_image_path('throbber.gif'),
          'title' => t('Loading...'),
          'alt' => t('Loading'),
        )),
      'modalSize' => array(
        'type' => 'standard',
        'width' => 570,
        'height' => .8,
      ),
    ),
  );

  return $settings;
}

/**
 * Removing the panel-separator empty div.
 */
function paddle_admin_theme_panels_default_style_render_region($vars) {
  $output = '';
  $output .= implode('', $vars['panes']);
  return $output;
}

/**
 * Implements theme_content_manager_moderation_history().
 */
function paddle_admin_theme_paddle_content_manager_moderation_history(&$variables) {
  $items = $variables['items'];
  $total_item_count = $variables['total_item_count'];
  $node = $variables['node'];
  $return = '';

  foreach ($items as $item) {
    $author = user_load($item->uid);
    $args = array(
      '@date' => date('d/m/Y H:i', $item->stamp),
      '!state' => '<br /><span class="history-link">' . workbench_moderation_state_label($item->state) . '</span>',
      '!author' => l($author->name, 'user/' . $author->uid),
    );
    $item_list[] = t('@date !state  by !author', $args);
  }

  if (!empty($item_list)) {
    $return = theme('item_list', array('items' => $item_list));

    // Show a 'See more' link if there are more than 5 revisions.
    if ($total_item_count > count($items)) {
      $link = array(
        '#markup' => l(t('See more'), 'node/' . $node->nid . '/moderation'),
        '#prefix' => '<div class="node-moderation-history">',
        '#suffix' => '</div>',
      );
      $return .= render($link);
    }
  }
  // Make sure it exists to reduce NOTICE.
  return $return;
}

/**
 * Implements hook_theme_file().
 *
 * Adds wrapper to input file.
 */
function paddle_admin_theme_file($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'file';
  element_set_attributes($element, array('id', 'name', 'size'));
  _form_set_class($element, array('form-file'));

  return '<div class="input-file-wrapper withButton"><input' . drupal_attributes($element['#attributes']) . ' /></div>';
}
/**
 * Implements hook_theme_select().
 *
 * Adds wrapper to form select.
 */
function paddle_admin_theme_select($variables) {
  $element = $variables['element'];
  element_set_attributes($element, array('id', 'name', 'size'));
  _form_set_class($element, array('form-select'));

  return '<div class="select-wrapper"><select' . drupal_attributes($element['#attributes']) . '>' . form_select_options($element) . '</select></div>';
}

/**
 * Implements hook_panels_ipe_pane_links_alter().
 *
 * Adds the following classes to the action links when editing panes:
 * - 'ui-icon'
 * - 'ui-icon-hiddentext'
 * - 'ui-icon-landing'
 * - 'ui-icon-landing-hiddentext'
 * - 'ui-icon-{action}'
 */
function paddle_admin_theme_panels_ipe_pane_links_alter(&$links, $context) {
  global $language_content;

  foreach ($links as $key => $link) {
    $classes = !empty($link['attributes']['class']) ? (array) $link['attributes']['class'] : array();
    $icon_class = $link['attributes']['icon-class'];
    $title = $link['attributes']['title'];

    // If the language is not english we need to translate the title string back
    // to English because the CSS has only been written on the english classes.
    if ($language_content->language != 'en') {
      $query = db_query("SELECT s.source FROM locales_source s INNER JOIN locales_target t ON s.lid = t.lid WHERE t.translation = '$title'");
      $result = $query->fetchAll();
      if (!empty($result)) {
        $title = $result[0]->source;
      }
    }

    $new_classes = array(
      'ui-icon',
      'ui-icon-hiddentext',
      'ui-icon-landing',
      'ui-icon-landing-hiddentext',
      drupal_clean_css_identifier(strtolower('ui-icon-' . $icon_class)),
    );
    $links[$key]['attributes']['class'] = array_unique(array_merge($classes, $new_classes));
  }
}

/**
 * Implements hook_panels_ipe_region_links_alter().
 *
 * Adds the following classes to the action links when adding panes to regions:
 * - 'ui-icon'
 * - 'ui-icon-hiddentext'
 * - 'ui-icon-landing'
 * - 'ui-icon-landing-hiddentext'
 * - 'ui-icon-{action}'
 */
function paddle_admin_theme_panels_ipe_region_links_alter(&$links, $context) {
  // Define actions that need to be renamed, and specific classes that should be
  // added.
  $mappings = array(
    // Rename the 'add-pane' action to 'add', and open in a wide modal.
    'add-pane' => array(
      'action' => 'add',
      'classes' => array(
        'ctools-modal-wide-modal',
      ),
    ),
  );

  foreach ($links as $key => $link) {
    // Rename actions if needed.
    $action = array_key_exists($key, $mappings) && !empty($mappings[$key]['action']) ? $mappings[$key]['action'] : $key;

    // Collect specific classes if they exist.
    $specific_classes = array_key_exists($key, $mappings) && !empty($mappings[$key]['classes']) ? $mappings[$key]['classes'] : array();

    // Merge our new classes with the existing and specific ones.
    $classes = !empty($link['attributes']['class']) ? (array) $link['attributes']['class'] : array();
    $new_classes = array(
      'ui-icon',
      'ui-icon-hiddentext',
      'ui-icon-landing',
      'ui-icon-landing-hiddentext',
      drupal_clean_css_identifier(strtolower('ui-icon-' . $action)),
    );
    $links[$key]['attributes']['class'] = array_unique(array_merge($classes, $new_classes, $specific_classes));
  }
}

/**
 * Implements hook_form_element().
 *
 * When we change the id of an form element with a label Drupal sets the wrong
 * "for" attribute of the label. See https://drupal.org/node/1679284.
 */
function paddle_admin_theme_form_element($variables) {
  $affected_types = array('checkbox', 'textfield');
  if (!empty($variables['element']['#type']) && in_array($variables['element']['#type'], $affected_types)) {
    if (isset($variables['element']['#attributes']['id']) && ($variables['element']['#attributes']['id'] != $variables['element']['#id'])) {
      $variables['element']['#id'] = $variables['element']['#attributes']['id'];
    }
  }

  // Adding a wrapper div with col-md-6 class for layout purposes.
  if (!empty($variables['element']['#name']) && in_array($variables['element']['#name'], array(
    'path[alias]',
    'path[pathauto]',
    'unpublish_on',
    'publish_on',
  ))) {
    $original = theme_form_element($variables);
    return '<div class="col-md-6">' . $original . '</div>';
  }
  return theme_form_element($variables);
}

/**
 * Implements hook_paddle_contextual_toolbar_actions_alter().
 */
function paddle_admin_theme_paddle_contextual_toolbar_actions_alter(&$actions) {
  // Inject spans in various Contextual Toolbar buttons to hide the action verb
  // in an accessible way. People without impaired vision will see an icon
  // representing the action.
  // These spans can optionally be disabled by setting a variable. This is
  // mainly intended to help building Selenium tests and Walkthroughs.
  //
  // @todo: Evaluate this code because it is totally useless...
  // The variable paddle_admin_theme_disable_link_spans is never set to TRUE.
  // Also it seems with its current naming, it should actually evaluate to FALSE
  // because now it is adding spans.
  if (variable_get('paddle_admin_theme_disable_link_spans', FALSE) && !empty($actions)) {
    foreach ($actions as $key => &$action) {
      if (is_string($key)) {
        switch ($key) {
          case 'paddle_themer_create_theme':
            $action['action'] = l(t('<span class="visuallyhidden">Create</span> Theme'),
              'admin/themes/create', array('html' => TRUE));
            break;

          case 'paddle_menu_manager_create_menu':
            $action['action'] = ctools_modal_text_button(t('<span class="visuallyhidden">Create</span> Menu'),
              'admin/structure/menu_manager/nojs/add', t('Create a new menu.'),
              'ctools-modal-overlay-persist');
            break;

          case 'paddle_menu_manager_edit_menu':
            $action['action'] = ctools_modal_text_button(t('<span class="visuallyhidden">Edit</span> Menu'),
              $action['link'], t('Edit the title and description of this menu'),
              'ctools-modal-overlay-persist');
            break;

          case 'paddle_menu_manager_create_menu_item':
            $action['action'] = ctools_modal_text_button(t('<span class="visuallyhidden">Create</span> Menu Item'),
              $action['link'], t('Create a new menu item.'),
              'ctools-modal-overlay-persist');
            break;

          case 'paddle_menu_manager_delete_menu':
            $action['action'] = ctools_modal_text_button(t('<span class="visuallyhidden">Delete</span> Menu'),
              $action['link'], t('Delete this menu'),
              'ctools-modal-overlay-persist');
            break;

          default:
            break;
        }
      }
    }
  }
}

/**
 * Implements theme_panels_ipe_pane_wrapper().
 */
function paddle_admin_theme_panels_ipe_pane_wrapper($vars) {
  $output = $vars['output'];
  $pane = $vars['pane'];
  $renderer = $vars['renderer'];

  if ($renderer->plugin['renderer'] == 'panels_renderer_paddle_content_region' && !array_key_exists($pane->panel, paddle_content_region_get_regions($renderer->display))) {
    return $output;
  }
  else {
    $attributes = array(
      'class' => 'panels-ipe-linkbar',
    );

    if (!empty($vars['links']['edit'])) {
      $vars['links']['edit']['attributes']['class'][] = 'ctools-modal-wide-modal';
    }
    $links = theme('links', array('links' => $vars['links'], 'attributes' => $attributes));

    if (!empty($pane->locks['type']) && $pane->locks['type'] == 'immovable') {
      $links = '<div class="panels-ipe-dragbar panels-ipe-nodraghandle clearfix">' . $links . '</div>';
    }
    else {
      $links = '<div class="panels-ipe-dragbar panels-ipe-draghandle clearfix">' . $links . '<span class="panels-ipe-draghandle-icon"><span class="panels-ipe-draghandle-icon-inner"></span></span></div>';
    }

    $handlebar = '<div class="panels-ipe-handlebar-wrapper panels-ipe-on">' . $links . '</div>';

    return $handlebar . $output;
  }
}

/**
 * Implements theme_checkbox_tree_level().
 */
function paddle_admin_theme_checkbox_tree_level($variables) {
  $element = $variables['element'];
  $sm = '';

  // We always want to start minimized, so if the depth is bigger then 1, we
  // minimize the fieldset.
  if ((array_key_exists('#level_start_minimized', $element) && $element['#level_start_minimized']) || $element['#depth'] > 1) {
    $sm = " style='display: none;'";
  }

  $max_choices = 0;
  if (array_key_exists('#max_choices', $element)) {
    $max_choices = $element['#max_choices'];
  }

  $output = "<ul class='term-reference-tree-level '$sm>";
  $children = element_children($element);
  foreach ($children as $child) {
    $output .= "<li>";
    $output .= drupal_render($element[$child]);
    $output .= "</li>";
  }

  $output .= "</ul>";

  return $output;
}

/**
 * Implements theme_checkbox_tree_item().
 */
function paddle_admin_theme_checkbox_tree_item($variables) {
  $element = $variables['element'];
  $children = element_children($element);
  $output = "";

  // Check if a term has has children, if so, set the correct icon.
  $sm = $element['#level_start_minimized'] || count($children) > 1 ? ' term-reference-tree-collapsed' : '';

  if (is_array($children) && count($children) > 1) {
    $output .= "<div class='term-reference-tree-button$sm'></div>";
  }
  elseif (!$element['#leaves_only']) {
    $output .= "<div class='no-term-reference-tree-button'></div>";
  }

  foreach ($children as $child) {
    $output .= drupal_render($element[$child]);
  }

  return $output;
}

/**
 * Implements theme_diff_node_revisions().
 */
function paddle_admin_theme_diff_node_revisions($variables) {
  $form = $variables['form'];
  $output = '';

  // Overview table:
  $header = array(
    t('Revision'),
    array('data' => drupal_render($form['submit']), 'colspan' => 2),
    array('data' => t('Operations'), 'colspan' => 3),
  );
  if (isset($form['info']) && is_array($form['info'])) {
    foreach (element_children($form['info']) as $key) {
      $row = array();
      if (isset($form['operations'][$key][0])) {
        // Note: even if the commands for revert and delete are not permitted,
        // the array is not empty since we set a dummy in this case.
        $row[] = drupal_render($form['info'][$key]);
        $row[] = drupal_render($form['diff']['old'][$key]);
        $row[] = drupal_render($form['diff']['new'][$key]);
        $row[] = drupal_render($form['operations'][$key][0]);
        $row[] = drupal_render($form['operations'][$key][1]);
        $row[] = drupal_render($form['operations'][$key][2]);
        $rows[] = array(
          'data' => $row,
          'class' => array('diff-revision'),
          'data-revision-id' => array($key),
        );
      }
      else {
        // The current revision (no commands to revert or delete).
        $row[] = array(
          'data' => drupal_render($form['info'][$key]),
          'class' => array('revision-current'),
        );
        $row[] = array(
          'data' => drupal_render($form['diff']['old'][$key]),
          'class' => array('revision-current'),
        );
        $row[] = array(
          'data' => drupal_render($form['diff']['new'][$key]),
          'class' => array('revision-current'),
        );

        $node = node_load($form['nid']['#value'], $key);
        $data = $node->status !== 0 ? t('This is the published revision.') : t('This is the active revision.');

        $row[] = array(
          'data' => '<strong>' . $data . '</strong>',
          'class' => array('revision-current'),
          'colspan' => '3',
        );
        $rows[] = array(
          'data' => $row,
          'class' => array('revision-published diff-revision'),
          'data-revision-id' => array($key),
        );
      }
    }
  }
  $output .= theme('table__diff__revisions', array(
    'header' => $header,
    'rows' => $rows,
    'sticky' => FALSE,
    'attributes' => array('class' => array('diff-revisions')),
  ));

  $output .= drupal_render_children($form);
  return $output;
}

/**
 * Implements theme_table().
 *
 * Renders the diff table.
 */
function paddle_admin_theme_table__diff($variables) {
  $header = $variables['header'];
  $rows = $variables['rows'];
  $attributes = $variables['attributes'];
  $caption = $variables['caption'];
  $colgroups = $variables['colgroups'];
  $sticky = $variables['sticky'];
  $empty = $variables['empty'];

  // Add sticky headers, if applicable.
  if (count($header) && $sticky) {
    drupal_add_js('misc/tableheader.js');
    // Add 'sticky-enabled' class to the table to identify it for JS.
    // This is needed to target tables constructed by this function.
    $attributes['class'][] = 'sticky-enabled';
  }

  $output = '<table' . drupal_attributes($attributes) . ">\n";

  if (isset($caption)) {
    $output .= '<caption>' . $caption . "</caption>\n";
  }

  // Format the table columns:
  if (count($colgroups)) {
    foreach ($colgroups as $number => $colgroup) {
      $attributes = array();

      // Check if we're dealing with a simple or complex column.
      if (isset($colgroup['data'])) {
        foreach ($colgroup as $key => $value) {
          if ($key == 'data') {
            $cols = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $cols = $colgroup;
      }

      // Build colgroup.
      if (is_array($cols) && count($cols)) {
        $output .= ' <colgroup' . drupal_attributes($attributes) . '>';
        $i = 0;
        foreach ($cols as $col) {
          $output .= ' <col' . drupal_attributes($col) . ' />';
        }
        $output .= " </colgroup>\n";
      }
      else {
        $output .= ' <colgroup' . drupal_attributes($attributes) . " />\n";
      }
    }
  }

  // Add the 'empty' row message if available.
  if (!count($rows) && $empty) {
    $header_count = 0;
    foreach ($header as $header_cell) {
      if (is_array($header_cell)) {
        $header_count += isset($header_cell['colspan']) ? $header_cell['colspan'] : 1;
      }
      else {
        $header_count++;
      }
    }
    $rows[] = array(
      array(
        'data' => $empty,
        'colspan' => $header_count,
        'class' => array('empty', 'message'),
      ),
    );
  }

  // Format the table header:
  if (count($header)) {
    $ts = tablesort_init($header);
    // HTML requires that the thead tag has tr tags in it followed by tbody
    // tags. Using ternary operator to check and see if we have any rows.
    $output .= (count($rows) ? ' <thead><tr>' : ' <tr>');

    // Change compared to theme_table(): get the current menu item and count
    // the number of cells.
    $menu_item = menu_get_item();
    $cell_number = 1;

    foreach ($header as $cell) {
      $cell = tablesort_header($cell, $header, $ts);

      // Change compared to theme_table(): add the revert link, if needed.
      if ($menu_item['path'] == 'node/%/moderation/diff/view') {
        $vids = array_values($menu_item['page_arguments']);

        if ($vids[$cell_number] != $vids[0]->vid && user_access('revert revisions')) {
          $cell['data'] .= l(t('Revert this version'), 'node/' . $vids[0]->nid . '/revisions/' . $vids[$cell_number] . '/revert', array('attributes' => array('class' => array('btn-revision'))));
        }
      }
      $output .= _theme_table_cell($cell, TRUE);
      $cell_number++;
    }

    // Using ternary operator to close the tags based on whether or not there
    // are rows.
    $output .= (count($rows) ? " </tr></thead>\n" : "</tr>\n");
  }
  else {
    $ts = array();
  }

  // Format the table rows:
  if (count($rows)) {
    $output .= "<tbody>\n";
    $flip = array('even' => 'odd', 'odd' => 'even');
    $class = 'even';
    foreach ($rows as $number => $row) {
      // Check if we're dealing with a simple or complex row.
      if (isset($row['data'])) {
        $cells = $row['data'];
        $no_striping = isset($row['no_striping']) ? $row['no_striping'] : FALSE;

        // Set the attributes array and exclude 'data' and 'no_striping'.
        $attributes = $row;
        unset($attributes['data']);
        unset($attributes['no_striping']);
      }
      else {
        $cells = $row;
        $attributes = array();
        $no_striping = FALSE;
      }
      if (count($cells)) {
        // Add odd/even class.
        if (!$no_striping) {
          $class = $flip[$class];
          $attributes['class'][] = $class;
        }

        // Build row.
        $output .= ' <tr' . drupal_attributes($attributes) . '>';
        $i = 0;
        foreach ($cells as $cell) {
          $cell = tablesort_cell($cell, $header, $ts, $i++);
          $output .= _theme_table_cell($cell);
        }
        $output .= " </tr>\n";
      }
    }
    $output .= "</tbody>\n";
  }

  $output .= "</table>\n";
  return $output;
}

/**
 * Implements theme_diff_content_line().
 */
function paddle_admin_theme_diff_content_line($vars) {
  $line = $vars['line'];

  // We show the actual image instead of the filename. We do this because the
  // DiffEngine strips out all html. To find images, we have to strip all
  // the markup that might have been added by the Diff module.
  $stripped_line = strip_tags($line);
  if (preg_match('/\.(jpe?g|png|gif)$/i', $stripped_line)) {
    $line = theme('image_style', array(
      'style_name' => 'thumbnail',
      'path' => $stripped_line,
    ));
  }

  return '<div>' . $line . '</div>';
}

/**
 * Implements theme_paddle_content_manager_add_links().
 */
function paddle_admin_theme_paddle_content_manager_add_links($variables) {
  return theme('paddle_core_titled_iconed_links_list', array('items' => $variables['items']));
}
