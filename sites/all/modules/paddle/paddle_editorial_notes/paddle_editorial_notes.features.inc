<?php
/**
 * @file
 * paddle_editorial_notes.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_editorial_notes_ctools_plugin_api() {
  list($module, $api) = func_get_args();
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_default_message_type().
 */
function paddle_editorial_notes_default_message_type() {
  $items = array();
  $items['paddle_editorial_note'] = entity_import('message_type', '{
    "name" : "paddle_editorial_note",
    "description" : "Editorial Note",
    "argument_keys" : [],
    "argument" : [],
    "category" : "message_type",
    "data" : {
      "token options" : { "clear" : 0 },
      "purge" : { "override" : 0, "enabled" : 0, "quota" : "", "days" : "" }
    },
    "language" : "nl",
    "arguments" : null,
    "message_text" : {
      "en" : [
        {
          "value" : "Message created with user account [message:user:name].",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EMessage created with user account [message:user:name].\\u003C\\/p\\u003E\\n"
        }
      ],
      "nl" : [
        {
          "value" : "Boodschap toegevoegd met gebruikersaccount [message:user:name]",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EBoodschap toegevoegd met gebruikersaccount [message:user:name]\\u003C\\/p\\u003E\\n"
        }
      ]
    }
  }');
  return $items;
}
