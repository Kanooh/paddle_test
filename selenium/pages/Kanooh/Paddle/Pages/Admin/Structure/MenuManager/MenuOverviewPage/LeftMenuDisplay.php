<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\LeftMenuDisplay.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage;

use Kanooh\Paddle\Pages\Element\MenuDisplay\MenuDisplay;

/**
 * Class representing the left menu display on the Menu Manager overview page.
 */
class LeftMenuDisplay extends MenuDisplay
{
    /**
     * @{@inheritdoc}
     */
    protected $xpathSelector = '//div[@id="block-paddle-menu-display-management-level-4"]//ul[contains(@class, "level-4")]';
}
