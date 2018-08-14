<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\OpeningHours\OpeningHoursTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\OpeningHours;

use Kanooh\Paddle\Pages\Element\ElementNotPresentException;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class OpeningHoursTableRow
 *
 * @property string $title
 * @property int $ohid
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkEdit
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDelete
 */
class OpeningHoursTableRow extends Row
{
    /**
     * The webdriver element of the opening hour table row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new OpeningHoursTableRow.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The webdriver element of the opening hour table row.
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
        switch ($name) {
            case 'title':
                $cell = $this->element->byXPath('.//td[contains(@class, "views-field-title")]');
                return $cell->text();
                break;
            case 'ohsid':
                return $this->element->attribute('data-opening-hour-id');
                break;
            case 'linkEdit':
                return $this->element->byXPath('.//td//a[contains(@class, "opening-hour-edit-link")]');
                break;
            case 'linkDelete':
                return $this->element->byXPath('.//td//a[contains(@class, "opening-hour-delete-link")]');
                break;
        }
        throw new ElementNotPresentException($name);
    }
}
