<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Maps\SearchResultsPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Maps;

use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\SearchPage\MapsSearchResults;

/**
 * Class for the Paddle Maps Search "block maps search results" content type.
 *
 * @property MapsSearchResults $results
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
                return new MapsSearchResults($this->webdriver);
                break;
        }

        throw new \Exception("Property with name $name not defined");
    }
}
