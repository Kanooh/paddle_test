<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentitiesTable.
 */

namespace Kanooh\Paddle\Pages\Element\SocialIdentities;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;

/**
 * Table containing a list of social identities.
 *
 * @property SocialIdentitiesTableRow[] $rows
 *   All of the items inside the table.
 */
class SocialIdentitiesTable extends Table
{
    /**
     * The webdriver element of the social identities table.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a new SocialIdentitiesTable.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param string $xpath
     *   The xpath selector of the social identities table instance.
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
                    $items[] = new SocialIdentitiesTableRow($this->webdriver, $row);
                }
                return $items;
                break;
        }
    }

    /**
     * Returns a row based on the psiid given.
     *
     * @param string $psiid
     *   Social identity ID of the row to return.
     *
     * @return SocialIdentitiesTableRow
     *   The row for the given psiid, or false if not found.
     */
    public function getRowByPsiid($psiid)
    {
        $criteria = $this->element->using('xpath')->value('.//tbody//tr[@data-identity-id="' . $psiid . '"]');
        $rows = $this->element->elements($criteria);
        if (empty($rows)) {
            return false;
        }
        return new SocialIdentitiesTableRow($this->webdriver, $rows[0]);
    }
}
