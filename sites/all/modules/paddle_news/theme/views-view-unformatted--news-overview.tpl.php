<?php

/**
 * @file
 * Template that displays all news items for the news overview View.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($title)): ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>
<?php
  // By design this view should have 3 columns.
  $column_number = 3;
  $columns_output = array();

  // Open the column divs.
  for ($i = 0; $i < $column_number; $i++) :
    $columns_output[$i] = '<div class="news-overview-column col-md-4 root-column">';
  endfor;

  // Add the rows data.
  $count = 0;
  foreach ($rows as $id => $row) :
    // Output the node ID as a data attribute.
    $nid = $view->result[$id]->nid;
    $classes = !empty($classes_array[$id]) ? ' ' . $classes_array[$id] : '';
    $columns_output[$count % $column_number] .= '<div class="panel-pane news-item' . $classes . '" data-news-item-nid="' . check_plain($nid) . '">' . $row . '</div>';
    $count++;
  endforeach;

  // Print the column divs.
  for ($i = 0; $i < $column_number; $i++) :
    print $columns_output[$i] . '</div>';
  endfor;
?>
