<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\LanguageSwitcher\FrontendLanguageSwitcher.
 */

namespace Kanooh\Paddle\Pages\Element\LanguageSwitcher;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The Language switcher as displayed on the front-end.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $button
 *   The button to toggle the language switcher.
 */
class FrontendLanguageSwitcher extends LanguageSwitcher
{
    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'button':
                return $this->element->byId('language-switcher-btn');
                break;
        }

        throw new \Exception("The property $property is undefined.");
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveLanguage()
    {
        return $this->button->text();
    }

    /**
     * {@inheritdoc}
     */
    public function switchLanguage($lang_code)
    {
        // First open the dropdown so we can access the link.
        $this->button->click();
        $this->getLanguageLink($lang_code)->click();
    }

    /**
     * Returns a language link for the desired language.
     *
     * @param string $lang_code
     *   The language code for the link you want.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element|null
     *   The language link for the language required, null if not found.
     */
    public function getLanguageLink($lang_code)
    {
        $xpath = './/li[contains(@class, "' . $lang_code. '")]/a';
        $elements = $this->element->elements($this->element->using('xpath')->value($xpath));
        if (count($elements)) {
            return $elements[0];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getAllLanguages()
    {
        $languages = array();

        // Add the current language as it's not a link.
        $languages[] = $this->getActiveLanguage();

        $criteria = $this->element->using('xpath')->value('.//li/a');
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $elements */
        $elements = $this->element->elements($criteria);
        foreach ($elements as $element) {
            $languages[] = $element->attribute('xml:lang');
        }

        return $languages;
    }
}
