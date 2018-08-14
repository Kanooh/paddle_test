<?php

/**
 * @file
 * A Paddle Style plugin for testing the Paddle Themer module.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'label' => 'paddle_themer_test',
  'handler' => array(
    'class' => 'PaddleThemerTestPlugin',
  ),
);

class PaddleThemerTestPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleStylesPluginInterface, PaddleStyleExecutePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $output = array();

    $options = array(
      'css' => t('Render CSS'),
      'exec' => t('Execute execute()'),
    );

    // Create the font emphasis checkboxes.
    $output['options'] = array(
      '#type' => 'checkboxes',
      '#options' => $options,
      '#default_value' => !empty($default_values['options']) ? $default_values['options'] : array(),
    );

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($values, ctools_context $context = NULL) {
    $errors = array();

    // Return a validation error when both checkboxes are checked.
    if (!empty($values['options']['css']) && !empty($values['options']['exec'])) {
      $errors['options][css'] = t('This CSS cannot be rendered.');
    }

    return $errors;
  }

  /**
   * {@inheritdoc}
   */
  public function submit($values, ctools_context $context = NULL) {
    // Output a message so we can verify this code has been run.
    drupal_set_message('The form has been submitted.');
  }

  /**
   * {@inheritdoc}
   */
  public function getProperties($values, ctools_context $context = NULL) {
    // Get the value. If it's not set, fallback to the default value.
    $value = isset($values['options']['css']) ? $values['options']['css'] : $values['default_values']['css'];

    // Output the value and context so we can check the passed arguments.
    drupal_set_message('getProperties():' . check_plain($value) . '/' . check_plain($context->data['machine_name']));

    // Return some custom CSS.
    return array('-paddle-themer-test' => '1');
  }

  /**
   * {@inheritdoc}
   */
  public function execute($values, ctools_context $context = NULL) {
    // Get the value. If it's not set, fallback to the default value.
    $value = isset($values['options']['exec']) ? $values['options']['exec'] : $values['default_values']['exec'];

    // Output the value and context so we can check the passed arguments.
    drupal_set_message('execute():' . check_plain($value) . '/' . check_plain($context->data['machine_name']));
  }

}
