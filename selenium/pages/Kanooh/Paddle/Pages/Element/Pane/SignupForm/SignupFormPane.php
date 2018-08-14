<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\SignupForm\SignupFormPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\SignupForm;

use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SignupFormPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Panels pane with Ctools content type 'Signup Form'.
 *
 * @property SignupFormPaneForm $mainForm
 *   The main Signup form in the pane.
 */
class SignupFormPane extends Pane
{

    /**
     * The object for the pane content type.
     *
     * @var SignupFormPanelsContentType
     */
    public $contentType;

    /**
     * Constructs an SignupFormPane pane.
     *
     * @param WebDriverTestCase $webdriver
     *   The webdriver object.
     * @param string $uuid
     *   The uuid of the pane.
     * @param string $pane_xpath_selector
     *   More general xpath selector for the pane.
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid, $pane_xpath_selector)
    {
        parent::__construct($webdriver, $uuid, $pane_xpath_selector);

        $this->contentType = new SignupFormPanelsContentType($this->webdriver);
    }

    /**
     * Magically provides all known elements of the pane.
     *
     * @param string $name
     *   An element machine name.
     *
     * @return mixed
     *   The requested pane element.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'mainForm':
                $xpath = $this->xpathSelector . '//form[contains(@class, "mailchimp-signup-subscribe-form")]';
                return new SignupFormPaneForm($this->webdriver, $this->webdriver->byXPath($xpath));
                break;
        }

        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_NOTICE);
    }
}
