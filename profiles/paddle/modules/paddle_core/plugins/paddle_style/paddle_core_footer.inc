<?php

/**
 * @file
 * Plugin to declare footer settings for a selector.
 */

/**
 * CTools plugin definition.
 */
$plugin = array(
  'label' => 'paddle_core_footer',
  'handler' => array(
    'class' => 'PaddleCoreFooterPlugin',
  ),
);

class PaddleCoreFooterPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleExecutePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $output = array();

    // Create the Footer Style form element.
    if (empty($allowed_values) || array_key_exists('footer_style', $allowed_values)) {
      $default_options = array(
        'no_footer' => array(
          'title' => t('No footer'),
          'description' => t('No footer is displayed.'),
        ),
        'thin_footer' => array(
          'title' => t('Thin footer'),
          'description' => t('Level 1 menu items will display when available.'),
        ),
        'fat_footer' => array(
          'title' => t('Fat footer'),
          'description' => t('Level 1 and Level 2 menu items will display when available.'),
        ),
      );

      $options = array_merge($default_options, paddle_core_get_footer_styles());

      $output['footer_style'] = array(
        '#type' => 'radios',
        '#title' => '',
        '#default_value' => isset($default_values['footer_style']) ? $default_values['footer_style'] : 'thin_footer',
        '#options' => $options,
        '#attributes' => array(
          'class' => array('form-radios-label-image'),
        ),
      );

      $output['footer_descriptions'] = array(
        '#type' => 'markup',
        '#markup' => '<ul id="paddle-footer-styles-descriptions">',
      );

      foreach ($output['footer_style']['#options'] as $key => $value) {
        $output['footer_style']['#options'][$key] = '<span class="label-text">' . $value['title'] . '</span>';
        $output['footer_descriptions']['#markup'] .= '<li class="description-text">' . $value['title'] . ': ' . $value['description'] . '</li>';
      }
      $output['footer_descriptions']['#markup'] .= '</ul>';
    }

    // Create the Footer Title form element.
    if (empty($allowed_values) || array_key_exists('footer_title', $allowed_values)) {
      $output['footer_title'] = array(
        '#type' => 'textfield',
        '#title' => t('Footer title'),
        '#default_value' => !empty($default_values['footer_title']) ? $default_values['footer_title'] : '',
        '#attributes' => array(
          'placeholder' => t('Enter a title'),
        ),
      );
    }

    // Create the Footer Subtitle form element.
    if (empty($allowed_values) || array_key_exists('footer_subtitle', $allowed_values)) {
      $output['footer_subtitle'] = array(
        '#type' => 'textfield',
        '#title' => t('Footer subtitle'),
        '#default_value' => !empty($default_values['footer_subtitle']) ? $default_values['footer_subtitle'] : '',
        '#attributes' => array(
          'placeholder' => t('Enter a subtitle'),
        ),
      );
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function submit($values, ctools_context $context = NULL) {
    // Clear the menu display cache of the footer menu.
    foreach (paddle_menu_display_get_displays() as $menu_display) {
      if ($menu_display->name == 'footer_menu') {
        $cid = $menu_display->mdid . ':%';
        module_load_include('inc', 'paddle_menu_display', 'paddle_menu_display_main');
        paddle_menu_display_cache_clear_by_cid($cid, FALSE);
        break;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function execute($values, ctools_context $context = NULL) {
    global $conf;
    $conf['paddle_core_footer_footer_style'] = isset($values['footer_style']) ? $values['footer_style'] : NULL;
    $conf['paddle_core_footer_footer_title'] = !empty($values['footer_title']) ? check_plain($values['footer_title']) : FALSE;
    $conf['paddle_core_footer_footer_subtitle'] = !empty($values['footer_subtitle']) ? check_plain($values['footer_subtitle']) : FALSE;
  }

}
