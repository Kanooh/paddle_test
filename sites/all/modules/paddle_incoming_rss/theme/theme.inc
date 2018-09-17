<?php
/**
 * @file
 * Theme and preprocess functions for incoming rss paddlet.
 */

/**
 * Theme function to render a list of feeds.
 */
function theme_paddle_incoming_rss_feed_list($variables) {
  $feeds = $variables['feeds'];

  $rows = array();
  foreach ($feeds as $feed) {
    /* @var PaddleIncomingRSSFeed $feed */
    $rows[] = array(
      'data' => array(
        array(
          'data' => $feed->label(),
          'class' => 'feed-title',
        ),
        array(
          'data' => ctools_modal_text_button(t('Delete'), 'admin/content/paddle_incoming_rss/nojs/delete/' . $feed->identifier(), t('Delete the feed'), 'ui-icon ui-icon-hiddentext ui-icon-delete'),
          'class' => 'feed-delete',
        ),
      ),
      'data-feed-id' => $feed->identifier(),
    );
  }

  $output = theme('table', array(
    'header' => array(t('title'), t('actions')),
    'rows' => $rows,
    'empty' => t('No incoming feeds created.'),
  ));

  return $output;
}
