<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\MailChimp\SignupFormDeleteForm.
 */

namespace Kanooh\Paddle\Pages\Element\MailChimp;

use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * Class SignupFormDeleteForm
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $deleteButton
 */
class SignupFormDeleteForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'deleteButton':
                return $this->element->byXPath('.//input[@value="Delete"]');
        }
    }
}
