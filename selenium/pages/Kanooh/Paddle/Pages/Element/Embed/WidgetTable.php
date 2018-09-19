<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Embed\WidgetTable.
 */

namespace Kanooh\Paddle\Pages\Element\Embed;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;

/**
 * List of widgets.
 *
 * @property WidgetTableRow[] $rows
 *   All of the items inside the table.
 */
class WidgetTable extends Table
{
    /**
     * The webdriver element of the widget table.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new WidgetTable.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param string $xpath
     *   The xpath selector of the widget table instance.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath;
        $this->element = $this->webdriver->byXPath($xpath);
    }

    /**
     * Magic getter for children elements.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'rows':
                $criteria = $this->element->using('xpath')->value('.//tbody//tr');
                $rows = $this->element->elements($criteria);
                $items = array();
                foreach ($rows as $row) {
                    $items[] = new WidgetTableRow($this->webdriver, $row);
                }
                return $items;
                break;
        }
    }

    /**
     * Returns a row based on the wid given.
     *
     * @param string $wid
     *   Widget ID of the row to return.
     *
     * @return WidgetTableRow
     *   The row for the given wid, or false if not found.
     */
    public function getRowByWid($wid)
    {
        $criteria = $this->element->using('xpath')->value('.//tbody//tr[@data-widget-id="' . $wid . '"]');
        $rows = $this->element->elements($criteria);
        if (empty($rows)) {
            return false;
        }
        return new WidgetTableRow($this->webdriver, $rows[0]);
    }
}
