<?php

/**
 * @file
 * Template of the right pane from an offer content type.
 */
?>

<?php if (!empty($address)): ?>
  <div class="offer-section-right">
    <div class="offer-section-label">
      <h3><?php echo t('Address'); ?></h3>
    </div>
    <div class="offer-section-body">
      <?php if (!empty($address['image_path'])): ?>
      <div class="offer-address-wrapper row">
        <div class="offer-image col-md-3 col-sm-1 col-xs-2">
          <img src="<?php echo $address['image_path']; ?>"/>
        </div>
        <div class="offer-address col-md-9 col-sm-11 col-xs-8">
          <?php endif; ?>
          <?php if (!empty($address['name'])): ?>
            <div class="offer-address-line">
              <?php echo $address['name']; ?>
            </div>
          <?php endif; ?>
          <?php if (!empty($address['thoroughfare'])): ?>
            <div class="offer-address-line">
              <?php echo $address['thoroughfare']; ?>
            </div>
          <?php endif; ?>
          <?php if (!empty($address['premise'])): ?>
            <div class="offer-address-line">
              <?php echo $address['premise']; ?>
            </div>
          <?php endif; ?>
          <?php if (!empty($address['municipality'])): ?>
            <div class="offer-address-line">
              <?php echo $address['municipality']; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <?php if (!empty($website)): ?>
        <div class="row">
          <div class="offer-image col-md-3 col-sm-1 col-xs-2">
            <img src="<?php echo $website['image_path']; ?>"/>
          </div>
          <div class="offer-website col-md-9 col-sm-11 col-xs-8">
            <?php echo l(t('Go to the website'), $website['url']); ?>
          </div>
        </div>
      <?php endif; ?>
      <?php if (!empty($geofield)): ?>
        <div class="offer-map">
          <?php print render($geofield); ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($facilities)): ?>
  <div class="offer-section-right">
    <div class="offer-section-label">
      <h3><?php echo t('Facilities'); ?></h3>
    </div>
    <div class="offer-section-body">
      <?php foreach ($facilities as $facility): ?>
        <div class="row">
          <div class="offer-image col-md-3 col-sm-1 col-xs-2">
            <img src="<?php echo $facility['image_path']; ?>"/>
          </div>
          <div class="offer-image-label col-md-9 col-sm-11 col-xs-8">
            <?php echo $facility['label']; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($social_media)): ?>
  <div class="offer-section-right">
    <div class="offer-section-label">
      <h3><?php echo t('Follow us on'); ?></h3>
    </div>
    <div class="offer-section-body">
      <?php if (!empty($social_media['facebook'])): ?>
        <div class="offer-social-media-image">
          <a href=" <?php echo $social_media['facebook']['url']; ?>">
            <img src="<?php echo $social_media['facebook']['image_path']; ?>"/>
          </a>
        </div>
      <?php endif; ?>
      <?php if (!empty($social_media['twitter'])): ?>
        <div class="offer-social-media-image">
          <a href=" <?php echo $social_media['twitter']['url']; ?>">
            <img src="<?php echo $social_media['twitter']['image_path']; ?>"/>
          </a>
        </div>
      <?php endif; ?>
      <?php if (!empty($social_media['youtube'])): ?>
        <div class="offer-social-media-image">
          <a href=" <?php echo $social_media['youtube']['url']; ?>">
            <img src="<?php echo $social_media['youtube']['image_path']; ?>"/>
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>
