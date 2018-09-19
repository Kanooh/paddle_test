<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\NewsItem\NewsItem.
 */

namespace Kanooh\Paddle\Pages\Element\NewsItem;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * A news item as shown in the news overview.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $readMoreLink
 *   The read more link as a Selenium element.
 */
class NewsItem
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
     * Constructs a NewsItem object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium web driver element representing the news item.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * Returns the node ID of the news item.
     *
     * @return int
     *   The node ID.
     *
     * @throws \Exception
     *   Thrown if the data property is missing on the news item element.
     */
    public function getNodeId()
    {
        // Get the node ID from the 'data-news-item-nid' data property.
        if (!$nid = (int) $this->element->attribute('data-news-item-nid')) {
            throw new \Exception('The node ID could not be derived from the news item.');
        }

        return $nid;
    }

    /**
     * Returns the date of the news article exactly as shown on the page.
     *
     * @return string
     *   The date the article was written, as shown on the page.
     */
    public function getDateString()
    {
        return trim($this->element->byXPath('.//li[contains(@class, "news-item-date")]/span')->text());
    }

    /**
     * Returns the article date as a UNIX timestamp.
     *
     * @return int
     *   A timestamp on success, false otherwise.
     */
    public function getDate()
    {
        return strtotime($this->getDateString());
    }

    /**
     * Returns whether or not the news item has an image.
     *
     * @return bool
     *   Whether or not the news item has an image.
     */
    public function hasImage()
    {
        $image_elements = $this->element->elements($this->webdriver->using('xpath')->value('.//div[contains(@class, "news-overview-item-image")]'));
        return (bool) count($image_elements);
    }

    /**
     * Returns the URL of the image that belongs to the article.
     *
     * @return string
     *   The image URL.
     */
    public function getImageUrl()
    {
        return $this->element->byXPath('.//div[contains(@class, "news-overview-item-image")]//img')->attribute('src');
    }

    /**
     * Returns the filename of the image that belongs to the article.
     *
     * @return string
     *   The image filename.
     */
    public function getImageFileName()
    {
        return basename(parse_url($this->getImageUrl(), PHP_URL_PATH));
    }

    /**
     * Returns the title of the news item.
     *
     * @return string
     *   The title.
     */
    public function getTitle()
    {
        return $this->element->byXPath('.//h3[contains(@class, "news-overview-item-title")]//a')->text();
    }

    /**
     * Returns the news article body text.
     *
     * @return string
     *   The body text.
     */
    public function getBody()
    {
        return $this->element->byXPath('.//div[contains(@class, "news-overview-item-body")]//div[contains(@class, "field-content")]')->text();
    }

    public function __get($property)
    {
        switch ($property) {
            case 'readMoreLink':
                return $this->element->byXPath('.//div[contains(@class, "pane-section-bottom")]/a');
        }

        throw new \Exception("The property $property is undefined.");
    }
}
