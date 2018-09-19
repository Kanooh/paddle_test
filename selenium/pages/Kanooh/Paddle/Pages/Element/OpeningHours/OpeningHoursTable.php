<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\OpeningHours\OpeningHoursTable.
 */

namespace Kanooh\Paddle\Pages\Element\OpeningHours;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;
use Rhumsaa\Uuid\Console\Exception;

/**
 * Table containing all opening hours.
 */
class OpeningHoursTable extends Table
{
    /**
     * The webdriver element of the opening hours table.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new OpeningHoursTable.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param string $xpath
     *   The xpath selector of the opening hours table.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath;
        $this->element = $this->webdriver->byXPath($xpath);
    }

    /**
     * Returns a row based on the opening hour given.
     *
     * @param string $title
     *   The opening hour title.
     *
     * @return OpeningHoursTableRow|bool
     *   The row for the given opening hour, or false if not found.
     */
    public function getRowByTitle($title)
    {
        $row_xpath = '//tr/td[contains(@class, "views-field-title") and normalize-space(text())="' . $title . '"]/..';
        $criteria = $this->webdriver->using('xpath')->value($this->xpathSelector . $row_xpath);

        try {
            $element = $this->webdriver->element($criteria);
            return new OpeningHoursTableRow($this->webdriver, $element);
        } catch (Exception $e) {
            return false;
        }
    }
}
