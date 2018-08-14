<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\MenuDisplay\MenuDisplay.
 */

namespace Kanooh\Paddle\Pages\Element\MenuDisplay;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing a menu display.
 */
class MenuDisplay extends Element
{
    /**
     * Returns a menu link object based on its title.
     *
     * @param string $menu_item_title
     *   The link title of the menu item.
     *
     * @return null|\PHPUnit_Extensions_Selenium2TestCase_Element
     *   The matching link object.
     */
    public function getMenuItemLinkByTitle($menu_item_title)
    {
        // Check if the menu item title is visible immediately. If not we assume
        // there is a menu slider and the menu item is not visible so we slide
        // the menu until we get it.
        while (!$this->webdriver->isTextPresent($menu_item_title)) {
            // Try to find the menu slider button.
            $xpath = '//button[@id="menuslider-next"]';
            $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
            if (!empty($elements[0])) {
                // Slide the menu.
                $elements[0]->click();

                // Check if we reached the right end of the slider. If we did
                // and the menu title is still not visible we are never going to
                // find it.
                $xpath = '//div[contains(@class, "menuslider-controls")]';
                $slider_container = $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
                $classes = $slider_container->attribute('class');
                if (!$this->webdriver->isTextPresent($menu_item_title) && strpos($classes, 'slider-right-end')) {
                    echo "EXIT\n";
                    return null;
                }
            } else {
                break;
            }
        }

        // The menu item is (now) visible - find it.
        $xpath = $this->xpathSelector . '//li[contains(@class,"menu-item")]/a';
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $menu_links */
        $menu_links = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        foreach ($menu_links as $menu_link) {
            if ($menu_link->text() == $menu_item_title) {
                return $menu_link;
            }
        }
        return null;
    }
}
