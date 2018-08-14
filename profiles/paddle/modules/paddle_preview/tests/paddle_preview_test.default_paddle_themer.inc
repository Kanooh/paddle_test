<?php
/**
 * @file
 * paddle_preview_test.default_paddle_themer.inc
 */

/**
 * Implements hook_default_paddle_themer_themes().
 */
function paddle_preview_test_default_paddle_themer_themes() {
  $export = array();

  $theme = new stdClass();
  $theme->api_version = 1;
  $theme->name = 'paddle_test_theme';
  $theme->human_name = 'Paddle test theme';
  $theme->theme = 'paddle_theme';
  $theme->style = array(
    'global' => array(
      'h1' => array(
        0 => array(
          'module' => 'paddle_style',
          'plugin' => 'font',
          'value' => array(
            'font_family' => 'comic sans ms',
            'font_size' => 'largest',
            'font_emphasis' => array(
              'bold' => 0,
              'italic' => 0,
              'underline' => 0,
            ),
          ),
        ),
      ),
      'h2' => array(
        0 => array(
          'module' => 'paddle_style',
          'plugin' => 'font',
          'value' => array(
            'font_family' => 'comic sans ms',
            'font_size' => 'very large',
            'font_emphasis' => array(
              'bold' => 0,
              'italic' => 0,
              'underline' => 0,
            ),
          ),
        ),
      ),
      'h3' => array(
        0 => array(
          'module' => 'paddle_style',
          'plugin' => 'font',
          'value' => array(
            'font_family' => 'comic sans ms',
            'font_size' => 'large',
            'font_emphasis' => array(
              'bold' => 0,
              'italic' => 0,
              'underline' => 0,
            ),
          ),
        ),
      ),
      'p' => array(
        0 => array(
          'module' => 'paddle_style',
          'plugin' => 'font',
          'value' => array(
            'font_family' => 'comic sans ms',
            'font_size' => 'normal',
            'font_emphasis' => array(
              'bold' => 0,
              'italic' => 0,
              'underline' => 0,
            ),
          ),
        ),
      ),
      'blockquote' => array(
        0 => array(
          'module' => 'paddle_style',
          'plugin' => 'font',
          'value' => array(
            'font_family' => 'comic sans ms',
            'font_size' => 'normal',
            'font_emphasis' => array(
              'bold' => 0,
              'italic' => 0,
              'underline' => 0,
            ),
          ),
        ),
      ),
      'ul, ul li, ol, ol li' => array(
        0 => array(
          'module' => 'paddle_style',
          'plugin' => 'font',
          'value' => array(
            'font_family' => 'comic sans ms',
            'font_size' => 'normal',
            'font_emphasis' => array(
              'bold' => 0,
              'italic' => 0,
              'underline' => 0,
            ),
          ),
        ),
      ),
      'a' => array(
        0 => array(
          'module' => 'paddle_style',
          'plugin' => 'font',
          'value' => array(
            'font_family' => 'comic sans ms',
            'font_size' => 'normal',
            'font_emphasis' => array(
              'bold' => 0,
              'italic' => 0,
              'underline' => 0,
            ),
          ),
        ),
      ),
    ),
  );
  $theme->changed = 1361956626;
  $export['paddle_test_theme'] = $theme;

  return $export;
}
