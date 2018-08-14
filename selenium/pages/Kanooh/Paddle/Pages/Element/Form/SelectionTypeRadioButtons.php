<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\SelectionTypeRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

/**
 * Class representing the selection type radio buttons of a pane.
 */
class SelectionTypeRadioButtons extends RadioButtons
{
    /**
     * {@inheritDoc}
     */
    public function __get($name)
    {
        return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="' . $name . '"]'));
    }

    /**
     * Selects a radio button and waits for ajax callbacks to be completed.
     *
     * @param string $value
     *   The value parameter of the button to select.
     */
    public function select($value)
    {
        // Cache the current element.
        $radio = $this->{$value};
        // Select the wanted value.
        $radio->select();
        // Wait until the element is stale. This also means that the
        // containing fieldset has been rebuilt.
        $this->webdriver->waitUntilElementIsStale($radio->getWebdriverElement());
        // Wait until the radio is back in place.
        $this->webdriver->waitUntilElementIsPresent('.//input[@value="' . $value . '"]');
    }
}
