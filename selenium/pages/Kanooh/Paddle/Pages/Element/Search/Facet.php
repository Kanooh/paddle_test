<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Search\Facet.
 */

namespace Kanooh\Paddle\Pages\Element\Search;

use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing a basic facet.
 *
 * @property Select $select
 */
class Facet
{
    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * The Selenium webdriver element representing the facet.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a new Facet.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium webdriver element representing the form field.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'select':
                return new Select($this->webdriver, $this->element->byXPath('.//select'));
        }
    }

    /**
     * Returns all inactive links present in the facet, keyed by value.
     *
     * @return FacetLink[]
     */
    public function getInactiveLinks()
    {
        return $this->getLinks('inactive');
    }

    /**
     * Retrieve an inactive link by its value.
     *
     * @param string $value
     *   The value of the link we want to retrieve.
     *
     * @return \Kanooh\Paddle\Pages\Element\Search\FacetLink|null
     *   The link if found, null otherwise.
     */
    public function getInactiveLinkByValue($value)
    {
        return $this->getLinkByTypeAndValue('inactive', $value);
    }

    /**
     * Returns all active the links present in the facet, keyed by value.
     *
     * @return FacetLink[]
     */
    public function getActiveLinks()
    {
        return $this->getLinks('active');
    }

    /**
     * Retrieve an active link by its value.
     *
     * @param string $value
     *   The value of the link we want to retrieve.
     *
     * @return \Kanooh\Paddle\Pages\Element\Search\FacetLink|null
     *   The link if found, null otherwise.
     */
    public function getActiveLinkByValue($value)
    {
        return $this->getLinkByTypeAndValue('active', $value);
    }

    /**
     * Retrieve all the links of a certain type.
     *
     * @param string $type
     *   The facet link type, inactive or active.
     *
     * @return FacetLink[]
     *   An array of facet links.
     */
    protected function getLinks($type)
    {
        $criteria = $this->element->using('xpath')->value('.//ul//a[contains(@class, "facetapi-' . $type . '")]');
        $elements = $this->element->elements($criteria);

        $links = array();
        foreach ($elements as $element) {
            $link = new FacetLink($this->webdriver, $element);
            $links[$link->value] = $link;
        }

        return $links;
    }

    /**
     * Retrieve a link by its value and type.
     *
     * @param string $type
     *   The facet link type, inactive or active.
     * @param string $value
     *   The value of the link we want to retrieve.
     *
     * @return FacetLink[]
     *   The link if found, null otherwise.
     */
    protected function getLinkByTypeAndValue($type, $value)
    {
        $links = $this->getLinks($type);

        return isset($links[$value]) ? $links[$value] : null;
    }
}
