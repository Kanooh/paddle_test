<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Glossary\GlossaryDefinitionForm.
 */

namespace Kanooh\Paddle\Pages\Element\Glossary;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;

/**
 * The main form of the add/edit glossary definition entities.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $saveButton
 *   The form's save button.
 * @property Text $definition
 *   The form's definition field.
 * @property Wysiwyg $description
 *   The description text in a wysiwyg.
 */
class GlossaryDefinitionForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'saveButton':
                return $this->element->byXPath('.//input[contains(@id, "edit-save")]');
                break;
            case 'definition':
                return new Text($this->webdriver, $this->element->byName('definition'));
                break;
            case 'description':
                return new Wysiwyg($this->webdriver, 'edit-field-glossary-description-und-0-value');
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
