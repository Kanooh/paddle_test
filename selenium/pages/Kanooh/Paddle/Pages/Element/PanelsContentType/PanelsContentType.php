<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\PanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for Panels pane types.
 */
abstract class PanelsContentType
{

    /**
     * The pane type machine name.
     */
    const TYPE = '';

    /**
     * The pane type title.
     */
    const TITLE = '';

    /**
     * The pane type description.
     */
    const DESCRIPTION = '';

    /**
     * The random data generation class.
     *
     * @var Random $random
     */
    protected $random;

    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Constructs a PanelsContentType object.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        $this->webdriver = $webdriver;
        $this->random = new Random();
    }

    /**
     * Fills in the pane configuration form.
     *
     * This does not submit the form. If values have been assigned to the
     * properties that represent the form fields they will be used. Random
     * values will be used for missing properties.
     *
     * @deprecated This should be based on a Form class.
     *
     * @param Element $element
     *   (Optional) The parent element which contains the configuration form.
     *   For example a modal dialog or a block. This is used to build a specific
     *   xpath selector for the form elements.
     */
    abstract public function fillInConfigurationForm(Element $element = null);

    /**
     * Moves to the element with the given XPath selector and sets its value.
     *
     * @deprecated This should no longer be necessary once we have a Form
     *   class.
     *
     * @param string $xpath
     *   The XPath selector of the element to set the value for.
     * @param mixed $value
     *   The value to set.
     * @param bool $clear
     *   Whether or not to clear the element before setting the value. Defaults
     *   to FALSE.
     */
    protected function moveToAndSetValue($xpath, $value, $clear = false)
    {
        $element = $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
        $this->webdriver->waitUntilElementIsPresent($xpath);
        $this->webdriver->moveto($element);

        if ($clear) {
            $element->clear();
        }

        $element->value($value);
    }

    /**
     * Moves to the checkbox with the given XPath selector and sets its value.
     *
     * @deprecated This should no longer be necessary once we have a Form
     *   class.
     *
     * @param string $xpath
     *   The XPath selector of the element to set the value for.
     * @param mixed $checked
     *   If set to true this will make sure the checkbox is checked. If set to
     *   false it will be unchecked.
     */
    protected function moveToAndSetCheckboxValue($xpath, $checked)
    {
        $element = $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
        if ($element->attribute('checked') != $checked) {
            $this->moveToAndClick($xpath);
        }
    }

    /**
     * Moves to the element with the given XPath selector and click it.
     *
     * @deprecated This should no longer be necessary once we have a Form
     *   class.
     *
     * @param string $xpath
     *   The XPath selector of the element to click.
     */
    protected function moveToAndClick($xpath)
    {
        $element = $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
        $this->webdriver->moveto($element);
        $element->click();
    }

    /**
     * Waits until the content type is ready to accept input.
     */
    public function waitUntilReady()
    {
        $this->webdriver->waitUntilElementIsPresent('//form[contains(@class, "paddle-add-pane-form")]');
    }
}
