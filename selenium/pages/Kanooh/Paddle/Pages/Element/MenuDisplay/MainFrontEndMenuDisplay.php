<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\MenuDisplay\MainFrontEndMenuDisplay.
 */

namespace Kanooh\Paddle\Pages\Element\MenuDisplay;

/**
 * Class representing a menu display.
 */
class MainFrontEndMenuDisplay extends MenuDisplay
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector =
              '//div[@id="block-paddle-menu-display-first-level"]//div[@id="menu-display-first-level"]';

    /**
     * Checks if the child is shown in the parent menu link. A child can be
     * shown in the main menu only if the Fly-out menu is used.
     *
     * @param int $parent
     *   The mlid of the parent.
     * @param int $child
     *   The mlid of the child.
     *
     * @return bool
     *   Returns true if the child is found within the parent, false otherwise.
     */
    public function checkNextLevelPresentInMenuDisplayBlock($parent, $child)
    {
        $xpath = $this->xpathSelector . '//ul//li/a[@class="mlid-' . $parent . '"]/..//div//ul[@class="paddle-sub-nav"]//li/a[contains(concat(\' \', @class, \' \')," mlid-' . $child . ' ")]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return count($elements) > 0;
    }
}
