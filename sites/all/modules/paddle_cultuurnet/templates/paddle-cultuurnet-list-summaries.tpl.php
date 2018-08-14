<?php

/**
 * @file
 * Template for the list view with the summaries view mode.
 */
?>
<div class="paddle-cultuurnet-list list-summaries">
  <?php if (!empty($events)) : ?>
    <?php foreach ($events as $event) : ?>
      <div class="paddle-cultuurnet-event">
        <?php if (isset($event['title'])) : ?>
          <div class="img-wrapper">
            <?php if (!empty($event['image_url'])) : ?>
              <?php
              //The properties: aria label, role and title are replacing the alt tag, which normally is being used with an <img> element.
              // @TODO refactor so that the background-image URL is added dynamically to the class
              ?>
              <div role="img" aria-label="<?php print $event['title']; ?>" title="<?php print $event['title']; ?>" class="event-image" style="background-image:url(<?php print $event['image_url']; ?>)"></div>
            <?php endif; ?>
          </div>
          <div class="detail-wrapper">
          <?php if (isset($event['url'])) : ?>
          <a href="<?php print $event['url']; ?>" class="paddle-cultuurnet-event-link">
          <?php endif; ?>
          <h4><?php echo $event['title']; ?></h4>
          <?php if (isset($event['url'])) : ?>
          </a>
          <?php endif; ?>
          <?php if (!empty($event['description'])) : ?>
            <div class="event-description">
              <?php !empty($event['period']) ? print $event['period'] . ' - ' . $event['description'] : print $event['description']; ?>
            </div>
            <?php else: ?>
            <?php !empty($event['period']) ? print $event['period'] : ''; ?>
            <?php endif; ?>
            </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
