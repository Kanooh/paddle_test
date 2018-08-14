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

<div class="opening-hours-set">
  <div class="opening-hours-set-calendar">
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
                  <div class="oh-time">
                    <?php if (isset($opening_hour['time'])) : ?>
                      <?php echo $opening_hour['time']; ?>
                    <?php endif; ?>
                  </div>
                  <div class="oh-description">
                    <?php if (isset($opening_hour['description']) && $opening_hour['description'] != "") : ?>
                      <?php echo $opening_hour['description']; ?>
                    <?php endif; ?>
                  </div>
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
        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
        <?php echo t('Different opening hours apply on:'); ?>
        <?php foreach ($exceptional_opening_hours['soon'] as $exceptional_opening_hour) : ?>
          <div class="exceptional-opening-hour">
            <?php echo $exceptional_opening_hour['value']; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
