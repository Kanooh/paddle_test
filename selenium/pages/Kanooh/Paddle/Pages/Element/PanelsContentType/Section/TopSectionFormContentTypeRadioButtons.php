<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\Section\TopSectionFormContentTypeRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\Section;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons that allow to select the content type.
 *
 * @property RadioButton $text
 *   The radio button representing the 'text' content type.
 * @property RadioButton $image
 *   The radio button representing the 'image' content type.
 * @property RadioButton $title
 *   The radio button representing the 'title' content type.
 */
class TopSectionFormContentTypeRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'text':
                return new RadioButton($this->webdriver, $this->element->byXPath('//input[@value = "text"]'));
            case 'image':
                return new RadioButton($this->webdriver, $this->element->byXPath('//input[@value = "image"]'));
            case 'title':
                return new RadioButton($this->webdriver, $this->element->byXPath('//input[@value = "title"]'));
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
