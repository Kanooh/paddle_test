<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Product\LegislationTable.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Product;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;

/**
 * Table containing the legistlation urls for a product on the node edit page.
 *
 * @property LegislationTableRow[] $rows
 *   An array with all the ligslation rows.
 */
class LegislationTable extends Table
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
                $xpath = $this->xpathSelector . '/tbody/tr';
                $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
                foreach ($elements as $element) {
                    $rows[] = new LegislationTableRow($this->webdriver, $element);
                }
                return $rows;
                break;
        }
        throw new \RuntimeException("The property with the name $name is not defined.");
    }
}
