<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Links\Links.
 */

namespace Kanooh\Paddle\Pages\Element\Links;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a collection of links on a page.
 */
abstract class Links
{

    /**
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * The Selenium element that contains the links.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a Links object.
     *
     * This requires either the Selenium webdriver or a Selenium element to be
     * passed. If possible pass an element, passing the webdriver is deprecated.
     *
     * @todo Rework to only support Selenium elements.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   A Selenium element.
     */
    public function __construct(WebDriverTestCase $webdriver = null, \PHPUnit_Extensions_Selenium2TestCase_Element $element = null)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * Magically provides all known links as properties.
     *
     * Properties that start with 'link', followed by the machine name of a link
     * defined in this::linkInfo() can be used to access the links directly. For
     * example: $this->linkConfigure->click().
     *
     * @param string $name
     *   A link machine name, prepended with 'link'.
     *
     * @return \Kanooh\Paddle\Pages\Element\Element
     *   The matching link.
     */
    public function __get($name)
    {
        // If the property starts with 'link...' then return the matching link.
        if (strpos($name, 'link') === 0) {
            $link_name = substr($name, 4);
            return $this->link($link_name);
        }

        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_NOTICE);
    }

    /**
     * Returns a link.
     *
     * @param $name
     *   The machine name of the link, as defined in this::linkInfo().
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *
     * @throws LinkNotPresentException
     *   Thrown when the requested link is not present.
     */
    public function link($name)
    {
        $link = $this->getLinkInfo($name);

        try {
            // We are transitioning from passing the webdriver and working with
            // XPath to working with Selenium elements directly. Only use the
            // webdriver if the element is not present.
            if (!empty($this->element)) {
                return $this->element->byXPath($link['xpath']);
            }
            return $this->webdriver->byXPath($link['xpath']);
        } catch (\Exception $e) {
            throw new LinkNotPresentException($name);
        }
    }

    /**
     * Returns metadata about a given link.
     *
     * @param string $name
     *   The machine name of the link.
     *
     * @return array
     *   An associative array of link metadata with the following keys:
     *   - xpath: the XPath expression that uniquely identifies the link.
     *   - title: the link title.
     *
     * @throws LinkNotDefinedException
     *   Throws an exception if no link is defined with the given name.
     */
    public function getLinkInfo($name)
    {
        $link_info = $this->linkInfo();
        if (empty($link_info[$name])) {
            throw new LinkNotDefinedException($name);
        }
        return $link_info[$name];
    }

    /**
     * Returns metadata about all available links on this page.
     *
     * @return array
     *   An associative array of link metadata, keyed by link name, each
     *   value consisting of an associative array with the following keys:
     *   - xpath: the XPath expression that uniquely identifies the link.
     *   - title: (optional) the link title.
     */
    abstract public function linkInfo();

    /**
     * Checks the presence of links on the page.
     *
     * @param array $names
     *   Array containing the machine names of the expected links. If omitted,
     *   will check for the presence of all defined links.
     */
    public function checkLinks(array $names = array())
    {
        // Default to all available links.
        $names = $names ?: array_keys($this->linkInfo());

        foreach ($names as $name) {
            // Access the link. If the link is not present this will throw an
            // exception.
            $this->link($name);
        }
    }

    /**
     * Checks that the given links are not present.
     *
     * @param array $names
     *   Array containing the machine names of the links that should not be
     *   present.
     */
    public function checkLinksNotPresent(array $names)
    {
        foreach ($names as $name) {
            // Access the link. If the link is not present this will throw an
            // exception. That is what we expect, so catch it and carry on.
            // If the link is present, we throw our own exception.
            try {
                $this->link($name);
                throw new LinkPresentException($name);
            } catch (LinkNotPresentException $e) {
                // Do nothing.
            }
        }
    }
}
