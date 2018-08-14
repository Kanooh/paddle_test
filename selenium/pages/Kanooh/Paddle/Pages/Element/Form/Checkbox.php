<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\Checkbox.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

/**
 * A form field representing a checkbox.
 */
class Checkbox extends FormField
{

    /**
     * Checks the checkbox.
     *
     * This will check the current state of the checkbox, and will only click it
     * if needed, to ensure that the checkbox is indeed checked.
     */
    public function check()
    {
        if (!$this->isChecked()) {
            $this->webdriver->clickOnceElementIsVisible($this->element);
        }
    }

    /**
     * Unchecks the checkbox.
     *
     * This will check the current state of the checkbox, and will only click it
     * if needed, to ensure that the checkbox is indeed unchecked.
     */
    public function uncheck()
    {
        if ($this->isChecked()) {
            $this->webdriver->clickOnceElementIsVisible($this->element);
        }
    }

    /**
     * Returns the status of the checkbox.
     *
     * @return bool
     *   True if the checkbox is checked, false otherwise.
     */
    public function isChecked()
    {
        return $this->element->selected();
    }

    /**
     * Returns the value of the checkbox.
     *
     * @return string
     *   The value of the checkbox.
     */
    public function getValue()
    {
        return $this->element->attribute('value');
    }
}
