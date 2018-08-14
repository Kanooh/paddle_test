<?php
/**
 * @file
 * paddle_apps.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_apps_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "facetapi" && $api == "facetapi_defaults") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_default_search_api_index().
 */
function paddle_apps_default_search_api_index() {
  $items = array();
  $items['paddle_apps'] = entity_import('search_api_index', '{
    "name" : "paddle_apps",
    "machine_name" : "paddle_apps",
    "description" : null,
    "server" : "database_server",
    "item_type" : "paddle_app",
    "options" : {
      "index_directly" : 0,
      "cron_limit" : "50",
      "fields" : {
        "level" : { "type" : "string" },
        "machineName" : { "type" : "string" },
        "name" : { "type" : "string" },
        "search_api_aggregation_1" : { "type" : "text" },
        "search_api_language" : { "type" : "string" },
        "status" : { "type" : "boolean" },
        "thirdPartyService" : { "type" : "boolean" },
        "vendor" : { "type" : "string" }
      },
      "data_alter_callbacks" : {
        "search_api_alter_language_control" : { "status" : 0, "weight" : "0", "settings" : { "languages" : [] } },
        "search_api_alter_add_viewed_entity" : { "status" : 0, "weight" : "0", "settings" : { "mode" : false } },
        "search_api_alter_add_aggregation" : {
          "status" : 1,
          "weight" : "0",
          "settings" : { "fields" : { "search_api_aggregation_1" : {
                "name" : "fulltext",
                "type" : "fulltext",
                "fields" : [ "machineName", "name" ],
                "description" : "A Fulltext aggregation of the following fields: Machine name, Name."
              }
            }
          }
        },
        "search_api_alter_add_url" : { "status" : 0, "weight" : "0", "settings" : [] }
      },
      "processors" : {
        "search_api_case_ignore" : { "status" : 0, "weight" : "0", "settings" : { "fields" : [] } },
        "search_api_html_filter" : {
          "status" : 0,
          "weight" : "10",
          "settings" : {
            "fields" : [],
            "title" : 0,
            "alt" : 1,
            "tags" : "h1 = 5\\r\\nh2 = 3\\r\\nh3 = 2\\r\\nstrong = 2\\r\\nb = 2\\r\\nem = 1.5\\r\\nu = 1.5"
          }
        },
        "search_api_tokenizer" : {
          "status" : 0,
          "weight" : "20",
          "settings" : { "fields" : [], "spaces" : "[^[:alnum:]]", "ignorable" : "[\\u0027]" }
        },
        "search_api_stopwords" : {
          "status" : 0,
          "weight" : "30",
          "settings" : {
            "fields" : [],
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

/**
 * Implements hook_default_search_api_server().
 */
function paddle_apps_default_search_api_server() {
  $items = array();

  $items['database_server'] = entity_import('search_api_server', '{
    "name" : "Database server",
    "machine_name" : "database_server",
    "description" : "",
    "class" : "search_api_db_service",
    "options" : {
      "database" : "default:default",
      "min_chars" : "1",
      "partial_matches" : 0,
      "indexes" : { "paddle_apps" : {
          "machineName" : {
            "table" : "search_api_db_paddle_apps",
            "column" : "machinename_1",
            "type" : "string",
            "boost" : "1.0"
          },
          "name" : {
            "table" : "search_api_db_paddle_apps",
            "column" : "name_1",
            "type" : "string",
            "boost" : "1.0"
          },
          "status" : {
            "table" : "search_api_db_paddle_apps",
            "column" : "status_1",
            "type" : "boolean",
            "boost" : "1.0"
          },
          "level" : {
            "table" : "search_api_db_paddle_apps",
            "column" : "level_1",
            "type" : "string",
            "boost" : "1.0"
          },
          "thirdPartyService" : {
            "table" : "search_api_db_paddle_apps",
            "column" : "thirdpartyservice_1",
            "type" : "boolean",
            "boost" : "1.0"
          },
          "vendor" : {
            "table" : "search_api_db_paddle_apps",
            "column" : "vendor_1",
            "type" : "string",
            "boost" : "1.0"
          },
          "search_api_language" : {
            "table" : "search_api_db_paddle_apps",
            "column" : "search_api_language_1",
            "type" : "string",
            "boost" : "1.0"
          },
          "search_api_aggregation_1" : {
            "table" : "search_api_db_paddle_apps_text_1",
            "type" : "text",
            "boost" : "1.0"
          }
        }
      }
    },
    "enabled" : "1"
  }');

  return $items;
}
