<?php
/**
 * @file
 * Theme functions and template preprocess.
 */

/**
 * Provides a template for the paddle store facet indicators.
 *
 * @param array $variables
 *   Template variables.
 *
 * @return string
 *   HTML string with the facet number.
 */
function theme_paddle_apps_facet_number($variables) {
  return '<span class="facet-number" data-number="' . $variables['n'] . '">(' . $variables['n'] . ')</span>';
}

/**
 * Processes variables for paddle_apps_paddlets_description.tpl.php.
 *
 * @see paddle_apps_paddlets_description.tpl.php
 */
function template_preprocess_paddle_apps_paddlet_description(&$variables) {
  if (!empty($variables['app'])) {
    $language = module_exists('paddle_i18n') ? admin_language_retrieve() : language_default();
    $variables['language'] = $language->language;

    /* @var Drupal\paddle_apps\App $app */
    $app = $variables['app'];

    // Quit if failed retrieving the app entity.
    if (get_class($app) != 'Drupal\paddle_apps\App') {
      return;
    }

    $variables['name'] = !empty($app->name) ? check_plain($app->name) : '';
    $variables['description'] = !empty($app->description) ? check_plain($app->description) : '';
    if (!empty($app->logo['uri'])) {
      $variables['image'] = array(
        '#theme' => 'image_style',
        '#style_name' => 'thumbnail',
        '#path' => $app->logo['uri'],
        '#alt' => $variables['name'],
      );
    }
    // @todo Provide a default image instead of this text.
    else {
      $variables['image'] = array('#markup' => t('Image not available'));
    }

    // Set the detailed description.
    if (!empty($app->detailedDescription)) {
      $variables['detailed_description'] = $app->detailedDescription;
    }
    else {
      // Try to retrieve detailed description directly.
      // @todo Ugly! Everything about app loading should be centralized.
      $variables['detailed_description'] = paddle_apps_get_detailed_description($app->machineName);
    }

    // Add the "Statistics" block.
    if (!empty($app->vendor)) {
      $variables['vendor'] = check_plain($app->vendor);
      if (!empty($app->vendorLink)) {
        $variables['vendor'] = l($app->vendor, $app->vendorLink);
      }
    }

    // Set the FAQ links.
    if (!empty($app->faq)) {
      $items = array();
      foreach ($app->faq as $title => $link) {
        $items[] = l($title, $link);
      }
      $variables['faq'] = theme('item_list', array('items' => $items));
    }

    if (!empty($app->screenshots)) {
      foreach ($app->screenshots as $screenshot) {
        $variables['screenshots'][] = array(
          '#theme' => 'image_style',
          '#style_name' => 'paddle_apps_screenshot',
          '#path' => $screenshot['uri'],
          '#alt' => $screenshot['alt'],
        );
      }
    }
  }
}

/**
 * Processes variables for paddle_apps_paddlets_detail.tpl.php.
 *
 * @see paddle_apps_paddlets_detail.tpl.php
 */
function template_preprocess_paddle_apps_paddlet_detail(&$variables) {

  global $user;
  global $base_url;

  if (!empty($variables['element']['#app'])) {
    $app = $variables['element']['#app'];
    $app_array = paddle_apps_app_load($app->machineName);

    // Each app can have up to three controls: Info, Configure, and Status
    // (install, installed, installation pending, ...)
    $info_icon = theme('paddle_core_icon', array(
      'type' => 'fa',
      'name' => 'bullhorn',
    ));
    $variables['controls'] = array(
      'info' => array(
        '#theme' => 'link',
        '#text' => $info_icon . t('Info'),
        '#path' => "admin/paddlet_store/app/{$app->machineName}",
        '#options' => array(
          'attributes' => array(
            'class' => array('button-info', 'button'),
          ),
          'html' => TRUE,
        ),
      ),
      'configure' => array('data' => ''),
      'status' => array('data' => ''),
    );

    $pending_icon = theme('paddle_core_icon', array(
      'type' => 'fa',
      'name' => 'magic',
    ));
    if (paddle_apps_app_activation_is_pending($app_array)) {
      // App will be enabled on next cron run.
      $variables['controls']['status'] = array(
        '#markup' => $pending_icon . t('Installing'),
        '#attributes' => array(
          'class' => array(
            'activation-pending',
            'button',
            'button-orange',
          ),
        ),
      );
    }
    elseif (paddle_apps_app_deactivation_is_pending($app_array)) {
      // App will be disabled on next cron run.
      $variables['controls']['status'] = array(
        '#markup' => $pending_icon . t('Uninstalling'),
        '#attributes' => array(
          'class' => array(
            'deactivation-pending',
            'button',
            'button-orange',
          ),
        ),
      );
    }
    elseif (paddle_apps_activate_access($app_array)) {


      if ($app_array->getLevel() == $app_array::LEVEL_FREE) {

        // App is not enabled, but can be enabled.
        ctools_include('modal');
        ctools_modal_add_js();

        $install_path = "admin/paddlet_store/app/{$app->machineName}/nojs/activate";
        $install_icon = theme('paddle_core_icon', array(
          'type' => 'fa',
          'name' => 'plus',
        ));
        $variables['controls']['status'] = array(
          '#theme' => 'link',
          '#text' => $install_icon . t('Install'),
          '#path' => $install_path,
          '#options' => array(
            'attributes' => array(
              'class' => array(
                'enable',
                'ctools-use-modal',
                'button',
                'button-red',
              ),
            ),
            'html' => TRUE,
          ),
        );
      }
      else {
        $mail_icon = theme('paddle_core_icon', array(
          'type' => 'fa',
          'name' => 'envelope',
        ));

        $variables['controls']['status'] = array(
          '#theme' => 'link',
          '#text' => $mail_icon . t('Contact us'),
          '#path' => "https://www.paddle.be/nl/aanvraag-activatie-module?website=" . variable_get('site_name', 'Default') . " - " . $base_url . "&module=" . $app_array->name . "&mail=" . $user->mail . "&name=" . $user->name,
          '#options' => array(
            'attributes' => array(
              'class' => array(
                'button',
                'disabled',
              ),
              'title' => t("This is a paying paddlet. To activate please contact Paddle Support"),
            ),
            'html' => TRUE,
          ),
        );
      }
    }
    elseif (paddle_apps_app_is_active($app_array) && $app_array->restorable) {
      // App is enabled.
      $enabled_icon = theme('paddle_core_icon', array(
        'type' => 'fa',
        'name' => 'check',
      ));
      // App is enabled, but can be disabled.
      ctools_include('modal');
      ctools_modal_add_js();

      $uninstall_path = "admin/paddlet_store/app/{$app->machineName}/nojs/deactivate";
      $uninstall_icon = theme('paddle_core_icon', array(
        'type' => 'fa',
        'name' => 'minus',
      ));
      $variables['controls']['status'] = array(
        '#theme' => 'link',
        '#text' => $uninstall_icon . t('Uninstall'),
        '#path' => $uninstall_path,
        '#options' => array(
          'attributes' => array(
            'class' => array(
              'enable',
              'ctools-use-modal',
              'button',
              'button-red',
            ),
          ),
          'html' => TRUE,
        ),
      );

      // Only show the Configure link if the app can be configured.
      $configure_icon = theme('paddle_core_icon', array(
        'type' => 'fa',
        'name' => 'gears',
      ));
      $configure_path = "admin/paddlet_store/app/{$app->machineName}/configure";
      if (drupal_valid_path($configure_path, TRUE)) {
        $variables['controls']['configure'] = array(
          '#theme' => 'link',
          '#text' => $configure_icon . t('Configure'),
          '#path' => $configure_path,
          '#options' => array(
            'attributes' => array(
              'class' => array(
                'button',
                'button-configure',
              ),
            ),
            'html' => TRUE,
          ),
        );
      }
    }
    elseif (paddle_apps_app_is_active($app_array) && !$app_array->restorable) {
      // App is enabled.
      $enabled_icon = theme('paddle_core_icon', array(
        'type' => 'fa',
        'name' => 'check',
      ));

      $variables['controls']['status'] = array(
        '#markup' => $enabled_icon . t('Installed'),
        '#attributes' => array(
          'class' => array(
            'enabled',
            'button',
            'button-green',
          ),
        ),
      );

      // Only show the Configure link if the app can be configured.
      $configure_icon = theme('paddle_core_icon', array(
        'type' => 'fa',
        'name' => 'gears',
      ));
      $configure_path = "admin/paddlet_store/app/{$app->machineName}/configure";
      if (drupal_valid_path($configure_path, TRUE)) {
        $variables['controls']['configure'] = array(
          '#theme' => 'link',
          '#text' => $configure_icon . t('Configure'),
          '#path' => $configure_path,
          '#options' => array(
            'attributes' => array(
              'class' => array(
                'button',
                'button-configure',
              ),
            ),
            'html' => TRUE,
          ),
        );
      }
    }
    elseif (!paddle_apps_activate_access($app_array)) {
      $install_icon = theme('paddle_core_icon', array(
        'type' => 'fa',
        'name' => 'plus',
      ));

      $variables['controls']['status'] = array(
        '#markup' => $install_icon . t('Install'),
        '#attributes' => array(
          'class' => array(
            'button',
            'button-red',
            'disabled',
          ),
          'data-title' => t("You've reached the maximum number of free and/or extra paddlets. Please upgrade first."),
        ),
      );
    }

    // Render all controls.
    foreach ($variables['controls'] as $key => $control) {
      if ($key == 'status' && isset($control['#attributes'])) {
        $control['#prefix'] = '<span ' . drupal_attributes($control['#attributes']) . '>';
        $control['#suffix'] = '</span>';
        unset($variables['controls'][$key]['#attributes']);
      }

      $variables['controls'][$key] = drupal_render($control);
    }

    // The description and name need to be in the user his set language.
    $options = array();
    if (module_exists('paddle_i18n')) {
      $admin_language = admin_language_retrieve();
      $options['langcode'] = $admin_language->language;
    }

    $variables['level'] = !empty($app->level) ? check_plain($app->level) : '';
    $variables['name'] = !empty($app->name) ? i18n_string_translate('paddle_apps:name:' . $app->machineName, $app->name, $options) : '';
    $variables['machine_name'] = $app->machineName;
    $variables['status'] = $app->status;
    $variables['app_class'] = drupal_html_class('app-' . $app->machineName);
    $variables['description'] = !empty($app->description) ? i18n_string_translate('paddle_apps:description:' . $app->machineName, $app->description, $options) : '';
    $variables['vendor'] = !empty($app->vendor) ? check_plain($app->vendor) : '';
    if (!empty($app->logo['uri'])) {
      $variables['image'] = array(
        '#theme' => 'image',
        '#path' => $app->logo['uri'],
        '#alt' => $variables['name'],
      );
    }
    else {
      $variables['image'] = array('#markup' => t('Image not available'));
    }
  }
}

/**
 * Processes variables for paddle_apps_paddlets_overview.tpl.php.
 *
 * @see paddle_apps_paddlets_overview.tpl.php
 */
function template_preprocess_paddle_apps_paddlets_overview(&$variables) {
  $language = module_exists('paddle_i18n') ? admin_language_retrieve() : language_default();
  $variables['language'] = $language->language;

  // Provide a rendered list of paddlet details to show in the overview.
  if (!empty($variables['apps'])) {
    $variables['paddlets'] = array();
    foreach ($variables['apps'] as $app) {
      if (!empty($app->machineName)) {
        $variables['paddlets'][] = array(
          '#theme' => 'paddle_apps_paddlet_detail',
          '#app' => $app,
        );
      }
    }

    // Add tooltips to buttons.
    drupal_add_js(drupal_get_path('module', 'paddle_apps') . '/javascript/tooltips.js', array('type' => 'file', 'scope' => 'footer'));
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
