<?php
/**
 * @file
 * Template for the user management block.
 */
?>
<div class="personal-info">
  <?php print $picture; ?>

  <div class="username">
    <p>
      <?php print $username; ?>
    </p>
  </div>

  <div class="user-links">
    <?php print render($links); ?>
  </div>
</div>
