<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Search\HeaderTopSearchBox.
 */

namespace Kanooh\Paddle\Pages\Element\Search;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\ElementNotPresentException;

/**
 * Class representing the search box in the header_top region.
 *
 * @property HeaderTopSearchBoxForm $form
 *   The search form which is inside this block.
 */
class HeaderTopSearchBox extends Element
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[@id="block-paddle-core-content-search"]';

    /**
     * Magic getter.
     *
     * @throws ElementNotPresentException
     *   Thrown when the requested element is not present.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new HeaderTopSearchBoxForm($this->webdriver, $this->webdriver->byId('paddle-core-header-top-search-form'));
        }

        throw new ElementNotPresentException($name);
    }
}
