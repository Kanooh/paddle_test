<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Listing\ArchivePage\ArchivePageContentTableRowLinks.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Listing\ArchivePage;

use Kanooh\Paddle\Pages\Element\Links\Links;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The action links in an archive overview table content table row.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDelete
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkRestore
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkAdminView
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkTitle
 */
class ArchivePageContentTableRowLinks extends Links
{
    /**
     * The XPath selector for the table row that contains the links.
     *
     * @var string
     */
    protected $rowXPath;

    /**
     * Constructs an ArchivePageContentTableRowLinks object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $table_row_xpath
     *   The XPath selector for the table row that contains the links.
     */
    public function __construct(WebDriverTestCase $webdriver, $table_row_xpath)
    {
        parent::__construct($webdriver);
        $this->rowXPath = $table_row_xpath;
    }

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return array(
            'Title' => array(
                'xpath' => $this->rowXPath . '//td[contains(@class, "views-field-title")]/a'
            ),
            'Restore' => array(
                'xpath' => $this->rowXPath . '//td//a[contains(@class, "ui-icon-restore")]',
            ),
            'AdminView' => array(
                'xpath' => $this->rowXPath . '//td//a[contains(@class, "ui-icon-visit-be")]',
            ),
            'Delete' => array(
                'xpath' => $this->rowXPath . '//td//a[contains(@class, "ui-icon-delete")]',
            ),
        );
    }
}
