<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Glossary\GlossaryDefinitionTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\Glossary;

use Kanooh\Paddle\Pages\Element\ElementNotPresentException;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class GlossaryDefinitionTableRow
 *
 * @property string $definition
 *   The word of the definition.
 * @property int $gdid
 *   The glossary definition ID.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkEdit
 *   The definition's edit link.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDelete
 *   The definition's delete link.
 */
class GlossaryDefinitionTableRow extends Row
{
    /**
     * The webdriver element of the glossary definition table row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new GlossaryDefinitionTableRow.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The webdriver element of the definition table row.
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
            case 'definition':
                $cell = $this->element->byXPath('.//td[contains(@class, "views-field-definition")]');
                return $cell->text();
                break;
            case 'gdid':
                return $this->element->attribute('data-definition-id');
                break;
            case 'linkEdit':
                return $this->element->byXPath('.//td//a[contains(@class, "glossary-definition-edit-link")]');
                break;
            case 'linkDelete':
                return $this->element->byXPath('.//td//a[contains(@class, "glossary-definition-delete-link")]');
                break;
        }
        throw new ElementNotPresentException($name);
    }
}
