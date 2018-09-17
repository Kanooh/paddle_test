<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\NewsletterRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons that allow to choose a list for a new campaign.
 *
 * @property RadioButton $listOne
 *   "List One" test list radio button.
 * @property RadioButton $listTwo
 *   "List Two" test list radio button.
 */
class NewsletterRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'listOne':
                // As the value of the radio button is the list id, we have to
                // target them by label.
                $xpath = '//label[contains(text(), "List One")]/../input';
                return new RadioButton($this->webdriver, $this->webdriver->byXPath($xpath));
            case 'listTwo':
                $xpath = '//label[contains(text(), "List Two")]/../input';
                return new RadioButton($this->webdriver, $this->webdriver->byXPath($xpath));
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
