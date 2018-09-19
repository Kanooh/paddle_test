<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\EmailsForm.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the emails overview form of a formbuilder node.
 *
 * @property RadioButton $addressRadio
 *   The address radio button.
 * @property RadioButton $componentRadio
 *   The componenent radio button
 * @property Text $addressText
 *   The "address" email text field.
 * @property Select $componentSelect
 *   The "component" select field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $add
 *   The "add" button.
 */
class EmailsForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'addressRadio':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('//input[@name="email_option" and @value="custom"]')
                );
            case 'componentRadio':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('//input[@name="email_option" and @value="component"]')
                );
            case 'addressText':
                return new Text($this->webdriver, $this->element->byName('email_custom'));
            case 'componentSelect':
                return new Select($this->webdriver, $this->element->byName('email_component'));
            case 'add':
                return $this->element->byCssSelector('input.form-submit');
        }
        throw new FormFieldNotDefinedException($name);
    }
}
