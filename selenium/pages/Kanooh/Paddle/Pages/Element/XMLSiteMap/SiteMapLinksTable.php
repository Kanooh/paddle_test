<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\XMLSiteMap\SiteMapLinksTable.
 */

namespace Kanooh\Paddle\Pages\Element\XMLSiteMap;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;
use Rhumsaa\Uuid\Console\Exception;

/**
 * Class representing the table of XML Site Map Links on the Paddle XML Site
 * Map config page.
 */
class SiteMapLinksTable extends Table
{
    /**
     * The webdriver element of the XML site map links table.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new SiteMapLinksTable.
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
     * Returns a row based on the language given.
     *
     * @param string $language
     *   The language of the XML site map
     *
     * @return SiteMapLinksTableRow|bool
     *   The row for the language, or false if not found.
     */
    public function getRowByLanguage($language)
    {
        $row_xpath = '//tr[contains(@class, "xml-site-map-' . $language .'")]';
        $criteria = $this->webdriver->using('xpath')->value($this->xpathSelector . $row_xpath);

        try {
            $element = $this->webdriver->element($criteria);
            return new SiteMapLinksTableRow($this->webdriver, $element);
        } catch (Exception $e) {
            return false;
        }
    }
}
