<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\ContactPerson\ContactPersonTable.
 */

namespace Kanooh\Paddle\Pages\Element\ContactPerson;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;

/**
 * List of contact persons.
 *
 * @property ContactPersonTableRow[] $rows
 */
class ContactPersonTable extends Table
{
    /**
     * The webdriver element of the Contact Person table.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a ContactPersonTable.
     *
     * @param WebDriverTestCase $webdriver
     * @param string $xpath
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
                    $items[] = new ContactPersonTableRow($this->webdriver, $row);
                }

                return $items;
                break;
        }
        throw new \Exception("The property with the name $name is not defined.");
    }

    /**
     * Returns a row based on the nid given.
     *
     * @param int $nid
     *   The node id to search for.
     *
     * @return ContactPersonTableRow
     *   Returns the row corresponding the node id.
     */
    public function getRowByNid($nid)
    {
        $criteria = $this->element->using('xpath')->value('.//tbody//tr[contains(concat(" ", normalize-space(@class), " "), " node-' . $nid . ' ")]');
        $rows = $this->element->elements($criteria);
        if (empty($rows)) {
            return false;
        }

        return new ContactPersonTableRow($this->webdriver, $rows[0]);
    }
}
