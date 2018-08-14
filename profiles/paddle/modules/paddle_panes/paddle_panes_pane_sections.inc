<?php

/**
 * @file
 * Contains functions to add top and bottom sections to panes.
 */

/**
 * The default weight of the body section.
 */
define('BODY_SECTION_WEIGHT', 0);

/**
 * Returns list of sections to add to the pane.
 *
 * @return array
 *   An associative array of additional sections, keyed on pane machine name.
 *   Each section is an associative array with the following keys:
 *   - 'title': A human readable title for the section.
 *   - 'weight': The weight that is used to determine the position of the
 *     section relative to the other sections.
 */
function paddle_panes_pane_sections_additional_sections() {
  return array(
    'top' => array(
      'title' => t('Top'),
      'weight' => -5,
    ),
    'bottom' => array(
      'title' => t('Bottom'),
      'weight' => 5,
    ),
  );
}

/**
 * Generates the HTML for a pane section and adds it to $body_output.
 *
 * The passed in $body_output will be updated with the rendered HTML.
 *
 * @param string $body_output
 *   The output generated for the body section of the pane.
 * @param array $conf
 *   The Ctools values array $conf.
 * @param ctools_context $context
 *   The Ctools context object.
 */
function paddle_panes_pane_sections_render(&$body_output, $conf, $context) {
  // Wrap the body section in div.
  if (!empty($body_output)) {
    $class = 'pane-section-body';
    if (isset($conf['view_mode'])) {
      $class .= ' view-mode-' . $conf['view_mode'];
    }

    $body_output = theme('html_tag', array(
      'element' => array(
        '#tag' => 'div',
        '#attributes' => array('class' => $class),
        '#value' => $body_output,
      ),
    ));
  }

  foreach (paddle_panes_pane_sections_additional_sections() as $section_name => $section) {
    $output = '';
    if (!empty($conf[$section_name])) {
      $config = $conf[$section_name];

      // Wrap the section in div.
      $class = 'pane-section-' . str_replace('_', '-', str_replace(' ', '-', strtolower($section_name)));

      // Add the view mode as a class.
      if (isset($conf['view_mode'])) {
        $class .= ' view-mode-' . $conf['view_mode'];
      }

      if (isset($config['enable_section']) && $config['enable_section']) {
        // The output (if any) is either text or image.
        if (isset($config['section_content_type']) && $config['section_content_type'] == 'image' && !empty($config['section_image'])) {
          // Older configuration of panes has the atom id as an integer, while
          // the newer code saves it as an array because the atom field always
          // returns it's value as an array since the R20141023 update. When we
          // access the atom id from the old configuration (stored as integer)
          // as if it were an array it would only take the first number of the
          // atom id and cut off the rest. Example: 1537 would become 1.
          // Therefor we need to do a check to see if the atom id is stored
          // inside an array or not before we retrieve it from the array.
          // See commits 27f5d176ecbd848278756def445fe1b3736f9de7 and
          // e5081bf02fd12ebc3564227e56df427d37b42999.
          $section_image = $config['section_image'];
          $atom_id = _paddle_panes_pane_section_extract_section_image_atom_id($section_image);

          $atom = scald_atom_load($atom_id);
          if ($atom) {
            $image_style = isset($section_image['style']) ? $section_image['style'] : NULL;
            $variables = array(
              'atom' => $atom,
              'image_style' => $image_style,
              'attributes' => array('class' => array('top-section-image')),
            );
            $output = theme('paddle_scald_render_atom', $variables);

            // Add an extra class when the sections contains an image.
            $class .= ' pane-section-image';
          }
        }
        elseif (!empty($config['section_text'])) {
          $output = $config['section_text'];
          // Retrieve if the top section text should be displayed as
          // <h2> or <div>.
          $pane_section_top_h2 = variable_get('paddle_style_display_pane_top_as_h2', FALSE);

          // Add the icon to the text in the top section.
          if (!empty($config['icon_image']) && $atom = scald_atom_load(_paddle_panes_pane_section_extract_section_image_atom_id($config['icon_image']))) {
            $top_section = array(
              'icon' => array(
                '#theme' => 'html_tag',
                '#tag' => 'div',
                '#attributes' => array('class' => 'top-section-icon'),
                '#value' => theme('paddle_scald_render_atom', array('atom' => $atom, 'image_style' => 'top_section_icon')),
              ),
              'text' => array(
                '#theme' => 'html_tag',
                '#tag' => ($pane_section_top_h2) ? 'h2' : 'div',
                '#attributes' => array('class' => 'top-section-text-with-icon'),
                '#value' => $config['section_text'],
              ),
            );
            $class .= ' pane-section-icon';
            $output = render($top_section);
          }
          elseif ($section_name == 'top' && $pane_section_top_h2) {
            $top_section = array(
              'text' => array(
                '#theme' => 'html_tag',
                '#tag' => 'h2',
                '#attributes' => array('class' => 'top-section-text u-named-anchor'),
                '#value' => $config['section_text'],
              ),
            );
            $output = drupal_render($top_section);
          }
        }


        // Check if there is a URL to put a link on the output.
        if (!empty($config['section_url_type']) && $config['section_url_type'] != 'no_link') {
          // Add the icon for the arrow.
          $render = array(
            '#markup' => $output,
          );
          // If section is bottom, then we place the right chevron.
          if ($section_name == 'bottom') {
            $render['#suffix'] = '<i class="fa fa-chevron-right"></i>';
          }
          $output = drupal_render($render);

          if ($config['section_url_type'] == 'internal' && $config['section_internal_url'] != '') {
            $matches = array();
            $result = preg_match('/node\/(\d+)/', $config['section_internal_url'], $matches);
            if ($result && strpos($matches[0], 'node/') === 0) {
              $output = l($output, $matches[0], array('html' => TRUE));
            }
          }
          elseif ($config['section_url_type'] == 'external' && $config['section_external_url'] != '') {
            $output = l($output, $config['section_external_url'], array('html' => TRUE, 'external' => TRUE));
          }
        }

        $output = theme('html_tag', array(
          'element' => array(
            '#tag' => 'div',
            '#attributes' => array(
              'class' => $class,
            ),
            '#value' => $output,
          ),
        ));
        if ($section['weight'] < BODY_SECTION_WEIGHT) {
          $body_output = $output . $body_output;
        }
        else {
          $body_output .= $output;
        }
      }
    }
  }
}

/**
 * Builds the pane edit form integrating the body with the sections forms.
 *
 * @param array $form
 *   The form that is about to be displayed.
 * @param array $form_state
 *   The form_state array.
 */
function paddle_panes_pane_sections_build_edit_form(&$form, &$form_state) {
  $conf = $form_state['conf'];

  $context_language = '';
  if (isset($form_state['contexts']['panelizer']->data->language)) {
    $context_language = $form_state['contexts']['panelizer']->data->language;
  }

  if (isset($form_state['contexts']['panelizer']->data->nid)) {
    $nid = $form_state['contexts']['panelizer']->data->nid;
    $uuid = $form_state['pane']->uuid;
    $form['anchor'] = array(
      '#markup' => l('<i class="fa fa-link" aria-hidden="true"></i>', 'node/' . $nid, array(
        'fragment' => $uuid,
        'alias' => TRUE,
        'html' => TRUE,
        'attributes' => array('class' => 'panel-link', 'title' => t('Click on your right mouse button and copy the link to obtain a direct link to this pane.')),
      )),
    );
  }

  $form['pane_settings'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
  );

  foreach (paddle_panes_pane_sections_additional_sections() as $section_name => $section) {
    $form['pane_settings'][$section_name] = array(
      '#type' => 'fieldset',
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      '#tree' => TRUE,
      '#weight' => $section['weight'],
      '#title' => $section['title'],
      '#attributes' => array(
        'class' => array(drupal_html_class('pane-section-' . $section_name)),
      ),
    );
    $section_form = paddle_panes_pane_sections_section_form($section_name, $section['title'], $conf, $context_language);
    $form['pane_settings'][$section_name] = array_merge($form['pane_settings'][$section_name], $section_form);
  }
  if (isset($form['section_body'])) {
    $form['pane_settings']['section_body'] = $form['section_body'];
    $form['pane_settings']['section_body']['#weight'] = 0;
    unset($form['section_body']);
  }
  $form['#attributes'] = array('class' => array('paddle-add-pane-form'));
  $form['buttons']['#attributes'] = array('class' => array('form-buttons'));
}

/**
 * Generates form elements for a pane section's edit form.
 *
 * @param string $section_name
 *   The machine name of the section for which to generate the form.
 * @param string $section_text
 *   The human-readable name of the section for which to generate the form.
 * @param string $conf
 *   Ctools configuration variable.
 * @param string $parent_lang
 *   The language of the parent node where the autocomplete is being called.
 *
 * @return array
 *   Returns array of form elements.
 */
function paddle_panes_pane_sections_section_form($section_name, $section_text, $conf, $parent_lang) {
  $form = array();
  // Add the checkbox that is used to disable the whole section.
  $form['enable_section'] = array(
    '#type' => 'checkbox',
    '#weight' => -10,
    '#title' => t('Enable'),
    '#default_value' => isset($conf[$section_name]['enable_section']) ? $conf[$section_name]['enable_section'] : FALSE,
  );

  $form['section_wrapper'] = array(
    '#type' => 'container',
    '#attributes' => array(
      'class' => array('section-wrapper'),
    ),
    '#states' => array(
      'visible' => array(
        ':input[name="' . $section_name . '[enable_section]"]' => array('checked' => TRUE),
      ),
    ),
  );

  $form['section_wrapper']['section_text'] = array(
    '#title' => t('Text'),
    '#type' => 'textfield',
    '#weight' => 0,
    '#attributes' => array(
      'placeholder' => t('Enter a text.'),
    ),
    '#default_value' => isset($conf[$section_name]['section_text']) ? $conf[$section_name]['section_text'] : '',
    '#states' => array(
      'required' => array(
        ':input[name="' . $section_name . '[enable_section]"]' => array('checked' => TRUE),
      ),
    ),
  );

  if ($section_name == 'top') {
    // Only show / require the text box when "text" is selected.
    $selected_type = ':input[name="' . $section_name . '[section_wrapper][section_content_type]"]';
    $form['section_wrapper']['section_text']['#states']['required'] += array(
      $selected_type => array(
        'value' => 'text',
      ),
    );
    $form['section_wrapper']['section_text']['#states']['visible'] = array(
      $selected_type => array(
        'value' => 'text',
      ),
    );

    $options = array(
      'text' => t('Text'),
      'image' => t('Image'),
    );
    $form['section_wrapper']['section_content_type'] = array(
      '#type' => 'radios',
      '#title' => '',
      '#weight' => -5,
      '#default_value' => isset($conf[$section_name]['section_content_type']) ? $conf[$section_name]['section_content_type'] : 'text',
      '#options' => $options,
    );

    // Take into account old configurations.
    if (isset($conf[$section_name]['section_image']) && !isset($conf[$section_name]['section_image']['sid'])) {
      $conf[$section_name]['section_image'] = array('sid' => $conf[$section_name]['section_image']);
    }

    $form['section_wrapper']['section_image'] = array(
      '#title' => t('Image file'),
      '#type' => 'paddle_scald_image_atom',
      '#weight' => 5,
      '#default_value' => isset($conf[$section_name]['section_image']) ? $conf[$section_name]['section_image'] : array(),
      '#states' => array(
        'required' => array(
          ':input[name="' . $section_name . '[enable_section]"]' => array('checked' => TRUE),
          $selected_type => array('value' => 'image'),
        ),
        'visible' => array(
          $selected_type => array('value' => 'image'),
        ),
      ),
    );

    // Add the icon for the text of the top section.
    $form['section_wrapper']['icon_image'] = array(
      '#title' => t('Icon'),
      '#button_text' => t('Choose icon'),
      '#type' => 'paddle_scald_atom',
      '#allowed_types' => array('image'),
      '#weight' => 5,
      '#default_value' => isset($conf[$section_name]['icon_image']) ? $conf[$section_name]['icon_image'] : array(),
      '#states' => array(
        'visible' => array(
          $selected_type => array('value' => 'text'),
        ),
      ),
    );
  }

  // Internal path and external URL.
  $form['section_wrapper']['section_url_type'] = array(
    '#type' => 'radios',
    '#weight' => 10,
    '#title' => t('Type of the URL'),
    '#options' => array(
      'no_link' => t('No link'),
      'internal' => t('Internal'),
      'external' => t('External'),
    ),
    '#default_value' => isset($conf[$section_name]['section_url_type']) ? $conf[$section_name]['section_url_type'] : 'no_link',
  );

  $form['section_wrapper']['section_internal_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Node'),
    '#weight' => 15,
    '#default_value' => isset($conf[$section_name]['section_internal_url']) ? $conf[$section_name]['section_internal_url'] : '',
    '#size' => 60,
    '#autocomplete_path' => 'admin/paddle_panes/node_autocomplete_callback/' . $parent_lang . '/all',
    '#states' => array(
      'visible' => array(':input[name="' . $section_name . '[section_wrapper][section_url_type]"]' => array('value' => 'internal')),
      'required' => array(':input[name="' . $section_name . '[section_wrapper][section_url_type]"]' => array('value' => 'internal')),
    ),
  );

  $section_external_url = isset($conf[$section_name]['section_external_url']) ? $conf[$section_name]['section_external_url'] : '';
  $form['section_wrapper']['section_external_url'] = array(
    '#type' => 'textfield',
    '#title' => t('External URL'),
    '#weight' => 20,
    '#default_value' => $section_external_url && url_is_external($section_external_url) !== FALSE ? $section_external_url : 'http://',
    '#size' => 60,
    '#maxlength' => defined('PADDLE_CORE_EXTERNAL_URL_MAX_LENGTH') ? PADDLE_CORE_EXTERNAL_URL_MAX_LENGTH : 255,
    '#states' => array(
      'visible' => array(':input[name="' . $section_name . '[section_wrapper][section_url_type]"]' => array('value' => 'external')),
      'required' => array(':input[name="' . $section_name . '[section_wrapper][section_url_type]"]' => array('value' => 'external')),
    ),
  );

  return $form;
}

/**
 * Used for validation of a pane section's edit form.
 *
 * @param array $form
 *   The form that has been displayed and needs to be validated.
 * @param array $form_state
 *   The form_state array that contains the submitted values.
 */
function paddle_panes_pane_sections_validate($form, &$form_state) {
  foreach (paddle_panes_pane_sections_additional_sections() as $section_name => $section) {
    $values = $form_state['values'][$section_name];
    $values = array_merge($values, $values['section_wrapper']);
    unset($values['section_wrapper']);

    // For each section either the text or the image should be required
    // (depending on which will be used) only if the section is enabled. We set
    // this conditions using #states but they have no effect on the validation
    // so we have to implement ourselves.
    if (!empty($values['enable_section'])) {
      $required_fields = $section_name == 'bottom' ? array('section_text') : array();
      if (!empty($values['section_content_type'])) {
        $required_fields[] = 'section_' . $values['section_content_type'];
      }
      foreach ($required_fields as $required_field) {
        // Allow forms to skip the validation of a field by setting a value
        // for the '#skip_pane_section_validation' key in
        // $form['pane_settings'][$section][$field] to TRUE. The forms are then
        // responsible for providing their own validation.
        $value_set = $required_field == 'section_image' ? !empty($values[$required_field]['sid']) : !empty($values[$required_field]);
        if (isset($form['pane_settings'][$section_name]['section_wrapper'][$required_field])
          && !isset($form['pane_settings'][$section_name]['section_wrapper'][$required_field]['#skip_pane_section_validation'])
          && !$value_set
        ) {
          $field_title = $form['pane_settings'][$section_name]['section_wrapper'][$required_field]['#title'];

          $required = $section_name . "][section_wrapper][$required_field";
          if ($required_field == 'section_image') {
            $required .= '][sid';
            $field_title = t('Image file');
          }
          form_set_error($required, t('@field field is required for section @section.', array('@field' => $field_title, '@section' => $section['title'])));
        }
      }

      // Validate the internal path/external URL.
      $internal_url = !empty($values['section_internal_url']) && empty($form['pane_settings'][$section_name]['section_wrapper']['section_internal_url']['#skip_pane_section_validation']) ? $values['section_internal_url'] : '';
      $external_url = !empty($values['section_external_url']) && empty($form['pane_settings'][$section_name]['section_wrapper']['section_external_url']['#skip_pane_section_validation']) ? $values['section_external_url'] : '';

      if ($values['section_url_type'] == 'internal' && !$internal_url) {
        form_set_error($section_name . '][section_wrapper][section_internal_url', t('Please enter a valid page for @section', array('@section' => $section['title'])));
      }
      elseif ($internal_url) {
        $matches = array();
        if (preg_match('/node\/(\d+)/', $internal_url, $matches)) {
          if (node_load($matches[1]) === FALSE) {
            form_set_error($section_name . '][section_wrapper][section_internal_url', t('Please enter a valid page for @section', array('@section' => $section['title'])));
          }
        }
      }

      if ($values['section_url_type'] == 'external' && $external_url == 'http://') {
        form_set_error($section_name . '][section_wrapper][section_external_url', t('Please enter a valid page for @section', array('@section' => $section['title'])));
      }
      elseif ($external_url != 'http://' && (!url_is_external($external_url) || !filter_var($external_url, FILTER_VALIDATE_URL))) {
        form_set_error($section_name . '][section_wrapper][section_external_url', t('Please enter a valid URL for @section', array('@section' => $section['title'])));
      }
    }
  }
}

/**
 * Processes the submitted values of a pane section's edit form.
 *
 * @param array $form
 *   The form that has been displayed and was submitted.
 * @param array $form_state
 *   The form_state array that contains the submitted values.
 */
function paddle_panes_pane_sections_submit($form, &$form_state) {
  foreach (paddle_panes_pane_sections_additional_sections() as $section_name => $section) {
    $values = $form_state['values'][$section_name];
    $values = array_merge($values, $values['section_wrapper']);
    unset($values['section_wrapper']);

    $form_state['conf'][$section_name] = array();
    foreach ($values as $name => $value) {
      // By default the value is set to "http://" so we remove this if the url
      // was not set.
      if ($name == 'section_url' && $value == 'http://') {
        $value = '';
      }
      $form_state['conf'][$section_name][$name] = $value;
    }
  }
}

/**
 * Normalizes the section image configuration in order to extract the atom id.
 *
 * @param mixed $section_image
 *   The configuration value for the section image.
 *
 * @return int
 *   The atom id.
 */
function _paddle_panes_pane_section_extract_section_image_atom_id($section_image) {
  if (isset($section_image['sid'])) {
    $atom_id = $section_image['sid'][0];
  }
  elseif (is_array($section_image)) {
    $atom_id = $section_image[0];
  }
  else {
    $atom_id = $section_image;
  }

  return $atom_id;
}

/**
 * Get the info for the sections for panes.
 *
 * @param array $conf
 *   The configuration of the pane.
 *
 * @return array
 *   An array containing the values which will be used.
 */
function paddle_panes_pane_sections_configuration_info($conf) {
  $result = array();

  foreach (paddle_panes_pane_sections_additional_sections() as $section_name => $section) {
    if (!empty($conf[$section_name]['enable_section'])) {
      $config = $conf[$section_name];

      if (isset($config['section_content_type']) && $config['section_content_type'] == 'image' && !empty($config['section_image'])) {
        $atom = scald_atom_load(_paddle_panes_pane_section_extract_section_image_atom_id($config['section_image']));
        $result[] = t('@section section image: @image', array(
          '@section' => $section['title'],
          '@image' => $atom->title . ' ',
        ));

        $styles = manualcrop_styles_with_crop(FALSE, NULL, TRUE);
        if (!empty($config['section_image']['style']) && isset($styles[$config['section_image']['style']])) {
          $result[] = t('@section section image style: @style', array(
            '@section' => $section['title'],
            '@style' => $styles[$config['section_image']['style']],
          ));
        }
      }
      else {
        $result[] = t('@section section text: @text', array(
          '@section' => $section['title'],
          '@text' => $config['section_text'],
        ));
      }

      if (!empty($config['section_url_type'])) {
        $types = array(
          'no_link' => t('No link'),
          'internal' => t('Internal'),
          'external' => t('External'),
        );

        $result[] = t('@section section url type: @type', array(
          '@section' => $section['title'],
          '@type' => $types[$config['section_url_type']],
        ));

        if ($config['section_url_type'] != 'no_link') {
          $result[] = t('@section section url: @value', array(
            '@section' => $section['title'],
            '@value' => $config["section_{$config['section_url_type']}_url"],
          ));
        }
      }
    }
  }

  return $result;
}
