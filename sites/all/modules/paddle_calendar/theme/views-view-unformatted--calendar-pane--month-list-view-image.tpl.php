<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<div class="month-list-view-image-day">
  <?php foreach ($rows as $id => $row): ?>
    <div<?php if ($classes_array[$id]) { print ' class="' . $classes_array[$id] .'"';  } ?>>
      <?php print $row; ?>

      <?php if (!empty($title)): ?>
        <?php print $title; ?>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>
