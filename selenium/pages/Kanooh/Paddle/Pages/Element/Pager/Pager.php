<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pager\Pager.
 */

namespace Kanooh\Paddle\Pages\Element\Pager;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * A pager.
 *
 * This is modelled after the 'mini' pager from Views, which has 'previous' and
 * 'next' links and displays the current page and total number of pages as
 * '{N} of {M}'.
 *
 * @todo At the moment we have two different types of pagers in use (for the
 * news overview page and the search results), but the plan is that all pagers
 * should look and function identically. Rework this when a decision is made on
 * how the pagers should look.
 * @see https://one-agency.atlassian.net/browse/KANWEBS-2361
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $firstLink
 *   The link that leads to the first page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $previousLink
 *   The link that leads to the previous page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $nextLink
 *   The link that leads to the next page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $lastLink
 *   The link that leads to the last page.
 */
class Pager
{
    /**
     * The Selenium web driver element representing the pager.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Constructs a Pager object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium web driver element representing the pager.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * Magic getter for element properties.
     *
     * @param string $property
     *   The property name.
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The wanted element.
     *
     * @throws \Exception
     */
    public function __get($property)
    {
        switch ($property) {
            case 'firstLink':
                return $this->element->byXPath('.//li[contains(@class, "pager-first")]/a');
            case 'previousLink':
                return $this->element->byXPath('.//li[contains(@class, "pager-previous")]/a');
            case 'nextLink':
                return $this->element->byXPath('.//li[contains(@class, "pager-next")]/a');
            case 'lastLink':
                return $this->element->byXPath('.//li[contains(@class, "pager-last")]/a');
        }

        throw new \Exception("The property $property is undefined.");
    }

    /**
     * Returns the text representation of the current page.
     *
     * This is in the format "{N} of {M}".
     *
     * @return string
     *   The text representation of the current page.
     */
    public function getCurrentText()
    {
        return $this->element->byXPath('./li[contains(@class, "pager-current")]')->text();
    }

    /**
     * Returns the current page number.
     *
     * @return int
     *   The current page number.
     */
    public function getCurrentPage()
    {
        $data = $this->parseCurrentText();

        return $data['current'];
    }

    /**
     * Returns the total number of pages.
     *
     * @return int
     *   The total number of pages.
     */
    public function getPageTotal()
    {
        $data = $this->parseCurrentText();

        return $data['total'];
    }

    /**
     * Extracts data from the "current text".
     *
     * This assumes that the current text is in the format "{N} of {M}" where
     * {N} represents the current page number and {M} the total number of pages.
     *
     * @return array
     *   An array with the following keys:
     *   - current: The current page number. Starts counting at 1.
     *   - total: The total number of pages.
     */
    protected function parseCurrentText()
    {
        preg_match('/(\d+) of (\d+)/', $this->getCurrentText(), $matches);

        return array(
            'current' => (int) $matches[1],
            'total' => (int) $matches[2],
        );
    }
}
