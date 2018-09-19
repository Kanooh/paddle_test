<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\OutgoingRssListPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OutgoingRssPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Panels pane with Ctools content type 'Outgoing RSS feeds list'.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element[] $feedsList
 *   Array with links to the RSS feeds pages.
 */
class OutgoingRssListPane extends Pane
{

    /**
     * The object for the pane content type.
     *
     * @var OutgoingRssPanelsContentType
     */
    public $contentType;

    /**
     * Constructs an OutgoingRssListPane pane.
     *
     * @param WebDriverTestCase $webdriver
     *   The webdriver object.
     * @param string $uuid
     *   The uuid of the pane.
     * @param string $pane_xpath_selector
     *   More general xpath selector for the pane.
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid, $pane_xpath_selector)
    {
        parent::__construct($webdriver, $uuid, $pane_xpath_selector);

        $this->contentType = new OutgoingRssPanelsContentType($this->webdriver);
    }

    /**
     * Magically provides all known elements of the pane.
     *
     * @param string $name
     *   An element machine name.
     *
     * @return mixed
     *   The requested pane element.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'feedsList':
                $xpath = $this->xpathSelector . '//ul/li/a[contains(@class, "outgoing-rss-feed")]';
                $criteria = $this->webdriver->using('xpath')->value($xpath);
                $elements = $this->webdriver->elements($criteria);
                $links = array();

                /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
                foreach ($elements as $element) {
                    // Key the array by entity id so we can easily target them.
                    $links[$element->attribute('data-feed-id')] = $element;
                }
                return $links;
                break;
        }

        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_NOTICE);
    }
}
