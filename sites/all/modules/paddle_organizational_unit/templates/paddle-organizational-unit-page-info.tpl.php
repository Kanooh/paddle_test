<?php

/**
 * @file
 * Template for the Page view of the Organizational Unit.
 */
?>
<?php if (!empty($phone) || !empty($fax) || !empty($email) || !empty($title)) : ?>
  <div class="paddle-oup-page-info">
    <h2 class="paddle-oup-page-title"><?php print $title; ?></h2>
    <?php if (!empty($email)) : ?>
      <div class="paddle-oup paddle-oup-email">
        <i class="fa fa-envelope"></i>
        <a href="mailto:<?php print $email; ?>"><?php print $email; ?></a>
      </div>
    <?php endif;
    if (!empty($phone)) : ?>
      <div class="paddle-oup paddle-oup-phone">
        <i class="fa fa-phone"></i>
        <div class="inline-block"><?php print $phone; ?></div>
      </div>
    <?php endif;
    if (!empty($fax)) : ?>
      <div class="paddle-oup paddle-oup-fax">
        <i class="fa fa-print"></i>
        <div class="inline-block"><?php print $fax; ?></div>
      </div>
    <?php endif; ?>
  </div>
<?php endif;
