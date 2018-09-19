<?php

/**
 * @file
 * Plugin to allow the user to select the current color palette.
 */

/**
 * CTools plugin definition.
 */
$plugin = array(
  'label' => 'color_palettes',
  'handler' => array(
    'class' => 'PaddleColorPalettesPlugin',
  ),
);

class PaddleColorPalettesPlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleCssPluginInterface, PaddleStyleExecutePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $options = array();
    $output = array();

    $palettes = $allowed_values;

    if (empty($palettes)) {
      return array();
    }
    // Make sure the color palette definitions are well formed.
    paddle_color_palettes_validate_palettes($palettes);

    $default_primary_palette_name = '';
    foreach ($palettes as $primary_palette_name => $primary_palette) {
      $option_output = '';
      if ($default_primary_palette_name == '') {
        // Determine this for the default value of the select form element.
        $default_primary_palette_name = $primary_palette_name;
      }
      foreach ($primary_palette as $palette_name => $palette) {
        // This happens when this is the definition of the primary palette.
        $class = $primary_palette_name == $palette_name ? 'paddle-color-palettes-primary-palette' : 'paddle-color-palettes-secondary-palette';
        if (!empty($primary_palette[$primary_palette_name]['color_pickers'])) {
          // Add class to indicate the color palette should have color picker.
          $class .= ' palette-with-color-picker';

          // Override the colors if they have been changed with color pickers.
          if (!empty($default_values["$palette_name-color-pickers"])) {
            $palette['colors'] = $default_values["$palette_name-color-pickers"];
          }
          $output["$palette_name-color-pickers"]["#tree"] = TRUE;
          foreach ($palette['colors'] as $index => $color) {
            $output["$palette_name-color-pickers"][$index] = array(
              '#type' => 'hidden',
              '#default_value' => $color,
              '#attributes' => array('id' => "$palette_name-color-picker-value-$index"),
            );
          }

          // Add the ColorPicker JS and CSS to the form.
          $path = libraries_get_path('colorpicker');
          $output['#attached']['css'][] = $path . '/css/colorpicker.css';
          $output['#attached']['js'][] = $path . '/js/colorpicker.js';
        }
        $option_output .= $this->buildColorOption($palette['title'], $palette['colors'], $class, $palette_name);
      }
      $options[$primary_palette_name] = theme('html_tag', array(
        'element' => array(
          '#tag' => 'div',
          '#attributes' => array(
            'class' => 'paddle-color-palettes-option',
          ),
          '#value' => $option_output,
          '#value_prefix' => '<h3 class="color-name">' . check_plain($primary_palette[$primary_palette_name]['title']) . '</h3>',
        ),
      ));
    }

    if (!empty($options)) {
      $output['#attached']['js'] = array(drupal_get_path('module', 'paddle_color_palettes') . '/js/paddle_color_palettes_plugin_form.js');

      $output['primary_color_palettes'] = array(
        '#type' => 'radios',
        '#title' => t('Select the primary colour palette:'),
        '#options' => $options,
        '#default_value' => isset($default_values['primary_color_palettes']) ? $default_values['primary_color_palettes'] : $default_primary_palette_name,
        '#attributes' => array(
          'class' => array('paddle-color-palettes-image-radios'),
        ),
        '#access' => count($options) > 1 ? TRUE : FALSE,
      );
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function execute($values, ctools_context $context = NULL) {
    global $conf;

    $conf['paddle_primary_color_palette'] = $values['primary_color_palettes'];
  }

  /**
   * {@inheritdoc}
   */
  public function getCss($values, ctools_context $context = NULL) {
    $output = '';

    // Get the palette and color positions.
    $theme_name = !empty($context->data['paddle_theme']->theme) ? $context->data['paddle_theme']->theme : '';
    $theme = paddle_themer_get_theme_by_name($theme_name);
    $color_positions = paddle_color_palettes_get_color_selectors($theme);
    $palette_name = isset($values['primary_color_palettes']) ? $values['primary_color_palettes'] : FALSE;
    $palette = paddle_color_palettes_get_palette($palette_name, $theme);

    if ($color_positions && $palette) {
      // Fill in missing subpalette colors.
      $palette = paddle_color_palettes_supplement_subpalettes($palette);

      // Cast to an indexed array, we need numerical values to generate
      // subpalette classes.
      $palette = array_values($palette);

      // Loop over the subpalettes.
      foreach ($palette as $subpalette_key => $subpalette) {
        // Use the values entered through the color picker if it has been used.
        if (!is_null($context)) {
          $palette_names = array_keys($context->data['instance']['allowed_values'][$palette_name]);
          $current_palette_name = $palette_names[$subpalette_key];
          if (!empty($subpalette['color_pickers']) && !empty($values["$current_palette_name-color-pickers"])) {
            // Replace the colors defined in theme-settings.php by these set
            // with color picker in the theme edit form.
            $palette[$subpalette_key]['colors'] = array_replace($palette[$subpalette_key]['colors'], $values["$current_palette_name-color-pickers"]);

            $subpalette = $palette[$subpalette_key];
          }
        }

        // Loop over each color in the subpalettes.
        foreach ($color_positions as $index => $colors) {
          foreach ($colors as $property => $selectors) {
            // The selectors might contain a transparency value so we need to
            // handle them differently. Group the selectors based on colours.
            $lines = array(
              $subpalette['colors'][$index] => array(),
            );

            foreach ($selectors as $selector) {
              $color = $subpalette['colors'][$index];

              if (is_array($selector)) {
                // Get the transparency first to form the colour.
                $transparency = array_values($selector);
                $transparency = $transparency[0];
                $rgba_pieces = $this->hex2RGB($color);
                $rgba_pieces[] = $transparency;
                $color = 'rgba(' . implode(', ', $rgba_pieces) . ')';

                // Add the colour to the list.
                if (!isset($lines[$color])) {
                  $lines[$color] = array();
                }

                // Add the current selector to the list for this colour.
                $selector = array_keys($selector);
                $selector = $selector[0];
              }

              // The selectors without wildcards only apply to the primary
              // palette. Filter them out unless we are on the primary palette.
              if ($subpalette_key == 0 || ($subpalette_key && strpos($selector, '{}') !== FALSE)) {
                // Replace wildcards with subpalette classes. This will convert
                // a selector such as 'ul li{} a' to the following selector:
                // 'ul li.paddle-color-palettes-subpalette-N a' where N is the
                // relative number of the subpalette.
                $lines[$color][] = preg_replace('/{}/', '.paddle-color-palettes-subpalette-' . $subpalette_key, $selector);
              }
            }

            foreach ($lines as $color => $selectors) {
              $output .= !empty($selectors) ? implode(",\n", $selectors) . " {\n  " . $property . ': ' . $color . ";\n}\n\n" : '';
            }
          }
        }
      }
    }

    return $output;
  }

  /**
   * Convert a hexa decimal color code to its RGB equivalent.
   *
   * @param string $hex_str
   *   A hexadecimal color value.
   * @param bool $return_string
   *   If TRUE, returns the value separated by the separator character.
   *   Otherwise returns an associative array.
   * @param string $seperator
   *   To separate RGB values. Applicable only if the second parameter is TRUE.
   *
   * @return mixed
   *   Depending on the second parameter. Returns FALSE if invalid hex color
   *   value.
   */
  protected function hex2RGB($hex_str, $return_string = FALSE, $seperator = ',') {
    // Gets a proper hex string.
    $hex_str = preg_replace("/[^0-9A-Fa-f]/", '', $hex_str);
    $rgb_array = array();

    if (strlen($hex_str) == 6) {
      // If a proper hex code, convert using bitwise operation. No overhead, so
      // it's faster.
      $color_val = hexdec($hex_str);
      $rgb_array['red'] = 0xFF & ($color_val >> 0x10);
      $rgb_array['green'] = 0xFF & ($color_val >> 0x8);
      $rgb_array['blue'] = 0xFF & $color_val;
    }
    elseif (strlen($hex_str) == 3) {
      // If shorthand notation, need some string manipulations.
      $rgb_array['red'] = hexdec(str_repeat(substr($hex_str, 0, 1), 2));
      $rgb_array['green'] = hexdec(str_repeat(substr($hex_str, 1, 1), 2));
      $rgb_array['blue'] = hexdec(str_repeat(substr($hex_str, 2, 1), 2));
    }
    else {
      // Invalid hex color code.
      return FALSE;
    }

    // Return the rgb string or the associative array.
    return $return_string ? implode($seperator, $rgb_array) : $rgb_array;
  }

  /**
   * Builds the HTML for a color palette select option.
   *
   * @param string $title
   *   The title to display on the color palette container.
   * @param array $colors
   *   An array of colors.
   * @param string $class
   *   The class to apply to the color palette container.
   * @param string $palette_name
   *   The machine name of the palette to which the color belongs.
   *
   * @return array
   *   The color palette as a render array.
   */
  protected function buildColorOption($title, $colors, $class, $palette_name = '') {
    $output = '';
    foreach ($colors as $index => $color) {
      $output .= theme('html_tag', array(
        'element' => array(
          '#tag' => 'div',
          '#attributes' => array(
            'class' => 'paddle-color-palettes-color',
            'color-palette-color' => $color,
            'color-palette-color-index' => $index,
            'color-palette-color-palette-name' => $palette_name,
          ),
          '#value' => '',
        ),
      ));
    }

    return theme('html_tag', array(
      'element' => array(
        '#tag' => 'div',
        '#attributes' => array(
          'title' => $title,
          'class' => $class,
        ),
        '#value' => $output,
      ),
    ));
  }

}
