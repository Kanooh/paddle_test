<?php

/**
 * @file
 * Plugin to declare branding settings for a theme.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'label' => 'paddle_core_branding',
  'handler' => array(
    'class' => 'PaddleCoreBrandingPlugin',
  ),
);

class PaddleCoreBrandingPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleExecutePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $values = array(), ctools_context $context = NULL) {
    $elements = array();

    if (empty($allowed_values) || array_key_exists('header_show_logo', $allowed_values)) {
      // Checkbox to determine if the logo should be displayed in the header.
      $elements['header_show_logo'] = array(
        '#type' => 'checkbox',
        '#title' => t('Show logo in header'),
        '#default_value' => !empty($values['header_show_logo']) ? $values['header_show_logo'] : FALSE,
      );
    }

    // Create the form element for the logo image field.
    // Allow to skip the form field.
    if (empty($allowed_values) || array_key_exists('logo', $allowed_values)) {
      $value = isset($values['logo']) ? $values['logo'] : NULL;

      // Provide a file form field.
      $elements['logo'] = array(
        '#title' => t('Logo'),
        '#type' => 'managed_file',
        '#default_value' => $value,
        '#upload_location' => 'public://paddle_core_plugin_data/branding',
        '#upload_validators' => array(
          'file_validate_extensions' => array('jpg jpeg png gif svg'),
          'file_validate_image_resolution' => array('400x400'),
        ),
      );

      // If any files were already uploaded, keep track of them so they can be
      // removed if the user removes or replaces them.
      if ($value) {
        $elements['logo_current'] = array(
          '#type' => 'value',
          '#value' => $value,
        );
      }
    }

    if (empty($allowed_values) || array_key_exists('logo_alt', $allowed_values)) {
      $elements['logo_alt'] = array(
        '#type' => 'textfield',
        '#default_value' => !empty($values['logo_alt']) ? $values['logo_alt'] : '',
        '#attributes' => array(
          'placeholder' => t('Home'),
        ),
      );
    }

    // Create the VO branding form element.
    if (empty($allowed_values) || array_key_exists('vo_branding', $allowed_values) || !empty($values['vo_branding'])) {
      $options = array(
        'no_branding' => t('No branding'),
        'vo_branding' => t('VO branding'),
        'federal_branding' => t('Federal branding'),
      );

      // @TODO: Refactor this element to 'branding' since we apply more branding than only for the VO from now on.
      // This has not been done yet since it requires multiples upgrade paths
      // and export changes.
      $elements['vo_branding'] = array(
        '#type' => 'radios',
        '#title' => '',
        '#default_value' => isset($values['vo_branding']) ? $values['vo_branding'] : 'no_branding',
        '#options' => $options,
        '#attributes' => array(
          'class' => array('form-radios-label-image'),
        ),
        '#access' => empty($allowed_values['vo_branding']) && !empty($values['vo_branding']) ? FALSE : TRUE,
      );

      foreach ($elements['vo_branding']['#options'] as $value => $label) {
        $elements['vo_branding']['#options'][$value] = '<span class="label-text">' . $label . '</span>';
        $elements['vo_branding'][$value] = array(
          '#attributes' => array(
            'class' => array(drupal_clean_css_identifier('vo-branding-option-' . $value)),
          ),
        );
      }

      // Add header and footer token fields.
      $elements['global_vo_tokens'] = array(
        '#type' => 'container',
        '#states' => array(
          // Hide the token fields when 'No VO branding' is selected.
          'visible' => array(
            ':input[name$="[vo_branding]"]' => array(
              'value' => 'vo_branding',
            ),
          ),
        ),
      );
    }

    if (empty($allowed_values) || array_key_exists('global_vo_tokens', $allowed_values)) {
      if (isset($values['vo_branding']) && $values['vo_branding'] == 'vo_branding') {
        $elements['global_vo_tokens']['#attributes']['style'] = array('display: block;');
      }
      else {
        $elements['global_vo_tokens']['#attributes']['style'] = array('display: none;');
      }

      $token_fields = array(
        'header' => t('Token for VO header'),
        'footer' => t('Token for VO footer'),
      );

      foreach ($token_fields as $key => $title) {
        $elements['global_vo_tokens'][$key] = array(
          '#type' => 'textfield',
          '#title' => $title,
          '#description' => t('When left empty, the default VO token will be used.'),
          '#default_value' => isset($values['global_vo_tokens'][$key]) ? $values['global_vo_tokens'][$key] : '',
          '#size' => 32,
          '#maxlength' => 32,
        );
      }
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($values, ctools_context $context = NULL) {
    $errors = array();

    if (!empty($values['global_vo_tokens']['header'])
      && !ctype_xdigit($values['global_vo_tokens']['header'])
    ) {
      $errors['global_vo_tokens][header'] = t('Token for VO header must be hexadecimal.');
    }
    if (!empty($values['global_vo_tokens']['footer'])
      && !ctype_xdigit($values['global_vo_tokens']['footer'])
    ) {
      $errors['global_vo_tokens][footer'] = t('Token for VO footer must be hexadecimal.');
    }

    return $errors;
  }

  /**
   * {@inheritdoc}
   */
  public function submit($values, ctools_context $context = NULL) {
    // Process the logo file upload.
    $new_fid = isset($values['values']['logo']) ? $values['values']['logo'] : NULL;
    $current_fid = isset($values['values']['logo_current']) ? $values['values']['logo_current'] : NULL;

    // Only process the file if it has changed.
    if ($current_fid != $new_fid) {
      // Remove the current file.
      if ($current_fid && $file = file_load($current_fid)) {
        file_usage_delete($file, 'paddle_core', 'logo');
        file_delete($file);
      }
      // Mark the new file as permanent.
      if ($new_fid && $file = file_load($new_fid)) {
        $file->status = FILE_STATUS_PERMANENT;
        file_save($file);
        file_usage_add($file, 'paddle_core', 'logo', $new_fid);
      }
    }

    if (isset($values['values']['vo_branding']) && module_exists('paddle_vo_themes')) {
      $header_id = $values['values']['global_vo_tokens']['header'];
      $footer_id = $values['values']['global_vo_tokens']['footer'];
      paddle_vo_themes_update_header_and_footer($header_id, $footer_id);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function execute($values, ctools_context $context = NULL) {
    global $conf;

    if (!empty($values['header_show_logo'])) {
      $conf['paddle_core_header_show_logo'] = $values['header_show_logo'];
    }
    // Set the logo if we have it.
    $theme_settings = &drupal_static('theme_get_setting', array());
    if (!empty($values['logo']) && $logo = file_load($values['logo'])) {
      $theme_settings[$GLOBALS['theme_key']]['logo'] = file_create_url($logo->uri);
    }

    if (!empty($values['logo_alt'])) {
      $conf['paddle_core_logo_alt'] = $values['logo_alt'];
    }

    // Add the VO branding setting to the global $conf. It will be accessed with
    // variable_get().
    if (!empty($values['vo_branding'])) {
      $conf['include_vo_branding_elements'] = $values['vo_branding'] == 'vo_branding';
    }
    if (module_exists('paddle_vo_themes')) {
      if (isset($values['global_vo_tokens']['header'])) {
        $conf['vo_global_header'] = paddle_vo_themes_global_header_block_view($values['global_vo_tokens']['header']);
      }
      if (isset($values['global_vo_tokens']['footer'])) {
        $conf['vo_global_footer'] = paddle_vo_themes_global_footer_block_view($values['global_vo_tokens']['footer']);
      }
    }
    if (!empty($values['vo_branding']) && $values['vo_branding'] == 'federal_branding') {
      $conf['federal_branding'] = TRUE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function onClone($values, ctools_context $context = NULL) {
    // Increase the file usage counter for the logo image.
    if (!empty($values['logo'])) {
      $file = file_load($values['logo']);
      if ($file) {
        file_usage_add($file, 'paddle_core', 'logo', $values['logo']);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function onDelete($values, ctools_context $context = NULL) {
    // Decrease the file usage counter on the logo image and delete it if it is
    // no longer used.
    if (!empty($values['logo'])) {
      $file = file_load($values['logo']);

      if (!empty($file)) {
        file_usage_delete($file, 'paddle_core', 'logo', $values['logo']);
        file_delete($file);
      }
    }
  }

}
