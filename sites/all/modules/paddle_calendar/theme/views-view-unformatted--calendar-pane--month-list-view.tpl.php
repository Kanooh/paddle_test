<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<div class="month-list-view-day">
  <?php if (!empty($title)): ?>
      <div class="day-name"><?php print $title; ?></div>
  <?php endif; ?>
  <?php foreach ($rows as $id => $row): ?>
      <div<?php if ($classes_array[$id]) { print ' class="' . $classes_array[$id] .'"';  } ?>>
          <?php print $row; ?>
      </div>
  <?php endforeach; ?>
</div>
