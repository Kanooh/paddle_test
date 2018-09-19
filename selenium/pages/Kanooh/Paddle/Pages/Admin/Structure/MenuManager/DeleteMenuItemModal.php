<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\MenuManager\DeleteMenuItemModal.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\MenuManager;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class representing the modal dialog for deleting menu items.
 */
class DeleteMenuItemModal extends Modal
{
    protected $submitButtonXPathSelector = '//form[@id="paddle-menu-manager-menu-item-delete-form"]//input[@class="form-submit"]';
}
