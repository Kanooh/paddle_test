<?php

/**
 * @file
 * Template for the Long view of the Contact Person Panes.
 */
?>

<div class="paddle-cp paddle-cp-title"><?php print $full_name; ?></div>
<?php if (!empty($function)) : ?>
  <div class="paddle-cp paddle-cp-function"><?php print $function; ?></div>
<?php endif; ?>
<div class="row">

    <?php if (!empty($address)) : ?>
      <div class="col-md-6">
        <div class="paddle-cp paddle-cp-address">
          <i class="fa fa-home"></i>
          <div class="inline-block"><?php print $address; ?></div>
        </div>
      </div>
    <?php endif;
    if (!empty($email) || !empty($phone_office) || !empty($mobile_office) || !empty($field_cp_office)) : ?>
      <div class="col-md-6">
        <?php if (!empty($email)) : ?>
          <div class="paddle-cp paddle-cp-email">
            <i class="fa fa-envelope valigntop"></i>
            <?php print $email; ?>
          </div>
        <?php endif;
        if (!empty($field_cp_office)) : ?>
          <div class="paddle-cp paddle-cp-phone">
            <i class="fa valigntop"></i>
            <div class="inline-block"><?php print $field_cp_office; ?></div>
          </div>
        <?php endif;
        if (!empty($phone_office)) : ?>
          <div class="paddle-cp paddle-cp-phone">
            <i class="fa fa-phone valigntop"></i>
            <div class="inline-block"><?php print $phone_office; ?></div>
          </div>
        <?php endif;
        if (!empty($mobile_office)) : ?>
          <div class="paddle-cp paddle-cp-mobile">
            <i class="fa fa-mobile valigntop"></i>
            <div class="inline-block"><?php print $mobile_office; ?></div>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
</div>
