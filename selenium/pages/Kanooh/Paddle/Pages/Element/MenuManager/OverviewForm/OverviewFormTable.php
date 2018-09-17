<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\MenuManager\OverviewForm\OverviewFormTable.
 */

namespace Kanooh\Paddle\Pages\Element\MenuManager\OverviewForm;

use \Kanooh\Paddle\Pages\Element\Table\Table;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * Class representing the table in the Menu overview form.
 */
class OverviewFormTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//table[@id="menu-overview"]';

    /**
     * Finds table <tr>s by the menu item title in them and returns their element
     * objects.
     *
     * @param string $title
     *   The name of the  menu item. It will be used in the xpath to find the
     *   menu item rows (if we have multiple menu item with the same name).
     *
     * @return OverviewFormTableRow
     *   The <tr> we are looking for.
     */
    public function getMenuItemRowByTitle($title)
    {
        $row_xpath = $this->xpathSelector . '//tr/td/a/span[text()="' . $title . '"]/../../..';
        return new OverviewFormTableRow($this->webdriver, $row_xpath);
    }

    /**
     * Finds table <tr> by the mlid in them and returns their Row object.
     *
     * @param int $mlid
     *   The id of the menu item. It will be used in the xpath to find the term
     *   rows (if we have multiple terms with the same name).
     *
     * @return OverviewFormTableRow
     *   The <tr> we are looking for.
     */
    public function getMenuItemRowByMlid($mlid)
    {
        $row_xpath = $this->xpathSelector . '//tr[contains(@class, "mlid-' . $mlid . '")]';
        $this->webdriver->waitUntilElementIsDisplayed($row_xpath);
        return new OverviewFormTableRow($this->webdriver, $row_xpath);
    }

    /**
     * Returns the position of a menu item in the menu overview table.
     *
     * This is the numeric position, counting starting with 0 from the top.
     *
     * @param int $mlid
     *   The mlid of the menu item to find.
     * @param int $parent_mlid
     *   The mlid of the parent menu item of the menu item to find.
     *
     * @return int | null
     *   The position of the item in the menu. If the menu item was not found
     *   it returns null.
     */
    public function getMenuItemPositionByMlid($mlid, $parent_mlid = '')
    {
        /* @var $row \PHPUnit_Extensions_Selenium2TestCase_Element */
        $position = 0;
        $xpath = '//table[@id = "menu-overview"]/tbody/tr';
        if ($parent_mlid) {
            $xpath .= '[contains(@class, "paddle-big-menu-parent-mlid-' . $parent_mlid . '")]';
        }
        foreach ($this->webdriver->elements($this->webdriver->using('xpath')->value($xpath)) as $row) {
            if (in_array('mlid-' . $mlid, explode(' ', $row->attribute('class')))) {
                return $position;
            }
            $position++;
        }

        return null;
    }

    /**
     * Moves a menu item to another position using the keyboard.
     *
     * @param  OverviewFormTableRow $source_row
     *   The dragged row.
     * @param  int $target_position
     *   The position among the siblings to which the source row should be moved.
     * @param  int $parent_mlid
     *   The mlid of the parent menu item of the source row.
     * @param  bool $make_child
     *   Whether the menu item should become child or not.
     */
    public function changeMenuItemPosition($source_row, $target_position, $parent_mlid = '', $make_child = false)
    {
        $source_row->focusTableDrag();
        $initial_position = $this->getMenuItemPositionByMlid($source_row->getMlid(), $parent_mlid);

        $key = $initial_position < $target_position ? Keys::DOWN : Keys::UP;
        $keys = str_repeat($key, abs($target_position - $initial_position));
        if ($make_child) {
            $keys .= Keys::RIGHT;
        }
        $this->webdriver->keys($keys);
    }

    /**
     * Loops over the table rows and returns all the mlids of the menu items.
     * @return array
     *   Array with the mlids of the found menu items.
     */
    public function getAllMenuItemMlids()
    {
        $mlids = array();
        $xpath = '//table[@id = "menu-overview"]/tbody/tr';
        foreach ($this->webdriver->elements($this->webdriver->using('xpath')->value($xpath)) as $row) {
            $mlid = '';
            $classes = explode(' ', $row->attribute('class'));
            foreach ($classes as $class) {
                if (strpos($class, 'mlid-') !== false) {
                    $mlids[] = str_replace('mlid-', '', $class);
                }
            }
        }

        return $mlids;
    }
}
