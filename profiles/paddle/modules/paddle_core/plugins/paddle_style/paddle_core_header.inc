<?php

/**
 * @file
 * Plugin to declare header settings for a selector.
 */

/**
 * CTools plugin definition.
 */
$plugin = array(
  'label' => 'paddle_core_header',
  'handler' => array(
    'class' => 'PaddleCoreHeaderPlugin',
  ),
);

class PaddleCoreHeaderPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleExecutePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $output = array();

    // Generate a textfield to configure the header title.
    if (empty($allowed_values) || array_key_exists('header_title', $allowed_values)) {
      $output['header_title'] = array(
        '#type' => 'textfield',
        '#default_value' => !empty($default_values['header_title']) ? $default_values['header_title'] : '',
        '#attributes' => array(
          'placeholder' => variable_get('site_name', t('Title')),
        ),
      );
    }

    // Generate a textfield to configure the header subtitle.
    if (empty($allowed_values) || array_key_exists('header_subtitle', $allowed_values)) {
      $output['header_subtitle'] = array(
        '#type' => 'textfield',
        '#default_value' => !empty($default_values['header_subtitle']) ? $default_values['header_subtitle'] : '',
        '#attributes' => array(
          'placeholder' => t('Subtitle'),
        ),
      );
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function execute($values, ctools_context $context = NULL) {
    global $conf;
    if (!empty($values['header_title'])) {
      $conf['paddle_core_header_title'] = $values['header_title'];
    }
    if (!empty($values['header_subtitle'])) {
      $conf['paddle_core_header_subtitle'] = $values['header_subtitle'];
    }
  }

}
