<?php

/**
 * @file
 * API documentation for the Paddle Style module.
 */

/**
 * Demonstration Paddle Style plugin.
 *
 * This plugin allows to turn elements upside down and rotate elements when
 * moused over.
 *
 * This is a standard CTools plugin. Place it in your custom module in a folder
 * named 'plugins/paddle_style', name it {plugin_name}.inc and implement
 * hook_ctools_plugin_directory() so that CTools knows where to look for it.
 */

/**
 * CTools plugin definition.
 */
$plugin = array(
  'label' => 'rotate',
  'handler' => array(
    'class' => 'PaddleStyleDemoPlugin',
  ),
);

/**
 * We extend the PaddleStyleConfigurablePlugin abstract class rather than
 * implementing PaddleStyleConfigurablePluginInterface because we do not need to
 * provide form validation.
 *
 * We implement both available interfaces:
 * - PaddleStyleStylesPluginInterface: allows us to provide CSS.
 * - PaddleStyleExecutePluginInterface: allows to run custom code, in this case
 *   to inject some javascript into the page.
 */
class PaddleStyleDemoPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleStylesPluginInterface, PaddleStyleExecutePluginInterface {

  /**
   * Provides form elements that allow to configure the plugin.
   *
   * We allow to configure two properties in this form:
   * - 'upside_down': a checkbox that will allow the element to be turned upside
   *   down.
   * - 'rotate': this allows elements to rotate when moused over. The user can
   *   configure the rotation speed and direction.
   *
   * @param array $allowed_values
   *   The $allowed_values parameter can be used to filter out unwanted
   *   settings for particular page elements. For example the site builder /
   *   themer can decide that for some page elements the rotating behaviour
   *   should not be available, and others should not be turned upside down.
   *   If a property has a range of settings it is also possible to limit these.
   *   For example if you want to disable the 'upside_down' property, and only
   *   allow two speeds for the 'rotate' property, you can use the following:
   *     $allowed_values = array(
   *       'rotate' => array(
   *         'speed' => array(
   *           0,
   *           250,
   *           1000,
   *         ),
   *         // Allow to configure the 'direction' option.
   *         'direction' => array(),
   *       ),
   *     );
   *
   * @param array $default_values
   *   An associative array, keyed on property name, defining the default values
   *   for the form elements which are being generated.
   * @param ctools_context $context
   *   The optional CTools context for which the form elements are defined.
   *
   * @return array
   *   An array with the form elements which need to be rendered in the form.
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $elements = array();

    // Generate a checkbox to configure the 'upside down' functionality.
    if (empty($allowed_values) || array_key_exists('upside_down', $allowed_values)) {
      $elements['upside_down'] = array(
        '#type' => 'checkbox',
        '#title' => t('Turn upside down'),
        '#default_value' => !empty($default_values['upside_down']) ? 1 : 0,
      );
    }

    // Generate form elements to configure the 'rotate' functionality.
    // In this example if the 'rotate' property is allowed, the 'rotation speed'
    // option is always shown, but the 'direction' is only shown when it is
    // explicitly allowed (or inexplicitly by passing an empty array). We could
    // also opt to show only one or the other depending on whether they are
    // 'allowed', or both at the same time. Plugins are free to interpret the
    // $allowed_values as they wish, as long as they keep the full spectrum of
    // options available when an empty array is passed.
    if (empty($allowed_values) || array_key_exists('rotate', $allowed_values)) {
      // Define a whitelist of supported rotation speed options. Plugins can
      // decide for themselves if they want to work with a whitelist of options,
      // or overwrite the options entirely if custom options are given.
      $rotate_options = array(
        0 => t('Do not rotate'),
        2500 => t('Very slow'),
        1000 => t('Slow'),
        500 => t('Moderate'),
        250 => t('Fast'),
        100 => t('Very fast'),
      );

      // Check if the rotate options have been limited.
      if (!empty($allowed_values['rotate']['speed'])) {
        $rotate_options = array_intersect_key($rotate_options, array_flip($allowed_values['rotate']['speed']));
      }
      $elements['rotate']['speed'] = array(
        '#type' => 'select',
        '#title' => t('Rotate on hover'),
        '#options' => $rotate_options,
        '#default_value' => !empty($default_values['rotate']['speed']) ? $default_values['rotate']['speed'] : '',
      );

      // Allow to select the rotation direction by typing it in. This allows to
      // demonstrate form validation.
      // Only show this when explicitly allowed, or when the option is left
      // empty.
      if (empty($allowed_values['rotate']) || isset($allowed_values['rotate']['direction'])) {
        $elements['rotate']['direction'] = array(
          '#type' => 'textfield',
          '#title' => t('Direction'),
          '#default_value' => !empty($default_values['rotate']['direction']) ? $default_values['rotate']['direction'] : '',
          '#description' => t('Either "left" or "right".'),
          '#maxlength' => 6,
          '#required' => TRUE,
        );
      }
    }

    return $elements;
  }

  /**
   * Validate arguments. Return error message if validation failed.
   *
   * We cannot use form_set_error() here since we don't know how the framework
   * that is using our plugins has constructed its forms. Instead we return an
   * array with any validation errors that occurred.
   *
   * @param array $values
   *   An array which contains the values which need to be validated.
   * @param ctools_context $context
   *   The optional CTools context for which the form elements are defined.
   *
   * @return array
   *   An associative array, keyed by property name, of validation errors that
   *   occurred. Return an empty array if there were no validation errors.
   */
  public function validate($values, ctools_context $context = NULL) {
    $validation_errors = array();

    // If the rotation direction is given we check if the direction is either
    // 'left' or 'right'.
    if (!empty($values['rotate']['direction']) && !in_array(strtolower($values['rotate']['direction']), array('left', 'right'))) {
      // Note the use of square brackets here. This is identical to how
      // form_set_error() handles options in a hierarchical tree.
      // @see form_set_error();
      $validation_errors['rotate][direction'] = t('The direction should be either "left" or "right".');
    }
    return $validation_errors;
  }

  /**
   * Submit handler. Allows to perform actions on form submit.
   *
   * We have no use for the submit handler in this example. The form values
   * should be handled by the framework that uses these plugins.
   * This is only needed if certain actions need to be performed that the
   * implementing framework does not know about. For example storing uploaded
   * files, or saving values to a database.
   *
   * @param array $values
   *   An array which contains the values that were submitted.
   * @param ctools_context $context
   *   The optional CTools context for which the form elements are defined.
   */
  public function submit($values, ctools_context $context = NULL) {}

  /**
   * Map the style properties to the values.
   *
   * @param array $values
   *   An array with the form elements with their values.
   * @param ctools_context $context
   *   The optional CTools context for which the form elements are defined.
   *
   * @return array
   *   Returns an array containing the css property mapped to the value of the
   *   corresponding form element.
   */
  public function getProperties($values, ctools_context $context = NULL) {
    $properties = array();

    // Check if we need to apply CSS to turn the element upside down.
    if (!empty($values['upside_down'])) {
      $properties['-webkit-transform'] = 'rotate(-180deg)';
      $properties['-moz-transform'] = 'rotate(-180deg)';
    }

    return $properties;
  }

  /**
   * Allows plugins to execute custom code.
   *
   * Your plugin might need to be able to execute some custom code. For example
   * you might need to include some javascript on the page, or manipulate Drupal
   * variables by changing the global $conf array.
   *
   * The Paddle Themer implementation for example will execute this code on
   * hook_init().
   *
   * @param array $values
   *   An array containing the form elements with their values.
   * @param ctools_context $context
   *   The optional CTools context for which the form elements are defined.
   */
  public function execute($values, ctools_context $context = NULL) {
    // If the element should rotate, we include the necessary javascript and
    // pass the speed and direction by using the element name as a setting.
    // The element is in this case supplied by the implementing framework as a
    // CTools context.
    if (!empty($values['rotate']['speed'])) {
      // Add the rotation script (not included in this example :).
      drupal_add_js(drupal_get_path('module', 'mymodule') . '/js/rotate.js');

      // Pass our data as settings to js.
      $data = array(
        'mymodule_rotate' => array(
          $context->data => array(
            'speed' => $values['rotate']['speed'],
            'direction' => isset($values['rotate']['direction']) ? $values['rotate']['direction'] : 'right',
          ),
        ),
      );
      drupal_add_js($data, 'setting');
    }
  }

}
