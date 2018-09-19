<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\MenuItemModal.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Modal\Modal;
use Kanooh\Paddle\Pages\Element\Modal\ModalFormElementNotDefinedException;

/**
 * Class representing the modal dialog for creating or editing a menu item.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element_Select $navigation
 *   The navigation select field.
 * @property \Kanooh\Paddle\Pages\Element\Form\Text $title
 *   The title textfield.
 */
class MenuItemModal extends Modal
{

    protected $submitButtonXPathSelector = '//form[@id="paddle-menu-manager-node-menu-item-menu-link-form"]//input[contains(@class, "form-submit")]';

    /**
     * Magic getter of form elements inside the modal.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'navigation':
                return $this->webdriver->select($this->webdriver->byXPath($this->xpathSelector . '//select[@name="navigation"]'));
                break;
            case 'title':
                return new Text($this->webdriver, $this->webdriver->byXPath($this->xpathSelector . '//input[@name="link_title"]'));
                break;
            default:
                throw new ModalFormElementNotDefinedException($name);
                break;
        }
    }
}
