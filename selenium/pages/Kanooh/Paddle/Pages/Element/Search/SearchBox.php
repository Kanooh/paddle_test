<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Search\SearchBox.
 */

namespace Kanooh\Paddle\Pages\Element\Search;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\ElementNotPresentException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the search box.
 *
 * @property Text $searchField
 *   The search field.
 * @property SearchBoxRadioButtons $searchMethod
 *   The search method we want to use.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $searchButton
 *   The search button to execute the search.
 */
class SearchBox extends Element
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[@id="block-search-api-page-search"]';

    /**
     * Magic getter.
     *
     * @throws ElementNotPresentException
     *   Thrown when the requested element is not present.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'searchField':
                return new Text(
                    $this->webdriver,
                    $this->webdriver->byXPath($this->xpathSelector . '//input[@type="text"]')
                );
            case 'searchMethod':
                $xpath = $this->xpathSelector .
                  '//div[contains(concat(" ", @class, " "), " form-item-search-method ")]/..';
                $locator = $this->webdriver
                  ->using('xpath')
                  ->value($xpath);
                $elements = $this->webdriver->elements($locator);
                $element = reset($elements);
                if (empty($element)) {
                    throw new ElementNotPresentException($xpath);
                }
                return new SearchBoxRadioButtons($this->webdriver, $element);
            case 'searchButton':
                return $this->webdriver->byXPath($this->xpathSelector . '//input[@value="Search"]');
        }

        return parent::__get($name);
    }

    /**
     * Checks the placeholder in the search textfield.
     *
     * @param string $placeholder
     *   The placeholder string to check on.
     *
     * @return bool
     *   True if the text field with this placeholder was found, false otherwise.
     */
    public function checkPlaceholder($placeholder)
    {
        $criteria = $this->webdriver->using('xpath')->value('//input[@placeholder="' . $placeholder .  '"]');
        $elements = $this->webdriver->elements($criteria);
        if (count($elements) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Checks the search button label.
     *
     * @param string $label
     *   The search button label to check on.
     *
     * @return bool
     *   True if the button with this label was found, false otherwise.
     */
    public function checkSearchButtonLabel($label)
    {
        $criteria = $this->webdriver->using('xpath')->value('//input[@value="' . $label .  '"]');
        $elements = $this->webdriver->elements($criteria);
        if (count($elements) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Checks the search popup icon.
     *
     * @param string $class
     *
     * @return bool
     *   True if the icon with this class was found, false otherwise.
     */
    public function checkSearchPopUpIcon($class)
    {
        $criteria = $this->webdriver->using('xpath')->value('//a[contains(@class, "search-pop-up")]/i[contains(@class, "fa-' . $class . '")]');
        $elements = $this->webdriver->elements($criteria);
        if (count($elements) > 0) {
            return true;
        }

        return false;
    }
}
