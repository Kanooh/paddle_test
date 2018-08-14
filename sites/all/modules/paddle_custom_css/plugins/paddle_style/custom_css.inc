<?php

/**
 * @file
 * Paddle Style plugin that allows to set a custom custom_css_file.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'label' => 'custom_css',
  'handler' => array(
    'class' => 'PaddleCustomCssPlugin',
  ),
);

class PaddleCustomCssPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleExecutePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $values = array(), ctools_context $context = NULL) {
    $elements = array();

    // Create the form element for the custom css field.
    if (empty($allowed_values) || array_key_exists('custom_css_file', $allowed_values)) {
      $value = isset($values['custom_css_file']) ? $values['custom_css_file'] : NULL;

      // Provide a file form field.
      $elements['custom_css_file'] = array(
        '#type' => 'managed_file',
        '#default_value' => $value,
        '#upload_location' => 'public://paddle_style_plugin_data/custom_css_file',
        '#upload_validators' => array(
          'file_validate_extensions' => array('css'),
        ),
        '#weight' => 1,
      );

      // If any files were already uploaded, keep track of them so they can be
      // removed if the user removes or replaces them.
      if ($value) {
        $elements['custom_css_file_current'] = array(
          '#type' => 'value',
          '#value' => $value,
        );
      }
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function submit($values, ctools_context $context = NULL) {
    // Process the custom css file upload.
    $new_fid = isset($values['values']['custom_css_file']) ? $values['values']['custom_css_file'] : NULL;
    $current_fid = isset($values['values']['custom_css_file_current']) ? $values['values']['custom_css_file_current'] : NULL;

    // Only process the file if it has changed.
    if ($current_fid != $new_fid) {
      // Remove the current file.
      if ($current_fid && $file = file_load($current_fid)) {
        file_usage_delete($file, 'paddle_style', 'custom_css_file');
        file_delete($file);
      }
      // Mark the new file as permanent.
      if ($new_fid && $file = file_load($new_fid)) {
        $file->status = FILE_STATUS_PERMANENT;
        file_save($file);
        file_usage_add($file, 'paddle_style', 'custom_css_file', $new_fid);
      }

      // Clear the CSS cache for the theme to remove any cached code from the
      // previous css file.
      $theme = $context->data['paddle_theme'];
      ctools_include('css');
      ctools_css_clear($theme->name);
      drupal_clear_css_cache();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function execute($values, ctools_context $context = NULL) {
    // Set the custom css file if we have it.
    $file = file_load($values['custom_css_file']);
    if ($file) {
      $options = array(
        'type' => 'file',
        'group' => CSS_THEME,
        'every_page' => TRUE,
        'weight' => 50,
      );
      drupal_add_css($file->uri, $options);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function onClone($values, ctools_context $context = NULL) {
    // Increase the file usage counter for the custom css file.
    if (!empty($values['custom_css_file'])) {
      $file = file_load($values['custom_css_file']);
      file_usage_add($file, 'paddle_style', 'custom_css_file', $values['custom_css_file']);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function onDelete($values, ctools_context $context = NULL) {
    // Decrease the file usage counter on the custom css file and delete it if
    // it is no longer used.
    if (!empty($values['custom_css_file'])) {
      $file = file_load($values['custom_css_file']);

      if (!empty($file)) {
        file_usage_delete($file, 'paddle_style', 'custom_css_file', $values['custom_css_file']);
        file_delete($file);
      }
    }
  }
}
