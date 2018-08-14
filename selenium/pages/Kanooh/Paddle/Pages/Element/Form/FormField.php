<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\FormField.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for form fields.
 */
abstract class FormField
{
    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * The Selenium webdriver element representing the form field.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a new FormField.
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
     * Returns the Selenium webdriver element for the form field.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The webdriver element for the form field.
     */
    public function getWebdriverElement()
    {
        return $this->element;
    }

    /**
     * Returns whether the field is enabled or not.
     *
     * @return bool
     *   TRUE if the field is enabled, FALSE if it is not.
     */
    public function isEnabled()
    {
        return $this->element->enabled();
    }

    /**
     * Returns whether the field is displayed or not.
     *
     * @return bool
     *   TRUE if the field is displayed, FALSE if it is not.
     */
    public function isDisplayed()
    {
        return $this->element->displayed();
    }

    /**
     * Waits until the form field is displayed.
     */
    public function waitUntilDisplayed()
    {
        $element = $this->element;
        $callable = new SerializableClosure(
            function () use ($element) {
                return $element->displayed() ? true : null;
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Retrieves an element's attribute.
     *
     * This method was introduced to ease the transition to FormField from
     * PHPUnit_Extensions_Selenium2TestCase_Element.
     *
     * @param string $name
     * @return string
     */
    public function attribute($name)
    {
        return $this->element->attribute($name);
    }
}
