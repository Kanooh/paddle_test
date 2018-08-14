<?php

/**
 * @file
 * Template of the left pane from an offer content type.
 */
?>

<?php if (!empty($validity)): ?>
  <div class="offer-section">
    <div class="offer-section-label">
      <h3><?php echo t('When valid?'); ?></h3>
    </div>
    <div class="offer-section-body">
      <p class="bold"><?php echo t('Validity period'); ?></p>
      <?php foreach ($validity['description'] as $description_line): ?>
        <div class="offer-section-text arrow">
          <?php echo $description_line; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($opening_hours)): ?>
  <div class="offer-section">
    <div class="offer-section-label">
      <h3><?php echo t('Opening hours'); ?></h3>
    </div>
    <div class="offer-section-body">
      <?php echo $opening_hours; ?>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($residence_description)): ?>
  <div class="offer-section">
    <div class="offer-section-label">
      <h3><?php echo t('The residence'); ?></h3>
    </div>
    <div class="offer-section-body">
      <?php echo $residence_description; ?>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($on_the_spot_description)): ?>
  <div class="offer-section">
    <div class="offer-section-label">
      <h3><?php echo t('On the spot'); ?></h3>
    </div>
    <div class="offer-section-body">
      <?php echo $on_the_spot_description; ?>
    </div>
  </div>
<?php endif; ?>
