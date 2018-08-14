<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Themer\FontPlugin.
 */

namespace Kanooh\Paddle\Pages\Element\Themer;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * FontPlugin class.
 */
class FontPlugin extends Element
{
    /**
     * The Selenium webdriver element.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public $element;

    /**
     * The name of the element.
     *
     * @var string
     */
    public $elementName;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }
}
