<?php

/**
 * @file
 * Template for the Medium view of the Organizational Unit Panes.
 */
?>
<div class="paddle-oup paddle-oup-title"><?php print $name; ?></div>
<?php if (!empty($email) || !empty($phone) || !empty($fax) || !empty($website)) : ?>
  <div class="row">
    <?php if (!empty($email)) : ?>
      <div class="col-md-6">
        <div class="paddle-oup paddle-oup-email">
          <i class="fa fa-envelope valigntop"></i>
          <a href="mailto:<?php print $email; ?>"><?php print $email; ?></a>
        </div>
        <?php if (!empty($website)) : ?>
          <div class="paddle-oup paddle-oup-website">
            <i class="fa fa-link valigntop"></i>
            <a href="<?php print $website; ?>"><?php print $website_simple; ?></a>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($phone) || !empty($fax)) : ?>
      <div class="col-md-6">
        <?php if (!empty($phone)) : ?>
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
    <?php endif; ?>
  </div>
<?php endif; ?>
