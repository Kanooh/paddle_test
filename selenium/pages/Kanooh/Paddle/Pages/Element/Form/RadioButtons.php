<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\RadioButtons.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

/**
 * A form field representing a group of radio buttons.
 */
abstract class RadioButtons extends FormField
{

    /**
     * Provides a magic property for each radio button.
     *
     * @param string $name.
     *   The name of the radio button.
     *
     * @throws RadioButtonNotDefinedException
     *   Thrown when a magic method refers to an undefined button.
     */
    abstract public function __get($name);

    /**
     * Selects a radio button.
     *
     * This is especially handy for forms that have dynamically generated radio
     * buttons which are not selectable through the magic methods.
     *
     * @param string $value
     *   The value parameter of the button to select.
     */
    public function select($value)
    {
        $button = new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value = "' . $value . '"]'));
        $button->select();
    }

    /**
     * Returns the currently selected radio button.
     *
     * @return RadioButton
     *   The currently selected radio button, or NULL if
     *   no radio button has been selected.
     */
    public function getSelected()
    {
        $element = $this->element->byXPath('.//input[@checked = "checked"]');
        if ($element) {
            return new RadioButton($this->webdriver, $element);
        }
    }
}
