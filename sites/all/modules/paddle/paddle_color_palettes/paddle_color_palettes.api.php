<?php

/**
 * @file
 * API documentation for the Paddle Color Palettes module.
 */

/**
 * Defines colour-selector pairs to be used in the color palettes functionality.
 *
 * The array that is returned represents a mapping between a colour on specific
 * position and a CSS selector and its properties. Each element of the array
 * represents one colour, so the element indexed 0 represents every first colour
 * of each palette (or sub-palette). Inside the array we need one element for
 * each CSS property which can have colour as a value - the key is the CSS
 * property and the value - array of selectors. Each selector can be a string
 * in which case it is only the selector or an array in which case the key of
 * this array is the actual selector and the value is the transparency of the
 * colour.
 */
function hook_paddle_color_palettes_color_selectors() {
  return array(
    array(
      'color' => array('h1', array('h2' => 0.3)),
      'background-color' => array('p'),
    ),
    array(
      'color' => array('a', 'a:visited', 'a:hover'),
    ),
    array(
      'background-color' => array('#page-content'),
    ),
    array(
      'background-color' => array('footer'),
    ),
    array(
      'background-color' => array('header'),
    ),
  );
}

/**
 * Modify the color-selector pairs to be used in color palettes functionality.
 *
 * @param array $data
 *   The color-selector pairs.
 * @param object $theme
 *   The theme the color palettes apply to.
 *
 * @see hook_paddle_color_palettes_color_selectors()
 */
function hook_paddle_color_palettes_color_selectors_alter(&$data, $theme) {

}
