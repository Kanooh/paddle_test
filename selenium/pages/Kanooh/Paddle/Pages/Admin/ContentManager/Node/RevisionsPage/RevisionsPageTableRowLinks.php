<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevisionsPage\RevisionsPageTableRowLinks.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevisionsPage;

use Kanooh\Paddle\Pages\Element\Links\Links;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The action links in the revisions table row.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkView
 *   The 'view' action link.
 */
class RevisionsPageTableRowLinks extends Links
{
    /**
     * The XPath selector for the table row that contains the links.
     *
     * @var string
     */
    protected $revisionsPageTableRowXPathSelector;

    /**
     * Constructs a RevisionsPageTableRowLinks object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $revisions_page_table_row_xpath_selector
     *   The XPath selector for the table row that contains the links.
     */
    public function __construct(WebDriverTestCase $webdriver, $revisions_page_table_row_xpath_selector)
    {
        parent::__construct($webdriver);

        $this->revisionsPageTableRowXPathSelector = $revisions_page_table_row_xpath_selector;
    }

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return array(
            'View' => array(
                'xpath' => $this->revisionsPageTableRowXPathSelector . '//td/a[contains(@class, "view-revision")]'
            ),
        );
    }
}
