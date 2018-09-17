<?php

/**
 * @file
 * Plugin to declare a color scheme settings for a Web Universe theme.
 */

/**
 * CTools plugin definition.
 */
$plugin = array(
  'label' => 'paddle_web_universe_color_scheme',
  'handler' => array(
    'class' => 'PaddleWebUniverseColorSchemePlugin',
  ),
);

/**
 * Class PaddleWebUniverseColorSchemePlugin.
 */
class PaddleWebUniverseColorSchemePlugin extends PaddleStyleConfigurablePlugin implements PaddleStyleExecutePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function form($allowed_values = array(), $default_values = array(), ctools_context $context = NULL) {
    $output = array();

    // Generate a select list to configure the color scheme.
    if (empty($allowed_values) || array_key_exists('web_universe_color_scheme', $allowed_values)) {
      $output['web_universe_color_scheme'] = array(
        '#type' => 'select',
        '#default_value' => isset($default_values['web_universe_color_scheme']) ? $default_values['web_universe_color_scheme'] : 'corporate',
        '#options' => array(
          'corporate' => t('Corporate'),
          'palet-1' => t('Theme 1'),
          'palet-2' => t('Theme 2'),
          'palet-3' => t('Theme 3'),
          'palet-4' => t('Theme 4'),
          'palet-5' => t('Theme 5'),
          'palet-6' => t('Theme 6'),
          'palet-7' => t('Theme 7'),
          'palet-8' => t('Theme 8'),
          'palet-9' => t('Theme 9'),
          'palet-10' => t('Theme 10'),
          'palet-11' => t('Theme 11'),
          'palet-12' => t('Theme 12'),
          'palet-13' => t('Theme 13'),
          'default' => t('Blank theme'),
        ),
      );
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function execute($values, ctools_context $context = NULL) {
    global $conf;

    if (!empty($values['web_universe_color_scheme'])) {
      $conf['paddle_web_universe_color_scheme'] = $values['web_universe_color_scheme'];
    }
  }

}
