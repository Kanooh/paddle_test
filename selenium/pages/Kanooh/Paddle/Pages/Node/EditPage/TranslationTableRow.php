<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\TranslationTableRow.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents a table row in the translations table on the node edit page.
 *
 * @property string $languageName
 *   The human-friendly name of the language of the row.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element|null $nodeLink
 *   The link to the translation for this language or null if there is no translation.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $translationLink
 *   The link to the edit page of the translation for this language or to add new translation.
 */
class TranslationTableRow extends Row
{

    /**
     * Constructs an TranslationTableRow object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $xpath_selector
     *   The XPath selector for this table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath_selector)
    {
        parent::__construct($webdriver);

        $this->xpathSelector = $xpath_selector;
    }

    /**
     * Magical getter method.
     *
     * @param string $property
     *   The name of the property we need.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The object of the property we need.
     *
     * @throws \Exception
     */
    public function __get($property)
    {
        switch ($property) {
            case 'languageName':
                $element = $this->webdriver->byXPath($this->xpathSelector . '/td[contains(@class, "language-name")]');
                return $element->text();
                break;
            case 'nodeLink':
            case 'translationLink':
                $type = str_replace('Link', '', $property);
                $xpath = $this->xpathSelector . '/td[contains(@class, "' . $type . '-link")]/a';
                try {
                    return $this->webdriver->byXPath($xpath);
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    return null;
                }
                break;
        }

        throw new \Exception("Property $property not found");
    }
}
