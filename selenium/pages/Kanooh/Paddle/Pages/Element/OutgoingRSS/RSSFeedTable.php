<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\OutgoingRSS\RSSFeedTable.
 */

namespace Kanooh\Paddle\Pages\Element\OutgoingRSS;

use Jeremeamia\SuperClosure\SerializableClosure;
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
     * Returns a row based on the fid given.
     *
     * @param string $fid
     *   Outgoing RSS feed ID of the row to return.
     *
     * @return RSSFeedTableRow
     *   The row for the given fid, or false if not found.
     */
    public function getRowByFid($fid)
    {
        $criteria = $this->element->using('xpath')->value('.//tbody//tr[@data-feed-id="' . $fid . '"]');
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
     * @return RSSFeedTableRow | null
     *   The row for the given title, or null if not found.
     */
    public function getRowByTitle($title)
    {
        $row_xpath = '//tr/td[contains(@class, "feed-title") and normalize-space(text())="' . $title . '"]/..';
        $criteria = $this->webdriver->using('xpath')->value($this->xpathSelector . $row_xpath);
        $elements = $this->webdriver->elements($criteria);
        if (count($elements) > 0) {
            return new RSSFeedTableRow($this->webdriver, $elements[0]);
        }

        return null;
    }

    /**
     * Wait until a row in the table with the passed title appears.
     *
     * @param  string $feed_title
     *   The title of the feed.
     */
    public function waitUntilTableUpdated($feed_title)
    {
        $table = $this;
        $callable = new SerializableClosure(
            function () use ($table, $feed_title) {
                if ($table->getRowByTitle($feed_title)) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
