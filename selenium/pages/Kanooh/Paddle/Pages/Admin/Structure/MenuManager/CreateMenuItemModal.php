<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\MenuManager\CreateMenuItemModal.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\MenuManager;

use Kanooh\Paddle\Pages\Element\Modal\Modal;
use Kanooh\Paddle\Pages\Element\Modal\ModalFormElementNotDefinedException;

/**
 * Class representing the modal dialog for creating new menu items.
 *
 * @property CreateMenuItemForm $createMenuItemForm
 *   The main form in the modal.
 */
class CreateMenuItemModal extends Modal
{

    protected $submitButtonXPathSelector = '//form[@id="paddle-menu-manager-menu-item-form"]//input[@class="form-submit"]';

    /**
     * Magic getter of elements inside the modal.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'createMenuItemForm':
                return new CreateMenuItemForm($this->webdriver, $this->webdriver->byId('paddle-menu-manager-menu-item-form'));
                break;
            default:
                throw new ModalFormElementNotDefinedException($name);
                break;
        }
    }
}
