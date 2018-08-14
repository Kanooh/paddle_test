<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Links\AdminMenuLinks.
 */

namespace Kanooh\Paddle\Pages\Element\Links;

/**
 * The collection of Language Selection links on the Splash Page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $dutch
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $english
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $french
 */
class LanguageSelectionLinks extends Links
{

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return array(
            'dutch' => array(
                'xpath' =>
                    '//a[contains(@href,"nl")]',
                'title' => 'Nederlands',
            ),
            'english' => array(
                'xpath' =>
                    '//a[contains(@href,"en")]',
                'title' => 'English',
            ),
            'french' => array(
                'xpath' =>
                    '//a[contains(@href,"fr")]',
                'title' => 'Français',
            ),
        );
    }
}
