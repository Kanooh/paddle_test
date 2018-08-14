<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\MenuManager\CreateMenuModal.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\MenuManager;

use Kanooh\Paddle\Pages\Element\Modal\Modal;
use Kanooh\Paddle\Pages\Element\Modal\ModalFormElementNotDefinedException;

/**
 * Class representing the modal dialog for creating new menus.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $title
 *   The title textfield.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element_Select $description
 *   The description textarea.
 */
class CreateMenuModal extends Modal
{

    protected $submitButtonXPathSelector = '//form[@id="paddle-menu-manager-menu-form"]//input[@class="form-submit"]';

    /**
     * Magic getter of form elements inside the modal.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'title':
                return $this->webdriver->byXPath($this->xpathSelector . '//input[@name="title"]');
                break;
            case 'description':
                return $this->webdriver->byXPath($this->xpathSelector . '//input[@name="description"]');
                break;
        }
        throw new ModalFormElementNotDefinedException($name);
    }
}
