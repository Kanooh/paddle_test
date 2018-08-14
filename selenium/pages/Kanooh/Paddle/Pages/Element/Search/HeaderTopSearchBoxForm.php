<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Search\HeaderTopSearchBoxForm.
 */

namespace Kanooh\Paddle\Pages\Element\Search;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the search form in the the header_top search block.
 *
 * @property Text $searchField
 *   The search field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $searchButton
 *   The search button to execute the search.
 */
class HeaderTopSearchBoxForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'searchField':
                return new Text($this->webdriver, $this->element->byXPath('.//input[@type="text"]'));
            case 'searchButton':
                return $this->element->byXPath('.//button[contains(@class, "search-button")]');
        }
        throw new FormFieldNotDefinedException($name);
    }
}
