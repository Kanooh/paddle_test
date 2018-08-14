<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\IncomingRSS\RSSFeedTable.
 */

namespace Kanooh\Paddle\Pages\Element\IncomingRSS;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;

/**
 * Table containing all RSS feeds.
 */
class RSSFeedTable extends Table
{
    /**
     * The webdriver element of the RSS feed table.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new RSSFeedTable.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param string $xpath
     *   The xpath selector of the RSS feed table.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath;
        $this->element = $this->webdriver->byXPath($xpath);
    }

    /**
     * Returns a row based on the feed id given.
     *
     * @param string $feed_id
     *   Incoming RSS feed ID of the row to return.
     *
     * @return RSSFeedTableRow
     *   The row for the given feed id, or false if not found.
     */
    public function getRowByFeedId($feed_id)
    {
        $criteria = $this->element->using('xpath')->value('.//tbody//tr[@data-feed-id="' . $feed_id . '"]');
        $rows = $this->element->elements($criteria);
        if (empty($rows)) {
            return false;
        }
        return new RSSFeedTableRow($this->webdriver, $rows[0]);
    }

    /**
     * Returns a row based on the title given.
     *
     * @param string $title
     *   The title of the RSS feed.
     *
     * @return RSSFeedTableRow
     *   The row for the given title, or false if not found.
     */
    public function getRowByTitle($title)
    {
        $row_xpath = '//tr/td[contains(@class, "feed-title") and normalize-space(text())="' . $title . '"]/..';
        $criteria = $this->webdriver->using('xpath')->value($this->xpathSelector . $row_xpath);
        $elements = $this->webdriver->elements($criteria);
        if (count($elements) > 0) {
            return new RSSFeedTableRow($this->webdriver, $elements[0]);
        }

        return false;
    }
}
