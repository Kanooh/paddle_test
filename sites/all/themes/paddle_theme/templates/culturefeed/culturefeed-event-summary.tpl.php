<?php
/**
 * @file
 * Template for the summary of an event.
 * Please don't remove the cf- prefixed id's. This is used by GTM for user behavior tracking.
 * Some day your client will benefit from our aggregated insights & benchmarks too.
 * See https://github.com/cultuurnet/culturefeed/wiki/Culturefeed-tracking
 * Thanks!
 */
?>

<article class="event-teaser container-fluid">
  <div class="row">
    <div class="col-sm-4">
      <div class="image">
        <?php if (!empty($thumbnail)): ?>
          <?php print culturefeed_search_detail_l('event', $cdbid, $title, '<img src="' . $thumbnail . '?width=145&height=145&crop=auto" />', array(
            'attributes' => array('id' => 'cf-image_' . $cdbid),
            'html' => TRUE,
          )); ?>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-sm-8">
      <h3 class="title <?php if (!empty($forkids)): ?> forkids <?php endif; ?>">
        <?php print culturefeed_search_detail_l('event', $cdbid, $title, $title, array(
          'attributes' => array('id' => 'cf-title_' . $cdbid),
          'html' => TRUE,
        )); ?>
      </h3>

      <?php if (!empty($types)): ?>
        <div class="types"><?php print implode(' / ', $types); ?></div>
      <?php endif; ?>

      <?php if (!empty($when_md)): ?>
        <div class="date"><?php print $when_md; ?></div>
      <?php endif; ?>

      <?php if ($location): ?>
        <div class="location">
          <?php if (!empty($location['city']) && !empty($location['title'])) : ?>
            <?php print $location['city'] . ', ' . $location['title']; ?>
          <?php elseif (!empty($location['city'])) : ?>
            <?php print $location['city']; ?>
          <?php elseif (!empty($location['title'])) : ?>
            <?php print $location['title']; ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <?php if ($organiser): ?>
        <div class="organiser">
          <?php if (!empty($organiser)) : ?>
            <?php print $organiser['title']; ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <?php print culturefeed_search_detail_l('event', $cdbid, $title, t('More info'), array(
        'attributes' => array(
          'class' => 'button',
          'id' => 'cf-readmore_' . $cdbid,
        ),
      )); ?>
      <?php if (!empty($tickets)): ?>
        <div class="buy-online"><?php print implode(', ', $tickets) ?></div>
      <?php endif; ?>
    </div>
  </div>
</article>
