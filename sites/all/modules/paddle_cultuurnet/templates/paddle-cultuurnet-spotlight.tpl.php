<?php

/**
 * @file
 * Template for the "In the Spotlight" view.
 */
?>
<div class="paddle-cultuurnet-spotlight-event">
  <?php if (!empty($title)) : ?>
    <?php if (!empty($url)) : ?>
      <a href="<?php print $url; ?>" class="paddle-cultuurnet-event-link">
    <?php endif; ?>
    <div class="paddle-cultuurnet-spotlight-details">
      <?php if (!empty($image_url)) : ?>
        <?php
        //The properties: aria label, role and title are replacing the alt tag, which normally is being used with an <img> element.
        // @TODO refactor so that the background-image URL is added dynamically to the class
        ?>
        <div role="img" aria-label="<?php print $title; ?>" title="<?php print $title; ?>" class="spotlight-image" style="background-image:url(<?php print $image_url; ?>)"></div>
      <?php endif; ?>
      <div class="spotlight-bottom">
        <h3><?php print $title; ?></h3>
        <?php if (!empty($period)) : ?>
          <div class="spotlight-period">
            <?php print $period; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <?php if (!empty($url)) : ?>
      </a>
    <?php endif; ?>
  <?php endif; ?>
</div>
