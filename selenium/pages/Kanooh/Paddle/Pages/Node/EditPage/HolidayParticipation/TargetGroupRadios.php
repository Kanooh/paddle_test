<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation\TargetGroupRadios
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * Class representing the target group choices.
 *
 * @property RadioButton $kidsAndYoungsters
 * @property RadioButton $adults
 * @property RadioButton $families
 * @property RadioButton $singleParents
 * @property RadioButton $teenParents
 * @property RadioButton $grandparentsAndGrandchildren
 * @property RadioButton $personsWithDisabilities
 */
class TargetGroupRadios extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'kidsAndYoungsters':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="Kids and youngsters"]'));
                break;
            case 'adults':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="Adults"]'));
                break;
            case 'families':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="Families"]'));
                break;
            case 'singleParents':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="Single parents"]'));
                break;
            case 'teenParents':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="Teen parents"]'));
                break;
            case 'grandparentsAndGrandchildren':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="Grandparents and grandchildren"]'));
                break;
            case 'personsWithDisabilities':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="Persons with disabilities"]'));
                break;
        }

        throw new RadioButtonNotDefinedException($name);
    }
}
