<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Table\Table.
 */

namespace Kanooh\Paddle\Pages\Element\Table;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * Generic representation of a table.
 */
class Table extends Element
{

    /**
     * Finds how many <tr>s the table has (visible).
     *
     * @param bool $ignore_header_row
     *   Whether to count the header row towards the total count of rows.
     * @return int
     *   The number of <tr>s.
     */
    public function getNumberOfRows($ignore_header_row = false)
    {
        $rows_xpath = $ignore_header_row ? $rows_xpath = $this->xpathSelector . '/tbody//tr' : $this->xpathSelector . '//tr';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($rows_xpath));
        return count($elements);
    }

    /**
     * Returns the row found on the passed position.
     *
     * @param int $position
     *   The position of the row starting from 0.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element | null
     *   The row element of null if it doesn't exist.
     */
    public function getRowByPosition($position)
    {
        $xpath = $this->xpathSelector . '/tbody/tr';
        $rows = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return isset($rows[$position]) ? $rows[$position] : null;
    }
}
