<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\IncomingRSS\RSSFeedTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\IncomingRSS;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class RSSFeedTableRow
 *
 * @property string $title
 *   The title of the feed.
 * @property int $feedId
 *   The Incoming RSS feed ID.
 */
class RSSFeedTableRow extends Row
{
    /**
     * The webdriver element of the Incoming RSS feed table row.
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
     * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDelete
     *   The feed's delete link.
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
            case 'feedId':
                return $this->element->attribute('data-feed-id');
                break;
            case 'linkDelete':
                return $this->element->byXPath('.//td[contains(@class, "feed-delete")]//a');
                break;
        }
        throw new \RuntimeException("The property with the name $name is not defined.");
    }
}
