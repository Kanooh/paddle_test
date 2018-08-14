<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The Themer Add page class.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSubmit
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element_Select $baseTheme
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $name
 */
class ThemerAddPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/themes/create';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'name':
                return $this->webdriver->byId('edit-name');
            case 'baseTheme':
                return $this->webdriver->select($this->webdriver->byId('edit-base-theme'));
            case 'buttonSubmit':
                return $this->webdriver->byXPath('//form[@id="paddle-themer-create-new-theme-form"]//input[@id="edit-submit"]');
        }
        return parent::__get($property);
    }
}
