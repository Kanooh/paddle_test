<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\LanguageSwitcher\LanguageSwitcher.
 */

namespace Kanooh\Paddle\Pages\Element\LanguageSwitcher;

use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The Language switcher as displayed on the back-end.
 */
class LanguageSwitcher
{
    /**
     * The Selenium web driver element representing the Language switcher.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element|Select
     */
    protected $element;

    /**
     * Indicates whether the language switcher element is an <ul> list or a <select>.
     *
     * @var bool
     */
    protected $elementIsList = true;

    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Constructs a LanguageSwitcher object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $container
     *   The Selenium web driver element representing the container of the language switcher.
     *
     * @throws \Exception
     *   Trows an exception if no element for the language switcher was found.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $container)
    {
        $this->element = null;
        $this->webdriver = $webdriver;

        // Try to get the element representing the switcher itself. It's either
        // an <ul> or <select>.
        $xpath = './ul[contains(@class, "language-switcher-locale-url")]';
        $elements = $container->elements($container->using('xpath')->value($xpath));
        if (count($elements)) {
            $this->element = $elements[0];
        } else {
            // It must be the select them. Try to get it.
            $this->elementIsList = false;
            $xpath = './/select[contains(@id, "language-switcher-select")]';
            $elements = $container->elements($container->using('xpath')->value($xpath));
            $this->element = new Select($this->webdriver, $elements[0]);
        }

        if (!$this->element) {
            throw new \Exception('Language switcher element not found.');
        }
    }

    /**
     * Switches the content language to the desired one.
     *
     * @param string $lang_code
     *   The code of the language you want to switch to.
     */
    public function switchLanguage($lang_code)
    {
        if ($this->elementIsList) {
            $xpath = './li[contains(@class, "' . $lang_code . '")]/a';
            /** @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $elements */
            $elements = $this->element->elements($this->element->using('xpath')->value($xpath));
            if (count($elements)) {
                $elements[0]->click();
            }
        } else {
            /** @var Select $select */
            $select = $this->element;
            $select->selectOptionByLabel($lang_code);
        }
    }

    /**
     * Find the active language according to the language switcher.
     *
     * @return string
     *   The language code of the active language.
     */
    public function getActiveLanguage()
    {
        if ($this->elementIsList) {
            $xpath = './li/a[contains(@class, "active-language")]';
            /** @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $elements */
            $elements = $this->element->elements($this->element->using('xpath')->value($xpath));
            if (count($elements)) {
                return $elements[0]->attribute('xml:lang');
            }
        }

        /** @var Select $select */
        $select = $this->element;
        return $select->getSelectedLabel();
    }

    /**
     * Returns whether the language switcher is displayed or not.
     *
     * @return bool
     *   TRUE if the field is displayed, FALSE if it is not.
     */
    public function isDisplayed()
    {
        if ($this->elementIsList) {
            return $this->element->displayed();
        }

        return $this->element->isDisplayed();
    }

    /**
     * Generates a list of all the languages in the language switcher.
     * @return array
     *   List of codes of the languages in the switcher.
     */
    public function getAllLanguages()
    {
        $languages = array();
        if ($this->elementIsList) {
            /** @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $elements */
            $elements = $this->element->elements($this->element->using('xpath')->value('./li/a'));
            foreach ($elements as $element) {
                $languages[] = $element->attribute('xml:lang');
            }
            return $languages;
        } else {
            /** @var Select $select */
            $select = $this->element;
            return array_values($select->getOptions());
        }
    }
}
