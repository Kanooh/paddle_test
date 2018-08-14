<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\SearchPage\GoogleCustomSearchPage.
 */

namespace Kanooh\Paddle\Pages\SearchPage;

use Kanooh\Paddle\Pages\Element\Search\GoogleCustomSearchLabelLinks;
use Kanooh\Paddle\Pages\Element\Search\GoogleCustomSearchPagerLinks;
use Kanooh\Paddle\Pages\FrontEndPaddlePage;

/**
 * The class representing the paddle search page.
 *
 * @property GoogleCustomSearchPagerLinks $pager
 *   The pager on the google custom search page.
 */
class GoogleCustomSearchPage extends FrontEndPaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'google-custom-search/%';

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $xpath = '//body[contains(concat(" ", normalize-space(@class), " "), " page-google-custom-search ")]';
        $this->webdriver->waitUntilElementIsDisplayed($xpath);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'pager':
                return new GoogleCustomSearchPagerLinks($this->webdriver);
                break;
            case 'labels':
                return new GoogleCustomSearchLabelLinks($this->webdriver);
                break;
        }

        return parent::__get($name);
    }

    /**
     * Checks if there are 10 google results actually present.
     *
     * @return bool
     *   Returns TRUE if there are search results present, FALSE otherwise.
     */
    public function checkSearchResultsPresent()
    {
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value('//ul[@class="search-results"]//div[@class="google-custom-search-result"]'));
        return (bool) count($elements) == 10;
    }
}
