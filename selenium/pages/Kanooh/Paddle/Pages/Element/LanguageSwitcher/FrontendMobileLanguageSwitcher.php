<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\LanguageSwitcher\FrontendMobileLanguageSwitcher.
 */

namespace Kanooh\Paddle\Pages\Element\LanguageSwitcher;

use Kanooh\Paddle\Pages\Element\Form\Select;

/**
 * The language switcher displayed in the frontend for mobile viewports.
 *
 * @property Select $select
 *   The select element to switch languages.
 */
class FrontendMobileLanguageSwitcher extends FrontendLanguageSwitcher
{
    /**
     * {@inheritDoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'select':
                return new Select($this->webdriver, $this->element->byId('language-switcher-select'));
                break;
        }

        return parent::__get($property);
    }

    /**
     * {@inheritDoc}
     */
    public function getActiveLanguage()
    {
        $element = $this->element->byClassName('current-language');

        return $element->text();
    }

    /**
     * {@inheritDoc}
     */
    public function switchLanguage($lang_code)
    {
        $this->select->selectOptionByLabel($lang_code);
    }

    /**
     * {@inheritDoc}
     */
    public function getAllLanguages()
    {
        $languages = array();

        // Add the current language as it's not a link.
        $languages[] = $this->getActiveLanguage();

        $languages = array_merge($languages, array_values($this->select->getOptions()));

        return $languages;
    }
}
