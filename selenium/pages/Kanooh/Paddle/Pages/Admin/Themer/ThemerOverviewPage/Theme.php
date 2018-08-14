<?php

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage;

/**
 * Class Theme
 * @package Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $edit
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $enable
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $title
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $preview
 * @property string $machineName
 */
class Theme
{

    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    public function __construct(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->element = $element;
    }

    public function __get($name)
    {
        switch ($name) {
            case 'edit':
                return $this->element->byXPath('.//a[@data-action = "edit"]');
            case 'enable':
                return $this->element->byXPath('.//a[@data-action = "enable"]');
            case 'title':
                return $this->element->byXPath('.//h3[contains(@class, "theme-title")]');
            case 'preview':
                return $this->element->byXPath('.//a[@data-action = "preview"]');
            case 'machineName':
                return $this->element->attribute('data-theme-name');
        }

        throw new \Exception('Property does not exist: ' . $name);
    }
}
