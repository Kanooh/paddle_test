<?php

/**
 * @file
 * Template for the Short view of the Contact Person Panes.
 */
?>
<div class="paddle-cp paddle-cp-title"><?php print $full_name; ?></div>
<?php if (!empty($function)) : ?>
  <div class="paddle-cp paddle-cp-function"><?php print $function; ?></div>
<?php endif; ?>
<?php if (!empty($email)) : ?>
  <div class="paddle-cp paddle-cp-email">
    <i class="fa fa-envelope valigntop"></i>
    <?php print $email; ?>
  </div>
<?php endif; ?>
