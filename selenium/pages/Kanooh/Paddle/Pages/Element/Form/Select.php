<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\Select.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * A form field representing a drop down.
 */
class Select extends FormField
{
    /**
     * The PHPUnit Select object.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element_Select
     */
    protected $select;

    /**
     * Constructs a new Select object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium webdriver element representing the form field.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        parent::__construct($webdriver, $element);

        $this->select = $webdriver->select($element);
    }

    /**
     * Selects an option by value.
     *
     * @param string $value
     *   The value parameter of the option to select.
     */
    public function selectOptionByValue($value)
    {
        $this->select->selectOptionByValue($value);
    }

    /**
     * Selects an option by label.
     *
     * @param string $label
     *   The label of the option to select.
     */
    public function selectOptionByLabel($label)
    {
        $element = $this->select->using('xpath')->value(".//option[normalize-space(text()) = '$label']");
        $this->select->selectOptionByCriteria($element);
    }

    /**
     * Returns the label of the currently selected option.
     *
     * @return string
     *   The label of the currently selected option.
     */
    public function getSelectedLabel()
    {
        return $this->select->selectedLabel();
    }

    /**
     * Returns the value of the currently selected option.
     *
     * @return string
     *   The value of the currently selected option.
     */
    public function getSelectedValue()
    {
        return $this->select->selectedValue();
    }

    /**
     * Returns the available options.
     *
     * @return array
     *   An associative array of select options, keyed by option value, with the
     *   option label as value.
     */
    public function getOptions()
    {
        $options = array();
        foreach ($this->select->elements($this->select->using('css selector')->value('option')) as $element) {
            /* @var $element \PHPUnit_Extensions_Selenium2TestCase_Element */
            $element_value = $element->attribute('value');
            if (!empty($element_value)) {
                $options[$element_value] = $element->text();
            }
        }
        return $options;
    }

    /**
     * Give the select some time for its selected value to be changed.
     *
     * By JavaScript for example.
     *
     * @param string $value
     */
    public function waitUntilSelectedValueEquals($value)
    {
        // Ensure the pane image style got reset.
        $select = $this;
        $callable = new SerializableClosure(
            function () use ($select, $value) {
                if ($select->getSelectedValue() == $value) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
