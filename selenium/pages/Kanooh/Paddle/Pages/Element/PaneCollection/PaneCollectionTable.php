<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PaneCollection\PaneCollectionTable.
 */

namespace Kanooh\Paddle\Pages\Element\PaneCollection;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;

/**
 * Table containing all pane collections.
 */
class PaneCollectionTable extends Table
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
     * Returns a row based on the title given.
     *
     * @param string $title
     *   The title of the pane collection.
     *
     * @return PaneCollectionTableRow|bool
     *   The row for the given title, or false if not found.
     */
    public function getRowByTitle($title)
    {
        $row_xpath = './/tr/td[contains(@class, "views-field-title") and contains(text(),"' . $title . '")]/..';
        try {
            return new PaneCollectionTableRow($this->webdriver, $this->element->byXPath($row_xpath));
        } catch (\Exception $e) {
            return false;
        }
    }
}
