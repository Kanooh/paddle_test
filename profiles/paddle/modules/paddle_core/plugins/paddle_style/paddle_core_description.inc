<?php

/**
 * @file
 * Plugin to declare description for a theme.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'label' => 'paddle_core_description',
  'handler' => array(
    'class' => 'PaddleCoreDescriptionPlugin',
  ),
);

class PaddleCoreDescriptionPlugin extends PaddleStyleConfigurablePlugin {

  /**
   * Maximum number of characters allowed.
   * @var int $maxNumChars
   */
  public $maxNumChars = 1000;

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $output = array();

    $output['description'] = array(
      '#type' => 'textarea',
      '#default_value' => isset($default_values['description']) ? $default_values['description'] : '',
      '#resizable' => FALSE,
    );

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($values, ctools_context $context = NULL) {
    $errors = array();
    $plain_chars = preg_replace('/\s+/', '', trim(strip_tags($values['description'])));

    if (strlen($plain_chars) > $this->maxNumChars) {
      $errors['description'] = t('The description should not be longer than @number characters.', array('@number' => $this->maxNumChars));
    }

    return $errors;
  }

}
