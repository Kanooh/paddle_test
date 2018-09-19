<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\AdvancedSearch\SearchResultsPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\AdvancedSearch;

use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\SearchPage\SearchResults;

/**
 * Class for the Paddle Advanced Search "Search results" content type.
 *
 * @property SearchResults $results
 */
class SearchResultsPane extends Pane
{
    /**
     * Magically provides all known elements of the pane.
     *
     * @param string $name
     *   An element machine name.
     *
     * @return mixed
     *   The requested pane element.
     *
     * @throws \Exception
     */
    public function __get($name)
    {
        switch ($name) {
            case 'results':
                return new SearchResults($this->webdriver);
                break;
        }

        throw new \Exception("Property with name $name not defined");
    }
}
