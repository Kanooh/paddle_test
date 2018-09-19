<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Content\ContentLockPage\ContentLockPageRow.
 */
namespace Kanooh\Paddle\Pages\Admin\Content\ContentLockPage;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The table row element class Content Lock overview page.
 */
class ContentLockPageRow extends Row
{
    /**
     * Constructs a ContentLockPageRow object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $xpath_selector
     *   The XPath selector representing a table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath_selector)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath_selector;
    }
}
