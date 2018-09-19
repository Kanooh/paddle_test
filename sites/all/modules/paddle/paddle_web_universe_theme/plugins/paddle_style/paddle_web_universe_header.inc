<?php

/**
 * @file
 * Plugin to declare header settings for a selector.
 */

/**
 * CTools plugin definition.
 */
$plugin = array(
  'label' => 'paddle_web_universe_header',
  'handler' => array(
    'class' => 'PaddleWebUniverseHeaderPlugin',
  ),
);

/**
 * Class PaddleWebUniverseHeaderPlugin.
 */
class PaddleWebUniverseHeaderPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleExecutePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $output = array();

    // Generate a radio box to configure the header type.
    if (empty($allowed_values) || array_key_exists('header_type', $allowed_values)) {
      $output['header_type'] = array(
        '#type' => 'radios',
        '#default_value' => isset($default_values['header_type']) ? $default_values['header_type'] : 'naked_header',
        '#options' => array(
          'naked_header' => t('Content header - naked type'),
          'image_header_full_width' => t('Content header with an image - full width'),
        ),
      );
    }

    // Generate a text field to configure the header title.
    if (empty($allowed_values) || array_key_exists('header_title', $allowed_values)) {
      $output['header_title'] = array(
        '#type' => 'textfield',
        '#default_value' => !empty($default_values['header_title']) ? $default_values['header_title'] : '',
      );
    }

    // Generate a text field to configure the header subtitle.
    if (empty($allowed_values) || array_key_exists('header_title_prefix', $allowed_values)) {
      $output['header_title_prefix'] = array(
        '#type' => 'textfield',
        '#default_value' => !empty($default_values['header_title_prefix']) ? $default_values['header_title_prefix'] : '',
      );
    }

    // Create the form element for the header image field.
    if (empty($allowed_values) || array_key_exists('header_image', $allowed_values)) {
      $output['header_image'] = array(
        '#title' => t('Header image'),
        '#type' => 'managed_file',
        '#default_value' => !empty($default_values['header_image']) ? $default_values['header_image'] : '',
        '#upload_location' => 'public://paddle_web_universe_plugin_data/header',
        '#upload_validators' => array(
          'file_validate_is_image' => array(),
        ),
      );
    }

    if (!empty($default_values['header_image'])) {
      $output['header_image_current'] = array(
        '#type' => 'value',
        '#value' => $default_values['header_image'],
      );
    }

    // Generate a text field to configure the header logo tagline.
    if (empty($allowed_values) || array_key_exists('header_logo_tag_line', $allowed_values)) {
      $output['header_logo_tag_line'] = array(
        '#type' => 'textfield',
        '#title' => t('The tag line accompanying the Flemish lion logo'),
        '#default_value' => !empty($default_values['header_logo_tag_line']) ? $default_values['header_logo_tag_line'] : '',
      );
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   *
   * @TODO: Multilingual variables when deemed necessary.
   */
  public function execute($values, ctools_context $context = NULL) {
    global $conf;

    if (!empty($values['header_image']) && $header_image = file_load($values['header_image'])) {
      $conf['paddle_web_universe_header_image'] = file_create_url($header_image->uri);
    }

    if (!empty($values['header_type'])) {
      $conf['paddle_web_universe_header_type'] = $values['header_type'];
    }

    if (!empty($values['header_title'])) {
      $conf['paddle_web_universe_header_title'] = $values['header_title'];
    }
    if (!empty($values['header_title_prefix'])) {
      $conf['paddle_web_universe_header_title_prefix'] = $values['header_title_prefix'];
    }
    if (!empty($values['header_logo_tag_line'])) {
      $conf['paddle_web_universe_header_logo_tag_line'] = $values['header_logo_tag_line'];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submit($values, ctools_context $context = NULL) {
    // Process the header image file upload.
    $new_fid = isset($values['values']['header_image']) ? $values['values']['header_image'] : NULL;
    $current_fid = isset($values['values']['header_image_current']) ? $values['values']['header_image_current'] : NULL;

    // Only process the file if it has changed.
    if ($current_fid != $new_fid) {
      // Remove the current file.
      if ($current_fid && $file = file_load($current_fid)) {
        file_usage_delete($file, 'paddle_web_universe_theme', 'header_image');
        file_delete($file);
      }
      // Mark the new file as permanent.
      if ($new_fid && $file = file_load($new_fid)) {
        $file->status = FILE_STATUS_PERMANENT;
        file_save($file);
        file_usage_add($file, 'paddle_web_universe_theme', 'header_image', $new_fid);
      }
    }
  }

}
