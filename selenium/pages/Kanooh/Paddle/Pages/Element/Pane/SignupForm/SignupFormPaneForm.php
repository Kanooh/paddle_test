<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\SignupForm\SignupFormPaneForm.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\SignupForm;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the main form in a Signup form pane.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSubmit
 *   The Submit button.
 */
class SignupFormPaneForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'buttonSubmit':
                return $this->element->byXPath('.//input[contains(@id, "edit-submit")]');
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Checks the checkbox of the appropriate MailChimp list.
     *
     * @param  string $name
     *   The human-readable name of list.
     *
     * @return bool
     *   True if a checkbox was found and checked, false otherwise.
     */
    public function selectListByLabel($name)
    {
        $xpath = './/label[normalize-space(text()) = "' . $name . '"]/../input';
        $criteria = $this->element->using('xpath')->value($xpath);
        $elements = $this->element->elements($criteria);
        if (count($elements) > 0) {
            $checkbox = new Checkbox($this->webdriver, $elements[0]);
            $checkbox->check();
            return true;
        }

        return false;
    }

    /**
     * Enters the passed value in the appropriate text field.
     *
     * @param  string $name
     *   The label of field.
     *
     * @return bool
     *   True if a found was found and the value entered, false otherwise.
     */
    public function fillInFieldByLabel($name, $value)
    {
        $xpath = './/label[normalize-space(text()) = "' . $name . '"]/../input';
        $criteria = $this->element->using('xpath')->value($xpath);
        $elements = $this->element->elements($criteria);
        if (count($elements) > 0) {
            $textfield = new Text($this->webdriver, $elements[0]);
            $textfield->fill($value);
            return true;
        }

        return false;
    }

    /**
     * Counts the number of List checkboxes.
     *
     * @return int
     *   Number of checkboxes found in the form.
     */
    public function getCheckboxCount()
    {
        $xpath = './/input[@type="checkbox"]';
        $criteria = $this->element->using('xpath')->value($xpath);
        return count($this->element->elements($criteria));
    }
}
