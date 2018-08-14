<?php

/**
 * @file
 * Contains metadata controllers for the Incoming RSS entities.
 */

/**
 * Class to provide metadata information for the feed item entity.
 */
class PaddleIncomingRSSFeedItemMetadataController extends EntityDefaultMetadataController {
  /**
   * {@inheritdoc}
   */
  public function entityPropertyInfo() {
    $info = parent::entityPropertyInfo();
    $properties = &$info['paddle_incoming_rss_feed_item']['properties'];

    // Feed item title field.
    $properties['title']['label'] = t('Title');
    $properties['title']['description'] = t('The title of the feed item.');
    $properties['title']['setter callback'] = 'entity_property_verbatim_set';

    // Feed item thumbnail field.
    $properties['thumbnail']['label'] = t('Thumbnail');
    $properties['thumbnail']['description'] = t('The thumbnail for the feed item.');
    $properties['thumbnail']['setter callback'] = 'entity_property_verbatim_set';

    // Feed item created field.
    $properties['created']['label'] = t('Created date');
    $properties['created']['description'] = t('The date the feed item was created.');
    $properties['created']['type'] = 'date';
    $properties['created']['setter callback'] = 'entity_property_verbatim_set';

    // Feed item changed field.
    $properties['changed']['label'] = t('Updated date');
    $properties['changed']['description'] = t('The date the feed item was last updated.');
    $properties['changed']['type'] = 'date';
    $properties['changed']['setter callback'] = 'entity_property_verbatim_set';

    // Feed item source field.
    $properties['source']['label'] = t('Source');
    $properties['source']['description'] = t('The Source URL of the feed.');
    $properties['source']['setter callback'] = 'entity_property_verbatim_set';

    return $info;
  }

}
