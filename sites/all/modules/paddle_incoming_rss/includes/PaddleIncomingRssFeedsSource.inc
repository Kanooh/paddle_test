<?php

/**
 * @file
 * Contains FeedsSource implementation for Incoming RSS paddlet.
 */

/**
 * Class extending FeedsSource class to allow entity ids to be used as feed_nid.
 */
class PaddleIncomingRssFeedsSource extends FeedsSource {

  /**
   * Returns if the configuration is valid.
   *
   * Overrides FeedsSource::existing(), removing checks against feed_nid
   * and content type.
   */
  public function existing() {
    $this->importer->existing();
    if ($this->export_type == FEEDS_EXPORT_NONE) {
      throw new FeedsNotExistingException(t('Object is not persistent.'));
    }
    if ($this->disabled) {
      throw new FeedsNotExistingException(t('Object is disabled.'));
    }
    return $this;
  }

}
