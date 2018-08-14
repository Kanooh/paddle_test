<?php

/**
 * @file
 * Template for the Opening Hours status label.
 */
?>
<?php if (isset($opening_hour_status)) : ?>
  <div class="opening-hours-status">
    <?php if (isset($current_opening_hour)): ?>
      <span class="opening-hours-label open">
        <?php echo $opening_hour_status; ?>
      </span>
      <span class="current-opening-hours">
        <?php echo $current_opening_hour; ?>
      </span>
    <?php else: ?>
      <span class="opening-hours-label closed">
        <?php echo $opening_hour_status; ?>
      </span>
    <?php endif; ?>
  </div>
<?php endif; ?>
<?php if (isset($description)) : ?>
  <span class="current-opening-hours-description">
        <?php echo $description; ?>
  </span>
<?php endif; ?>
