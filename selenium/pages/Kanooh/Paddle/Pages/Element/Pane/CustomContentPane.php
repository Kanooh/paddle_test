<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\CustomContentPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Panels pane with Ctools content type 'Custom content'.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $top
 *   The top section of the pane.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $body
 *   The body section of the pane.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $bottom
 *   The bottom section of the pane.
 */
class CustomContentPane extends Pane
{

    /**
     * The object for the pane content type.
     *
     * @var CustomContentPanelsContentType
     */
    public $contentType;

    /**
     * Constructs a CustomContentPane.
     *
     * @param WebDriverTestCase $webdriver
     *   The webdriver object.
     * @param string $uuid
     *   The uuid of the pane.
     * @param string $pane_xpath_selector
     *   More general xpath selector for the pane.
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid, $pane_xpath_selector = '')
    {
        parent::__construct($webdriver, $uuid, $pane_xpath_selector);

        $this->contentType = new CustomContentPanelsContentType($this->webdriver);
    }

    /**
     * Checks if the pane has a body.
     *
     * @return bool
     *   True if the body was found, false otherwise.
     */
    public function checkBodyDisplayedInPane()
    {
        $xpath = $this->xpathSelector . '//div[contains(@class, "pane-section-body")]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return count($elements) ? (bool) $elements[0] : false;
    }

    /**
     * Magically provides all known elements of the pane as properties.
     *
     * @param string $name
     *   The machine name of the property we are looking for.
     *
     * @return mixed
     *   The matching element object.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'top':
                $xpath = $this->xpathSelector . '//div[contains(@class, "pane-section-top")]';
                return $this->webdriver->byXPath($xpath);
            case 'body':
                $xpath = $this->xpathSelector . '//div[contains(@class, "pane-section-body")]';
                return $this->webdriver->byXPath($xpath);
            case 'bottom':
                $xpath = $this->xpathSelector . '//div[contains(@class, "pane-section-bottom")]';
                return $this->webdriver->byXPath($xpath);
        }

        parent::__get($name);
    }
}
