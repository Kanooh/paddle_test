<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\AjaxService.
 */

namespace Kanooh\Paddle\Utilities;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Utility class to help waiting for AJAX requests to complete.
 */
class AjaxService
{
    /**
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Constructs an AjaxService object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        $this->webdriver = $webdriver;
    }

    /**
     * Indicates if the element is waiting for AJAX callbacks.
     *
     * This checks for the 'progress-disabled' class to be present on the HTML
     * element.
     *
     * @return bool
     *   True if ajax callback is in progress, false if not.
     *
     * @see markAsWaitingForAutoCompleteResults()
     */
    public function isWaitingForAjaxCallback(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $css_classes = explode(' ', $element->attribute('class'));
        return in_array('progress-disabled', $css_classes);
    }

    /**
     * Marks the element as if it is waiting for AJAX callbacks.
     *
     * @see \Kanooh\Paddle\Pages\Element\Form\AutoCompletedText::markAsWaitingForAutoCompleteResults()
     */
    public function markAsWaitingForAjaxCallback(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver->execute(
            array(
                'script' => "arguments[0].className += ' progress-disabled';",
                'args' => array($element->toWebDriverObject()),
            )
        );
    }

    /**
     * Waits until the AJAX callback has been run.
     *
     * @see markAsWaitingForAjaxCallback()
     */
    public function waitForAjaxCallback(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $field = $element;

        $this->webdriver->waitUntil(
            function (\PHPUnit_Extensions_Selenium2TestCase $webdriver) use ($field) {
                if (!$this->isWaitingForAjaxCallback($field)) {
                    return true;
                }
            },
            $this->webdriver->getTimeout()
        );
    }
}
