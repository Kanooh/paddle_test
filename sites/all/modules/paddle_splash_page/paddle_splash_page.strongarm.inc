<?php
/**
 * @file
 * paddle_splash_page.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function paddle_splash_page_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'locale_language_providers_weight_language';
  $strongarm->value = array(
    'locale-url' => '-10',
    'language_cookie' => '-9',
    'language-selection-page' => '-8',
    'locale-session' => '-7',
    'locale-user' => '-6',
    'locale-browser' => '-5',
    'language-default' => '-4',
  );
  $export['locale_language_providers_weight_language'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'locale_language_providers_weight_language_content';
  $strongarm->value = array(
    'locale-url' => '-10',
    'language-selection-page' => '-8',
    'locale-session' => '-7',
    'language_cookie' => '-9',
    'locale-user' => '-6',
    'locale-browser' => '-5',
    'locale-interface' => '-4',
    'language-default' => '-3',
  );
  $export['locale_language_providers_weight_language_content'] = $strongarm;

  return $export;
}
