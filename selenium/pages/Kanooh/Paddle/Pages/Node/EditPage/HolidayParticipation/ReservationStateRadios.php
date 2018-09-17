<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation\ReservationStateRadios
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * Class representing the target group choices.
 *
 * @property RadioButton $basedOnAvailability
 * @property RadioButton $available
 * @property RadioButton $fullyBooked
 */
class ReservationStateRadios extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'basedOnAvailability':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="-1"]'));
                break;
            case 'available':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="0"]'));
                break;
            case 'fullyBooked':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="2"]'));
                break;
        }

        throw new RadioButtonNotDefinedException($name);
    }
}
