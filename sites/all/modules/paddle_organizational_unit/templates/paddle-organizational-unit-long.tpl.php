<?php

/**
 * @file
 * Template for the Long view of the Organizational Unit Panes.
 */
?>

<div class="paddle-oup paddle-oup-title"><?php print $name; ?></div>
<?php if (!empty($address_formatted) || !empty($email) || !empty($phone) || !empty($fax) || !empty($website)) : ?>
  <div class="row">
    <?php if (!empty($address_formatted)) : ?>
      <div class="col-md-6">
        <div class="paddle-oup paddle-oup-address">
          <i class="fa fa-home"></i>
          <div class="inline-block"><?php print $address_formatted; ?></div>
        </div>
      </div>
    <?php endif;
    if (!empty($email) || !empty($phone) || !empty($fax) || !empty($website)) : ?>
      <div class="col-md-6">
        <?php if (!empty($email)) : ?>
          <div class="paddle-oup paddle-oup-email">
            <i class="fa fa-envelope valigntop"></i>
            <a href="mailto:<?php print $email; ?>"><?php print $email; ?></a>
          </div>
        <?php endif;
        if (!empty($website)) : ?>
          <div class="paddle-oup paddle-oup-website">
            <i class="fa fa-link valigntop"></i>
            <a href="<?php print $website; ?>"><?php print $website_simple; ?></a>
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
    <?php endif; ?>
  </div>
<?php endif; ?>
