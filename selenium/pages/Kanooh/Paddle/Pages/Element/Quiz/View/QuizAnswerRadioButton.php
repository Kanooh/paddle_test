<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\View\QuizAnswerRadioButton.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\View;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;

/**
 * Class QuizAnswerRadioButton
 * @package Kanooh\Paddle\Pages\Element\Quiz\View
 */
class QuizAnswerRadioButton extends RadioButton
{
    /**
     * Returns the label of the answer's radio button.
     *
     * @return string
     *   Label of the radio button.
     */
    public function getLabel()
    {
        $id = $this->element->attribute('id');
        $xpath = './..//label[@for="' . $id . '"]';
        $element = $this->element->byXPath($xpath);
        return $element->text();
    }
}
