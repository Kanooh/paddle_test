<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\BrandingRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons that allow to select to use or not use VO branding.
 *
 * @property RadioButton $noVoBranding
 * @property RadioButton $yesVoBranding
 * @property RadioButton $federalBranding
 */
class BrandingRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'noVoBranding':
                return new RadioButton($this->webdriver, $this->element->byXPath('//input[@value = "no_branding"]'));
            case 'yesVoBranding':
                return new RadioButton($this->webdriver, $this->element->byXPath('//input[@value = "vo_branding"]'));
            case 'federalBranding':
                return new RadioButton($this->webdriver, $this->element->byXPath('//input[@value = "federal_branding"]'));
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
