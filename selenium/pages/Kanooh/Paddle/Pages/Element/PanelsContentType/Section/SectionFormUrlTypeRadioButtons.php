<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\Section\SectionFormUrlTypeRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\Section;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons that allow to select the url type.
 *
 * @property RadioButton $noLink
 *   The radio button representing the 'no link' url type.
 * @property RadioButton $internal
 *   The radio button representing the 'internal' url type.
 * @property RadioButton $external
 *   The radio button representing the 'external' url type.
 * @property RadioButton $menuLink
 *   The radio button representing the 'menu_link' url type.
 * @property RadioButton $nodeLink
 *   The radio button representing the 'node_link' url type.
 */
class SectionFormUrlTypeRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'noLink':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value = "no_link"]'));
            case 'internal':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value = "internal"]'));
            case 'external':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value = "external"]'));
            case 'menuLink':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value = "menu_link"]'));
            case 'nodeLink':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value = "node_link"]'));
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
