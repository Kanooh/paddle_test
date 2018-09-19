<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\Maps\MapsViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\Maps;

use Kanooh\Paddle\Pages\Element\Maps\MapsVocabularyTermFilterFacet;
use Kanooh\Paddle\Pages\Element\Pane\Maps\SearchFormPane;
use Kanooh\Paddle\Pages\Element\Pane\Maps\SearchResultsPane;
use Kanooh\Paddle\Pages\Element\Pane\Maps\SearchSortingBox;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;

/**
 * Class representing an Maps Page in the frontend view.
 *
 * @property SearchResultsPane $searchResultsPane
 * @property SearchFormPane $searchFormPane
 * @property MapsVocabularyTermFilterFacet[] $facets
 * @property SearchSortingBox $sorting
 */
class MapsViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'searchResultsPane':
                $element = $this->webdriver->byCssSelector('.panel-pane.pane-block-maps-search-results');
                return new SearchResultsPane($this->webdriver, $element->attribute('data-pane-uuid'));
            case 'searchFormPane':
                $element = $this->webdriver->byCssSelector('.pane-maps-search-form');
                return new SearchFormPane($this->webdriver, $element->attribute('data-pane-uuid'));
            case 'facets':
                return $this->getFacets();
        }

        return parent::__get($property);
    }

    /**
     * Returns all the term facets present in the page, keyed by tid.
     *
     * @return MapsVocabularyTermFilterFacet[]
     */
    protected function getFacets()
    {
        $criteria = $this->webdriver->using('css selector')->value('div.pane-map-vocabulary-term-filter');
        $elements = $this->webdriver->elements($criteria);

        $facets = array();
        foreach ($elements as $element) {
            $facet = new MapsVocabularyTermFilterFacet($this->webdriver, $element);
            $facets[$facet->getTermId()] = $facet;
        }

        return $facets;
    }
}
