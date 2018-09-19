<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\PaddleMegaDropdown\DeletePage\DeletePage.
 */

namespace Kanooh\Paddle\Pages\Admin\PaddleMegaDropdown\DeletePage;

use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The Mega Dropdown Entity Delete page class.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonConfirm
 *   The "Confirm" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCancel
 *   The "Cancel" button.
 */
class DeletePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddle-mega-dropdown/delete/%';

    /**
     * The xpath of the "Confirm" button on the page.
     * @todo - replace with the appropriate object when we have it - like "Button" or "FormElement".
     * @var string $buttonConfirmXPath
     */
    protected $buttonConfirmXPath = '//form[@id="paddle-mega-dropdown-delete-form"]//input[@id="edit-submit"]';

    /**
     * The xpath of the "Cancel" link on the page.
     * @todo - replace with the appropriate object when we have it - like "ActionLink".
     * @var string $buttonCancelXPath
     */
    protected $buttonCancelXPath = '//form[@id="paddle-mega-dropdown-delete-form"]//a[@id="edit-cancel"]';

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver);
    }

    /**
     * Magically provides all known buttons and links as properties.
     *
     * Properties that start with 'button' (links are also considered buttons), followed by the machine name of a
     * button. For example: $this->buttonCancel.
     *
     * @param string $name
     *   A button machine name prepending with 'button'.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The matching button element object.
     */
    public function __get($name)
    {
        if (isset($this->{$name . 'XPath'})) {
            return $this->webdriver->element($this->webdriver->using('xpath')->value($this->{$name . 'XPath'}));
        }

        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_NOTICE);
    }
}
