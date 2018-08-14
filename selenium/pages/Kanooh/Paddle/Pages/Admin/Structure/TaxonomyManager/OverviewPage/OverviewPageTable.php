<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPageTable.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage;

use \Kanooh\Paddle\Pages\Element\Table\Table;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class OverviewPageTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//form[@id="paddle-taxonomy-manager-vocabulary-overview-form"]//table[@id="taxonomy"]';

    /**
     * Finds table <tr>s by the term title in them and returns their element
     * objects.
     *
     * @param string $title
     *   The name of the term. It will be used in the xpath to find the term
     *   rows (if we have multiple terms with the same name).
     *
     * @return OverviewPageTableRow
     *   The <tr> we are looking for.
     */
    public function getTermRowsByTitle($title)
    {
        $row_xpath = $this->xpathSelector . '//tr/td[text()="' . $title . '"]/..';
        return new OverviewPageTableRow($this->webdriver, $row_xpath);
    }

    /**
     * Finds table <tr>s by the term id in them and returns their element
     * objects.
     *
     * @param int $tid
     *   The id of the term. It will be used in the xpath to find the term rows
     *   (if we have multiple terms with the same name).
     *
     * @return OverviewPageTableRow
     *   The <tr> we are looking for.
     */
    public function getTermRowsByTid($tid)
    {
        $row_xpath = $this->xpathSelector . '//tr[@data-term-id="' . $tid . '"]';
        $this->webdriver->waitUntilElementIsDisplayed($row_xpath);
        return new OverviewPageTableRow($this->webdriver, $row_xpath);
    }

    /**
     * Moves a term to another position using the keyboard.
     *
     * @param  OverviewPageTableRow $source_row
     *   The dragged row.
     * @param  int $target_position
     *   The position among the siblings to which the source row should be moved.
     * @param  int|null $parent_tid
     *   The parent tid of the term source row.
     * @param  bool $make_child
     *   Whether the menu item should become child or not.
     */
    public function changeTermPosition($source_row, $target_position, $parent_tid = null, $make_child = false)
    {
        $source_row->focusTableDrag();
        $initial_position = $this->getTermPositionByTid($source_row->termId, $parent_tid);

        $key = $initial_position < $target_position ? Keys::DOWN : Keys::UP;
        $keys = str_repeat($key, abs($target_position - $initial_position));
        if ($make_child) {
            $keys .= Keys::RIGHT;
        }
        $this->webdriver->keys($keys);
    }

    /**
     * Returns the position of a term in the overview table.
     *
     * This is the numeric position, counting starting with 0 from the top.
     *
     * @param int $tid
     *   The tid of the term to find.
     * @param int|null $parent_tid
     *   The parent tid of the term to find.
     *
     * @return int | null
     *   The position of the item in the table. If the term was not found
     *   it returns null.
     */
    public function getTermPositionByTid($tid, $parent_tid = null)
    {
        /* @var $row \PHPUnit_Extensions_Selenium2TestCase_Element */
        $position = 0;
        $xpath = $this->xpathSelector . '/tbody/tr';
        if (null !== $parent_tid) {
            $xpath .= '[contains(@class, "paddle-big-vocabulary-parent-tid-' . $parent_tid . '")]';
        }
        foreach ($this->webdriver->elements($this->webdriver->using('xpath')->value($xpath)) as $row) {
            if ($tid == $row->attribute('data-term-id')) {
                return $position;
            }
            $position++;
        }

        return null;
    }
}
