<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\PaneSection.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Pane Section.
 *
 * @property string $linkUrl
 *   The URL of the link on the section.
 * @property string $text
 *   The text in the section.
 */
class PaneSection extends Element
{
    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

      /**
     * Constructs a PaneSection object.
     *
     * @param WebDriverTestCase $webdriver
     *   The web driver.
     * @param string $xpath_selector
     *   The XPath selector for this pane section.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath_selector)
    {
        parent::__construct($webdriver);

        $this->xpathSelector = $xpath_selector;

        $this->element = $this->getWebdriverElement();
    }

    /**
     * Magically provides all known elements of the pane section as properties.
     *
     * @param string $property
     *   The machine name of the property we are looking for.
     *
     * @return mixed
     *   The matching element object.
     * @throws \Exception
     */
    public function __get($property)
    {
        switch ($property) {
            case 'linkUrl':
                return $this->element->byXPath('.//a');
            case 'text':
                return $this->element->text();
        }

        throw new \Exception("The property $property is undefined.");
    }
}
