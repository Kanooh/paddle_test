<?php

/**
 * @file
 * Plugin to allow the user to select pane style from the available subpalettes.
 */

/**
 * CTools plugin definition.
 */
$plugin = array(
  'label' => 'color_subpalettes',
  'handler' => array(
    'class' => 'PaddleColorSubPalettesPlugin',
  ),
);

class PaddleColorSubPalettesPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleClassPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $options = array();

    // Get the sub-palettes from the palette that was chosen as the default by
    // the site administrator.
    $palette = paddle_color_palettes_filter_subpalettes(paddle_color_palettes_get_default_palette());

    if (empty($palette)) {
      return array();
    }

    // If no default value has been saved, default to the primary palette.
    $default_value = !empty($default_values['paddle_color_subpalette']) ? $default_values['paddle_color_subpalette'] : 0;

    // Build the form options, using numerical values as keys. The palette can
    // be changed so we should not rely on the exact subpalette names, but use
    // relative values.
    $i = 0;
    $options = array();
    foreach ($palette as $machine_name => $info) {
      $output = '';

      // Generate a range of color swatches.
      foreach ($info['colors'] as $color) {
        $output .= theme('html_tag', array(
          'element' => array(
            '#tag' => 'div',
            '#attributes' => array(
              'class' => 'paddle-color-palettes-color',
              'color-palette-color' => $color,
            ),
            '#value' => '',
          ),
        ));
      }

      // Wrap them in a container.
      $options[$i++] = theme('html_tag', array(
        'element' => array(
          '#tag' => 'div',
          '#attributes' => array(
            'class' => 'paddle-color-palettes-option',
          ),
          '#value' => $output,
        ),
      ));
    }

    // Output as radio buttons.
    $form['paddle_color_subpalette'] = array(
      '#type' => 'radios',
      '#options' => $options,
      '#default_value' => $default_value,
      '#attributes' => array(
        'class' => array('paddle-color-palettes-image-radios'),
      ),
    );

    $form['#attached']['js'] = array(drupal_get_path('module', 'paddle_color_palettes') . '/js/paddle_color_palettes_plugin_form.js');
    $form['#attached']['css'] = array(drupal_get_path('module', 'paddle_color_palettes') . '/css/paddle_color_palettes_plugin_form.css');

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getClasses($values, ctools_context $context = NULL) {
    // If the value is missing, use the default from the plugin definition.
    if (!isset($values['paddle_color_subpalette'])) {
      $values['paddle_color_subpalette'] = isset($context->data['instance']['default_values']['paddle_color_subpalette']) ? $context->data['instance']['default_values']['paddle_color_subpalette'] : FALSE;
    }
    return $values['paddle_color_subpalette'] !== FALSE ? array('paddle-color-palettes-subpalette-' . $values['paddle_color_subpalette']) : array();
  }

}
