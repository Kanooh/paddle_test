<?php

/**
 * @file
 * Template of the introduction component of the Web Universe.
 */
?>

<div class="web-universe-introduction-component">
  <!-- component -->
  <?php if (!empty($title)) : ?>
    <h2 class="h2">
      <?php print $title; ?>
    </h2>
  <?php endif; ?>
<?php if (!empty($body)) : ?>
  <div class="introduction">
    <?php print $body; ?>
  </div>
<?php endif; ?>
</div>
