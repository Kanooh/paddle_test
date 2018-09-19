<?php

/**
 * @file
 * Template for the list view with the titles view mode.
 */
?>
<div class="paddle-cultuurnet-list list-title">
  <?php if (!empty($events)) : ?>
    <?php foreach ($events as $event) : ?>
      <?php if (isset($event['url'])) : ?>
        <a href="<?php print $event['url']; ?>" class="paddle-cultuurnet-event-link">
      <?php endif; ?>
      <div class="paddle-cultuurnet-event">
        <?php if (isset($event['title'])) : ?>
          <div class="event-title"><?php echo $event['title']; ?></div>
        <?php endif; ?>
      </div>
      <?php if (isset($event['url'])) : ?>
        </a>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
