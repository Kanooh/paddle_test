<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\LanguageSelectionPage.
 */

namespace Kanooh\Paddle\Pages;

use Kanooh\Paddle\Pages\Element\Links\LanguageSelectionLinks;

/**
 * A base class for a backend page.
 *
 * @property LanguageSelectionLinks $languageSelection
 */
class LanguageSelectionPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'language_selection';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'languageSelection':
                return new LanguageSelectionLinks($this->webdriver);
        }

        return parent::__get($property);
    }
}
