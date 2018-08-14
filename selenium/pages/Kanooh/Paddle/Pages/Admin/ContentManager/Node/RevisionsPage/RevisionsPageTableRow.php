<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevisionsPage\RevisionsPageTableRow.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevisionsPage;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents a table row on the Revisions page.
 */
class RevisionsPageTableRow extends Row
{
    /**
     * The action links on the table row.
     *
     * @var RevisionsPageTableRowLinks
     */
    public $links;

    /**
     * Constructs an RevisionsPageTableRow object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $xpath_selector
     *   The XPath selector for this table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath_selector)
    {
        parent::__construct($webdriver);

        $this->xpathSelector = $xpath_selector;
        $this->links = new RevisionsPageTableRowLinks($this->webdriver, $xpath_selector);
    }
}
