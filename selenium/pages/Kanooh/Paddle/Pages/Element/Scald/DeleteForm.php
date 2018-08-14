<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\DeleteForm.
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * Class DeleteForm
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $cancelButton
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $deleteButton
 */
class DeleteForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'cancelButton':
                return $this->element->byXPath('.//input[@value="Cancel"]');
            case 'deleteButton':
                return $this->element->byXPath('.//input[@value="Delete"]');
        }
    }
}
