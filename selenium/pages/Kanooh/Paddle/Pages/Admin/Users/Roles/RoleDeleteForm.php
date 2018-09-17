<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Users\Roles\RoleDeleteForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Users\Roles;

use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * Represents the form from which you delete roles.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $cancelButton
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $deleteButton
 */
class RoleDeleteForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'cancelButton':
                return $this->element->byXPath('.//input[@value="Cancel"]');
            case 'deleteButton':
                return $this->element->byXPath('.//input[@value="Delete"]');
        }

        throw new \Exception("The property with the name $name is not defined.");
    }
}
