<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\SearchPage\MapsSearchResults.
 */

namespace Kanooh\Paddle\Pages\SearchPage;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * A set of search results.
 */
class MapsSearchResults
{
    /**
     * The Selenium webdriver.
     */
    protected $webdriver;

    /**
     * Constructs a MapsSearchResults object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        $this->webdriver = $webdriver;
    }

    /**
     * Returns the number of search results that are shown on the page.
     *
     * Note that this does not necessarily equal the total number of results in
     * the result set.
     *
     * @return int
     *   The number of search results that are shown.
     */
    public function count()
    {
        return count($this->getResults());
    }

    /**
     * Returns the search results that are shown on the page.
     *
     * Note that this is not necessarily the entire result set. There might be
     * more results on subsequent pages.
     *
     * @return MapsSearchResult[]
     *   An array of search results.
     */
    public function getResults()
    {
        $results = array();

        $elements = $this->webdriver->elements($this->webdriver->using('css selector')->value('div.pane-block-maps-search-results .view-paddle-maps.view-display-id-block_maps_text .view-content .views-row'));
        foreach ($elements as $element) {
            $results[] = new MapsSearchResult($element);
        }

        return $results;
    }

    /**
     * Returns the research results keyed by title.
     *
     * @return MapsSearchResult[]
     *   An array of search results keyed by title.
     *
     * @throws \Exception
     *   Exception is thrown if any non-unique title is found.
     */
    public function getResultsKeyedByTitle()
    {
        $results = array();

        foreach ($this->getResults() as $result) {
            $title = $result->title;
            if (isset($results[$title])) {
                throw new \Exception("Non-unique title $title found.");
            }
            $results[$title] = $result;
        }

        return $results;
    }
}
