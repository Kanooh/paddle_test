<?php
/**
 * @file
 * Theme and preprocess functions for poll paddlet.
 */

/**
 * Default preprocess function for a chart legend.
 */
function template_preprocess_paddle_poll_chart_legend(&$variables) {
  $node = $variables['node'];
  $colors = $variables['colors'];

  $color_count = count($colors);
  $choices = array_values($node->choice);
  $items = array();
  foreach ($choices as $index => $choice) {
    $items[] = array(
      'label' => $choice['chtext'],
      'color' => $colors[$index % $color_count],
    );
  }

  $variables['items'] = $items;
}
