<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\MenuManager\DeleteMenuModal.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\MenuManager;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class representing the modal dialog for deleting custom menu.
 */
class DeleteMenuModal extends Modal
{
    protected $submitButtonXPathSelector = '//form[@id="paddle-menu-manager-menu-delete-confirm-form"]//input[@class="form-submit"]';
}
