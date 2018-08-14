<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Users\Roles\RoleAddForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Users\Roles;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Represents the form from which you add new roles.
 *
 * @property Text $name
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addButton
 */
class RoleAddForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'name':
                $element = $this->element->byXPath('.//input[@name="name"]');
                return new Text($this->webdriver, $element);
        }

        throw new \Exception("The property with the name $name is not defined.");
    }
}
