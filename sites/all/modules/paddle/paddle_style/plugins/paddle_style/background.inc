<?php

/**
 * @file
 * Plugin to declare background settings for a selector.
 *
 * This plugin provides control over the "background" CSS properties. It handles
 * the following CSS properties:
 * - 'background-color': The plugin provides a text-field which displays the hex
 *   value of the picked color and a user-friendly way of choosing the color
 *   using the jQuery colorpicker plugin. The color cannot be entered as text,
 *   it must be chosen from the colorpicker dialog. The plugin also provides a
 *   checkbox to disable the background color. This will make the color fully
 *   transparent.
 * - 'background-image': The plugin allows the user to have an image displayed
 *   as a background. If this is set it can be either pattern which allows the
 *   user to choose a tiled background image or a custom image uploaded by the
 *   user.
 * - 'background-position': Allows the user to choose how the background image
 *   will be positioned. This can be chosen using a full set of radio buttons.
 * - 'background-attachment': The user can choose 'fixed' and 'scroll'.
 * - 'background-repeat': All 4 options are supported.
 *
 * The form elements that are shown can be controlled in theme-settings.php by
 * the following 'allowed values':
 * - 'background_color': Set to TRUE to enable the color picker.
 * - 'background_image': Allows to upload a file to set as a background. If this
 *   is set to TRUE the only validation that occurs is to check that the file is
 *   actually an image. Optionally an array can be passed with additional
 *   validation options:
 *   - 'max_resolution': The maximum dimensions allowed. Example: '640x480'.
 *   - 'min_resolution': The minimum dimensions allowed. Example: '16x16'.
 *   - 'max_file_size': The maximum size of the image, in bytes.
 * - 'background_pattern': Your theme can provide a curated list of background
 *   patterns. This is an array, keyed by pattern machine name, containing an
 *   array with the following keys:
 *   - 'title': the human readable title of the pattern.
 *   - 'file': the path to the pattern image file.
 * - 'background_repeat': Set to TRUE to display the repeat options. This will
 *   only work in combination with 'background_image' or 'background_pattern'.
 * - 'background_attachment': Set to TRUE to display the attachment options.
 *   This only works in combination with 'background_image' or
 *   'background_pattern'.
 * - 'background_position': Set to TRUE to display the position options. This
 *   only works in combination with 'background_image' or 'background_pattern'.
 *
 * If no allowed values are specified, all form elements (except patterns) will
 * be displayed.
 *
 * Supported default values:
 * - 'color_enabled': Either TRUE or FALSE.
 * - 'background_color': The color of your choice (eg 'fc800c').
 * - 'background_pattern': A machine name of a pattern, or one of the following:
 *   - 'no_image': No pattern chosen by default.
 *   - 'upload_image': An uploaded image.
 * - 'background_image': The file id of an uploaded image.
 * - 'background_repeat': 'no-repeat', 'repeat-x', 'repeat-y' or 'repeat'.
 * - 'background_attachment': 'fixed' or 'scroll'.
 * - 'background_position': 'left top', 'center bottom' etc.
 *
 * Full example:
 * @code
 * function mytheme_paddle_themer_plugin_instances() {
 *   $plugin_instances = array();
 *   $plugin_instances['header_background'] = array(
 *     'plugin' => 'background',
 *     'selector' => 'header',
 *     'allowed_values' => array(
 *       'background_color' => TRUE,
 *       'background_pattern' => array(
 *         'tiles' => array(
 *           'title' => t('Tiles'),
 *           'file' => '/path_to_my_theme/patterns/tiles.png',
 *         ),
 *         'stripes' => array(
 *           'title' => t('Stripes'),
 *           'file' => '/path_to_my_theme/patterns/stripes.png',
 *         ),
 *       ),
 *       'background_image' => array(
 *         'max_resolution' => '1440x100',
 *         'min_resolution' => '10x10',
 *         'max_file_size' => 102400,
 *       ),
 *       'background_repeat' => TRUE,
 *       'background_attachment' => TRUE,
 *       'background_position' => TRUE,
 *     ),
 *     'default_values' => array(
 *       'color_enabled' => TRUE,
 *       'background_color' => 'eeeeee',
 *       'background_pattern' => 'no_image',
 *     ),
 *   );
 *   return $plugin_instances;
 * }
 * @endcode
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'label' => 'background',
  'handler' => array(
    'class' => 'PaddleStyleBackgroundPlugin',
  ),
);

class PaddleStyleBackgroundPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleStylesPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $output = array();

    // Generate the color field to choose the background color.
    if (empty($allowed_values) || array_key_exists('background_color', $allowed_values)) {
      $output['color_enabled'] = array(
        '#type' => 'checkbox',
        '#title' => t('Background color'),
        '#default_value' => !empty($default_values['color_enabled']) && $default_values['color_enabled'] == 1 ? 1 : 0,
        '#attributes' => array(
          'id' => drupal_html_id('paddle-style-background-color-enabled'),
          'class' => array('paddle-style-background-checkbox'),
        ),
        '#access' => empty($allowed_values) ? TRUE : $allowed_values['background_color'],
      );

      // Add the color picker.
      $output['background_color'] = array(
        '#type' => 'jquery_colorpicker',
        '#default_value' => !empty($default_values['background_color']) ? $default_values['background_color'] : '',
        '#access' => empty($allowed_values) ? TRUE : $allowed_values['background_color'],
      );

      // Add state to hide the colorpicker if it is not enabled.
      $output['background_color']['#states'] = array(
        'visible' => array(
          '#' . $output['color_enabled']['#attributes']['id'] => array('checked' => TRUE),
        ),
      );
    }

    // Add the background pattern selector, image upload field and controls on
    // positioning the background.
    $image_is_allowed = empty($allowed_values) || !empty($allowed_values['background_image']);
    $patterns_are_allowed = !empty($allowed_values['background_pattern']);

    if ($image_is_allowed || $patterns_are_allowed) {
      // Add the option to disable the background image / pattern.
      $options = array('no_image' => t('No background'));

      // Add options for the patterns.
      if ($patterns_are_allowed) {
        foreach ($allowed_values['background_pattern'] as $machine_name => $value) {
          $options[$machine_name] = $value['title'];
        }
      }

      // Add an option for the background image.
      if ($image_is_allowed) {
        $options['upload_image'] = t('Upload an image');
      }

      // Wrap the image selector choices to allow targeting them with css.
      foreach ($options as $value => $label) {
        $options[$value] = '<span class="label-text label-hidden paddle-style-background-' . drupal_clean_css_identifier($value) . '">' . $label . '</span>';
      }

      // Output the radios, wrapped with a unique ID so we can target it for
      // state changes.
      $radios_id = drupal_html_id('paddle-style-background-pattern');
      $output['background_pattern'] = array(
        '#type' => 'radios',
        '#options' => $options,
        '#prefix' => '<div id="' . $radios_id . '">',
        '#suffix' => '</div>',
        '#default_value' => !empty($default_values['background_pattern']) ? $default_values['background_pattern'] : 'no_image',
        '#attributes' => array(
          'class' => array('form-image-radios'),
        ),
      );

      // Append the image upload field.
      if ($image_is_allowed) {
        $background_image_value = isset($default_values['background_image']) ? $default_values['background_image'] : NULL;
        $output['background_image'] = array(
          '#type' => 'managed_file',
          '#title' => t('Image'),
          '#default_value' => $background_image_value,
          '#upload_location' => 'public://paddle_style_plugin_data/background',
          '#attributes' => array(
            'id' => drupal_html_id('paddle_style_background_image'),
          ),
          '#states' => array(
            'visible' => array(
              '#' . $radios_id . ' input[name$="[background_pattern]"]' => array('value' => 'upload_image'),
            ),
          ),
        );

        // Define file upload validators. We require that the file is an image.
        $validators = array('file_validate_is_image' => array());

        // Check if additional file validators are given in the allowed values.
        if (!empty($allowed_values['background_image']) && is_array($allowed_values['background_image'])) {
          // Define supported file validators.
          $supported_validators = array(
            'min_resolution' => 'fileValidateImageResolution',
            'max_resolution' => 'fileValidateImageResolution',
            'max_file_size' => 'file_validate_size',
          );

          foreach ($allowed_values['background_image'] as $key => $value) {
            if (array_key_exists($key, $supported_validators)) {
              $validator = $supported_validators[$key];
              // Use a custom handler for the validator if it exists.
              if (method_exists($this, $validator)) {
                $this->$validator($validators, $key, $value);
              }
              // Otherwise just add the value to the list of validators.
              else {
                $validators[$validator] = array($value);
              }
            }
          }
        }
        // Add the file validators to the form.
        $output['background_image']['#upload_validators'] = $validators;

        // If a file was already uploaded, keep track of it so it can be removed
        // if the user removes or replaces it.
        if ($background_image_value) {
          $output['background_image_current'] = array(
            '#type' => 'value',
            '#value' => $background_image_value,
          );
        }
      }

      // Set the background-position property.
      // @todo Provide more fine-grained control over which position options to
      //   allow.
      if (empty($allowed_values) || !empty($allowed_values['background_position'])) {
        $output['background_position'] = array(
          '#type' => 'radios',
          '#title' => t('Starting position'),
          '#options' => array(
            'left top' => t('Left top'),
            'left center' => t('Left center'),
            'left bottom' => t('Left bottom'),
            'right top' => t('Right top'),
            'right center' => t('Right center'),
            'right bottom' => t('Right bottom'),
            'center top' => t('Center top'),
            'center center' => t('Center center'),
            'center bottom' => t('Center bottom'),
          ),
          '#prefix' => '<div id="' . drupal_html_id('paddle-style-background-position') . '" class="paddle-style-background-position">',
          '#suffix' => '</div>',
          '#default_value' => isset($default_values['background_position']) ? $default_values['background_position'] : 'center center',
          '#states' => array(
            'invisible' => array(
              "#{$radios_id} :input" => array('value' => 'no_image'),
            ),
          ),
        );
      }

      // Set the background-attachment property.
      // @todo Provide more fine-grained control over which attachment options
      //   to allow.
      if (empty($allowed_values) || !empty($allowed_values['background_attachment'])) {
        $output['background_attachment'] = array(
          '#type' => 'radios',
          '#title' => t('Background behaviour'),
          '#options' => array(
            'fixed' => t('Fixed'),
            'scroll' => t('Scroll'),
          ),
          '#prefix' => '<div id="' . drupal_html_id('paddle-style-background-attachment') . '" class="paddle-style-background-attachment">',
          '#suffix' => '</div>',
          '#default_value' => isset($default_values['background_attachment']) ? $default_values['background_attachment'] : 'scroll',
          '#states' => array(
            'invisible' => array(
              "#{$radios_id} :input" => array('value' => 'no_image'),
            ),
          ),
        );
      }

      // Set the background-repeat property.
      // @todo Provide more fine-grained control over which repetition options
      //   to allow.
      if (empty($allowed_values) || !empty($allowed_values['background_repeat'])) {
        $output['background_repeat'] = array(
          '#type' => 'radios',
          '#title' => '',
          '#options' => array(
            'no-repeat' => t('Do not repeat'),
            'repeat-x' => t('Repeat horizontally'),
            'repeat-y' => t('Repeat vertically'),
            'repeat' => t('Repeat both directions'),
          ),
          '#prefix' => '<div id="' . drupal_html_id('paddle-style-background-repeat') . '" class="paddle-style-background-repeat">',
          '#suffix' => '</div>',
          '#default_value' => isset($default_values['background_repeat']) ? $default_values['background_repeat'] : 'no-repeat',
          '#states' => array(
            'invisible' => array(
              "#{$radios_id} :input" => array(
                'value' => 'no_image',
              ),
            ),
          ),
        );
      }
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($values, ctools_context $context = NULL) {
    $validation_errors = array();

    // Check that a file has been uploaded when a custom image upload has been
    // chosen.
    if (
      isset($values['background_pattern']) &&
      $values['background_pattern'] == 'upload_image' &&
      empty($values['background_image'])
    ) {
      $validation_errors['background_image'] = t('Please upload a background image.');
    }

    return $validation_errors;
  }

  /**
   * {@inheritdoc}
   */
  public function submit($values, ctools_context $context = NULL) {
    // If the form has been validated we need to mark the background image as
    // permanent.
    $new_fid = isset($values['values']['background_image']) ? $values['values']['background_image'] : NULL;
    $current_fid = isset($values['values']['background_image_current']) ? $values['values']['background_image_current'] : NULL;

    // Only process the file if it has changed.
    if ($current_fid != $new_fid) {
      // Remove the current file.
      if ($current_fid && $file = file_load($current_fid)) {
        file_usage_delete($file, 'paddle_style', 'background_image');
        file_delete($file);
      }
      // Mark the new file as permanent.
      if ($new_fid && $file = file_load($new_fid)) {
        $file->status = FILE_STATUS_PERMANENT;
        file_save($file);
        file_usage_add($file, 'paddle_style', 'background_image', $new_fid);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getProperties($values, ctools_context $context = NULL) {
    $properties = array();

    // Sets the background-color property.
    if (!empty($values['color_enabled']) && !empty($values['background_color'])) {
      $rgb = array();
      for ($i = 0; $i < 6; $i = $i + 2) {
        $rgb[] = hexdec(substr($values['background_color'], $i, 2));
      }

      // @todo A real opacity slider would be a nice enhancement.
      $properties['background-color'] = 'rgba(' . implode(', ', $rgb) . ', 1)';
    }

    // Sets the background-image property. This can be either a pattern or an
    // uploaded image.
    if (isset($values['background_pattern']) && $values['background_pattern'] != 'no_image') {
      if ($values['background_pattern'] == 'upload_image') {
        $image = file_load($values['background_image']);
        $url = !empty($image) ? file_create_url($image->uri) : '';
      }
      else {
        $pattern = $values['background_pattern'];
        $patterns = &$context->data['instance']['allowed_values']['background_pattern'];
        $url = !empty($patterns[$pattern]['file']) ? file_create_url($patterns[$pattern]['file']) : '';
      }
      if ($url) {
        $properties['background-image'] = 'url(' . $url . ')';
      }

      // Sets the background attachment property.
      if (!empty($values['background_attachment'])) {
        $properties['background-attachment'] = $values['background_attachment'];
      }

      // Sets the background repeat property.
      if (!empty($values['background_repeat'])) {
        $properties['background-repeat'] = $values['background_repeat'];
      }

      // Sets the background position property.
      if (!empty($values['background_position'])) {
        $properties['background-position'] = $values['background_position'];
      }
    }

    return $properties;
  }

  /**
   * Callback for setting image resolution file validator options.
   *
   * @see PaddleStyleBackgroundPlugin::form()
   *
   * @param array $validators
   *   An associative array, keyed by file validator, with values that will be
   *   passed to the file validator. This will be updated.
   * @param string $option
   *   The option being set, either 'min_resolution' or 'max_resolution'.
   * @param string $value
   *   The value for the given option, eg '500x200'.
   */
  protected function fileValidateImageResolution(&$validators, $option, $value) {
    switch ($option) {
      case 'max_resolution':
        $max_resolution = $value;
        $min_resolution = !empty($validators['file_validate_image_resolution'][1]) ? $validators['file_validate_image_resolution'][1] : 0;
        break;

      case 'min_resolution':
        $min_resolution = $value;
        $max_resolution = !empty($validators['file_validate_image_resolution'][0]) ? $validators['file_validate_image_resolution'][0] : 0;
        break;
    }
    $validators['file_validate_image_resolution'] = array($max_resolution, $min_resolution);
  }

  /**
   * {@inheritdoc}
   */
  public function onClone($values, ctools_context $context = NULL) {
    // Increase the file usage counter for the background image.
    if (!empty($values['background_pattern']) && $values['background_pattern'] == 'upload_image' && $values['background_image'] > 0) {
      $file = file_load($values['background_image']);
      if ($file) {
        file_usage_add($file, 'paddle_style', 'background_image', $values['background_image']);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function onDelete($values, ctools_context $context = NULL) {
    // Decrease the file usage counter on the background image and delete it if
    // it is no longer used.
    if (!empty($values['background_pattern']) && $values['background_pattern'] == 'upload_image' && $values['background_image'] > 0) {
      $file = file_load($values['background_image']);

      if (!empty($file)) {
        file_usage_delete($file, 'paddle_style', 'background_image', $values['background_image']);
        file_delete($file);
      }
    }
  }

}
