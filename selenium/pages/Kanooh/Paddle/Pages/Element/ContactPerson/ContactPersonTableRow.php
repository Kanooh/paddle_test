<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\ContactPerson\ContactPersonTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\ContactPerson;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class ContactPersonTableRow
 *
 * @property string $title
 * @property string $function
 * @property string $functionFieldCollection
 * @property string $organisation
 * @property string $organisationFieldCollection
 * @property string $status
 */
class ContactPersonTableRow extends Row
{
    /**
     * The webdriver element of the contact person table row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new ContactPersonTableRow.
     *
     * @param WebDriverTestCase $webdriver
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'title':
                $cell = $this->element->byXPath('.//td[contains(@class, "views-field-title")]');
                return $cell->text();
                break;

            case 'function':
                $cell = $this->element->byXPath('.//td[contains(@class, "views-field-field-paddle-cp-function")]');
                return $cell->text();
                break;

            case 'functionFieldCollection':
                $cell = $this->element->byXPath('.//td[contains(@class, "views-field-field-cp-function")]');
                return $cell->text();
                break;

            case 'organisation':
                $cell = $this->element->byXPath('.//td[contains(@class, "views-field-field-paddle-cp-ou-level-3")]');
                return $cell->text();
                break;

            case 'organisationFieldCollection':
                $cell = $this->element->byXPath('.//td[contains(@class, "views-field-field-cp-organisation")]');
                return $cell->text();
                break;

            case 'status':
                $cell = $this->element->byXPath('.//td[contains(@class, "views-field-state")]');
                return $cell->text();
                break;
        }

        throw new \Exception("The property with the name $name is not defined.");
    }
}
