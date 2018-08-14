<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Formbuilder\PermissionsTable.
 */

namespace Kanooh\Paddle\Pages\Element\Formbuilder;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;

/**
 * List of permissions.
 *
 * @property PermissionsTableRow[] $rows
 */
class PermissionsTable extends Table
{
    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath;
        $this->element = $this->webdriver->byXPath($xpath);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'rows':
                $criteria = $this->element->using('xpath')->value('.//tbody//tr');
                $rows = $this->element->elements($criteria);
                $items = array();
                foreach ($rows as $row) {
                    $items[] = new PermissionsTableRow($this->webdriver, $row);
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
