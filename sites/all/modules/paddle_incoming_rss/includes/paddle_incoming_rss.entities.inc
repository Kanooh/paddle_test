<?php
/**
 * @file
 * Contains entity classes for Paddle Incoming RSS.
 */

/**
 * Class for the Incoming RSS feed entity.
 */
class PaddleIncomingRSSFeed extends Entity {
  /**
   * The feed id.
   *
   * @var int
   */
  public $feed_id;

  /**
   * The feed title.
   *
   * @var string
   */
  public $title;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $values = array()) {
    parent::__construct($values, 'paddle_incoming_rss_feed');
  }

}

/**
 * Class for the Incoming RSS feed item entity.
 */
class PaddleIncomingRSSFeedItem extends Entity {
  /**
   * The feed item id.
   *
   * @var int
   */
  public $feed_item_id;

  /**
   * The feed item title.
   *
   * @var string
   */
  public $title;

  /**
   * The feed item thumbnail.
   *
   * @var string
   */
  public $thumbnail;

  /**
   * The feed item created timestamp.
   *
   * @var int
   */
  public $created;

  /**
   * The feed item changed timestamp.
   *
   * @var int
   */
  public $changed;

  /**
   * The feed item source.
   *
   * @var string
   */
  public $source;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $values = array()) {
    parent::__construct($values, 'paddle_incoming_rss_feed_item');
  }

  /**
   * Returns the uri of the entity.
   */
  public function uri() {
    // Load the info from the feeds_item mapping table.
    $info = feeds_item_info_load($this->entityType(), $this->internalIdentifier());

    // If no url is present, return NULL.
    if (empty($info->url)) {
      return NULL;
    }

    return array(
      'path' => $info->url,
    );
  }

}
