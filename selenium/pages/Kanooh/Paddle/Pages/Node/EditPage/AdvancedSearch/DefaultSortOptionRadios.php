<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch\DefaultSortOptionRadios
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * Class representing the default sort option selection of an advanced search page.
 *
 * @property RadioButton $relevance
 * @property RadioButton $title
 * @property RadioButton $publication_date
 */
class DefaultSortOptionRadios extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'relevance':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@value="search_api_relevance"]')
                );
            case 'title':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@value="title"]')
                );
            case 'publication_date':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@value="publication_date"]')
                );
        }

        throw new RadioButtonNotDefinedException($name);
    }
}
