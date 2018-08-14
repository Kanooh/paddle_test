<?php

/**
 * @file
 * Contains \Kanooh\Paddle\pages\Admin\Themer\ThemerEditPage\Section.
 */

namespace Kanooh\Paddle\pages\Admin\Themer\ThemerEditPage;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class Section
 * @package Kanooh\Paddle\pages\Admin\Themer\ThemerEditPage
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $header
 */
class Section
{

    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    public function __get($name)
    {
        switch ($name) {
            case 'header':
                return $this->element->byXPath('.//h2');
        }

        throw new \Exception('Property does not exist: ' . $name);
    }
}
