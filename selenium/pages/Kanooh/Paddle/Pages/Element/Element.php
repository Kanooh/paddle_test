<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Element.
 */

namespace Kanooh\Paddle\Pages\Element;

use Kanooh\WebDriver\WebDriverTestCase;

abstract class Element
{
    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * The XPath selector that identifies the element.
     *
     * @var string $xpathSelector
     */
    protected $xpathSelector;

    /**
     * The Selenium webdriver element representing the element.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs an Element object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        $this->webdriver = $webdriver;
    }

    /**
     * Returns the XPath selector for this element.
     *
     * @return string
     *   The XPath selector.
     */
    public function getXPathSelector()
    {
        return $this->xpathSelector;
    }

    /**
     * Returns the element.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The element.
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Returns the number of elements that are present in the browser page.
     *
     * This is parsing the HTML source with DOMDocument and DOMXPath instead of
     * relying on the webdriver, as the webdriver will wait for missing elements
     * to appear.
     *
     * Use this method whenever you want a count of elements that might not be
     * present and don't like to wait forever.
     *
     * @param string $xpath
     *   The XPath expression that selects the elements to count.
     *
     * @return int
     *   The number of elements found.
     *
     * @throws InvalidHTMLException
     *   Thrown when there is invalid HTML.
     *
     * @deprecated
     *   Counting elements shouldn't happen in method on this class. Instead,
     *   use:
     *   $elements = $this->webdriver->elements(
     *     $this->webdriver->using('xpath')->value('//your xpath expression')
     *   );
     *   It gives you an array of all matching elements. An empty array if none
     *   matched.
     */
    public function getElementCountByXPath($xpath)
    {
        libxml_use_internal_errors(true);
        libxml_clear_errors();

        $document = new \DOMDocument();
        $document->loadHTML($this->webdriver->source());
        $domxpath = new \DOMXPath($document);

        // @todo This has been disabled since we are getting validation errors
        //   on the administrative node view of landing pages.
        // @see https://one-agency.atlassian.net/browse/KANWEBS-1412
        if (false && $errors = libxml_get_errors()) {
            throw new InvalidHTMLException($this->webdriver->url(), $errors);
        }

        return $domxpath->query($xpath)->length;
    }

    /**
     * Returns the Selenium webdriver element for the element.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The webdriver element for the Element object.
     *
     * @throws ElementNotPresentException
     *   Throws an exception if the element is not found in the page.
     */
    public function getWebdriverElement()
    {
        try {
            return $this->webdriver->byXPath($this->getXPathSelector());
        } catch (\Exception $e) {
            try {
                return $this->getElement();
            } catch (\Exception $e) {
                throw new ElementNotPresentException($this->getXPathSelector());
            }
        }
    }

    /**
     * Get the classes applied to the element.
     *
     * @return array
     *   The classes, as an array of strings.
     */
    public function getClasses()
    {
        $classes_string = trim($this->getWebdriverElement()->attribute('class'));
        $classes = preg_split('/\s+/', $classes_string);

        return $classes;
    }

    /**
     * Check if a the element has a particular HTML class applied.
     *
     * @return bool
     *   If the element has the class applied or not.
     */
    public function hasClass($class)
    {
        $classes = $this->getClasses();

        return in_array($class, $classes);
    }

    /**
     * Checks whether the element is present on the page.
     *
     * @return bool
     *   True if the element is present on the page. False if not.
     */
    public function isPresent()
    {
        return (bool) $this->webdriver->elements($this->webdriver->using('xpath')->value($this->getXPathSelector()));
    }
}
