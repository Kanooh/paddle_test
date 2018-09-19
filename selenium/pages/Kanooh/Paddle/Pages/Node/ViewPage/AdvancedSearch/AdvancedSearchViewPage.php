<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\AdvancedSearch\AdvancedSearchViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\AdvancedSearch;

use Kanooh\Paddle\Pages\Element\AdvancedSearch\VocabularyTermFilterFacet;
use Kanooh\Paddle\Pages\Element\Pane\AdvancedSearch\SearchFormPane;
use Kanooh\Paddle\Pages\Element\Pane\AdvancedSearch\SearchResultsPane;
use Kanooh\Paddle\Pages\Element\Pane\AdvancedSearch\SearchSortingBox;
use Kanooh\Paddle\Pages\Element\Search\AdvancedSearchPagerLinks;
use Kanooh\Paddle\Pages\Element\Search\Facet;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;

/**
 * Class representing an Advanced Search Page in the frontend view.
 *
 * @property SearchResultsPane $searchResultsPane
 * @property SearchFormPane $searchFormPane
 * @property VocabularyTermFilterFacet[] $facets
 * @property SearchSortingBox $sorting
 * @property Facet $authorsFilterFacet
 * @property Facet $keywordsFilterFacet
 * @property Facet $publicationYearFilterFacet
 * @property Facet $actionStrategiesFilterFacet
 * @property Facet $policyThemesFilterFacet
 * @property Facet $settingsFilterFacet
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $searchResultCount
 * @property AdvancedSearchPagerLinks $pagerTop
 * @property AdvancedSearchPagerLinks $pagerBottom
 */
class AdvancedSearchViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'searchResultsPane':
                $element = $this->webdriver->byCssSelector('.panel-pane.pane-advanced-search-results');
                return new SearchResultsPane($this->webdriver, $element->attribute('data-pane-uuid'));
            case 'searchFormPane':
                $element = $this->webdriver->byCssSelector('.pane-advanced-search-form');
                return new SearchFormPane($this->webdriver, $element->attribute('data-pane-uuid'));
            case 'facets':
                return $this->getFacets();
            case 'authorsFilterFacet':
                $element = $this->webdriver->byCssSelector('.pane-authors-filter');
                return new Facet($this->webdriver, $element);
            case 'keywordsFilterFacet':
                $element = $this->webdriver->byCssSelector('.pane-keywords-filter');
                return new Facet($this->webdriver, $element);
            case 'publicationYearFilterFacet':
                $element = $this->webdriver->byCssSelector('.pane-publication-year-filter');
                return new Facet($this->webdriver, $element);
            case 'actionStrategiesFilterFacet':
                $element = $this->webdriver->byCssSelector('.pane-action-strategies-filter');
                return new Facet($this->webdriver, $element);
            case 'policyThemesFilterFacet':
                $element = $this->webdriver->byCssSelector('.pane-policy-themes-filter');
                return new Facet($this->webdriver, $element);
            case 'settingsFilterFacet':
                $element = $this->webdriver->byCssSelector('.pane-settings-filter');
                return new Facet($this->webdriver, $element);
            case 'sorting':
                return new SearchSortingBox($this->webdriver);
            case 'searchResultCount':
                return $this->webdriver->byCssSelector('.search-result-count');
            case 'pagerTop':
                $element = $this->webdriver->byCssSelector('.pager-top ul.pager');
                return new AdvancedSearchPagerLinks($this->webdriver, $element);
            case 'pagerBottom':
                $element = $this->webdriver->byCssSelector('.pager-bottom ul.pager');
                return new AdvancedSearchPagerLinks($this->webdriver, $element);
        }

        return parent::__get($property);
    }

    /**
     * Returns all the term facets present in the page, keyed by tid.
     *
     * @return VocabularyTermFilterFacet[]
     */
    protected function getFacets()
    {
        $criteria = $this->webdriver->using('css selector')->value('div.pane-vocabulary-term-filter');
        $elements = $this->webdriver->elements($criteria);

        $facets = array();
        foreach ($elements as $element) {
            $facet = new VocabularyTermFilterFacet($this->webdriver, $element);
            $facets[$facet->getTermId()] = $facet;
        }

        return $facets;
    }

    /**
     * Returns whether the requested pager is shown.
     *
     * @param $location
     *   Either 'Top' or 'Bottom' location.
     *
     * @return bool
     *      Whether the pager is shown or not.
     */
    public function isPagerShown($location)
    {
        try {
            $pager_string = 'pager' . $location;
            $pager = $this->$pager_string;
            return true;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            return false;
        }
    }
}
