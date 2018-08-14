<?php

/**
 * @file
 * Template of the center pane from an offer content type.
 */
?>

<?php if (!empty($room_and_board)): ?>
  <div class="offer-section">
    <div class="offer-section-label">
      <h3><?php echo t('Residence formula'); ?></h3>
    </div>
    <div class="offer-section-body">
      <?php foreach ($room_and_board as $room_and_board_item): ?>
        <div class="row">
          <div class="offer-image col-lg-3 col-sm-1 col-xs-2">
            <img src="<?php echo $room_and_board_item['image_path']; ?>"/>
          </div>
          <div class="offer-image-label col-lg-9 col-sm-11 col-xs-8">
            <?php echo $room_and_board_item['label']; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($reservation)): ?>
  <div class="offer-section">
    <div class="offer-section-label">
      <h3><?php echo t('When to book?'); ?></h3>
    </div>
    <div class="offer-section-body">
      <?php if (!empty($reservation['individual'])): ?>
        <h5><?php echo t('Individually'); ?></h5>
        <div class="offer-section-text arrow">
          <?php echo $reservation['individual']; ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($reservation['group'])): ?>
        <h5><?php echo t('With a group'); ?></h5>
        <div class="offer-section-text arrow">
          <?php echo $reservation['group']; ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($reservation['comments'])): ?>
        <h5><?php echo t('Extra information'); ?></h5>
        <div class="offer-section-text">
          <?php echo $reservation['comments']; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($labels)): ?>
  <div class="offer-section">
    <div class="offer-section-label">
      <h3><?php echo t('Label'); ?></h3>
    </div>
    <div class="offer-section-body">
      <?php foreach ($labels as $label_item): ?>
        <div class="row">
          <div class="offer-image col-lg-3 col-sm-1 col-xs-2">
            <img src="<?php echo $label_item['image_path']; ?>"/>
          </div>
          <div class="offer-image-label col-lg-9 col-sm-11 col-xs-8">
            <?php echo $label_item['label']; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>
