<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage\ExceptionalClosingDaysFieldset.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class ExceptionalOpeningHoursDaysFieldset.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $header
 * @property ExceptionalOpeningHoursDay[] $days
 */
class ExceptionalOpeningHoursDaysFieldset
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
            case 'days':
                $rows = array();

                $elements = $this->element->elements($this->element->using('xpath')->value('.//div[contains(@class, "weekday")]'));
                foreach ($elements as $element) {
                    $rows[] = new ExceptionalOpeningHoursDay($this->webdriver, $element);
                }

                return $rows;
                break;
        }

        throw new \Exception('Property does not exist: ' . $name);
    }

    /**
     * Gets the weekday by the weekday index.
     *
     * @param int $index
     *   The index of the weekday.
     *
     * @return ExceptionalOpeningHoursDay
     *   The day to fill out the hours for.
     */
    public function getDayByWeekdayIndex($index)
    {
        foreach ($this->days as $day) {
            if ($day->weekday == $index) {
                return $day;
            }
        }
    }
}
