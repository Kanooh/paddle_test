<?php

/**
 * @file
 * Template for the Short view of the Organizational Unit Panes.
 */
?>
<div class="paddle-oup paddle-oup-title"><?php print $name; ?></div>
<?php if (!empty($email)) : ?>
  <div class="paddle-oup paddle-oup-email">
    <i class="fa fa-envelope valigntop"></i>
    <a href="mailto:<?php print $email; ?>"><?php print $email; ?></a>
  </div>
<?php endif; ?>
