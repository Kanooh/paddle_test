<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\NewsLeadImagePositionRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons that allow to choose a lead image display position.
 *
 * @property RadioButton $fullTop
 *   Full width, on top.
 * @property RadioButton $halfLeft
 *   Half width, float left.
 * @property RadioButton $halfRight
 *   Half width, float right.
 */
class NewsLeadImagePositionRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'fullTop':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@value="full_top"]')
                );
            case 'halfLeft':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@value="half_left"]')
                );
            case 'halfRight':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@value="half_right"]')
                );
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
