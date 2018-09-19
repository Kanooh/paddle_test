<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\MenuItemPositionModal.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class representing the modal dialog for positioning a menu item.
 */
class MenuItemPositionModal extends Modal
{

    protected $submitButtonXPathSelector = '//form[@id="paddle-menu-manager-node-menu-item-menu-placement-form"]//input[contains(@class, "form-submit")]';
}
