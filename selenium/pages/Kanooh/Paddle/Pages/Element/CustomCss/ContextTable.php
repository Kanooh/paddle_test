<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\CustomCss\ContextTable.
 */

namespace Kanooh\Paddle\Pages\Element\CustomCss;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * List of contexts.
 *
 * @property ContextTableRow[] $rows
 */
class ContextTable extends Table
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
                    $items[] = new ContextTableRow($this->webdriver, $row);
                }
                return $items;
                break;
        }
    }
}
