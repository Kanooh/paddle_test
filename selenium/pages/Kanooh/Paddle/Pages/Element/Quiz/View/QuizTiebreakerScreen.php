<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\View\QuizTiebreakerScreen.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\View;

use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class QuizTiebreakerScreen
 * @package Kanooh\Paddle\Pages\Element\Quiz\View
 *
 * @property Text $answer
 *   Answer input field.
 * @property string $answerSuffix
 *   Suffix label for the answer input field.
 * @property string $question
 *   Tiebreaker question string.
 */
class QuizTiebreakerScreen extends QuizScreen
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'answer':
                $xpath = './/input[@type="text"][@name="answer_tiebreaker"]';
                $element = $this->element->byXPath($xpath);
                return new Text($this->webdriver, $element);
            case 'answerSuffix':
                $xpath = './/div[contains(@class, "form-item-answer-tiebreaker")]//span[contains(@class, "field-suffix")]';
                $element = $this->element->byXPath($xpath);
                return $element->text();
            case 'question':
                $xpath = './/div[contains(@class, "paddle-quiz-question")]';
                $element = $this->element->byXPath($xpath);
                return $element->text();
        }
        return parent::__get($property);
    }
}
