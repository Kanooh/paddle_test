<?php

/**
 * @file
 * Template of the image pane from an offer content type.
 */
?>

<?php if (!empty($main_image)): ?>
  <div class="offer-section">
    <div class="offer-section-body">
      <div class="main-image">
        <?php echo $main_image; ?>
      </div>
      <?php if (!empty($images)): ?>
        <div class="pane-photo-album">
          <div class="pane-section-body">
            <?php echo $images; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>
