<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\IncomingRSS\RSSFeedItem.
 */

namespace Kanooh\Paddle\Pages\Element\IncomingRSS;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * A RSS feed item displayed in the frontend pane.
 *
 * @property string $title
 *   The title of the feed item.
 * @property string $link
 *   The link of the feed item.
 * @property string $description
 *   The description of the feed item.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element|bool $thumbnail
 *   The thumbnail of the feed item.
 * @property string $created
 *   The creation date of the feed item.
 */
class RSSFeedItem
{
    /**
     * The Selenium web driver element representing the news item.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Constructs a RSSFeedItem object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium web driver element representing the RSS feed item.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * Magic getter for the feed item's properties.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'title':
                $element = $this->element->byXPath('.//h2/a');
                return $element->text();
                break;
            case 'link':
                $element = $this->element->byXPath('.//h2/a');
                return $element->attribute('href');
                break;
            case 'description':
                $criteria = $this->element->using('xpath')
                    ->value('.//div[contains(@class, "field-name-field-feed-item-description")]//div[contains(@class, "field-item")]');
                $elements = $this->element->elements($criteria);
                return count($elements) ? $elements[0]->text() : false;
                break;
            case 'thumbnail':
                $criteria = $this->element->using('xpath')
                  ->value('.//div[contains(@class, "entity-property-thumbnail")]/img');
                $elements = $this->element->elements($criteria);
                return count($elements) ? $elements[0] : false;
                break;
            case 'created':
                $criteria = $this->element->using('xpath')
                    ->value('.//div[contains(@class, "entity-property-created")]');
                $elements = $this->element->elements($criteria);
                return count($elements) ? $elements[0]->text() : false;
                break;
        }
        throw new \RuntimeException("The property with the name $name is not defined.");
    }
}
