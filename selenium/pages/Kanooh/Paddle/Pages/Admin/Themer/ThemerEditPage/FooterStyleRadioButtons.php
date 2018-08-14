<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\FooterStyleRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons that allow to select which footer style to use.
 *
 * @property RadioButton $noFooter
 * @property RadioButton $richFooter
 */
class FooterStyleRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'noFooter':
                return new RadioButton($this->webdriver, $this->element->byXPath('//input[@value = "no_footer"]'));
            case 'richFooter':
                return new RadioButton($this->webdriver, $this->element->byXPath('//input[@value = "rich_footer"]'));
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
