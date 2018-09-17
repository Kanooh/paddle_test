<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ColorPaletteColorBoxes.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing the boxes showing the colors of a color palette on the
 * Theme Edit Page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element[] $mainPaletteBoxes
 *   The boxes of the main palette.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element[] $subPaletteBoxes
 *   The boxes of the sub-palettes each in it's oun su-array.
 */
class ColorPaletteColorBoxes
{
    /**
     * The webdriver element representing the set of color boxes.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs an ColorPicker object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The element representing the color picker modal.
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * Magically provides all known links as properties.
     *
     * @param string $name
     *   A link machine name of the property we are looking for.
     *
     * @return mixed
     *   The matching element object.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'mainPaletteBoxes':
                $xpath = './/div[contains(@class, "paddle-color-palettes-primary-palette")]/div';
                return $this->element->elements($this->element->using('xpath')->value($xpath));
            case 'subPaletteBoxes':
                $boxes = array();
                $xpath = './/div[contains(@class, "paddle-color-palettes-secondary-palette")]';
                $palettes = $this->element->elements($this->element->using('xpath')->value($xpath));
                /* @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $palettes */
                foreach ($palettes as $palette) {
                    $boxes[] = $palette->elements($palette->using('xpath')->value('./div'));
                }
                return $boxes;
        }

        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_NOTICE);

        return false;
    }
}
