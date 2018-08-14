<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\PaddlePage.
 *
 * @see https://code.google.com/p/selenium/wiki/PageObjects
 */

namespace Kanooh\Paddle\Pages;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\InvalidHTMLException;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class PaddlePage
 *
 * @package Kanooh\Paddle\Pages
 */
abstract class PaddlePage
{

    /**
     * @var WebDriverTestCase reference to webdriver object.
     */
    protected $webdriver;

    /**
     * The URL path for this page.
     *
     * Path components that start with a '%'-sign are treated as wildcards.
     *
     * @var string $path
     */
    protected $path;

    /**
     * The path arguments used for getting the current page.
     *
     * These map to the wildcards used in $this->path.
     *
     * @var array $pathArguments
     */
    protected $pathArguments;

    /**
     * A reference to the body element of the page.
     *
     * If this goes stale we know the page is no longer visible.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $bodyElement;

    /**
     * @param $webdriver
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        $this->webdriver = $webdriver;
    }

    /**
     * Catch-all for undefined magic properties on children of PaddlePage.
     *
     * If a child class that is using magic getters cannot return the property
     * it will pass it up to the parent class. If it gets here the property is
     * not supported. We will throw an exception to inform the user.
     *
     * @param $property
     *   The property that was not returned by any of the child classes.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     * @throws \Exception
     */
    public function __get($property)
    {
        throw new \Exception("The property $property is undefined.");
    }

    /**
     * @todo - move the method to a dedicated Modal class - see
     *     https://one-agency.atlassian.net/browse/KANWEBS-1228.
     */
    public function waitUntilModalBackdropIsNotDisplayed()
    {
        $this->webdriver->waitUntilElementIsNotDisplayed('//div[@id="modalBackdrop"]');
    }

    /**
     * Checks that the browser is on the current PaddlePage.
     *
     * @throws PaddlePageWrongPathException
     *   Thrown if the browser is not on this PaddlePage.
     */
    public function checkPath()
    {
        $current_url = parse_url($this->webdriver->path());
        $current_path = trim($current_url['path'], '/');
        $current_components = explode('/', $current_path);
        $page_components = explode('/', $this->path);

        // If we have a language prefix, remove it as it will break the
        // comparison.
        if (MultilingualService::getLanguagePathPrefix($this->webdriver)) {
            array_shift($current_components);
        }

        // Check if the components are different, ignoring the ones that start
        // with a '%'-character.
        $result = array_udiff_assoc(
            $current_components,
            $page_components,
            function ($current_component, $page_component) {
                return (int) ($current_component != $page_component && strpos($page_component, '%') !== 0);
            }
        );

        if (!empty($result)) {
            throw new PaddlePageWrongPathException($this->path, $current_path);
        }
    }

    /**
     * Navigate to this page in the browser.
     *
     * @param mixed $arguments
     *   An indexed array of arguments to use for the wildcards in the URL.
     *   These will replace path components that start with a '%'-character.
     *   This can be omitted if there are no wildcards in the URL.
     */
    public function go($arguments = array())
    {
        // Ensure we have an array.
        $arguments = (array) $arguments;

        // Store the path arguments.
        $this->pathArguments = $arguments;

        $components = explode('/', $this->path);
        foreach ($components as &$component) {
            if (strpos($component, '%') === 0) {
                $component = array_shift($arguments);
            }
        }
        $path = implode('/', $components);

        // If there was a language prefix add it to the path we go to so the
        // language is preserved.
        if (MultilingualService::isMultilingual($this->webdriver)) {
            $language_prefix = MultilingualService::getLanguagePathPrefix($this->webdriver);
            if ($language_prefix) {
                $path = $language_prefix . '/' . $path;
            }
        }
        $this->webdriver->url($path);
        // Check if the browser has successfully arrived on this page.
        $this->checkArrival();
    }

    /**
     * Returns the path.
     *
     * @return string
     *   Returns this::path.
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Reload the page by going to the same path again.
     */
    public function reloadPage()
    {
        // Store a reference to the body element which we can use to see if the
        // page has been reloaded.
        $this->bodyElement = $this->webdriver->byXPath('//body');

        // Reload.
        $this->go($this->pathArguments);
    }

    /**
     * Retrieve the dynamic arguments for the current path.
     *
     * @return array
     *   An array of dynamic arguments. These match the path components that
     *   start with a '%'-character as defined in this::path.
     */
    public function getPathArguments()
    {
        // Retrieve the path from the current browser URL and disect it into
        // individual path components.
        $url = parse_url($this->webdriver->path());
        $path = trim($url['path'], '/');
        $components = explode('/', $path);

        // If we have a language prefix, remove it as it will break the
        // comparison.
        if (MultilingualService::getLanguagePathPrefix($this->webdriver)) {
            array_shift($components);
        }

        // Loop over the path with wildcard arguments that is defined for this
        // page, and populate the arguments array with the arguments that match
        // wildcards.
        $arguments = array();
        foreach (explode('/', $this->path) as $key => $component) {
            if (strpos($component, '%') === 0) {
                $arguments[] = $components[$key];
            }
        }

        return $arguments;
    }

    /**
     * Waits until the current page is loaded.
     */
    public function waitUntilPageIsLoaded()
    {
        // If this page has been loaded before, wait until the previous instance
        // goes stale before continuing.
        if (!empty($this->bodyElement)) {
            $body = $this->bodyElement;
            $webdriver = $this->webdriver;
            $callable = new SerializableClosure(
                function () use ($body, $webdriver) {
                    try {
                        $body->click();
                    } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                        return true;
                    }
                }
            );
            $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
        }
        $this->webdriver->waitUntilElementIsDisplayed('//body');
    }

    /**
     * Checks if the browser has successfully arrived on this page.
     *
     * Call this whenever you have clicked a link in a test which leads you to a
     * new page.
     */
    public function checkArrival()
    {
        $this->waitUntilPageIsLoaded();
        $this->checkPath();
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
     * This has been taken from Element::getElementCountByXPath().
     * @see Element::getElementCountByXPath()
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
     * Trigger the "Back" button of the browser.
     */
    public function goBack()
    {
        $this->webdriver->back();
    }

    /**
     * Checks if a class can be found on a page.
     *
     * @param string $class_name
     *   The class name to search for.
     *
     * @return bool
     *   True if the class is found, false otherwise.
     */
    public function checkClassPresent($class_name)
    {
        try {
            $this->webdriver->byClassName($class_name);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Returns the language set as interface language.
     *
     * @return string
     *  The current interface language.
     */
    public function getInterfaceLanguage()
    {
        return $this->webdriver->byTag('html')->attribute('lang');
    }
}
