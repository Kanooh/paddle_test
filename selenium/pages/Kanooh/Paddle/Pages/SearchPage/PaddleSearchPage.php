<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\SearchPage\PaddleSearchPage.
 */

namespace Kanooh\Paddle\Pages\SearchPage;

use Kanooh\Paddle\Pages\Element\Search\TermFacet;
use Kanooh\Paddle\Pages\FrontEndPaddlePage;

/**
 * The class representing the paddle search page.
 *
 * @property SearchResults $searchResults
 *   The search results that are shown on the page.
 * @property PaddleSearchPageForm $form
 *   The search form in the page body.
 * @property TermFacet[] $facets
 *   The facets present in this page.
 */
class PaddleSearchPage extends FrontEndPaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'search/%';

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $xpath = '//body[contains(concat(" ", normalize-space(@class), " "), " page-search ")]';
        $this->webdriver->waitUntilElementIsDisplayed($xpath);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'searchResults':
                return new SearchResults($this->webdriver);
            case 'form':
                return new PaddleSearchPageForm(
                    $this->webdriver,
                    $this->webdriver->byId('search-api-page-search-form')
                );
            case 'facets':
                return $this->getFacets();
        }

        return parent::__get($property);
    }

    /**
     * Returns all the term facets present in the page, keyed by tid.
     *
     * @return TermFacet[]
     */
    protected function getFacets()
    {
        $criteria = $this->webdriver->using('css selector')->value('div.pane-facetapi');
        $elements = $this->webdriver->elements($criteria);

        $facets = array();
        foreach ($elements as $element) {
            $facet = new TermFacet($this->webdriver, $element);
            $facets[$facet->getTermId()] = $facet;
        }

        return $facets;
    }
}
