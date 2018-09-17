<?php

/**
 * @file
 * Plugin that allows to define the box model for a selector.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'label' => 'boxmodel',
  'handler' => array(
    'class' => 'PaddleStyleBoxModelPlugin',
  ),
);

class PaddleStyleBoxModelPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleStylesPluginInterface {

  /**
   * Stores supported properties along with their human readable names.
   *
   * @var array $properties
   */
  public $properties;

  /**
   * Class constructor.
   */
  public function __construct() {
    // Define supported properties with their human readable names.
    $this->properties = array(
      'margin' => array(
        'margin_left' => t('Left margin'),
        'margin_right' => t('Right margin'),
        'margin_top' => t('Top margin'),
        'margin_bottom' => t('Bottom margin'),
      ),
      'padding' => array(
        'padding_left' => t('Left padding'),
        'padding_right' => t('Right padding'),
        'padding_top' => t('Top padding'),
        'padding_bottom' => t('Bottom padding'),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $elements = array();

    // Add some basic styling to the form.
    $elements['#attached']['css'][] = array(
      'data' => drupal_get_path('module', 'paddle_style') . '/css/boxmodel.css',
      'type' => 'file',
    );

    // Generate form fields.
    foreach ($this->properties as $group => $properties) {
      // Create a container for each group.
      $elements[$group] = array(
        '#type' => 'container',
        '#attributes' => array(
          'class' => array(
            'paddle-style-plugin-boxmodel-group',
            'paddle-style-plugin-boxmodel-group-' . $group,
          ),
        ),
      );
      // Place the properties in the group container.
      foreach ($properties as $property => $title) {
        if (empty($allowed_values) || array_key_exists($property, $allowed_values)) {
          $elements[$group][$property] = array(
            '#type' => 'textfield',
            '#title' => $title,
            '#size' => 6,
            '#default_value' => !empty($default_values[$group][$property]) ? trim($default_values[$group][$property]) : '',
          );
        }
      }
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($values, ctools_context $context = NULL) {
    $validation_errors = array();

    // Check if the values are numeric.
    foreach ($this->properties as $group => $properties) {
      foreach ($properties as $property => $title) {
        $value = !empty($values[$group][$property]) ? trim($values[$group][$property]) : '';
        if (!empty($value) && !is_numeric($value)) {
          $validation_errors[$group . '][' . $property] = t('@property needs to be a numeric value.', array('@property' => $title));
        }
      }
    }

    return $validation_errors;
  }

  /**
   * {@inheritdoc}
   */
  public function getProperties($values, ctools_context $context = NULL) {
    $css_properties = array();

    foreach ($this->properties as $group => $properties) {
      foreach ($properties as $property => $title) {
        if (!empty($values[$group][$property])) {
          $css_properties[strtr($property, '_', '-')] = $values[$group][$property] . 'px';
        }
      }
    }

    return $css_properties;
  }

}
