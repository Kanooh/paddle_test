<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\RadioButton.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

/**
 * A form field representing a radio button.
 */
class RadioButton extends FormField
{

    /**
     * Selects the radio button.
     */
    public function select()
    {
        $this->webdriver->clickOnceElementIsVisible($this->element);
    }

    /**
     * Returns the value of the radio button.
     *
     * @return string
     *   The value of the button.
     */
    public function getValue()
    {
        return $this->element->attribute('value');
    }

    /**
     * Returns the status of the radio button.
     *
     * @return bool
     *   True if the radio button is selected, false otherwise.
     */
    public function isSelected()
    {
        return $this->element->selected();
    }

    /**
     * Checks whether the radio button is enabled or not.
     *
     * @return bool
     *   True if the radio button is enabled, false otherwise.
     */
    public function isEnabled()
    {
        return $this->element->enabled();
    }

    /**
     * Retrieves the label associated with this radio button.
     *
     * @return string
     *   The label, if there is one, empty string otherwise.
     */
    public function getLabel()
    {
        $xpath = '//label[@for = "' . $this->element->attribute('id') . '"]';
        try {
            return $this->webdriver->byXPath($xpath)->text();
        } catch (\Exception $e) {
            return '';
        }
    }
}
