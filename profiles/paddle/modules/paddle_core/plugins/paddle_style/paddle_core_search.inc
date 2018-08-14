<?php

/**
 * @file
 * Plugin to declare search settings for a theme.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'label' => 'paddle_core_search',
  'handler' => array(
    'class' => 'PaddleCoreSearchPlugin',
  ),
);

class PaddleCoreSearchPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleExecutePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $elements = array();

    $elements['search_placeholder_text_checkbox'] = array(
      '#type' => 'checkbox',
      '#title' => t('Standard text search field'),
      '#default_value' => isset($default_values['search_placeholder_text_checkbox']) ? $default_values['search_placeholder_text_checkbox'] : TRUE,
    );

    $elements['search_placeholder_text'] = array(
      '#type' => 'textfield',
      '#title' => '',
      '#maxlength' => 64,
      '#default_value' => !empty($default_values['search_placeholder_text']) ? $default_values['search_placeholder_text'] : t('Looking for what?'),
      '#states' => array(
        'visible' => array(
          '#edit-header-search-box-sections-form-elements-search-box-options-search-placeholder-text-checkbox' => array('checked' => FALSE),
        ),
      ),
    );

    $elements['search_placeholder_button_label_checkbox'] = array(
      '#type' => 'checkbox',
      '#title' => t('Standard text search button'),
      '#default_value' => isset($default_values['search_placeholder_button_label_checkbox']) ? $default_values['search_placeholder_button_label_checkbox'] : TRUE,
    );

    $elements['search_placeholder_button_label'] = array(
      '#type' => 'textfield',
      '#title' => '',
      '#maxlength' => 12,
      '#default_value' => !empty($default_values['search_placeholder_button_label']) ? $default_values['search_placeholder_button_label'] : t('Search'),
      '#states' => array(
        'visible' => array(
          '#edit-header-search-box-sections-form-elements-search-box-options-search-placeholder-button-label-checkbox' => array('checked' => FALSE),
        ),
      ),
    );

    // Get all available search engines.
    $engines = paddle_core_get_search_engines();

    // Only show the additional form elements if more then engine is found.
    if (count($engines) > 1) {
      // Set for all engines a checkbox to enable/disable the search engine,
      // and a text field to adjust the label.
      foreach ($engines as $engine_name => $engine_info) {
        $enabled_key = $engine_name . '_enabled';
        $text_key = $engine_name . '_text';

        $elements[$engine_name . '_enabled'] = array(
          '#type' => 'checkbox',
          '#title' => check_plain($engine_info['label']),
          '#default_value' => isset($default_values[$enabled_key]) ? $default_values[$enabled_key] : $engine_info['enabled'],
          '#states' => array(
            'visible' => array(
              '#edit-header-search-box-sections-form-elements-show-search-box-show-search-box' => array('checked' => TRUE),
            ),
          ),
        );

        $elements[$engine_name . '_text'] = array(
          '#type' => 'textfield',
          '#title' => '',
          '#default_value' => !empty($default_values[$text_key]) ? $default_values[$text_key] : $engine_info['text'],
          '#states' => array(
            'visible' => array(
              '#edit-header-search-box-sections-form-elements-show-search-box-show-search-box' => array('checked' => TRUE),
            ),
          ),
        );
      }
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function execute($values, ctools_context $context = NULL) {
    global $conf;

    foreach ($values as $key => $value) {
      $conf['paddle_search_' . $key] = $value;
    }

    if (empty($values['search_placeholder_button_label_checkbox']) && !empty($values['search_placeholder_button_label'])) {
      $conf['search_placeholder_button_label'] = $values['search_placeholder_button_label'];
    }

    if (empty($values['search_placeholder_text_checkbox']) && !empty($values['search_placeholder_text'])) {
      $conf['search_placeholder_text'] = $values['search_placeholder_text'];
    }
  }
}