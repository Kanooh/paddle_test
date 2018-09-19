<?php
/**
 * @file
 * paddle_comment.features.inc
 */

/**
 * Implements hook_views_api().
 */
function paddle_comment_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_comment_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_default_message_type().
 */
function paddle_comment_default_message_type() {
  $items = array();
  $items['paddle_notifications_comment_added_responsible'] = entity_import('message_type', '{
    "name" : "paddle_notifications_comment_added_responsible",
    "description" : "A comment has been added to a page where a user is responsible for",
    "argument_keys" : [],
    "argument" : [],
    "category" : "message_type",
    "data" : {
      "token options" : { "clear" : 0 },
      "purge" : { "override" : 0, "enabled" : 0, "quota" : "", "days" : "" }
    },
    "language" : "en",
    "arguments" : null,
    "message_text" : {
      "en" : [
        {
          "value" : "A comment has been added to the page \\u0022[message:field-paddle-notifications-node:title]\\u0022.",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EA comment has been added to the page \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot;.\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Dear [message:user],\\r\\n\\r\\nOn [message:timestamp:long] a comment has been added to the page \\u0022[message:field-paddle-notifications-node:title]\\u0022 which you are responsible for.\\r\\n\\r\\nClick the link below to check the comment.\\r\\n[message:field-paddle-notifications-node:url]",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EDear [message:user],\\u003C\\/p\\u003E\\n\\u003Cp\\u003EOn [message:timestamp:long] a comment has been added to the page \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; which you are responsible for.\\u003C\\/p\\u003E\\n\\u003Cp\\u003EClick the link below to check the comment.\\u003Cbr \\/\\u003E\\n[message:field-paddle-notifications-node:url]\\u003C\\/p\\u003E\\n"
        }
      ],
      "nl" : [
        {
          "value" : "Aan de pagina \\u0022[message:field-paddle-notifications-node:title]\\u0022 is een reactie toegevoegd.",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EAan de pagina \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; is een reactie toegevoegd.\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Beste [message:user],\\n \\nOp [message:timestamp:long]  is aan de pagina \\u0022[message:field-paddle-notifications-node:title]\\u0022 waar jij verantwoordelijk voor bent een reactie toegevoegd.\\n\\nKlik op onderstaande link om direct naar de betreffende pagina te gaan. \\n[message:field-paddle-notifications-node:url]",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EBeste [message:user],\\u003C\\/p\\u003E\\n\\u003Cp\\u003EOp [message:timestamp:long]  is aan de pagina \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; waar jij verantwoordelijk voor bent een reactie toegevoegd.\\u003C\\/p\\u003E\\n\\u003Cp\\u003EKlik op onderstaande link om direct naar de betreffende pagina te gaan.\\u003Cbr \\/\\u003E\\n[message:field-paddle-notifications-node:url]\\u003C\\/p\\u003E\\n"
        }
      ]
    }
  }');
  return $items;
}
