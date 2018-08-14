<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Glossary\GlossaryDefinition.
 */

namespace Kanooh\Paddle\Pages\Element\Glossary;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * A glossary definition as shown in the glossary overview.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_S $definition
 *   The definition as a Selenium element.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $description
 *   The description as a Selenium element.
 */
class GlossaryDefinition
{
    /**
     * The Selenium web driver element representing the definition.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Constructs a GlossaryDefinition object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium web driver element representing the definition.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * Magic getter for the glossary definition.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'definition':
                return $this->element->byXPath('.//div[contains(@class, "views-field-definition")]');
            case 'description':
                return $this->element->byXPath('.//div[contains(@class, "views-field-field-glossary-description")]');
        }

        throw new \Exception("The property $property is undefined.");
    }
}
