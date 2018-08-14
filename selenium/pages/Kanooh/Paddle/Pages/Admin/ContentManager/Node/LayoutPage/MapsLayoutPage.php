<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\MapsLayoutPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage;

use Kanooh\Paddle\Pages\Element\Display\LandingPageDisplay;
use Kanooh\Paddle\Pages\Element\NodeMetadataSummary\NodeMetadataSummary;

/**
 * The Panels display editor for maps content.
 *
 * @property \Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPageContextualToolbar $contextualToolbar
 * @property \Kanooh\Paddle\Pages\Element\Display\LandingPageDisplay $display
 * @property NodeMetadataSummary $nodeSummary
 */
class MapsLayoutPage extends LayoutPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'display':
                return new LandingPageDisplay($this->webdriver);
        }
        return parent::__get($property);
    }
}
