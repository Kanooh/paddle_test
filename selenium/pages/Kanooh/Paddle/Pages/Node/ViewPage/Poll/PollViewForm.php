<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\Poll\PollForm.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\Poll;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;

/**
 * Class representing the poll voting form in the frontend view.
 *
 * @property RadioButton[] $pollChoices
 *   The radio buttons for the poll choices.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $votingButton
 *   The 'Vote' button.
 */
class PollViewForm extends Form
{
    public function __get($property)
    {
        switch ($property) {
            case 'pollChoices':
                $choice_radios = array();
                $xpath = './/div[contains(@class, "choices")]//input[@type="radio"]';
                $elements = $this->element->elements($this->element->using('xpath')->value($xpath));
                foreach ($elements as $element) {
                    $choice_radios[] = new RadioButton($this->webdriver, $element);
                }
                return $choice_radios;
                break;
            case 'votingButton':
                $xpath = './/input[contains(@class, "form-submit")]';
                return $this->element->byXPath($xpath);
                break;
        }
        return parent::__get($property);
    }
}
