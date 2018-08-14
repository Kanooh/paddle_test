<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\OutgoingRSS\RSSFeedSettingsModal.
 */

namespace Kanooh\Paddle\Pages\Element\OutgoingRSS;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class representing the add/edit modal for the Outgoing RSS feed entities.
 *
 * @property RSSFeedSettingsForm $form
 */
class RSSFeedSettingsModal extends Modal
{
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new RSSFeedSettingsForm($this->webdriver, $this->webdriver->byXPath('//form[contains(@id, "paddle-outgoing-rss-feed-settings-form")]'));
        }
    }
}
