<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPaneRowLinks.
 */

namespace Kanooh\Paddle\Pages\Admin\DashboardPage;

use Kanooh\Paddle\Pages\Element\Links\Links;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The action links in a dashboard pane row.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkView
 *   The link to the frontend view of the node.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkAdminView
 *   The link to the administrative node view.
 */
class DashboardPaneRowLinks extends Links
{
    /**
     * The XPath selector for the dashboard pane row that contains the links.
     *
     * @var string
     */
    protected $dashboardPaneRowXPathSelector;

    /**
     * Constructs a DashboardPaneRowLinks object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $dashboard_pane_row_xpath_selector
     *   The XPath selector for the dashboard pane row that contains the links.
     */
    public function __construct(WebDriverTestCase $webdriver, $dashboard_pane_row_xpath_selector)
    {
        parent::__construct($webdriver);

        $this->dashboardPaneRowXPathSelector = $dashboard_pane_row_xpath_selector;
    }

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return array(
            'View' => array(
                'xpath' => $this->dashboardPaneRowXPathSelector . '//span[contains(@class, "view-node")]/a',
            ),
            'AdminView' => array(
                'xpath' => $this->dashboardPaneRowXPathSelector . '//span[contains(@class, "admin-view-link")]/a',
            ),
        );
    }
}
