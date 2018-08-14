<?php

/**
 * @file
 * Template of the basic information pane of the Company page.
 */
?>

<div class="pane-company-basic-info">

  <?php if (!empty($logo)) : ?>
    <div class="company-logo">
      <img src="<?php print $logo ?>" alt="<?php print $title ?> - logo"/>
    </div>
  <?php endif; ?>

  <div class="company-info">
    <div class="company-top-info">
      <?php if (!empty($title)) : ?>
        <h1 class="company-name">
          <?php print $title; ?>
        </h1>
      <?php endif; ?>
      <?php if (!empty($site)) : ?>
        <div class="company-site">
          <a href="<?php print $site; ?>"> <?php print $site_simple; ?></a>
        </div>
      <?php endif; ?>
    </div>
    <div class="company-details">
      <div class="company-address">
        <?php if (!empty($address)) : ?>
        <i class="fa fa-home valigntop"></i>
        <div class="company-address-container">
          <?php print $address; ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($vat)) : ?>
          <div class="company-vat">
          <label><?php print t('VAT:'); ?></label><?php print $vat; ?>
          </div>
        <?php endif; ?>
      </div>
      <?php if (!empty($mail) || !empty($phone) || !empty($facebook) || !empty($twitter) || !empty($linkedin)) : ?>
        <div class="company-social">
          <?php if (!empty($mail)) : ?>
            <a href="mailto:<?php print $mail; ?>"><i
                class="fa fa-envelope valigntop"></i>
              <?php print $mail; ?></a>
          <?php endif; ?>
          <?php if (!empty($phone)) : ?>
            <p><a href="tel:<?php print $phone; ?>"><i
                  class="fa fa-phone valigntop"></i>
                <?php print $phone; ?></a></p>
          <?php endif; ?>

          <div class="company-social-icons">
            <?php if (!empty($facebook)) : ?>
              <a href="<?php print $facebook['value']; ?>">
                <i aria-label="Facebook" class="fa fa-facebook"></i>
              </a>
            <?php endif; ?>
            <?php if (!empty($twitter)) : ?>
              <a href="<?php print $twitter['value']; ?>">
                <i aria-label="Twitter" class="fa fa-twitter"></i>
              </a>
            <?php endif; ?>
            <?php if (!empty($linkedin)) : ?>
              <a href="<?php print $linkedin['value']; ?>">
                <i aria-label="LinkedIn" class="fa fa-linkedin"></i>
              </a>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
