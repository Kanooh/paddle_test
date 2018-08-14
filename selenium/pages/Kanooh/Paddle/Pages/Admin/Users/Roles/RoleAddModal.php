<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Users\Roles\RoleAddModal.
 */

namespace Kanooh\Paddle\Pages\Admin\Users\Roles;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class RoleAddModal.
 *
 * @property RoleAddForm $form
 */
class RoleAddModal extends Modal
{
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                $xpath = '//form[contains(@id, "paddle-protected-content-role-form")]';

                return new RoleAddForm($this->webdriver, $this->webdriver->byXPath($xpath));
        }

        throw new \Exception("The property with the name $name is not defined.");
    }
}
