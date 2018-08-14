<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Users\Roles\RoleDeleteModal.
 */

namespace Kanooh\Paddle\Pages\Admin\Users\Roles;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class RoleDeleteModal.
 *
 * @property RoleDeleteForm $form
 */
class RoleDeleteModal extends Modal
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                $xpath = '//form[contains(@id, "paddle-protected-content-delete-form")]';
                return new RoleDeleteForm($this->webdriver, $this->webdriver->byXPath($xpath));
        }

        throw new \Exception("The property with the name $name is not defined.");
    }
}
