<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\Delete\ConfirmationForm.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\Delete;

use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * Class ConfirmationForm
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $cancelButton
 *   Cancel button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $deleteButton
 *   Delete button.
 */
class ConfirmationForm extends Form
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
