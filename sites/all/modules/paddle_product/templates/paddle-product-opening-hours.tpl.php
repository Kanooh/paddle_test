<?php
/**
 * @file
 * Template for the openings hours on a product page.
 */
?>

<div class="product-opening-hours">
  <h2><?php print $link; ?></h2>

  <?php if (!empty($address)): ?>
    <div class="address">
      <?php print $address; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($phone)): ?>
    <div class="phone">
      <?php print t('Tel.') . ' ' . $phone; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($website)): ?>
    <div class="website">
      <a href="<?php print $website; ?>"><?php print $website; ?></a>
    </div>
  <?php endif; ?>

  <?php if (!empty($email)): ?>
    <div class="email">
      <a href="mailto:<?php print $email; ?>"><?php print $email; ?></a>
    </div>
  <?php endif; ?>

  <?php if (isset($opening_hour_status)) : ?>
    <div class="opening-hours-status">
      <?php if (isset($current_opening_hour)): ?>
        <div class="opening-hour-status">
          <?php echo $opening_hour_status; ?>
        </div>
        <div class="current-opening-hour">
          <?php echo $current_opening_hour; ?>
        </div>
      <?php else: ?>
        <div class="opening-hour-status">
          <?php echo $opening_hour_status; ?>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <?php if (isset($description)) : ?>
    <div class="current-opening-hours-description">
      <?php echo $description; ?>
    </div>
  <?php endif; ?>
</div>
