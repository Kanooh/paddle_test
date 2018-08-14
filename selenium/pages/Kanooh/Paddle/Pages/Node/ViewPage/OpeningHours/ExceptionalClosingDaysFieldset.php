<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\OpeningHours\ExceptionalClosingDaysFieldset.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\OpeningHours;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class ExceptionalClosingDaysFieldset.
 *
 * @package Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $header
 * @property ClosingDay[] $closingDays
 */
class ExceptionalClosingDaysFieldset
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
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'header':
                return $this->element->byXPath('.//a[@class = "fieldset-title"]');
                break;
            case 'closingDays':
                $rows = array();

                $elements = $this->element->elements($this->element->using('xpath')->value('.//div[@class = "closing-day"]'));
                foreach ($elements as $element) {
                    $rows[] = new ClosingDay($this->webdriver, $element);
                }

                return $rows;
                break;
        }

        throw new \Exception('Property does not exist: ' . $name);
    }
}
