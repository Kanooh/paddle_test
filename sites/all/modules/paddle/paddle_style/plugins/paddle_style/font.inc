<?php

/**
 * @file
 * Plugin to declare font settings for a selector.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'label' => 'font',
  'handler' => array(
    'class' => 'PaddleStyleFontPlugin',
  ),
);

class PaddleStyleFontPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleStylesPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $output = array();

    // Add some basic styling to the form.
    $output['#attached']['css'][] = array(
      'data' => drupal_get_path('module', 'paddle_style') . '/css/font.css',
      'type' => 'file',
    );

    // Check if font families are allowed for this instance.
    if (empty($allowed_values) || array_key_exists('font_family', $allowed_values)) {
      $font_family_options = array(
        'Arial, Helvetica, sans-serif' => 'Arial',
        '"Courier New", Courier, monospace' => 'Courier New',
        'Tahoma, Geneva, sans-serif' => 'sans-serif',
        '"Times New Roman", Times, serif' => 'Times New Roman',
      );
      if (!empty($allowed_values['font_family'])) {
        $font_family_options = $allowed_values['font_family'];
      }

      // Generate the font family form field.
      $output['font_family'] = array(
        '#type' => 'select',
        '#title' => t('Font family'),
        '#options' => $font_family_options,
        '#default_value' => !empty($default_values['font_family']) ? $default_values['font_family'] : '',
      );
    }

    // Check if font sizes are allowed for this instance.
    if (empty($allowed_values) || array_key_exists('font_size', $allowed_values)) {
      $font_size_options = array(
        '48px' => t('Largest'),
        '32px' => t('Very large'),
        '24px' => t('Large'),
        '14px' => t('Normal'),
        '12px' => t('Small'),
        '10px' => t('Very small'),
        '8px' => t('Smallest'),
      );
      if (!empty($allowed_values['font_size'])) {
        $font_size_options = $allowed_values['font_size'];
      }
      // Default to the font size in the middle of the list.
      $font_sizes = array_keys($font_size_options);
      $font_sizes_middle_key = floor((count($font_sizes) - 1) / 2);

      // Generate the font size field.
      $output['font_size'] = array(
        '#type' => 'select',
        '#title' => t('Font size'),
        '#options' => $font_size_options,
        '#default_value' => !empty($default_values['font_size']) ? $default_values['font_size'] : $font_sizes[$font_sizes_middle_key],
      );
    }

    // Check if font sizes by pixels are allowed for this instance.
    if (array_key_exists('font_size_pixels', $allowed_values)) {
      // Generate the font size field.

      $output['font_size_container'] = array(
        '#type' => 'container',
        '#attributes' => array(
          'class' => array(
            'form-item',
            'font-size-container',
          ),
        ),
      );

      $output['font_size_container']['font_size_pixels'] = array(
        '#type' => 'textfield',
        '#title' => t('Font size'),
        '#default_value' => !empty($default_values['font_size_container']['font_size_pixels']) ? $default_values['font_size_container']['font_size_pixels'] : '15',
        '#suffix' => '<span class="suffix">px</span>',
        '#size' => 4,
      );
    }

    // Check if font style settings are allowed for this instance.
    if (empty($allowed_values) || array_key_exists('font_style', $allowed_values)) {
      $font_style_options = array(
        'bold' => t('Bold'),
        'italic' => t('Italic'),
        'underline' => t('Underline'),
      );
      if (!empty($allowed_values['font_style'])) {
        $font_style_options = $allowed_values['font_style'];
      }

      foreach ($font_style_options as $value => $label) {
        $font_style_options[$value] = '<span class="label-text label-hidden">' . $label . '</span>';
      }

      // Create the font style checkboxes.
      $output['font_style'] = array(
        '#title' => t('Font style'),
        '#type' => 'checkboxes',
        '#attributes' => array(
          'class' => array('form-image-checkboxes'),
        ),
        '#options' => $font_style_options,
        '#default_value' => !empty($default_values['font_style']) ? $default_values['font_style'] : array(),
      );

      foreach (array_keys($font_style_options) as $key) {
        $output['font_style'][$key] = array(
          '#attributes' => array(
            'class' => array(drupal_clean_css_identifier('font-style-option-' . $key)),
          ),
        );
      }
    }

    // Check if font capitalization settings are allowed for this instance.
    if (empty($allowed_values) || array_key_exists('font_capitalization', $allowed_values)) {
      $font_capitalization_options = array(
        'none' => t('Normal'),
        'uppercase' => t('Uppercase'),
        'capitalize' => t('Capitalize'),
        'lowercase' => t('Lowercase'),
      );
      if (!empty($allowed_values['font_capitalization'])) {
        $font_capitalization_options = $allowed_values['font_capitalization'];
      }

      foreach ($font_capitalization_options as $value => $label) {
        $font_capitalization_options[$value] = '<span class="label-text label-hidden">' . $label . '</span>';
      }

      // Create the font capitalization radios.
      $output['font_capitalization'] = array(
        '#title' => t('Font capitalization'),
        '#type' => 'radios',
        '#options' => $font_capitalization_options,
        '#default_value' => !empty($default_values['font_capitalization']) ? $default_values['font_capitalization'] : 'none',
        '#attributes' => array(
          'class' => array('form-image-radios'),
        ),
      );

      foreach (array_keys($font_capitalization_options) as $key) {
        $output['font_capitalization'][$key] = array(
          '#attributes' => array(
            'class' => array(drupal_clean_css_identifier('font-capitalization-option-' . $key)),
          ),
        );
      }
    }

    // Check if font color settings are allowed for this instance.
    if (empty($allowed_values) || array_key_exists('font_color', $allowed_values)) {
      $font_color_allowed_values = array();

      // It doesn't seem to be possible to limit the colors in a sensible way,
      // so we are not going to use this variable ($font_color_allowed_values)
      // for the moment.
      if (!empty($allowed_values['font_color'])) {
        foreach ($allowed_values['font_color'] as $color) {
          $color = str_replace('#', '', $color);
          $font_color_allowed_values[] = $color;
        }
      }

      // Add a checkbox to toggle the color picker.
      $output['font_color_enabled'] = array(
        '#type' => 'checkbox',
        '#title' => t('Set color'),
        '#default_value' => !empty($default_values['font_color_enabled']),
        '#attributes' => array(
          'id' => drupal_html_id('paddle-style-font-color-enabled'),
        ),
      );

      // Add the color picker.
      $output['font_color'] = array(
        '#title' => t('Font color'),
        '#type' => 'jquery_colorpicker',
        '#default_value' => !empty($default_values['font_color']) ? $default_values['font_color'] : '',
        '#states' => array(
          'visible' => array(
            '#' . $output['font_color_enabled']['#attributes']['id'] => array('checked' => TRUE),
          ),
        ),
      );

    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function getProperties($values, ctools_context $context = NULL) {
    $properties = array();
    // Sets the font-family property.
    if (!empty($values['font_family'])) {
      $properties['font-family'] = $values['font_family'];
    }

    // Sets the font-size property.
    if (!empty($values['font_size'])) {
      $properties['font-size'] = $values['font_size'];
    }

    // Sets the font-size pixels property.
    if (!empty($values['font_size_container']['font_size_pixels'])) {
      $properties['font-size'] = $values['font_size_container']['font_size_pixels'] . 'px';
    }

    // Sets the font-weight property.
    if (!empty($values['font_style']['bold'])) {
      $properties['font-weight'] = 'bold';
    }

    // Sets the font-style property.
    if (!empty($values['font_style']['italic'])) {
      $properties['font-style'] = 'italic';
    }

    // Sets the text-decoration property.
    if (!empty($values['font_style']['underline'])) {
      $properties['text-decoration'] = 'underline';
    }
    elseif (
        empty($values['font_style']['underline'])
        && !empty($context->data['paddle_theme'])
        && $context->data['paddle_theme']->theme == 'kanooh_theme_v2'
    ) {
      $properties['text-decoration'] = 'none';
    }

    if (!empty($values['font_weight'])) {
      $properties['font-weight'] = $values['font_weight'];
    }

    // Sets the text-transform property.
    if (!empty($values['font_capitalization']) && $values['font_capitalization'] != 'none') {
      $properties['text-transform'] = $values['font_capitalization'];
    }

    // Sets the color property.
    if (!empty($values['font_color']) && !empty($values['font_color_enabled'])) {
      $properties['color'] = '#' . $values['font_color'];
    }

    return $properties;
  }
}
