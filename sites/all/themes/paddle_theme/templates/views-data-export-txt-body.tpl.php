<?php

/**
 * @file
 * Views-view-table.tpl.php.
 *
 * Template to display a view as a table.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $rows: An array of row items. Each row is an array of content
 *   keyed by field ID.
 * - $header: an array of headers(labels) for fields.
 * - $themed_rows: a array of rows with themed fields.
 * @ingroup views_templates
 */

if (!empty($themed_rows)) {
  foreach ($themed_rows as $count => $row):
    foreach ($row as $field => $content):
      if (in_array($field, $multi_value_fields)) {
        $keywords = array();
        $keywords = explode(",", $content);
        foreach ($keywords as $keyword) {
          if (!empty($header[$field]) && !empty($keyword)) {
            print trim(strip_tags($header[$field])) . '  - ' . trim(strip_tags($keyword)) . "\r\n";
          }
          elseif (!empty($keyword)) {
            print trim(strip_tags($keyword)) . "\r\n";
          }
        }
      }
      else {
        if (!empty($header[$field]) && !empty($content)) {
          // Overwrite the type value to meet the Bibliographic software standards, see PADKCE-64.
          // We do it in the template to avoid creating an update path for the list keys and values.
          if ($header[$field] === 'TY') {
            $content = $publications_types[$content];
          }
          print trim(strip_tags($header[$field])) . '  - ' . trim(strip_tags($content)) . "\r\n";
        }
        elseif (!empty($content)) {
          print trim(strip_tags($content)) . "\r\n";
        }
      }
    endforeach;
  endforeach;
// Print the closing tag of the document.
  print "ER -\r\n";
}
