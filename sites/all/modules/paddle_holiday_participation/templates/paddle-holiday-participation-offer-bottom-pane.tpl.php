<?php

/**
 * @file
 * Template of the bottom pane from an offer content type.
 */
?>

<?php if (!empty($surroundings_description)): ?>
  <div class="offer-section">
    <div class="offer-section-label">
      <h3><?php echo t('In the neighbourhood'); ?></h3>
    </div>
    <div class="offer-section-body">
      <ul>
        <?php foreach ($surroundings_description as $surroundings_description_line): ?>
          <li>
            <?php echo $surroundings_description_line; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($practical_info)): ?>
  <div class="offer-section">
    <div class="offer-section-label">
      <h3><?php echo t('Practical information'); ?></h3>
    </div>
    <div class="offer-section-body">
      <?php if (!empty($practical_info['private_transport'])): ?>
        <details>
          <summary>
            <?php echo t('How do you reach us with private transport?'); ?>
          </summary>
          <ul class="transport">
            <?php foreach ($practical_info['private_transport'] as $private_transport_line): ?>
              <li>
                <?php echo $private_transport_line; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </details>
      <?php endif; ?>
      <?php if (!empty($practical_info['public_transport'])): ?>
        <details>
          <summary>
            <?php echo t('How do you reach us with public transport?'); ?>
          </summary>
          <ul class="transport">
            <?php foreach ($practical_info['public_transport'] as $public_transport_line): ?>
              <li>
                <?php echo $public_transport_line; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </details>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>
