<?php

/**
 * @file
 * Plugin to add Theme grid settings for a theme.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'label' => 'paddle_core_theme_grid',
  'handler' => array(
    'class' => 'PaddleCoreThemeGridPlugin',
  ),
);

class PaddleCoreThemeGridPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleExecutePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $output = array();

    if (empty($allowed_values) || isset($allowed_values['navigation_behaviour'])) {
      $output['navigation_behaviour'] = array(
        '#type' => 'radios',
        '#attributes' => array(
          'class' => array('form-radios-label-image'),
        ),
        '#title' => t('Dropdown'),
        '#default_value' => isset($default_values['navigation_behaviour']) ? $default_values['navigation_behaviour'] : 'no_dropdown',
        '#options' => array(
          'no_dropdown' => t('No dropdown'),
          'simple_dropdown' => t('Simple dropdown'),
          'mega_dropdown' => t('Mega dropdown'),
        ),
      );

      foreach ($output['navigation_behaviour']['#options'] as $value => $label) {
        $output['navigation_behaviour']['#options'][$value] = '<span class="label-text">' . $label . '</span>';
        $output['navigation_behaviour'][$value] = array(
          '#attributes' => array(
            'class' => array(drupal_clean_css_identifier('navigation_behaviour-option-' . $value)),
          ),
        );
      }
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function execute($values, ctools_context $context = NULL) {
    global $conf;
    $conf['paddle_core_theme_grid_navigation_behaviour'] = $values['navigation_behaviour'];
  }

}
