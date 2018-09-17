<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<div class="week-list-view-day <?php print strip_tags($title); ?>">
  <?php if (!empty($title)): ?>
    <div class="day-name"><?php print $title; ?></div></h3>
  <?php endif; ?>
  <?php if (!empty($rows)): ?>
      <div class="rows-wrapper">
        <?php foreach ($rows as $id => $row): ?>
          <div<?php if ($classes_array[$id]) { print ' class="' . $classes_array[$id] .'"';  } ?>>
            <?php print $row; ?>
          </div>
        <?php endforeach; ?>
      </div>
  <?php endif; ?>
</div>
