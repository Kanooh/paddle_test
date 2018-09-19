<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\NewsletterLayoutPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage;

use Kanooh\Paddle\Pages\Element\Display\NewsletterDisplay;
use Kanooh\Paddle\Pages\Element\NodeMetadataSummary\NodeMetadataSummary;

/**
 * The Panels display editor for newsletter content.
 *
 * @property \Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property \Kanooh\Paddle\Pages\Element\Display\NewsletterDisplay $display
 *   The Panels newsletter display.
 * @property NodeMetadataSummary $nodeSummary
 *   The node summary (metadata).
 */
class NewsletterLayoutPage extends LayoutPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'display':
                return new NewsletterDisplay($this->webdriver);
        }
        return parent::__get($property);
    }
}
