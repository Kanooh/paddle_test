<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Maps\SearchSortingBox.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Maps;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * Class for the Paddle Maps Search "Sorting box".
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $relevance
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $title
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $publicationDate
 */
class SearchSortingBox extends Element
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'relevance':
                return $this->webdriver->byXPath('//ul[@class="search-api-sorts"]//li//a[contains(@href, "sort=search_api_relevance")]');
                break;
            case 'title':
                return $this->webdriver->byXPath('//ul[@class="search-api-sorts"]//li//a[contains(@href, "sort=title")]');
                break;
            case 'publicationDate':
                return $this->webdriver->byXPath('//ul[@class="search-api-sorts"]//li//a[contains(@href, "sort=publication_date")]');
                break;
        }

        throw new \Exception("Property with name $name not defined");
    }
}
