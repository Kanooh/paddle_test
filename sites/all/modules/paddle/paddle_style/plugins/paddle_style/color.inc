<?php

/**
 * @file
 * Plugin to declare color settings for a selector.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'label' => 'color',
  'handler' => array(
    'class' => 'PaddleStyleColorPlugin',
  ),
);

class PaddleStyleColorPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleStylesPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $output = array();

    // Generate the color field to choose the color.
    $output['color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => t('Font Color'),
      '#default_value' => !empty($default_values['color']) ? $default_values['color'] : '000000',
    );

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function getProperties($values, ctools_context $context = NULL) {
    $properties = array();

    // Sets the color property.
    if (!empty($values['color'])) {
      $properties['color'] = '#' . $values['color'];
    }

    return $properties;
  }

}
