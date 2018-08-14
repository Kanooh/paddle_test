<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPane.
 */

namespace Kanooh\Paddle\Pages\Admin\DashboardPage;

use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * A dashboard pane.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $moreLink
 *   The 'more' link.
 * @property DashboardPaneRow[] $rows
 */
class DashboardPane extends Pane
{
    const ALL_IN_REVIEW = '//div[contains(@class, "pane-paddle-dashboard-status-panel-pane-all-in-review")]';
    const ALL_PUBLISHED = '//div[contains(@class, "pane-paddle-dashboard-status-panel-pane-all-published")]';
    const ALL_TO_CHECK = '//div[contains(@class, "pane-paddle-dashboard-status-panel-pane-all-to-check")]';
    const ALL_UNPUBLISHED = '//div[contains(@class, "pane-paddle-dashboard-status-panel-pane-all-unpublished")]';
    const MINE_IN_REVIEW = '//div[contains(@class, "pane-paddle-dashboard-status-panel-pane-mine-in-review")]';
    const MY_CONCEPTS = '//div[contains(@class, "pane-paddle-dashboard-status-panel-pane-my-concepts")]';
    const PLANNED_PUBLICATIONS = '//div[contains(@class, "pane-paddle-dashboard-status-panel-pane-planned-publications")]';
    const PLANNED_UNPUBLICATIONS = '//div[contains(@class, "pane-paddle-dashboard-status-panel-pane-planned-unpublications")]';

    /**
     * Constructs a DashboardPane.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $xpath_selector
     *   The XPath selector for the dashboard pane. You can use the constants.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath_selector)
    {
        parent::__construct($webdriver, '', $xpath_selector);
    }

    /**
     * Magic getter.
     *
     * @param string $name
     *   The name of the property to get.
     *
     * @return string
     *   The requested root path.
     *
     * @throws \Exception
     *   Thrown when any property other than '404_root_path' is requested.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'moreLink':
                $xpath = $this->xpathSelector .
                    '//div[contains(@class, "view-footer")]/a[./span[contains(text(), "More")]]';
                return $this->webdriver->byXPath($xpath);
            case 'rows':
                $rows = array();
                $xpath = './/div[contains(@class, "views-row")]';
                $elements = $this->webdriver->elements($this->getWebdriverElement()->using('xpath')->value($xpath));
                foreach ($elements as $element) {
                    $xpath = $this->xpathSelector .
                        '//div[contains(concat(" ", @class, " "), " ' . $this->getViewsRowClass($element) . ' ")]';
                    $rows[] = new DashboardPaneRow($this->webdriver, $xpath);
                }

                return $rows;
        }
        throw new \Exception("Property $name is not defined.");
    }

    /**
     * Returns the row with the given title.
     *
     * @param string $title
     *   The title of the row to return. Leading and trailing whitespace will be
     *   trimmed to account for spacing in the HTML output.
     *
     * @return DashboardPaneRow
     *   The requested row.
     *
     * @throws DashboardPaneRowNotPresentException
     *   Thrown when the requested row is not present.
     */
    public function getRowByTitle($title)
    {
        // Ignore leading and trailing whitespace.
        $title = trim($title);

        try {
            $xpath = $this->xpathSelector . '//div[contains(@class, "views-row")]//span[@title="' . $title . '"]/../..';
            return new DashboardPaneRow($this->webdriver, $xpath);
        } catch (\Exception $e) {
            throw new DashboardPaneRowNotPresentException($title);
        }
    }

    /**
     * Returns the row class for a given Views row element.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   A webdriver element representing a Views row.
     *
     * @return string
     *   The Views row class, for example 'views-row-1'.
     */
    protected function getViewsRowClass($element)
    {
        $classes = $element->attribute('class');
        $matches = array();
        preg_match('/views-row-\d+/', $classes, $matches);
        return $matches[0];
    }
}
