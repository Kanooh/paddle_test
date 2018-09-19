<?php

/**
 * @file
 * Plugin to declare the position of header elements.
 */

/**
 * CTools plugin definition.
 */
$plugin = array(
  'label' => 'header_positioning',
  'handler' => array(
    'class' => 'PaddleStyleHeaderPositionPlugin',
  ),
);

/**
 * Class PaddleStyleHeaderPositionPlugin.
 */
class PaddleStyleHeaderPositionPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleCssPluginInterface, PaddleStyleExecutePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $output = array();

    $output['header_position'] = array(
      '#type' => 'radios',
      '#title' => '',
      '#default_value' => isset($default_values['header_position']) ? $default_values['header_position'] : 'standard',
      '#options' => array(
        'standard' => t('Standard'),
        'customized' => t('Customized'),
      ),
      '#attributes' => array(
        'class' => array('form-radios-header-position'),
      ),
    );

    $output['position_fields'] = array(
      '#id' => 'page-wide-header-position',
      '#type' => 'fieldset',
      '#states' => array(
        'visible' => array(
          ':input[name="header[header_positioning][sections][form_elements][header_positioning][header_position]"]' => array('value' => 'customized'),
        ),
      ),
    );

    $output['position_fields']['logo'] = array(
      '#type' => 'radios',
      '#title' => t('Logo'),
      '#default_value' => isset($default_values['position_fields']['logo']) ? $default_values['position_fields']['logo'] : 'left',
      '#options' => array(
        'left' => t('Left'),
        'center' => t('Center'),
      ),
      '#attributes' => array(
        'class' => array('form-radios-header-logo-position'),
      ),
    );

    $output['position_fields']['navigation'] = array(
      '#type' => 'radios',
      '#title' => t('Navigation'),
      '#default_value' => isset($default_values['position_fields']['navigation']) ? $default_values['position_fields']['navigation'] : 'right',
      '#options' => array(
        'right' => t('Right'),
        'center' => t('Center'),
      ),
      '#attributes' => array(
        'class' => array('form-radios-header-navigation-position'),
      ),
    );

    $output['position_fields']['sticky_header'] = array(
      '#type' => 'checkbox',
      '#title' => t('Sticky header'),
      '#default_value' => isset($default_values['position_fields']['sticky_header']) ? $default_values['position_fields']['sticky_header'] : FALSE,
      '#attributes' => array(
        'class' => array('form-sticky-header'),
      ),
    );

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($values, ctools_context $context = NULL) {
    // If logo and menu are both in the center then we cant user sticky header.
    $validation_errors = array();
    if (($values['position_fields']['logo'] == 'center') && ($values['position_fields']['navigation'] == 'center') && $values['position_fields']['sticky_header'] == TRUE) {
      $validation_errors['position_fields'] = t('You can not use sticky header with logo and Navigation positioned at the center of the header.');
    }
    return $validation_errors;
  }

  /**
   * {@inheritdoc}
   */
  public function execute($values, ctools_context $context = NULL) {
    global $conf;

    $conf['paddle_page_wide_theme_customized_header'] = $values['header_position'] == 'customized' ? TRUE : FALSE;
    $conf['paddle_page_wide_theme_sticky_header'] = $values['position_fields']['sticky_header'] == TRUE ? TRUE : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getCss($values, ctools_context $context = NULL) {
    $output = '';
    if ($values['header_position'] == 'customized') {
      $logo_value = $values['position_fields']['logo'];
      $navigation_value = $values['position_fields']['navigation'];

      $output .= "@media (min-width: 889px) {\n";
      if ($logo_value == 'left') {
        if ($navigation_value == 'right') {
          $output .= "body.customized-header .logo  {\n margin-right: auto;\n}\n";
        }
        elseif ($navigation_value == 'center') {
          $output .= "body.customized-header .logo {\n padding-right: 50px;\n}\n";
        }
      }
      elseif ($logo_value == 'center') {
        if ($navigation_value == 'right') {
          $output .= "body.customized-header .logo  {\n  margin-left: 33%;\n}\n";
          $output .= "body.customized-header .navigation  {\n margin-left: auto;\n}\n";
        }

        if ($navigation_value == 'center') {
          $output .= "body.customized-header .header-row  {\n  flex-direction: column;\n -webkit-flex-direction: column;\n -webkit-box-direction: normal;\n -webkit-box-orient: vertical;\n margin-top: initial;\n height: 100%;\n margin-top: 15px;\n}\n";
          $output .= "body.customized-header .logo, body.customized-header .navigation  {\n  align-self: center;\n}\n";
          $output .= "body.customized-header .navigation  {\n  max-width: calc(100% - 20px);\n}\n";
          $output .= "body.customized-header .region-service-links  {\n  width: 90%;\n right: 0;\n}\n";
        }
      }
    }

    $output .= "\n}\n";

    return $output;
  }

}
