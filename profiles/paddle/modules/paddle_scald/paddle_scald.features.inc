<?php
/**
 * @file
 * paddle_scald.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_scald_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function paddle_scald_views_api($module = NULL, $api = NULL) {
  return array(
    'api' => '3.0',
    'template path' => drupal_get_path('module', 'paddle_scald') . '/theme',
  );
}

/**
 * Implements hook_image_default_styles().
 */
function paddle_scald_image_default_styles() {
  $styles = array();

  // Exported image style: 16_9.
  $styles['16_9'] = array(
    'label' => '16:9',
    'effects' => array(
      4 => array(
        'name' => 'manualcrop_crop',
        'data' => array(
          'width' => 16,
          'height' => 9,
          'keepproportions' => 1,
          'reuse_crop_style' => '',
          'fallback_crop' => 1,
          'maximize_fallback_crop' => 1,
          'style_name' => '16_9',
        ),
        'weight' => 0,
      ),
    ),
  );

  // Exported image style: 2_3.
  $styles['2_3'] = array(
    'label' => '2:3',
    'effects' => array(
      5 => array(
        'name' => 'manualcrop_crop',
        'data' => array(
          'width' => 2,
          'height' => 3,
          'keepproportions' => 1,
          'reuse_crop_style' => '',
          'fallback_crop' => 1,
          'maximize_fallback_crop' => 1,
          'style_name' => '2_3',
        ),
        'weight' => 0,
      ),
    ),
  );

  // Exported image style: 3_2.
  $styles['3_2'] = array(
    'label' => '3:2',
    'effects' => array(
      6 => array(
        'name' => 'manualcrop_crop',
        'data' => array(
          'width' => 3,
          'height' => 2,
          'keepproportions' => 1,
          'reuse_crop_style' => '',
          'fallback_crop' => 1,
          'maximize_fallback_crop' => 1,
          'style_name' => '3_2',
        ),
        'weight' => 0,
      ),
    ),
  );

  // Exported image style: 3_4.
  $styles['3_4'] = array(
    'label' => '3:4',
    'effects' => array(
      3 => array(
        'name' => 'manualcrop_crop',
        'data' => array(
          'width' => 3,
          'height' => 4,
          'keepproportions' => 1,
          'reuse_crop_style' => '',
          'fallback_crop' => 1,
          'maximize_fallback_crop' => 1,
          'style_name' => '3_4',
        ),
        'weight' => 0,
      ),
    ),
  );

  // Exported image style: 4_3.
  $styles['4_3'] = array(
    'label' => '4:3',
    'effects' => array(
      2 => array(
        'name' => 'manualcrop_crop',
        'data' => array(
          'width' => 4,
          'height' => 3,
          'keepproportions' => 1,
          'reuse_crop_style' => '',
          'fallback_crop' => 1,
          'maximize_fallback_crop' => 1,
          'style_name' => '4_3',
        ),
        'weight' => 0,
      ),
    ),
  );

  // Exported image style: paddle_scald_atom_element_preview.
  $styles['paddle_scald_atom_element_preview'] = array(
    'label' => 'paddle_scald_atom_element_preview',
    'effects' => array(
      4 => array(
        'name' => 'image_scale',
        'data' => array(
          'width' => 80,
          'height' => 80,
          'upscale' => 1,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: paddle_scald_library_item.
  $styles['paddle_scald_library_item'] = array(
    'label' => 'paddle_scald_library_item',
    'effects' => array(
      2 => array(
        'name' => 'image_scale',
        'data' => array(
          'width' => 120,
          'height' => 120,
          'upscale' => 1,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: square.
  $styles['square'] = array(
    'label' => 'Square',
    'effects' => array(
      1 => array(
        'name' => 'manualcrop_crop',
        'data' => array(
          'width' => 1,
          'height' => 1,
          'keepproportions' => 1,
          'reuse_crop_style' => '',
          'fallback_crop' => 1,
          'maximize_fallback_crop' => 1,
          'style_name' => 'square',
        ),
        'weight' => 0,
      ),
    ),
  );

  // Exported image style: 3_1.
  $styles['3_1'] = array(
    'label' => '3:1',
    'effects' => array(
      7 => array(
        'name' => 'manualcrop_crop',
        'data' => array(
          'width' => 3,
          'height' => 1,
          'keepproportions' => 1,
          'reuse_crop_style' => '',
          'fallback_crop' => 1,
          'maximize_fallback_crop' => 1,
          'style_name' => '3_1',
        ),
        'weight' => 0,
      ),
    ),
  );

  return $styles;
}

/**
 * Implements hook_default_search_api_index().
 */
function paddle_scald_default_search_api_index() {
  $items = array();
  $items['paddle_atoms'] = entity_import('search_api_index', '{
    "name" : "paddle_atoms",
    "machine_name" : "paddle_atoms",
    "description" : null,
    "server" : "solr_server",
    "item_type" : "scald_atom",
    "options" : {
      "index_directly" : 1,
      "cron_limit" : "50",
      "fields" : {
        "changed" : { "type" : "date" },
        "created" : { "type" : "date" },
        "field_paddle_general_tags" : { "type" : "list\\u003Cinteger\\u003E", "entity_type" : "taxonomy_term" },
        "field_paddle_tags" : { "type" : "list\\u003Cinteger\\u003E", "entity_type" : "taxonomy_term" },
        "field_scald_alt_tag" : { "type" : "text" },
        "field_scald_caption" : { "type" : "text" },
        "field_scald_description" : { "type" : "text" },
        "search_api_language" : { "type" : "string" },
        "title" : { "type" : "text" },
        "type" : { "type" : "string" }
      },
      "data_alter_callbacks" : {
        "search_api_alter_bundle_filter" : {
          "status" : 0,
          "weight" : "-10",
          "settings" : { "default" : "1", "bundles" : [] }
        },
        "search_api_alter_language_control" : {
          "status" : 0,
          "weight" : "0",
          "settings" : { "lang_field" : "", "languages" : [] }
        },
        "search_api_alter_add_hierarchy" : { "status" : 0, "weight" : "0", "settings" : { "fields" : [] } },
        "search_api_alter_add_aggregation" : { "status" : 0, "weight" : "0", "settings" : [] },
        "search_api_alter_add_url" : { "status" : 0, "weight" : "0", "settings" : [] },
        "search_api_alter_add_viewed_entity" : { "status" : 0, "weight" : "0", "settings" : { "mode" : "title" } }
      },
      "processors" : {
        "search_api_case_ignore" : {
          "status" : 1,
          "weight" : "0",
          "settings" : { "fields" : {
              "title" : true,
              "field_scald_alt_tag" : true,
              "field_scald_caption" : true,
              "field_scald_description" : true
            }
          }
        },
        "search_api_html_filter" : {
          "status" : 1,
          "weight" : "10",
          "settings" : {
            "fields" : {
              "title" : true,
              "field_scald_alt_tag" : true,
              "field_scald_caption" : true,
              "field_scald_description" : true
            },
            "title" : 0,
            "alt" : 1,
            "tags" : "h1 = 5\\r\\nh2 = 3\\r\\nh3 = 2\\r\\nstrong = 2\\r\\nb = 2\\r\\nem = 1.5\\r\\nu = 1.5"
          }
        },
        "search_api_tokenizer" : {
          "status" : 0,
          "weight" : "20",
          "settings" : {
            "fields" : {
              "title" : true,
              "field_scald_alt_tag" : true,
              "field_scald_caption" : true,
              "field_scald_description" : true
            },
            "spaces" : "[^[:alnum:]]",
            "ignorable" : "[\\u0027]"
          }
        },
        "search_api_stopwords" : {
          "status" : 0,
          "weight" : "30",
          "settings" : {
            "fields" : {
              "title" : true,
              "field_scald_alt_tag" : true,
              "field_scald_caption" : true,
              "field_scald_description" : true
            },
            "file" : "",
            "stopwords" : "but\\r\\ndid\\r\\nthe this that those\\r\\netc"
          }
        },
        "search_api_highlighting" : {
          "status" : 0,
          "weight" : "35",
          "settings" : {
            "prefix" : "\\u003Cstrong\\u003E",
            "suffix" : "\\u003C\\/strong\\u003E",
            "excerpt" : 1,
            "excerpt_length" : "256",
            "exclude_fields" : [],
            "highlight" : "always"
          }
        }
      }
    },
    "enabled" : "1",
    "read_only" : "0"
  }');
  return $items;
}
