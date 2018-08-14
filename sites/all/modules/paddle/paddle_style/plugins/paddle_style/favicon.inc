<?php

/**
 * @file
 * Paddle Style plugin that allows to set a custom favicon.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'label' => 'favicon',
  'handler' => array(
    'class' => 'PaddleStyleFaviconPlugin',
  ),
);

class PaddleStyleFaviconPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleExecutePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $values = array(), ctools_context $context = NULL) {
    $elements = array();

    // Create the form element for the favicon image field.
    if (empty($allowed_values) || array_key_exists('favicon', $allowed_values)) {
      $value = isset($values['favicon']) ? $values['favicon'] : NULL;

      // Provide a file form field.
      $elements['favicon'] = array(
        '#type' => 'managed_file',
        '#default_value' => $value,
        '#upload_location' => 'public://paddle_style_plugin_data/favicon',
        '#upload_validators' => array(
          'file_validate_extensions' => array('ico'),
        ),
        '#weight' => 1,
      );

      // If any files were already uploaded, keep track of them so they can be
      // removed if the user removes or replaces them.
      if ($value) {
        $elements['favicon_current'] = array(
          '#type' => 'value',
          '#value' => $value,
        );
      }
    }

    if (empty($allowed_values) || array_key_exists('toggle', $allowed_values)) {
      // Checkbox to determine if a custom favicon is to be used.
      $elements['toggle'] = array(
        '#type' => 'checkbox',
        '#title' => t('Upload a custom favicon'),
        '#default_value' => !empty($values['toggle']) ? $values['toggle'] : FALSE,
        '#attributes' => array(
          'id' => drupal_html_id('paddle-style-favicon-toggle'),
        ),
        '#weight' => 0,
      );

      $elements['favicon']['#states'] = array(
        'visible' => array(
          '#' . $elements['toggle']['#attributes']['id'] => array('checked' => TRUE),
        ),
      );
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function submit($values, ctools_context $context = NULL) {
    // Process the favicon file upload.
    $new_fid = isset($values['values']['favicon']) ? $values['values']['favicon'] : NULL;
    $current_fid = isset($values['values']['favicon_current']) ? $values['values']['favicon_current'] : NULL;

    // Only process the file if it has changed.
    if ($current_fid != $new_fid) {
      // Remove the current file.
      if ($current_fid && $file = file_load($current_fid)) {
        file_usage_delete($file, 'paddle_style', 'favicon');
        file_delete($file);
      }
      // Mark the new file as permanent.
      if ($new_fid && $file = file_load($new_fid)) {
        $file->status = FILE_STATUS_PERMANENT;
        file_save($file);
        file_usage_add($file, 'paddle_style', 'favicon', $new_fid);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function execute($values, ctools_context $context = NULL) {
    // Set the favicon if we have it.
    $toggle = !isset($values['toggle']) || $values['toggle'];
    $favicon = !empty($values['favicon']) ? file_load($values['favicon']) : FALSE;
    if ($toggle && $favicon) {
      global $theme_key;
      $theme_settings = &drupal_static('theme_get_setting', array());
      $theme_settings[$theme_key]['toggle_favicon'] = TRUE;
      $theme_settings[$theme_key]['favicon'] = file_create_url($favicon->uri);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function onClone($values, ctools_context $context = NULL) {
    // Increase the file usage counter for the favicon image.
    if (!empty($values['favicon'])) {
      $file = file_load($values['favicon']);
      if ($file) {
        file_usage_add($file, 'paddle_style', 'favicon', $values['favicon']);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function onDelete($values, ctools_context $context = NULL) {
    // Decrease the file usage counter on the favicon image and delete it if it
    // is no longer used.
    if (!empty($values['favicon'])) {
      $file = file_load($values['favicon']);

      if (!empty($file)) {
        file_usage_delete($file, 'paddle_style', 'favicon', $values['favicon']);
        file_delete($file);
      }
    }
  }
}
