<?php

/**
 * @file
 * Template for the Opening Hours section.
 */
?>
<div class="opening-hours-set">
  <div class="opening-hours-set-calendar">
    <?php if (isset($title)): ?>
      <h3><?php echo $title ?></h3>
    <?php endif; ?>
    <?php if (!empty($current_week)) : ?>
      <div class="ohs-upcoming-week">
        <?php foreach ($current_week as $weekday) : ?>
          <div class="title-box">
            <?php if (isset($weekday['title'])) : ?>
              <?php echo $weekday['title']; ?>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
      <?php foreach ($current_week as $weekday) : ?>
        <div class="ohs-upcoming-day element-invisible">
          <?php if (!empty($weekday['opening_hours'])) : ?>
            <div class="opening-hours">
              <?php foreach ($weekday['opening_hours'] as $opening_hour) : ?>
                <div class="opening-hour">
                  <?php if (isset($opening_hour['time'])) : ?>
                    <div class="oh-time">
                      <?php echo $opening_hour['time']; ?>
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($opening_hour['description'])) : ?>
                    <div class="oh-description">
                      <?php echo $opening_hour['description']; ?>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <div class="opening-hours-exceptions">
    <?php if (!empty($closing_days['soon'])) : ?>
      <div class="closing-days">
        <?php foreach ($closing_days['soon'] as $closing_day) : ?>
          <div class="closing-day">
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
            <?php echo $closing_day['value']; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($exceptional_opening_hours['soon'])) : ?>
      <div class="exceptional-opening-hours">
        <div class="exceptional-oh-label">
          <i class="fa fa-exclamation-triangle" aria-hidden="true"></i><?php echo t('Different opening hours apply on:'); ?>
        </div>
        <?php foreach ($exceptional_opening_hours['soon'] as $exceptional_opening_hour) : ?>
          <div class="exceptional-opening-hour">
            <?php echo $exceptional_opening_hour['value']; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($fieldsets)) : ?>
      <div class="fieldsets">
        <?php echo $fieldsets; ?>
      </diV>
    <?php endif; ?>
  </div>
</div>
