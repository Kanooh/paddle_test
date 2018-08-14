<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Users\UsersManagementPageForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Users;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;

/**
 * Class Users management filter form.
 *
 * @property Select $operations
 * @property Select $rolesFilter
 * @property Select $statusFilter
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $execute
 * @property Checkbox $selectAll
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $cancel
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $apply
 */
class UsersManagementPageForm extends Form
{

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'operations':
                return new Select($this->webdriver, $this->webdriver->byName('operation'));
            case 'rolesFilter':
                return new Select($this->webdriver, $this->webdriver->byName('rid'));
            case 'statusFilter':
                return new Select($this->webdriver, $this->webdriver->byName('status'));
            case 'apply':
                return $this->webdriver->byId('edit-submit-users-overview');
            case 'execute':
                return $this->webdriver->byId('edit-submit--2');
            case 'selectAll':
                return new Checkbox($this->webdriver, $this->webdriver->byClassName('vbo-table-select-all'));
            case 'cancel':
                return $this->webdriver->byId('edit-cancel');
        }
        throw new FormFieldNotDefinedException($name);
    }
}
