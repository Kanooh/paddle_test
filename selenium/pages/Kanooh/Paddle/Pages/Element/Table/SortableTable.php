<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Table\SortableTable.
 */

namespace Kanooh\Paddle\Pages\Element\Table;

use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * Class representing a table with tabledrag behaviour.
 *
 * @package Kanooh\Paddle\Pages\Element\Table
 */
class SortableTable extends Table
{
    /**
     * Drags a row from a position to another.
     *
     * @param int $from
     *   The zero-based index of the row we want to drag.
     * @param int $to
     *   The zero-based position where we want to drag the row to.
     */
    public function dragRow($from, $to)
    {
        $this->focusTableRowDrag($from);

        $key = $from < $to ? Keys::DOWN : Keys::UP;
        $keys = str_repeat($key, abs($to - $from));

        $this->webdriver->keys($keys);

        // Remove focus from the handler.
        $this->getWebdriverElement()->click();

        // Wait for the drag message to be shown.
        $this->webdriver->waitUntilTextIsPresent(
            'Changes made in this table will not be saved until the form is submitted'
        );
    }

    /**
     * Helper method to focus the drag handle for a specific row.
     *
     * @param int $row_index
     *   The zero-based index of the row that we are going to focus.
     */
    public function focusTableRowDrag($row_index)
    {
        // Css indexes start from 1.
        $index = $row_index + 1;
        $this->webdriver->execute(
            array(
                'script' => "document.querySelector('tr:nth-child(" . $index . ") a.tabledrag-handle').focus();",
                'args' => array(),
            )
        );
    }
}
