<?php

/**
 * @file
 * Default theme implementation to display the VUB banner block.
 */
?>

<div class="vub-banner-block">
  <?php if (!empty($image)) : ?>
    <div class="vub-banner-image" style="background-image: url(<?php print $image; ?>)">
      <div class="vub-outer-div">
        <div class="vub-inner-div">
          <div class="vub-center-div">
            <?php if (!empty($subtitle)) : ?>
              <h3> <?php print $subtitle; ?> </h3>
            <?php endif; ?>
            <?php if (!empty($title)) : ?>
              <h2> <?php print $title; ?> </h2>
            <?php endif; ?>
            <?php if (!empty($body)) : ?>
              <p> <?php print $body; ?> </p>
            <?php endif; ?>
            <?php if (!empty($link_url)) : ?>
              <a href="<?php print $link_url; ?>" class="vub-button">
                <?php if (!empty($link_text)) : ?>
                  <?php print $link_text; ?>
                <?php else: ?>
                  <?php print t('Click here for more info'); ?>
                <?php endif; ?>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
