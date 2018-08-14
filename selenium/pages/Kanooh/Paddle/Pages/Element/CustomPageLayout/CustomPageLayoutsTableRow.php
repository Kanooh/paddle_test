<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\CustomPageLayout\CustomPageLayoutsTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\CustomPageLayout;

use Kanooh\Paddle\Pages\Element\ElementNotPresentException;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class CustomPageLayoutsTableRow
 *
 * @property string $name
 * @property string $linkEdit
 */
class CustomPageLayoutsTableRow extends Row
{
    /**
     * The webdriver element of the custom page layouts row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new CustomPageLayoutsTableRow.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The webdriver element of the custom page layouts table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }

    /**
     * Magic getter for the definition's properties.
     */
    public function __get($name)
    {
        $criteria = $this->element->using('xpath')->value('.//td');
        $cells = $this->element->elements($criteria);

        switch ($name) {
            case 'name':
                return $cells[0]->text();
                break;
            case 'linkEdit':
                return $this->element->byXPath('.//td[contains(@class, "links")]/a');
                break;
        }
        throw new ElementNotPresentException($name);
    }
}
