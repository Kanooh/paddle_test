<?php
/**
 * @file
 * paddle_notifications.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_notifications_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "elysia_cron" && $api == "default_elysia_cron_rules") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_default_message_type().
 */
function paddle_notifications_default_message_type() {
  $items = array();
  $items['paddle_notifications_node_update_responsible'] = entity_import('message_type', '{
    "name" : "paddle_notifications_node_update_responsible",
    "description" : "A page an user is responsible for has been modified",
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
          "value" : "The page \\u0022[message:field-paddle-notifications-node:title]\\u0022 has been modified.",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EThe page \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; has been modified.\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Dear [message:user],\\n\\nOn [message:timestamp:long] [message:field-paddle-notifications-user] has changed the page \\u0022[message:field-paddle-notifications-node:title]\\u0022 which you are responsible for.\\n\\nTo see what has changed, go to the page where the latest revisions can be compared. \\n\\nClick the link below to review the latest changes.\\n[message:field-paddle-notifications-node:diff-comparison-url]",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EDear [message:user],\\u003C\\/p\\u003E\\n\\u003Cp\\u003EOn [message:timestamp:long] [message:field-paddle-notifications-user] has changed the page \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; which you are responsible for.\\u003C\\/p\\u003E\\n\\u003Cp\\u003ETo see what has changed, go to the page where the latest revisions can be compared. \\u003C\\/p\\u003E\\n\\u003Cp\\u003EClick the link below to review the latest changes.\\u003Cbr \\/\\u003E\\n[message:field-paddle-notifications-node:diff-comparison-url]\\u003C\\/p\\u003E\\n"
        }
      ],
      "nl" : [
        {
          "value" : "De pagina \\u0022[message:field-paddle-notifications-node:title]\\u0022 is aangepast.",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EDe pagina \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; is aangepast.\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Beste [message:user],\\r\\n\\r\\nOp [message:timestamp:long] heeft [message:field-paddle-notifications-user] de pagina \\u0022[message:field-paddle-notifications-node:title]\\u0022 waar jij verantwoordelijk voor bent gewijzigd.\\r\\n\\r\\nOm te zien wat er gewijzigd is, ga je naar de pagina waar deze wijziging met de laatste revisie vergeleken kan worden.\\r\\n\\r\\nKlik op onderstaande link om direct de laatste wijzigingen te bekijken.\\r\\n[message:field-paddle-notifications-node:diff-comparison-url]",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EBeste [message:user],\\u003C\\/p\\u003E\\n\\u003Cp\\u003EOp [message:timestamp:long] heeft [message:field-paddle-notifications-user] de pagina \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; waar jij verantwoordelijk voor bent gewijzigd.\\u003C\\/p\\u003E\\n\\u003Cp\\u003EOm te zien wat er gewijzigd is, ga je naar de pagina waar deze wijziging met de laatste revisie vergeleken kan worden.\\u003C\\/p\\u003E\\n\\u003Cp\\u003EKlik op onderstaande link om direct de laatste wijzigingen te bekijken.\\u003Cbr \\/\\u003E\\n[message:field-paddle-notifications-node:diff-comparison-url]\\u003C\\/p\\u003E\\n"
        }
      ]
    }
  }');
  $items['paddle_notifications_note_added_responsible'] = entity_import('message_type', '{
    "name" : "paddle_notifications_note_added_responsible",
    "description" : "Editorial note added on node",
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
          "value" : "A note has been added to the page \\u0022[message:field-paddle-notifications-node:title]\\u0022.",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EA note has been added to the page \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot;.\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Dear [message:user],\\r\\n\\r\\non [message:timestamp:long] [message:field-paddle-notification-user] has added a note to the page \\u0022[message:field-paddle-notification-node:title]\\u0022 which you are responsible for.\\r\\n\\r\\nClick the link below to check the note.\\r\\n[message:field-paddle-notifications-node:edit-url]",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EDear [message:user],\\u003C\\/p\\u003E\\n\\u003Cp\\u003Eon [message:timestamp:long] [message:field-paddle-notification-user] has added a note to the page \\u0026quot;[message:field-paddle-notification-node:title]\\u0026quot; which you are responsible for.\\u003C\\/p\\u003E\\n\\u003Cp\\u003EClick the link below to check the note.\\u003Cbr \\/\\u003E\\n[message:field-paddle-notifications-node:edit-url]\\u003C\\/p\\u003E\\n"
        }
      ],
      "nl" : [
        {
          "value" : "Aan de pagina \\u0022[message:field-paddle-notifications-node:title]\\u0022 is een kanttekening toegevoegd.",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EAan de pagina \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; is een kanttekening toegevoegd.\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Beste [message:user],\\n \\nOp [message:timestamp:long] heeft [message:field-paddle-notifications-user] aan de pagina \\u0022[message:field-paddle-notifications-node:title]\\u0022 waar jij verantwoordelijk voor bent een kanttekening toegevoegd.\\n \\nKlik op onderstaande link om direct naar de betreffende pagina te gaan.\\n[message:field-paddle-notifications-node:edit-url]",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EBeste [message:user],\\u003C\\/p\\u003E\\n\\u003Cp\\u003EOp [message:timestamp:long] heeft [message:field-paddle-notifications-user] aan de pagina \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; waar jij verantwoordelijk voor bent een kanttekening toegevoegd.\\u003C\\/p\\u003E\\n\\u003Cp\\u003EKlik op onderstaande link om direct naar de betreffende pagina te gaan.\\u003Cbr \\/\\u003E\\n[message:field-paddle-notifications-node:edit-url]\\u003C\\/p\\u003E\\n"
        }
      ]
    }
  }');
  $items['paddle_notifications_page_assigned'] = entity_import('message_type', '{
    "name" : "paddle_notifications_page_assigned",
    "description" : "A page has been assigned to a user.",
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
          "value" : "The page \\u0022[message:field-paddle-notifications-node:title]\\u0022 has been assigned to you.\\r\\n",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EThe page \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; has been assigned to you.\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Dear [message:user],\\r\\n\\r\\nOn [message:timestamp:long] [message:field-paddle-notifications-user] has assigned page \\u0022[message:field-paddle-notifications-node:title]\\u0022 to you.\\r\\n\\r\\nClick the link below to go to the page directly.\\r\\n[message:field-paddle-notifications-node:url]",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EDear [message:user],\\u003C\\/p\\u003E\\n\\u003Cp\\u003EOn [message:timestamp:long] [message:field-paddle-notifications-user] has assigned page \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; to you.\\u003C\\/p\\u003E\\n\\u003Cp\\u003EClick the link below to go to the page directly.\\u003Cbr \\/\\u003E\\n[message:field-paddle-notifications-node:url]\\u003C\\/p\\u003E\\n"
        }
      ],
      "nl" : [
        {
          "value" : "De pagina \\u0022[message:field-paddle-notifications-node:title]\\u0022 is aan jou toegewezen.\\n",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EDe pagina \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; is aan jou toegewezen.\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Beste [message:user],\\n\\nOp [message:timestamp:long] heeft [message:field-paddle-notifications-user] de pagina \\u0022[message:field-paddle-notifications-node:title]\\u0022 aan jou toegewezen.\\n\\nKlik op onderstaande link om direct naar de betreffende pagina te gaan. \\n[message:field-paddle-notifications-node:url]",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EBeste [message:user],\\u003C\\/p\\u003E\\n\\u003Cp\\u003EOp [message:timestamp:long] heeft [message:field-paddle-notifications-user] de pagina \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; aan jou toegewezen.\\u003C\\/p\\u003E\\n\\u003Cp\\u003EKlik op onderstaande link om direct naar de betreffende pagina te gaan.\\u003Cbr \\/\\u003E\\n[message:field-paddle-notifications-node:url]\\u003C\\/p\\u003E\\n"
        }
      ]
    }
  }');
  $items['paddle_notifications_page_expiration'] = entity_import('message_type', '{
    "name" : "paddle_notifications_page_expiration",
    "description" : "A published node is going to expire within 2 weeks",
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
          "value" : "The page \\u0022 [message:field-paddle-notifications-node:title]\\u0022 will elapse within 2 weeks.",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EThe page \\u0026quot; [message:field-paddle-notifications-node:title]\\u0026quot; will elapse within 2 weeks.\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Dear [message:user],\\r\\n\\r\\nThe page \\u0022[message:field-paddle-notifications-node:title]\\u0022(1) will be put offline within 2 weeks.\\r\\n Click the link below to go to the page directly.\\r\\n\\r\\n(1) [message:field-paddle-notifications-node:url]\\r\\n",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EDear [message:user],\\u003C\\/p\\u003E\\n\\u003Cp\\u003EThe page \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot;(1) will be put offline within 2 weeks.\\u003Cbr \\/\\u003E\\n Click the link below to go to the page directly.\\u003C\\/p\\u003E\\n\\u003Cp\\u003E(1) [message:field-paddle-notifications-node:url]\\u003C\\/p\\u003E\\n"
        }
      ],
      "nl" : [
        {
          "value" : "De pagina \\u0022[message:field-paddle-notifications-node:title]\\u0022 wordt over 2 weken gedepubliceerd.",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EDe pagina \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; wordt over 2 weken gedepubliceerd.\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Beste [message:user],\\n\\nDe pagina, \\u0022[message:field-paddle-notifications-node:title]\\u0022(1), wordt over 2 weken automatisch gedepubliceerd.\\nKlik op onderstaande link om direct naar de betreffende pagina te gaan.\\n\\n(1) [message:field-paddle-notifications-node:url]\\n",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EBeste [message:user],\\u003C\\/p\\u003E\\n\\u003Cp\\u003EDe pagina, \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot;(1), wordt over 2 weken automatisch gedepubliceerd.\\u003Cbr \\/\\u003E\\nKlik op onderstaande link om direct naar de betreffende pagina te gaan.\\u003C\\/p\\u003E\\n\\u003Cp\\u003E(1) [message:field-paddle-notifications-node:url]\\u003C\\/p\\u003E\\n"
        }
      ]
    }
  }');
  $items['paddle_notifications_page_expired'] = entity_import('message_type', '{
    "name" : "paddle_notifications_page_expired",
    "description" : "Expiration date of a page has elapsed",
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
          "value" : "The page \\u0022[message:field-paddle-notifications-node:title]\\u0022 is unpublished.",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EThe page \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; is unpublished.\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Dear [message:user],\\r\\n\\r\\nThe page \\u0022[message:field-paddle-notifications-node:title]\\u0022 has been unpublished today.\\r\\n\\r\\nClick the link below to go to the page directly.\\r\\n[message:field-paddle-notifications-node:url]",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EDear [message:user],\\u003C\\/p\\u003E\\n\\u003Cp\\u003EThe page \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; has been unpublished today.\\u003C\\/p\\u003E\\n\\u003Cp\\u003EClick the link below to go to the page directly.\\u003Cbr \\/\\u003E\\n[message:field-paddle-notifications-node:url]\\u003C\\/p\\u003E\\n"
        }
      ],
      "nl" : [
        {
          "value" : "De pagina \\u0022[message:field-paddle-notifications-node:title]\\u0022 is gedepubliceerd.",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EDe pagina \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; is gedepubliceerd.\\u003C\\/p\\u003E\\n"
        },
        {
          "value" : "Beste [message:user],\\n\\nDe pagina \\u0022[message:field-paddle-notifications-node:title]\\u0022 is vandaag automatisch gedepubliceerd.\\n\\nKlik op onderstaande link om direct naar de betreffende pagina te gaan. \\n[message:field-paddle-notifications-node:url]",
          "format" : "plain_text",
          "safe_value" : "\\u003Cp\\u003EBeste [message:user],\\u003C\\/p\\u003E\\n\\u003Cp\\u003EDe pagina \\u0026quot;[message:field-paddle-notifications-node:title]\\u0026quot; is vandaag automatisch gedepubliceerd.\\u003C\\/p\\u003E\\n\\u003Cp\\u003EKlik op onderstaande link om direct naar de betreffende pagina te gaan.\\u003Cbr \\/\\u003E\\n[message:field-paddle-notifications-node:url]\\u003C\\/p\\u003E\\n"
        }
      ]
    }
  }');
  return $items;
}
