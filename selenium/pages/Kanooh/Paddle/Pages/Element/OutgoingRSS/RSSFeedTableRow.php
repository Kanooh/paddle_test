<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\OutgoingRSS\RSSFeedTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\OutgoingRSS;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class RSSFeedTableRow
 *
 * @property string $title
 *   Title of the feed.
 * @property string $contentTypes
 *   The content types for the feed.
 * @property string $path
 *   The path on which the feed is displayed.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPreview
 *   The feed's preview link.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkEdit
 *   The feed's edit link.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDelete
 *   The feed's delete link.
 * @property int $fid
 *   The Outgoing RSS feed ID.
 */
class RSSFeedTableRow extends Row
{
    /**
     * The webdriver element of the Outgoing RSS feed table row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new RSSFeedTableRow.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The webdriver element of the feed table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }

    /**
     * Magic getter for the feed's properties.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'title':
                $cell = $this->element->byXPath('.//td[contains(@class, "feed-title")]');
                return $cell->text();
                break;
            case 'contentTypes':
                $cell = $this->element->byXPath('.//td[contains(@class, "feed-content-types")]');
                return $cell->text();
                break;
            case 'path':
                $cell = $this->element->byXPath('.//td[contains(@class, "feed-path")]');
                return $cell->text();
                break;
            case 'linkPreview':
                return $this->element->byXPath('.//td[contains(@class, "feed-preview")]//a');
                break;
            case 'linkEdit':
                return $this->element->byXPath('.//td[contains(@class, "feed-edit")]//a');
                break;
            case 'linkDelete':
                return $this->element->byXPath('.//td[contains(@class, "feed-delete")]//a');
                break;
            case 'fid':
                return $this->element->attribute('data-feed-id');
                break;
        }
    }
}
