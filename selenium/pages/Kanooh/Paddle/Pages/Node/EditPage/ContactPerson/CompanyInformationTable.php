<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\CompanyInformationTable.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\ContactPerson;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Table containing the company information on the node edit page.
 *
 * @property CompanyInformationTableRow[] $rows
 */
class CompanyInformationTable extends Table
{
    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'rows':
                $rows = array();
                $xpath = $this->xpathSelector . '/tbody/tr[contains(@class, "draggable")]';
                $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
                foreach ($elements as $element) {
                    $rows[] = new CompanyInformationTableRow($this->webdriver, $element);
                }
                return $rows;
                break;
        }
        throw new \Exception("The property with the name $name is not defined.");
    }

    /**
     * Returns a table row based on the given node title and id.
     *
     * @param string $title
     *   The title of the organization.
     * @param int $nid
     *   The node id of the organization.
     *
     * @return CompanyInformationTableRow
     *   The correpsonding table row holding the company info.
     */
    public function getOrganizationTableRowByTitleAndNid($title, $nid)
    {
        $row_xpath = $this->xpathSelector . '//tr/td/div[contains(@class, "field-name-field-cp-organisation")]/div/div/input[@value="' . $title . ' (' . $nid . ')"]/../../../..';
        $element = $this->webdriver->element($this->webdriver->using('xpath')->value($row_xpath));

        return new CompanyInformationTableRow($this->webdriver, $element);
    }
}
