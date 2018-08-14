<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch\DefaultSortOrderRadios
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * Class representing the default sort order selection of an advanced search page.
 *
 * @property RadioButton $asc
 * @property RadioButton $desc
 */
class DefaultSortOrderRadios extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'asc':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@value="ASC"]')
                );
            case 'desc':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@value="DESC"]')
                );
        }

        throw new RadioButtonNotDefinedException($name);
    }
}
