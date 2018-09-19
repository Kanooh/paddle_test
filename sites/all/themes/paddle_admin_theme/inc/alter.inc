<?php
/**
 * @file
 * General alters.
 */

/**
 * Implements hook_ajax_render_alter().
 */
function paddle_admin_theme_ajax_render_alter(&$commands) {
  $settings = paddle_admin_theme_ctools_modal_settings();
  $commands[] = ajax_command_settings($settings, TRUE);
}
/**
 * Implements hook_css_alter().
 *
 * Unset module css dependencies.
 */
function paddle_admin_theme_css_alter(&$css) {
  unset($css['misc/ui/jquery.ui.accordion.css']);
  unset($css['misc/ui/jquery.ui.core.css']);
  unset($css['misc/ui/jquery.ui.dialog.css']);
  unset($css['misc/ui/jquery.ui.theme.css']);
  unset($css[drupal_get_path('module', 'active_tags') . '/active_tags.css']);
  unset($css[drupal_get_path('module', 'ctools') . '/css/modal.css']);
  unset($css[drupal_get_path('module', 'locale') . '/locale.css']);
  unset($css[drupal_get_path('module', 'panels') . '/css/panels_admin.css']);

  $current_path = menu_get_item();
  if ($current_path['path'] !== 'admin/structure/pages/%/operation/%') {
    unset($css[drupal_get_path('module', 'panels') . '/css/panels_dnd.css']);
  }

  unset($css[drupal_get_path('module', 'panels_ipe') . '/css/panels_ipe.css']);
  unset($css[drupal_get_path('module', 'system') . '/system.menus.css']);

  // Remove all styling on the login page and replace it with a custom
  // stylesheet. This is a temporary solution until our base theme is ready??
  $menu_item = menu_get_item();

  // Define a range of paths that show the login screen.
  $paths = array(
    'user',
    'user/login',
    'user/password',
    'toboggan/denied',
  );
  // Check if login page.
  if (in_array($menu_item['path'], $paths)) {
    // Add our CSS files to the list, then remove all others.
    $css_resets = drupal_get_path('theme', 'paddle_admin_theme') . '/css/resets.css';
    $css_structure = drupal_get_path('theme', 'paddle_admin_theme') . '/css/structure.css';
    $css_file = drupal_get_path('theme', 'paddle_admin_theme') . '/css/paddle_login.css';
    $honeypot_file = honeypot_get_css_file_path();
    $keep = array(
      $css_resets,
      $css_structure,
      $honeypot_file,
      $css_file,
    );
    if (($base = variable_get('walkhub_client_base_url')) && ($hub = variable_get('walkhub_hub_origin'))) {
      $keep[] = "$base/walkthrough.css";
      $keep[] = "$base/joyride.css";
    }
    $css = drupal_add_css($css_resets, array('every_page' => FALSE, 'weight' => -16));
    $css = drupal_add_css($css_structure, array('every_page' => FALSE, 'weight' => -15));
    $css = drupal_add_css($css_file, array('every_page' => FALSE, 'weight' => -14));

    // Include the paddle admin branded theme fonts.
    // @todo Move to paddle admin branded.
    $admin_theme = variable_get('admin_theme', '');
    $css_file_1 = '';
    if ($admin_theme) {
      $themes = list_themes();
      if (isset($themes[$admin_theme]->info['paddle_preview_toolbar_stylesheet_location'])) {
        $css_file_1 = drupal_get_path('theme', $admin_theme) . '/';
        $css_file_1 .= $themes[$admin_theme]->info['paddle_preview_toolbar_stylesheet_location'];
        $css = drupal_add_css($css_file_1, array('every_page' => FALSE, 'weight' => -16));
        $keep[] = $css_file_1;
      }
    }

    $keep_css = array_fill_keys($keep, NULL);
    // Remove all css except css related to login page.
    $css = array_intersect_key($css, $keep_css);
  }
}

/**
 * Implements hook_js_alter().
 */
function paddle_admin_theme_js_alter(&$js) {
  // Override modal.js by Ctools.
  $path = drupal_get_path('theme', 'paddle_admin_theme') . '/javascript/modal.js';
  $oldpath = drupal_get_path('module', 'ctools') . '/js/modal.js';
  if (isset($js[$oldpath])) {
    $js[$path] = $js[$oldpath];
    $js[$path]['data'] = $path;
    unset($js[drupal_get_path('module', 'ctools') . '/js/modal.js']);
  }

  $js['settings']['data'][] = array('modalThrobberPath' => '/' . drupal_get_path('theme', 'paddle_admin_theme') . '/images/modal_throbler.gif');
}

/**
 * Implements hook_form_FORM_ID_alter().
 *
 * Alter the Paddle Themer plugin edit form:
 * - The fieldsets inside style sets should not be collapsible.
 * - If a style set only contains one element, the title of the element should
 *   be hidden.
 */
function paddle_admin_theme_form_paddle_themer_theme_edit_form_alter(&$form, &$form_state) {
  foreach (element_children($form) as $style_set) {
    // Check if the element is an actual style set.
    if (!empty($form[$style_set]['#group']) && $form[$style_set]['#group'] == 'style_sets') {
      // Retrieve the children of the style set.
      $elements = element_children($form[$style_set]);
      foreach ($elements as $element) {
        // The fieldsets should not be collapsible.
        $form[$style_set][$element]['#collapsible'] = FALSE;
        // Hide the title if the style set only contains a single item.
        if (count($elements) == 1) {
          $form[$style_set][$element]['#title'] = FALSE;
        }
      }
    }
  }
}

/**
 * Implements hook_form_FORM_ID_alter().
 *
 * Alter the Panel change layout edit form:
 * -  Override the right arrow image.
 */
function paddle_admin_theme_form_panels_change_layout_alter(&$form, &$form_state) {
  if (isset($form['container']['right_arrow'])) {
    $form['container']['right_arrow'] = array(
      '#markup' => theme('image', array(
      'path' => drupal_get_path('theme', 'paddle_admin_theme') . '/images/panels-change-layout-arrow.png')),
    );
  }
}

/**
 * Implements hook_form_FORM_ID_alter().
 *
 * Alters the landing page add image form.
 */
function paddle_admin_theme_form_paddle_landing_page_add_image_content_type_edit_form_alter(&$form, &$form_state) {
  unset($form['pane_settings']['url_wrapper']['internal_url']['#title']);
  unset($form['pane_settings']['url_wrapper']['external_url']['#title']);
  $form['#after_build'][] = 'paddle_landing_page_add_image_content_type_edit_form_after_build';
}

/**
 * Custom after build function for landing page add image form.
 */
function paddle_landing_page_add_image_content_type_edit_form_after_build($form, &$form_state) {
  $form['pane_settings']['url_wrapper']['url_type']['internal']['#suffix'] = drupal_render($form['pane_settings']['url_wrapper']['internal_url']);
  $form['pane_settings']['url_wrapper']['url_type']['external']['#suffix'] = drupal_render($form['pane_settings']['url_wrapper']['external_url']);
  return $form;
}

/**
 * Implements hook_form_FORM_ID_alter().
 *
 * Alters the Create menu form.
 */
function paddle_admin_theme_form_paddle_menu_manager_menu_item_form_alter(&$form, &$form_state) {
  $form['#after_build'][] = 'paddle_menu_manager_menu_item_form_after_build';
}

/**
 * Custom after build function for Create menu form.
 */
function paddle_menu_manager_menu_item_form_after_build($form, &$form_state) {
  if (isset($form['body']['content_button']['internal']) && isset($form['body']['internal_link'])) {
    $form['body']['content_button']['internal']['#suffix'] = drupal_render($form['body']['internal_link']);
  }
  if (isset($form['body']['content_button']['external']) && isset($form['body']['external_link'])) {
    $form['body']['content_button']['external']['#suffix'] = drupal_render($form['body']['external_link']);
  }

  return $form;
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function paddle_admin_theme_form_news_item_node_form_alter(&$form, &$form_state) {
  // Render the field labels of the lead image upload and position next to each
  // other.
  $form['field_paddle_featured_image']['#attributes']['class'][] = 'col-md-6';
  $form['field_paddle_news_image_position']['#attributes']['class'][] = 'col-md-6';

  // Hide the field label of the news lead image position field.
  $form['field_paddle_news_image_position'][LANGUAGE_NONE]['#title_display'] = 'invisible';
}

/**
 * Extends theme_paddle_scald_atom_element().
 */
function paddle_admin_theme_paddle_scald_atom_element($variables) {
  $object = menu_get_object();
  if (
      !empty($object) &&
      $object->type == 'news_item' &&
      !empty($variables['element']['#name']) &&
      $variables['element']['#name'] == 'field_paddle_featured_image[und][0][sid]'
  ) {
    // Hide the field label of the news featured image upload field.
    $variables['element']['atoms']['picker']['#title_display'] = 'invisible';
  }
  return theme_paddle_scald_atom_element($variables);
}

/**
 * Implements hook_form_FORM_ID_alter().
 *
 * Add bootstrap classes to the paddle store overview page.
 */
function paddle_admin_theme_form_paddle_apps_paddlets_form_alter(&$form, &$form_state) {
  $form['content']['#attributes']['class'][] = 'row';

  $form['content']['filters']['#attributes']['class'][] = 'col-md-3';

  $form['content']['apps']['#prefix'] = '<div class="col-md-9">';
  $form['content']['apps']['#suffix'] = '</div>';
}

/**
 * Implements hook_form_form_builder_preview_alter().
 */
function paddle_admin_theme_form_form_builder_preview_alter(&$form, &$form_state) {
  // Override scroll behaviour for the controls block.
  $form['#attached']['js'][] = drupal_get_path('theme', 'paddle_admin_theme') . '/javascript/paddle_formbuilder.js';
}

/**
 * Implements hook_form_paddle_advanced_search_form_alter().
 */
function paddle_admin_theme_form_paddle_advanced_search_form_alter(&$form, &$form_state) {
  // Do not allow searches on the backend.
  $form['search']['#disabled'] = TRUE;
  $form['actions']['submit']['#disabled'] = TRUE;
}
