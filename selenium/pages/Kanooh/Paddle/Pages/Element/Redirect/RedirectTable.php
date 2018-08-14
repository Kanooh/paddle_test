<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Redirect\RedirectTable.
 */

namespace Kanooh\Paddle\Pages\Element\Redirect;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;

/**
 * List of redirects.
 *
 * @property RedirectTableRow[] $rows
 *   All of the items inside the table.
 */
class RedirectTable extends Table
{
    /**
     * The webdriver element of the Redirect table.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new RedirectTable.
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
                    $items[] = new RedirectTableRow($this->webdriver, $row);
                }
                return $items;
                break;
        }
    }

    /**
     * Returns a row based on the rid given.
     *
     * @param string $rid
     *   Redirect ID of the row to return.
     *
     * @return RedirectTableRow
     *   The row for the given rid, or false if not found.
     */
    public function getRowByRid($rid)
    {
        $criteria = $this->element->using('xpath')->value('.//tbody//tr[@data-redirect-id="' . $rid . '"]');
        $rows = $this->element->elements($criteria);
        if (empty($rows)) {
            return false;
        }
        return new RedirectTableRow($this->webdriver, $rows[0]);
    }

    /**
     * Returns the actual number of rows in a table.
     *
     * Table::getNumberOfRows() gives back a row if an empty text is set as
     * well.
     *
     * @return int
     *   The actual number of rows.
     */
    public function getRedirectTableRowCount()
    {
        $count = count($this->rows);

        // Check if an empty text is set. If so, we do not take it as a redirect
        // row on its own. So we detract 1 of the total count.
        $xpath = $this->xpathSelector . '//td[contains(@class, "empty message")]';
        $elements = $this->element->elements($this->element->using('xpath')->value($xpath));
        $empty = (bool) count($elements);

        if ($empty) {
            $count -= 1;
        }

        return $count;
    }

    /**
     * Check if the filter field is present.
     *
     * @return bool
     *   TRUE if the filter field is present, FALSE otherwise.
     */
    public function checkRedirectSelectboxPresent()
    {
        $xpath = '//input[@class="redirect-list-table form-checkbox"]';
        $elements = $this->element->elements($this->element->using('xpath')->value($xpath));
        return (bool) count($elements);
    }
}
