<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\OpeningHours\ClosingDay.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\OpeningHours;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class ClosingDay.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $date
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $description
 */
class ClosingDay
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
    public function __construct(WebDriverTestCase $webdriver, $element)
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
            case 'date':
                return $this->element->byXPath('.//div[contains(@class, "closing-day-date")]');
                break;
            case 'description':
                return $this->element->byXPath('.//div[contains(@class, "closing-day-description")]');
                break;
        }
        throw new \RuntimeException("The property with the name $name is not defined.");
    }
}
