<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\BulkActions\BulkActions.
 */

namespace Kanooh\Paddle\Pages\Element\BulkActions;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing the bulk actions.
 *
 * @property Select $selectAction
 *   The select to select a bulk action.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $executeButton
 *   The execute button.
 * @property Checkbox $selectAll
 *   The checkbox to select all nodes.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonConfirm
 *   The confirm button on the Confirm step of the form.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCancel
 *   The cancel button on the Confirm step of the form.
 */
class BulkActions
{
    /**
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * Magical getter providing all the properties of the web element.
     *
     * @param string $property
     *   The name of the property we need.
     *
     * @return mixed
     *   The property found.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'selectAction':
                return new Select($this->webdriver, $this->element->byName('operation'));
            case 'executeButton':
                return $this->element->byXPath('.//input[@value="Execute"]');
            case 'selectAll':
                return new Checkbox($this->webdriver, $this->webdriver->byClassName('vbo-table-select-all'));
            case 'buttonConfirm':
                return $this->webdriver->byXPath('//input[@id="edit-submit"]');
            case 'buttonCancel':
                return $this->webdriver->byXPath('//a[@id="edit-cancel"]');
        }
        throw new FormFieldNotDefinedException($property);
    }
}
