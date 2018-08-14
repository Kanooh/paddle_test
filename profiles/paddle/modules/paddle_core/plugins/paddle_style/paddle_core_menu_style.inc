<?php

/**
 * @file
 * Plugin to declare menu style settings for a theme.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'label' => 'paddle_core_menu_style',
  'handler' => array(
    'class' => 'PaddleCoreMenuStylePlugin',
  ),
);

class PaddleCoreMenuStylePlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleExecutePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $elements = array();

    // Get all available menu styles.
    $menu_styles = paddle_core_get_menu_styles();

    // Only show the additional form elements if more then 1 style is found.
    if (count($menu_styles) > 0) {
      $options = array();
      // Set a default radio button for no menu style.
      $options['no_dropdown'] = t('No dropdown');

      // Set radio buttons to choose a menu style.
      foreach ($menu_styles as $style_name => $style_info) {
        $options[$style_name] = $style_info['text'];
      }

      $elements['menu_style'] = array(
        '#type' => 'radios',
        '#title' => '',
        '#default_value' => !empty($default_values['menu_style']) ? $default_values['menu_style'] : 'no_dropdown',
        '#options' => $options,
        '#description' => t('Choose a menu style.'),
      );
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function execute($values, ctools_context $context = NULL) {
    global $conf;

    $conf['paddle_menu_style'] = $values['menu_style'];
  }

}
