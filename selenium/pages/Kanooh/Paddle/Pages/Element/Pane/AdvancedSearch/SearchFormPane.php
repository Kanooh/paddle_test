<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\AdvancedSearch\SearchFormPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\AdvancedSearch;

use Kanooh\Paddle\Pages\Element\Pane\Pane;

/**
 * Class for the Paddle Advanced Search "Search form" content type.
 *
 * @property SearchFormPaneForm $form
 */
class SearchFormPane extends Pane
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
            case 'form':
                $element = $this->webdriver->byXPath($this->getXPathSelectorByUuid() . '//form');
                return new SearchFormPaneForm($this->webdriver, $element);
        }

        throw new \Exception("Property with name $name not defined");
    }
}
