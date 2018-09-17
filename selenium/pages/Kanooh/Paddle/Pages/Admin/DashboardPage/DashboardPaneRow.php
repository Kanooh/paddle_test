<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPaneRow.
 */

namespace Kanooh\Paddle\Pages\Admin\DashboardPage;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * A row in a dashboard pane.
 *
 * @property string $title
 * @property DashboardPaneRowLinks $links
 */
class DashboardPaneRow extends Row
{
    /**
     * The webdriver element for this row. This is a Views row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a DashboardPaneRow object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $xpath_selector
     *   The XPath selector for the Views row.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath_selector)
    {
        parent::__construct($webdriver);

        $this->xpathSelector = $xpath_selector;
    }

    /**
     * Magic getter.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'title':
                return $this->webdriver->byXPath($this->xpathSelector . '/div[contains(@class, "dashboard-title-1")]/span[contains(concat(" ", @class, " "), " title ")]')->text();
            case 'links':
                return new DashboardPaneRowLinks($this->webdriver, $this->xpathSelector);
        }
        throw new \Exception("Property $name is not defined.");
    }
}
