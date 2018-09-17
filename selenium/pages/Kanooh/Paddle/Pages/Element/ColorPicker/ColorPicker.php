<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\ColorPicker\ColorPicker.
 */

namespace Kanooh\Paddle\Pages\Element\ColorPicker;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing the color picker modal.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $rgbRColor
 *   The text input field to enter red as RGB color value.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $rgbGColor
 *   The text input field to enter green as RGB color value.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $rgbBColor
 *   The text input field to enter blue as RGB color value.
 */
class ColorPicker
{
    /**
     * The webdriver element representing the color picker modal.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * The HTML attribute "id" of the element.
     *
     * @var string
     */
    protected $id;

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
        $this->id = $element->attribute('id');
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
            case 'rgbRColor':
                $xpath = './div[contains(@class, "colorpicker_rgb_r")]/input[@type = "text"]';
                return $this->element->byXPath($xpath);
            case 'rgbGColor':
                $xpath = './div[contains(@class, "colorpicker_rgb_g")]/input[@type = "text"]';
                return $this->element->byXPath($xpath);
            case 'rgbBColor':
                $xpath = './div[contains(@class, "colorpicker_rgb_b")]/input[@type = "text"]';
                return $this->element->byXPath($xpath);
        }

        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_NOTICE);
    }

    /**
     * Wait until the Color Picker modal has been opened.
     */
    public function waitUntilOpened()
    {
        $webdriver = $this->webdriver;
        $id = $this->id;
        $callable = new SerializableClosure(
            function () use ($webdriver, $id) {
                $xpath = '//div[@id = "' . $id . '"]';
                $elements = $webdriver->elements($webdriver->using('xpath')->value($xpath));
                return (bool) count($elements);
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Wait until the Color Picker modal has been closed.
     */
    public function waitUntilClosed()
    {
        $webdriver = $this->webdriver;
        $id = $this->id;
        $callable = new SerializableClosure(
            function () use ($webdriver, $id) {
                $xpath = '//div[@id = "' . $id . '"]';
                $elements = $webdriver->elements($webdriver->using('xpath')->value($xpath));
                return !(bool) count($elements);
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
