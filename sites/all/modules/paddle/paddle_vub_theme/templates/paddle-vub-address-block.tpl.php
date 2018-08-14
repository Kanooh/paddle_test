<?php

/**
 * @file
 * Default theme implementation to display the VUB footer block.
 */
global $base_url;
?>

<div class="vub-address-block">
  <?php if (!empty($logo)) : ?>
    <?php if (!empty($website)) : ?>
      <a href="<?php print $website; ?>">
    <?php else: ?>
      <a href="#">
    <?php endif; ?>
      <div class="vub-logo">
        <?php print $logo; ?>
      </div>
    </a>
  <?php endif; ?>
    <hr>
    <p>
      <?php if (!empty($thoroughfare)) : ?>
        <?php print $thoroughfare; ?>
      <?php endif; ?>
      <?php if (!empty($postal_code)) : ?>
        <?php print $postal_code; ?>
      <?php endif; ?>
      <?php if (!empty($city)) : ?>
        <?php print $city; ?>
      <?php endif; ?>
      <br/>
      <?php if (!empty($phone_number)) : ?>
        <?php print $phone_number; ?>
      <?php endif; ?>
      <br/>
      <?php if (!empty($mail)) : ?>
        <a href="mailto:<?php print $mail; ?>"><?php print $mail; ?></a>
      <?php endif; ?>
    </p>
</div>
