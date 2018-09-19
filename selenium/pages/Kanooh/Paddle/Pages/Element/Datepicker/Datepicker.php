<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Datepicker\Datepicker.
 */

namespace Kanooh\Paddle\Pages\Element\Datepicker;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a datepicker.
 */
abstract class Datepicker extends Element
{
    /**
     * The XPath selector of the text input field of the datepicker.
     * This property should be set by any class that extends this class.
     * @var string
     */
    protected $xpathInputFieldSelector = '';

    /**
     * Popup element with the calendar.
     * @var \Kanooh\Paddle\Pages\Element\Datepicker\DatepickerPopup
     */
    public $popup;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver);
        $this->popup = new DatepickerPopup($webdriver);
    }

    /**
     * Sets the focus on the text field, which makes the popup appear.
     */
    public function focus()
    {
        $this->webdriver->waitUntilElementIsDisplayed($this->xpathInputFieldSelector);
        $element = $this->webdriver->element($this->webdriver->using('xpath')->value($this->xpathInputFieldSelector));
        $this->webdriver->moveto($element);
        $this->webdriver->click();
    }
}
