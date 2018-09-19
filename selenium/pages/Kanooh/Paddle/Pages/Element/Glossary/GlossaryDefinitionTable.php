<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Glossary\GlossaryDefinitionTable.
 */

namespace Kanooh\Paddle\Pages\Element\Glossary;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;

/**
 * Table containing all Glossary definitions.
 */
class GlossaryDefinitionTable extends Table
{
    /**
     * The webdriver element of the glossary definitions table.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new GlossaryDefinitionTable.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param string $xpath
     *   The xpath selector of the glossary definition table.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath;
        $this->element = $this->webdriver->byXPath($xpath);
    }

    /**
     * Returns a row based on the definition given.
     *
     * @param string $definition
     *   The definition word.
     *
     * @return GlossaryDefinitionTableRow
     *   The row for the given definition, or false if not found.
     */
    public function getRowByDefinition($definition)
    {
        $row_xpath = '//tr/td[contains(@class, "views-field-definition") and normalize-space(text())="' . $definition . '"]/..';
        $criteria = $this->webdriver->using('xpath')->value($this->xpathSelector . $row_xpath);
        $elements = $this->webdriver->elements($criteria);
        if (count($elements) > 0) {
            return new GlossaryDefinitionTableRow($this->webdriver, $elements[0]);
        }

        return false;
    }
}
