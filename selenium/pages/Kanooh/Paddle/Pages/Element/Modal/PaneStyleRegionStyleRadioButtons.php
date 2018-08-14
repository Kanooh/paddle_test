<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Modal\PaneStyleRegionStyleRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Element\Modal;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons that allow to select the region style.
 *
 * @property RadioButton $backgroundImageStyle
 * @property RadioButton $containerStyle
 * @property RadioButton $pageWideStyle
 */
class PaneStyleRegionStyleRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'backgroundImageStyle':
                return new RadioButton($this->webdriver, $this->element->byId('edit-style-background-image'));
            case 'containerStyle':
                return new RadioButton($this->webdriver, $this->element->byId('edit-style-container-page'));
            case 'pageWideStyle':
                return new RadioButton($this->webdriver, $this->element->byId('edit-style-page-wide'));
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
