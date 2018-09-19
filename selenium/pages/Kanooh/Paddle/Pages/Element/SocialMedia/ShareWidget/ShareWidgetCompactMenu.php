<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\SocialMedia\ShareWidget\ShareWidgetCompactMenu.
 */

namespace Kanooh\Paddle\Pages\Element\SocialMedia\ShareWidget;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents the compact menu for the share widget.
 */
class ShareWidgetCompactMenu
{
    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * The Selenium webdriver element representing the compact menu of the share
     * widget.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * Finds and returns the network links in the compact menu.
     *
     * @return array
     *   Array of \PHPUnit_Extensions_Selenium2TestCase_Element one for each link.
     */
    public function getNetworks()
    {
        $networks = array();
        $elements = $this->element->elements($this->element->using('xpath')->value('./a'));
        foreach ($elements as $element) {
            $id = str_replace('atic_', '', $element->attribute('id'));
            if ($id != 'settings') {
                $networks[$id] = $element;
            }
        }

        return $networks;
    }
}
