<?php
/**
 * @file
 * paddle_news.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_news_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "page_manager" && $api == "pages_default") {
    return array("version" => "1");
  }
  if ($module == "panelizer" && $api == "panelizer") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function paddle_news_views_api($module = NULL, $api = NULL) {
  return array(
    "api" => "3.0",
    'template path' => drupal_get_path('module', 'paddle_news') . '/theme',
  );
}

/**
 * Implements hook_image_default_styles().
 */
function paddle_news_image_default_styles() {
  $styles = array();

  // Exported image style: paddle_news_thumbnail.
  $styles['paddle_news_thumbnail'] = array(
    'name' => 'paddle_news_thumbnail',
    'label' => 'paddle_news_thumbnail',
    'effects' => array(
      1 => array(
        'label' => 'Scale and crop',
        'help' => 'Scale and crop will maintain the aspect-ratio of the original image, then crop the larger dimension. This is most useful for creating perfectly square thumbnails without stretching the image.',
        'effect callback' => 'image_scale_and_crop_effect',
        'dimensions callback' => 'image_resize_dimensions',
        'form callback' => 'image_resize_form',
        'summary theme' => 'image_resize_summary',
        'module' => 'image',
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 90,
          'height' => 90,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: paddle_news_top_news.
  $styles['paddle_news_top_news'] = array(
    'name' => 'paddle_news_top_news',
    'label' => 'paddle_news_top_news',
    'effects' => array(
      1 => array(
        'label' => 'Scale and crop',
        'help' => 'Scale and crop will maintain the aspect-ratio of the original image, then crop the larger dimension. This is most useful for creating perfectly square thumbnails without stretching the image.',
        'effect callback' => 'image_scale_and_crop_effect',
        'dimensions callback' => 'image_resize_dimensions',
        'form callback' => 'image_resize_form',
        'summary theme' => 'image_resize_summary',
        'module' => 'image',
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 394,
          'height' => 288,
        ),
        'weight' => 1,
      ),
    ),
  );

  return $styles;
}

/**
 * Implements hook_node_info().
 */
function paddle_news_node_info() {
  $items = array(
    'news_item' => array(
      'name' => t('News item'),
      'base' => 'node_content',
      'description' => t('This page is used to communicate news facts on your website. The information is automatically published on a news overview. You can also add this in panes.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
