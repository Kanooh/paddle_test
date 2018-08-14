<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\IncomingRSS\RSSFeedModal.
 */

namespace Kanooh\Paddle\Pages\Element\IncomingRSS;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class representing the add/edit modal for the Incoming RSS feed entities.
 *
 * @property RSSFeedForm $form
 */
class RSSFeedModal extends Modal
{
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new RSSFeedForm(
                    $this->webdriver,
                    $this->webdriver->byXPath('//form[contains(@id, "paddle-incoming-rss-feed-form")]')
                );
        }
        throw new \RuntimeException("The property with the name $name is not defined.");
    }
}
