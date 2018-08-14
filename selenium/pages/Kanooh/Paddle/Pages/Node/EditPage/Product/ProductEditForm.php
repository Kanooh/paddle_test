<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Product\ProductEditForm.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Product;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;
use Kanooh\Paddle\Utilities\AjaxService;

/**
 * Class representing the product edit form.
 *
 * @property ProductFormField $form
 * @property AutoCompletedText $organizationalUnit
 * @property Wysiwyg $body
 * @property Wysiwyg $introduction
 * @property Wysiwyg $conditions
 * @property Wysiwyg $procedure
 * @property Wysiwyg $amount
 * @property Wysiwyg $required
 * @property Wysiwyg $targetGroup
 * @property Wysiwyg $exceptions
 * @property LegislationTable $legislationTable
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $moreLegislationsButton
 */
class ProductEditForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new ProductFormField($this->webdriver, $this->element->byId('edit-field-paddle-prod-form-und-0'));
            case 'organizationalUnit':
                return new AutoCompletedText($this->webdriver, $this->element->byName('field_paddle_responsible_ou[und][0][target_id]'));
            case 'introduction':
                return new Wysiwyg($this->webdriver, 'edit-field-paddle-introduction-und-0-value');
            case 'conditions':
                return new Wysiwyg($this->webdriver, 'edit-field-paddle-conditions-und-0-value');
            case 'procedure':
                return new Wysiwyg($this->webdriver, 'edit-field-paddle-procedure-und-0-value');
            case 'amount':
                return new Wysiwyg($this->webdriver, 'edit-field-paddle-amount-und-0-value');
            case 'required':
                return new Wysiwyg($this->webdriver, 'edit-field-paddle-required-und-0-value');
            case 'targetGroup':
                return new Wysiwyg($this->webdriver, 'edit-field-paddle-target-group-und-0-value');
            case 'exceptions':
                return new Wysiwyg($this->webdriver, 'edit-field-paddle-exceptions-und-0-value');
            case 'body':
                return new Wysiwyg($this->webdriver, 'edit-body-und-0-value');
            case 'legislationTable':
                return new LegislationTable($this->webdriver, '//table[contains(@id, "field-paddle-legislation-values")]');
            case 'moreLegislationsButton':
                return $this->element->byName('field_paddle_legislation_add_more');
        }
        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Helper method to add a new row to the choices table.
     */
    public function addChoice()
    {
        $ajax_service = new AjaxService($this->webdriver);
        $ajax_service->markAsWaitingForAjaxCallback($this->moreChoicesButton);
        $this->moreChoicesButton->click();
        $ajax_service->waitForAjaxCallback($this->moreChoicesButton);
    }
}
