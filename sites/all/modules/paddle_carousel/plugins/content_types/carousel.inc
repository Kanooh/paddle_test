<?php

/**
 * @file
 * Plugin to show a carousel.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Carousel'),
  'no title override' => TRUE,
  'description' => t('Add a carousel.'),
  'category' => t('Paddle Content Manager'),
  'defaults' => array(
    'slides' => array(),
  ),
  'paddle_panes sections' => TRUE,
  'reference tracker' => array(
    'slides' => array(
      'value_type' => 'paddle_carousel_slides',
    ),
  ),
);

/**
 * Render callback.
 */
function paddle_carousel_carousel_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';

  $slides = $conf['slides'];
  $autoplay = !empty($conf['autoplay']) ? $conf['autoplay'] : FALSE;
  $speed = !empty($conf['slider_speed']) ? 1000 * (int) $conf['slider_speed'] : 5000;
  uasort($slides, 'drupal_sort_weight');

  $output = theme(
    'paddle_carousel',
    array('slides' => $slides, 'autoplay' => $autoplay, 'speed' => $speed)
  );

  // Allow the pane sections to contribute output.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  $block->content = $output;
  return $block;
}

/**
 * Settings form builder callback.
 */
function paddle_carousel_carousel_content_type_edit_form($form, &$form_state) {
  $context_language = '';
  if (isset($form_state['contexts']['panelizer']->data->language)) {
    $context_language = $form_state['contexts']['panelizer']->data->language;
  }

  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  $form['section_body']['autoplay'] = array(
    '#type' => 'checkbox',
    '#title' => t('Autoplay on start'),
    '#default_value' => isset($form_state['conf']['autoplay']) ? $form_state['conf']['autoplay'] : FALSE,
  );

  $form['section_body']['slider_speed'] = array(
    '#type' => 'select',
    '#title' => t('Speed of the slider in seconds'),
    '#options' => array(
      9 => t('Very slow (9s)'),
      7 => t('Slow (7s)'),
      5 => t('Normal (5s)'),
      3 => t('Fast (3s)'),
      2 => t('Very fast (2s)'),
    ),
    '#default_value' => isset($form_state['conf']['slider_speed']) ? $form_state['conf']['slider_speed'] : 5,
  );

  $form['section_body']['slides'] = array(
    '#tree' => TRUE,
    '#theme' => 'paddle_carousel_slides_table',
    '#prefix' => '<div id="carousel-slides-wrapper">',
    '#suffix' => '</div>',
  );
  $form['section_body']['slides']['list'] = array(
    '#type' => 'fieldset',
  );

  $slides = $form_state['conf']['slides'];
  uasort($slides, 'drupal_sort_weight');

  foreach ($slides as $uuid => $slide) {
    $form['section_body']['slides']['list'][$uuid] = array(
      '#type' => 'fieldset',
    );

    // Take into account old configurations.
    if (isset($slide['image']) && !isset($slide['image']['sid'])) {
      $slide['image'] = array('sid' => $slide['image']);
    }

    // Image field.
    $form['section_body']['slides']['list'][$uuid]['image'] = array(
      '#title' => t('Image'),
      '#type' => 'paddle_scald_image_atom',
      '#required' => TRUE,
      '#default_value' => $slide['image'],
    );

    // Weight field.
    $form['section_body']['slides']['list'][$uuid]['weight'] = array(
      '#type' => 'textfield',
      '#default_value' => $slide['weight'],
      '#size' => 3,
      '#attributes' => array('class' => array('row-weight')),
    );

    // Link fields.
    $form['section_body']['slides']['list'][$uuid]['url_type'] = array(
      '#type' => 'radios',
      '#weight' => 10,
      '#title' => t('Type of the URL'),
      '#options' => array(
        'no_link' => t('No link'),
        'internal' => t('Internal'),
        'external' => t('External'),
      ),
      '#default_value' => !empty($slide['url_type']) ? $slide['url_type'] : 'no_link',
    );

    $form['section_body']['slides']['list'][$uuid]['internal_url'] = array(
      '#type' => 'textfield',
      '#title' => t('Node'),
      '#weight' => 15,
      '#default_value' => isset($slide['internal_url']) ? $slide['internal_url'] : '',
      '#size' => 60,
      '#autocomplete_path' => 'admin/paddle_panes/node_autocomplete_callback/' . $context_language . '/all',
      '#states' => array(
        'visible' => array(':input[name="slides[list][' . $uuid . '][url_type]"]' => array('value' => 'internal')),
      ),
    );

    $external_url = isset($slide['external_url']) ? $slide['external_url'] : '';
    $form['section_body']['slides']['list'][$uuid]['external_url'] = array(
      '#type' => 'textfield',
      '#title' => t('External URL'),
      '#weight' => 20,
      '#default_value' => $external_url && url_is_external($external_url) !== FALSE ? $external_url : 'http://',
      '#size' => 60,
      '#maxlength' => PADDLE_CORE_EXTERNAL_URL_MAX_LENGTH,
      '#states' => array(
        'visible' => array(':input[name="slides[list][' . $uuid . '][url_type]"]' => array('value' => 'external')),
      ),
    );

    // Caption field.
    $form['section_body']['slides']['list'][$uuid]['caption'] = array(
      '#type' => 'textfield',
      '#title' => t('Caption'),
      '#default_value' => !empty($slide['caption']) ? $slide['caption'] : '',
      '#size' => 60,
      '#maxlength' => 210,
    );

    // Button to remove the slide. Make sure we have a unique #name, otherwise
    // the clicked_button in the $form_state won't be correct when the remove
    // slide button is clicked. Also don't validate anything when removing a
    // slide, otherwise you might not be able to remove a slide with no data
    // (eg. the required image) in it.
    $form['section_body']['slides']['list'][$uuid]['remove'] = array(
      '#name' => 'remove_' . $uuid,
      '#type' => 'submit',
      '#value' => t('Remove slide'),
      '#submit' => array('paddle_carousel_remove_slide'),
      '#ajax' => array(
        'callback' => 'paddle_carousel_slides_callback',
        'wrapper' => 'carousel-slides-wrapper',
      ),
      '#limit_validation_errors' => array(),
    );
  }

  // Add button. Don't validate anything when adding a slide, otherwise you
  // are not able to add more then one empty slide every time. (Until you fill
  // in the required data for each slide.)
  $form['section_body']['slides']['add'] = array(
    '#type' => 'submit',
    '#value' => t('Add slide'),
    '#submit' => array('paddle_carousel_add_slide'),
    '#ajax' => array(
      'callback' => 'paddle_carousel_slides_callback',
      'wrapper' => 'carousel-slides-wrapper',
    ),
    '#limit_validation_errors' => array(),
  );

  // Include the form elements for the pane sections in the form.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);
  }

  form_load_include($form_state, 'inc', 'paddle_carousel', 'plugins/content_types/carousel');

  return $form;
}

/**
 * Callback for all carousel config ajax-enabled buttons.
 *
 * return array
 *   Fieldset with all slides in it.
 */
function paddle_carousel_slides_callback($form, $form_state) {
  return $form['pane_settings']['section_body']['slides'];
}

/**
 * Submit handler for the "add slide" button.
 *
 * Adds an empty slide to the form_state and causes a form rebuild.
 */
function paddle_carousel_add_slide($form, &$form_state) {
  // Default new weight is 0.
  $weight = 0;

  // If there are existing slides, make the new slide heavier than the heaviest
  // slide.
  if (!empty($form_state['input']['slides']['list'])) {
    // Sort all slides by weight.
    $slides = $form_state['input']['slides']['list'];
    uasort($slides, 'drupal_sort_weight');

    // Get the key of the last slide.
    $keys = array_keys($slides);
    $last_key = $keys[count($keys) - 1];

    // Get the weight of the last slide, and make the new slide heavier.
    $weight = $slides[$last_key]['weight'] + 1;
  }

  // Add the empty slide to the form configuration.
  $form_state['conf']['slides'][uniqid()] = array(
    'image' => array(),
    'weight' => $weight,
    'url_type' => '',
    'internal_url' => '',
    'external_url' => '',
  );

  // Rebuild the form so the changes are visible.
  $form_state['rebuild'] = TRUE;
}

/**
 * Submit handler for the "remove slide" button.
 *
 * Removes the specified slide from the form_state and causes a form rebuild.
 */
function paddle_carousel_remove_slide($form, &$form_state) {
  // Get the slide uuid from the clicked button.
  $uuid = paddle_carousel_remove_button_slide_uuid($form_state['clicked_button']);

  // Remove the slide with the corresponding uuid from the configuration.
  unset($form_state['conf']['slides'][$uuid]);

  // Rebuild the form so the changes are visible.
  $form_state['rebuild'] = TRUE;
}

/**
 * Gets a remove button's slide uuid from the element array.
 *
 * @param array $button
 *   The remove button.
 *
 * @return string
 *   The slide's uuid.
 */
function paddle_carousel_remove_button_slide_uuid($button) {
  return $button['#parents'][2];
}

/**
 * Validate handler for the edit form.
 */
function paddle_carousel_carousel_content_type_edit_form_validate($form, &$form_state) {
  $values = $form_state['values'];
  if (!isset($values['slides']['list']) || empty($values['slides']['list'])) {
    form_set_error('slides][list', t('A carousel should consist of at least one slide.'));
  }

  // Validate the internal path/external URL.
  foreach ($values['slides']['list'] as $uuid => $slide) {
    if ($slide['internal_url']) {
      $matches = array();
      if (preg_match('/node\/(\d+)/', $slide['internal_url'], $matches)) {
        if (node_load($matches[1]) === FALSE) {
          form_set_error('slides][list][' . $uuid . '][internal_url', t('Please enter a valid URL for the internal URL.'));
        }
      }
    }
    elseif ($slide['external_url'] != 'http://' && (!url_is_external($slide['external_url']) || !filter_var($slide['external_url'], FILTER_VALIDATE_URL))) {
      form_set_error('slides][list][' . $uuid . '][external_url', t('Please enter a valid URL for the external URL.'));
    }
  }

  // Allow the form elements provided by pane sections to be validated.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_validate($form, $form_state);
  }
}

/**
 * Submit callback of the settings form.
 */
function paddle_carousel_carousel_content_type_edit_form_submit($form, &$form_state) {
  // Save the autoplay setting.
  $form_state['conf']['autoplay'] = $form_state['values']['autoplay'];
  $form_state['conf']['slider_speed'] = $form_state['values']['slider_speed'];

  // Throw away the old configuration, otherwise we'd be appending the updated
  // slides to the old slides.
  $form_state['conf']['slides'] = array();

  // Loop over each slide and store the data.
  foreach ($form_state['values']['slides']['list'] as $uuid => $slide) {
    $form_state['conf']['slides'][$uuid] = array(
      'image' => $slide['image'],
      'weight' => $slide['weight'],
      'url_type' => $slide['url_type'],
      'internal_url' => $slide['internal_url'],
      'external_url' => $slide['external_url'],
      'caption' => $slide['caption'],
    );
  }

  // Allow the form elements provided by pane sections to be saved.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_submit($form, $form_state);
  }
}
