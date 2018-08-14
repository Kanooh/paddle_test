<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\Image\ManualCropOverlay.
 */

namespace Kanooh\Paddle\Pages\Element\Scald\Image;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class ManualCropOverlay
 * @package Kanooh\Paddle\Pages\Element\Scald\Image
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $saveButton
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $cancelButton
 */
class ManualCropOverlay
{
    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * The Selenium webdriver element representing the form.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a new ManualCropOverlay object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium webdriver element representing the overlay.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * Magically provides all known elements as properties.
     *
     * @param string $name
     *   A element machine name.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The desired element.
     *
     * @throws \Exception
     */
    public function __get($name)
    {
        switch ($name) {
            case 'saveButton':
                return $this->element->byXPath('.//a[contains(@class, "manualcrop-close")]');
            case 'cancelButton':
                return $this->element->byXPath('.//a[contains(@class, "manualcrop-cancel")]');
        }

        throw new \Exception("The property $name is undefined.");
    }

    /**
     * Makes the browser wait until the overlay is fully loaded.
     *
     * This is determined by the fact that the save button is displayed.
     */
    public function waitUntilLoaded()
    {
        $xpath = './/a[contains(@class, "manualcrop-close")]';
        $this->webdriver->waitUntilElementIsPresent($xpath);
    }
}
