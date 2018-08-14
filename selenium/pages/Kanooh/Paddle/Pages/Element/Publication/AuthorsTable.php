<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Publication\AuthorsTable.
 */

namespace Kanooh\Paddle\Pages\Element\Publication;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;

/**
 * List of authors.
 *
 * @property AuthorsTableRow[] $rows
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addAnotherItem
 */
class AuthorsTable extends Table
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
                    $items[] = new AuthorsTableRow($this->webdriver, $row);
                }

                return $items;
                break;
            case 'addAnotherItem':
                return $this->webdriver->byName('field_paddle_kce_authors_add_more');
                break;
        }
        throw new \Exception("The property with the name $name is not defined.");
    }
}
