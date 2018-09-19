<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation\PlacesTable.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Table containing the places on the node edit page.
 *
 * @property PlacesTableRow[] $rows
 */
class PlacesTable extends Table
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
                    $rows[] = new PlacesTableRow($this->webdriver, $element);
                }
                return $rows;
                break;
        }
        throw new \Exception("The property with the name $name is not defined.");
    }
}
