<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\CustomPageLayout\CustomPageLayoutsTable.
 */

namespace Kanooh\Paddle\Pages\Element\CustomPageLayout;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;
use Rhumsaa\Uuid\Console\Exception;

/**
 * Class representing the table of Custom page layouts on the Paddle Custom Page
 * Layout config page.
 */
class CustomPageLayoutsTable extends Table
{
    /**
     * The webdriver element of the custom page layouts table.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new CustomPageLayoutsTable.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param string $xpath
     *   The xpath selector of the custom page layouts table.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath;
        $this->element = $this->webdriver->byXPath($xpath);
    }

    /**
     * Returns a row based on the name given.
     *
     * @param string $name
     *   The name of the custom page layout.
     *
     * @return CustomPageLayoutsTableRow|bool
     *   The row for the given name, or false if not found.
     */
    public function getRowByName($name)
    {
        $row_xpath = '//tr/td[normalize-space(text())="' . strtolower($name) . '"]/..';
        $criteria = $this->webdriver->using('xpath')->value($this->xpathSelector . $row_xpath);

        try {
            $element = $this->webdriver->element($criteria);
            return new CustomPageLayoutsTableRow($this->webdriver, $element);
        } catch (Exception $e) {
            return false;
        }
    }
}
