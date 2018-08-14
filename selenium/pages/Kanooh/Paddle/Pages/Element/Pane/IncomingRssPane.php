<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\IncomingRssPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\IncomingRSS\RSSFeedItem;
use Kanooh\Paddle\Pages\Element\PanelsContentType\IncomingRssPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Panels pane with Ctools content type 'Incoming RSS'.
 *
 * @property string $incomingRssTitle
 *   The title of the RSS feed.
 */
class IncomingRssPane extends Pane
{

    /**
     * The object for the pane content type.
     *
     * @var IncomingRssPanelsContentType
     */
    public $contentType;

    /**
     * Constructs an IncomingRssPane pane.
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

        $this->contentType = new IncomingRssPanelsContentType($this->webdriver);
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
            case 'incomingRssTitle':
                $element = $this->webdriver->byXPath($this->xpathSelector . '//h2[contains(@class, "pane-title")]');
                return $element->text();
        }
        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_NOTICE);
    }

    /**
     * Returns all the feed items.
     *
     * @return RSSFeedItem[]
     */
    public function getFeedItems()
    {
        $feed_items = array();

        $xpath = $this->xpathSelector . '//div[contains(@class, "entity-paddle-incoming-rss-feed-item")]';
        $criteria = $this->webdriver->using('xpath')->value($xpath);
        $elements = $this->webdriver->elements($criteria);
        foreach ($elements as $element) {
            $feed_items[] = new RSSFeedItem($this->webdriver, $element);
        }

        return $feed_items;
    }
}
