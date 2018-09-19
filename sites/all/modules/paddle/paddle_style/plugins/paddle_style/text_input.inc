<?php

/**
 * @file
 * Class defining a generic text input plugin.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'label' => 'text_input',
  'handler' => array(
    'class' => 'PaddleStyleTextInputPlugin',
  ),
);

class PaddleStyleTextInputPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleExecutePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    // We require a machine name.
    if (empty($context->data['machine_name'])) {
      throw new Exception('Missing required machine name.');
    }

    $elements = array();

    // As we have only one element in this plugin we don't need allowed_values.
    $machine_name = $context->data['machine_name'];
    $title = isset($context->data['instance']['label']) ? $context->data['instance']['label'] : '';
    $elements[$machine_name] = array(
      '#type' => 'textfield',
      '#title' => $title,
      '#default_value' => isset($default_values[$machine_name]) ? $default_values[$machine_name] : FALSE,
    );

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function execute($values, ctools_context $context = NULL) {
    global $conf;

    // We require a machine name.
    if (empty($context->data['machine_name'])) {
      throw new Exception('Missing required machine name.');
    }

    // Set a variable based on the machine name.
    $machine_name = $context->data['machine_name'];
    if (isset($values[$machine_name])) {
      $conf['paddle_style_' . $machine_name] = $values[$machine_name];
    }
  }

}
