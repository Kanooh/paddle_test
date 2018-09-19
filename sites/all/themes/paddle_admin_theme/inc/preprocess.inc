<?php
/**
 * @file
 * All Preprocess functions for templates and theme functions.
 */

/**
 * Implements template_preprocess_html().
 */
function paddle_admin_theme_preprocess_html(&$variables) {
  $vars['head_title'] = implode(' | ', array(drupal_get_title(), variable_get('site_name')));

  $base_theme_path = drupal_get_path('theme', 'paddle_admin_theme');
  drupal_add_library('system', 'ui.accordion');
  drupal_add_library('system', 'ui.position');
  drupal_add_library('system', 'ui.menu');
  drupal_add_library('system', 'effects.fade');

  drupal_add_css($base_theme_path . '/css/modal.css',
    array(
      'group' => CSS_THEME,
      'every_page' => TRUE,
    ));
  drupal_add_css($base_theme_path . '/css/jquery.ui.dialog.css',
    array(
      'group' => CSS_THEME,
      'every_page' => TRUE,
    ));
  drupal_add_css($base_theme_path . '/css/media.css',
    array(
      'group' => CSS_THEME,
      'every_page' => TRUE,
    ));
  drupal_add_css($base_theme_path . '/css/jquery.tooltip.css',
    array(
      'group' => CSS_THEME,
      'every_page' => TRUE,
      'weight' => -1,
    ));
  drupal_add_css($base_theme_path . '/css/paddlets.css',
    array(
      'group' => CSS_THEME,
      'every_page' => TRUE,
    ));
  drupal_add_css($base_theme_path . '/css/paddle_apps.screenshots.css',
    array(
      'group' => CSS_THEME,
      'every_page' => TRUE,
    ));

  drupal_add_js($base_theme_path .  '/javascript/other.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'group' => JS_THEME,
      'every_page' => TRUE,
    ));
  drupal_add_js($base_theme_path .  '/javascript/foldable.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'group' => JS_THEME,
      'every_page' => TRUE,
    ));
  drupal_add_js($base_theme_path .  '/javascript/forms.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'group' => JS_THEME,
      'every_page' => TRUE,
    ));
  drupal_add_js($base_theme_path .  '/javascript/jquery.tooltip.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'group' => JS_THEME,
      'every_page' => TRUE,
    ));
  drupal_add_js($base_theme_path .  '/javascript/tooltip.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'group' => JS_THEME,
      'every_page' => TRUE,
    ));
  drupal_add_js($base_theme_path .  '/javascript/scrolltofixed.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'group' => JS_THEME,
      'every_page' => TRUE,
    ));
  drupal_add_js($base_theme_path .  '/javascript/init.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'group' => JS_THEME,
      'every_page' => TRUE,
    ));

  // Override settings stickyHeader.
  $settings = array(
    'tableHeaderOffset' => 'themeTableHeaderOffset',
  );

  $ctools_modal_settings = paddle_admin_theme_ctools_modal_settings();
  $settings += $ctools_modal_settings;

  if (empty($variables['page']['header_top'])) {
    $variables['classes_array'][] = "no-admin-menu";
  }
  drupal_add_js($settings, 'setting');
}

/**
 * Implements template_preprocess_page().
 */
function paddle_admin_theme_preprocess_page(&$variables) {
  global $user;

  // Admin home icon link.
  $variables['admin_home_logo_link'] = l(
    t('Admin'),
   'admin/',
    array(
      'attributes' => array(
        'class' => 'admin-home',
        'title' => 'Admin home',
      ),
      'external' => FALSE,
    ));

  $router_item = menu_get_item();
  $path = $router_item['path'];

  // On these pages the title should be hidden.
  $paths_hidden_title = array(
    'admin/paddlet_store',
    'admin/paddlet_store/available',
    'node/add/*',
    'node/*/edit',
    'admin/content_manager/node/*/view',
  );
  $variables['title_visible'] = !drupal_match_path($path, implode("\n", $paths_hidden_title));

  // Generate the links for the Site settings menu.
  $block = module_invoke('paddle_core', 'block_view', 'site_settings_menu');
  $variables['site_settings_menu'] = render($block['content']);

  $block = module_invoke('paddle_user', 'block_view', 'user_management_block');
  $variables['toolbar_menu'] = render($block['content']);

  // 403 page.
  $variables['status_forbidden'] = FALSE;
  $status = drupal_get_http_header("status");
  if ($status == "403 Forbidden") {
    $variables['status_forbidden'] = TRUE;
    // 403 page for logged in users.
    if (user_is_logged_in()) {
      $variables['theme_hook_suggestions'][] = 'page__403_logged_in';
    }
    else {
      $variables['theme_hook_suggestions'][] = 'page__user__anonymous';
    }
  }

  // By default the tabs are hidden for all users except UID 1, except for these
  // pages. On these pages the tabs are visible for all users.
  $paths_visible_tabs = array(
    'admin/paddlet_store',
    'admin/paddlet_store/active',
    'admin/paddlet_store/available',
  );
  if ($user->uid != 1 && !in_array($path, $paths_visible_tabs)) {
    $variables['tabs'] = '';
  }

  // Disable rendering of messages for the anonymous user page.
  // Any status message will be rendered on the user login form itself.
  $login_paths = array(
    'user',
    'user/login',
  );
  if ((user_is_anonymous() && in_array($path, $login_paths)) || $path == 'user/password') {
    $variables['show_messages'] = FALSE;
    $variables['theme_hook_suggestions'][] = 'page__user__anonymous';
  }
}

/**
 * Regions classes.
 */
function paddle_admin_theme_preprocess_region(&$variables, $hook) {
  if ($variables['region'] == "header_bottom") {
    $variables['classes_array'][] = 'max-width';
    $variables['classes_array'][] = 'clearfix';
  }
  if ($variables['region'] == "header_top") {
    $variables['classes_array'][] = 'max-width';
    $variables['classes_array'][] = 'row';
  }
}

/**
 * Implements template_preprocess_views_view_fields().
 */
function paddle_admin_theme_preprocess_views_view_fields(&$vars) {
  $view = $vars['view'];
  if ($view->name == 'paddle_dashboard_status') {
    // Loop through the fields for this view.
    $previous_inline = FALSE;
    // Ensure it's at least an empty array.
    $vars['fields'] = array();
    foreach ($view->field as $id => $field) {
      // Render this even if set to exclude so it can be used elsewhere.
      $field_output = $view->style_plugin->get_field($view->row_index, $id);
      $empty = $field->is_value_empty($field_output, $field->options['empty_zero']);
      if (empty($field->options['exclude']) && (!$empty || (empty($field->options['hide_empty']) && empty($vars['options']['hide_empty'])))) {
        $object = new stdClass();
        $object->handler = &$view->field[$id];
        $object->inline = !empty($vars['options']['inline'][$id]);

        $object->element_type = $object->handler->element_type(TRUE, !$vars['options']['default_field_elements'], $object->inline);
        // Protect ourself somewhat for backward compatibility. This will
        // prevent
        // old templates from producing invalid HTML when no element type is
        // selected.
        if (empty($object->element_type)) {
          $object->element_type = 'span';
        }

        $object->content = $field_output;
        if (isset($view->field[$id]->field_alias) && isset($vars['row']->{$view->field[$id]->field_alias})) {
          $object->raw = $vars['row']->{$view->field[$id]->field_alias};
        }
        else {
          // Make sure it exists to reduce notice.
          $object->raw = NULL;
        }

        if (!empty($vars['options']['separator']) && $previous_inline && $object->inline && $object->content) {
          $object->separator = filter_xss_admin($vars['options']['separator']);
        }

        $object->class = drupal_clean_css_identifier($id);

        $previous_inline = $object->inline;
        $object->inline_html = $object->handler->element_wrapper_type(TRUE, TRUE);
        if ($object->inline_html === '' && $vars['options']['default_field_elements']) {
          $object->inline_html = $object->inline ? 'span' : 'div';
        }

        // Set up the wrapper HTML.
        $object->wrapper_prefix = '';
        $object->wrapper_suffix = '';

        if ($object->inline_html) {
          $class = '';
          if ($object->handler->options['element_default_classes']) {
            $class = "views-field " . $object->class;
          }

          if ($classes = $object->handler->element_wrapper_classes($view->row_index)) {
            if ($class) {
              $class .= ' ';
            }
            $class .= $classes;
          }
        }

        $object->wrapper_prefix = '<' . $object->inline_html;
        if ($class) {
          $object->wrapper_prefix .= ' class="' . $class . '"';
        }
        $object->wrapper_prefix .= '>';
        $object->wrapper_suffix = '</' . $object->inline_html . '>';
      }

      // Set up the label for the value and the HTML to make it easier
      // on the template.
      $object->label = check_plain($view->field[$id]->label());
      $object->label_html = '';
      if ($object->label) {
        $object->label_html .= $object->label;
        if ($object->handler->options['element_label_colon']) {
          $object->label_html .= ': ';
        }

        $object->element_label_type = $object->handler->element_label_type(TRUE, !$vars['options']['default_field_elements']);
        if ($object->element_label_type) {
          $class = '';
          if ($object->handler->options['element_default_classes']) {
            $class = 'views-label views-label-' . $object->class;
          }

          $element_label_class = $object->handler->element_label_classes($view->row_index);
          if ($element_label_class) {
            if ($class) {
              $class .= ' ';
            }

            $class .= $element_label_class;
          }

          $pre = '<' . $object->element_label_type;
          if ($class) {
            $pre .= ' class="' . $class . '"';
          }
          $pre .= '>';

          $object->label_html = $pre . $object->label_html . '</' . $object->element_label_type . '>';
        }
      }

      $vars['fields'][$id] = $object;
    }
  }
}

/**
 * Implements preprocess_preprocess_user_login().
 */
function paddle_admin_theme_preprocess_user_login(&$variables) {
  // Check if the login form is on 403 page.
  $variables['status_forbidden'] = FALSE;
  $status = drupal_get_http_header("status");
  if ($status == "403 Forbidden") {
    $variables['status_forbidden'] = TRUE;
  }

  $variables['form']['name']['#title_display'] = 'invisible';
  $variables['form']['name']['#attributes']['placeholder'] = t('personal id or email address');
  $variables['form']['pass']['#title_display'] = 'invisible';
  $variables['form']['pass']['#attributes']['placeholder'] = t('password');
  $variables['form']['remember_me']['#title'] = t('keep me logged in');

  // Form variables.
  $variables['form']['name']['#attributes']['placeholder'] = t('personal id or email address');
  $variables['form']['pass']['#attributes']['placeholder'] = t('password');
  $variables['form']['remember_me']['#title'] = t('keep me logged in');
  unset($variables['form']['name']['#description']);
  unset($variables['form']['pass']['#description']);

  // Show status messages on the form itself.
  $variables['messages'] = theme('status_messages');
}

/**
 * Implements template_preprocess_paddle_themer_theme_detail().
 */
function paddle_admin_theme_preprocess_paddle_themer_theme_detail(&$vars) {
  $theme = $vars['theme'];
  $operations = array();

  $enabled_theme = variable_get('paddle_theme', '');

  $is_enabled = $theme->name == $enabled_theme;

  // Add a preview link.
  $operations[] = l(t('Preview'), paddle_themer_preview_url($theme, '<front>'), array(
    'external' => TRUE,
    'attributes' => array(
      'class' => array('ui-icon', 'ui-icon-visit'),
      'data-action' => 'preview',
    ),
  ));

  // If the theme is not a 'preset' allow to edit it.
  if (!($theme->export_type & EXPORT_IN_CODE)) {
    $edit_path = "admin/themes/{$theme->name}/edit";

    $screenshot_edit_path = "admin/themes/{$theme->name}/edit_screenshot";
    if (drupal_valid_path($screenshot_edit_path)) {
      $operations[] = l(t('Edit Screenshot'), $screenshot_edit_path, array(
        'query' => array(
          'destination' => 'admin/themes',
        ),
        'attributes' => array(
          'class' => array('ui-icon', 'ui-icon-edit'),
          'data-action' => 'edit-screenshot',
        ),
      ));
    }

    if (drupal_valid_path($edit_path)) {
      $operations[] = l(t('Edit'), $edit_path, array(
        'query' => array(
          'destination' => 'admin/themes',
        ),
        'attributes' => array(
          'class' => array('ui-icon', 'ui-icon-edit'),
          'data-action' => 'edit',
        ),
      ));
    }

  }

  // Add a link to delete the theme.
  $delete_path = "admin/themes/{$theme->name}/delete";
  if (drupal_valid_path($delete_path)) {
    $operations[] = l(t('Delete'), $delete_path, array(
      'attributes' => array(
        'class' => array('ui-icon', 'ui-icon-delete'),
        'data-action' => 'delete',
      ),
    ));
  }

  // Add a link to enable the theme, or mark it as enabled.
  if (!$is_enabled) {
    $operations[] = l(t('Enable'), "admin/themes/{$theme->name}/enable", array(
      'attributes' => array(
        'class' => array('ui-icon', 'ui-icon-add', 'button', 'button-activate'),
        'data-action' => 'enable',
      ),
    ));
  }
  else {
    $operations[] = '<div class="ui-icon fa-check button button-activated">' . t('Enabled') . '</div>';
  }

  $vars['operations'] = $operations;
}

/**
 * Processes variables for paddle_apps_paddlets_overview.tpl.php.
 *
 * @see paddle_apps_paddlets_overview.tpl.php
 */
function paddle_admin_theme_preprocess_paddle_apps_paddlets_overview(&$variables) {
  // Provide a rendered list of paddlet details to show in the overview.
  if (!empty($variables['apps'])) {
    $variables['paddlets'] = array();
    // Add a new container for each 3 items.
    $i = 0;
    $container = 0;
    foreach ($variables['apps'] as $app) {
      if ($i++ % 3 == 0) {
        $container++;
        $variables['paddlets'][$container] = array(
          '#type' => 'container',
          '#attributes' => array('class' => array('row')),
        );
      }
      if (!empty($app->machineName)) {
        $variables['paddlets'][$container][] = array(
          '#theme' => 'paddle_apps_paddlet_detail',
          '#app' => $app,
        );
      }
    }
  }
  else {
    $variables['paddlets'] = array(
      '#theme' => 'html_tag',
      '#tag' => 'p',
      '#attributes' => array('class' => array('paddle_paddlets_none_available')),
    );
    $variables['paddlets']['#value'] = t('There are no available paddlets');
  }
}

/**
 * Implements template_preprocess_views_view().
 */
function paddle_admin_theme_preprocess_views_view(&$vars) {
  if ($vars['view']->name == 'content_manager') {
    drupal_add_js(drupal_get_path('theme', 'paddle_admin_theme') .  '/javascript/accessible_dropdowns.js', array(
      'type' => 'file',
      'scope' => 'header',
      'group' => JS_THEME,
      'every_page' => TRUE,
    ));
  }
}

/**
 * Implements hook_preprocess_entity().
 */
function paddle_admin_theme_preprocess_entity(&$variables) {
  // Add a view mode class to the entity.
  $variables['classes_array'][] = drupal_html_class($variables['entity_type'] . '--' . $variables['view_mode']);
  $variables['classes_array'] = array_unique($variables['classes_array']);
}
