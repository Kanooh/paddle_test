<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\SearchPage\SearchResult.
 */

namespace Kanooh\Paddle\Pages\SearchPage;

/**
 * A search result.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $featuredImage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $readMoreLink
 *   A Selenium element representing the read more link.
 * @property string $snippet
 *   The preview text that is shown with the search result.
 * @property string $title
 *   The title of the page represented by the search result.
 * @property string $url
 *   The URL of the page represented by the search result.
 */
class SearchResult
{
    /**
     * The Selenium element on which this search result is based.
     */
    protected $element;

    /**
     * Constructs a SearchResult object.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium element on which this search result is based.
     */
    public function __construct(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->element = $element;
    }

    public function __get($property)
    {
        switch ($property) {
            case 'featuredImage':
                return $this->element->byXPath('.//div[@class="featured-image"]/img');
            case 'readMoreLink':
                return $this->element->byXPath('.//div[contains(@class, "search-read-more")]/a');
            case 'snippet':
                return $this->element->byXPath('.//p[contains(@class, "search-snippet")]')->text();
            case 'title':
                return $this->element->byXPath('.//h3/a')->text();
            case 'url':
                return $this->element->byXPath('.//h3/a')->attribute('href');
        }

        throw new \Exception("The property $property is undefined.");
    }
}
