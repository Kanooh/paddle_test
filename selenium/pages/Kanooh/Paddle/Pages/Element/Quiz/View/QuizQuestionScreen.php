<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\View\QuizQuestionScreen.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\View;

/**
 * Class QuizQuestionScreen
 * @package Kanooh\Paddle\Pages\Element\Quiz\View
 *
 * @property string $question
 *   Question as a string.
 * @property QuizAnswerRadioButton[] $answers
 *   Radio buttons and their labels for each answer.
 */
class QuizQuestionScreen extends QuizScreen
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'question':
                $xpath = './/p[contains(@class, "paddle-quiz-question")]';
                $element = $this->element->byXPath($xpath);
                return $element->text();
            case 'answers':
                $xpath = './/input[@type="radio"]';
                $criteria = $this->element->using('xpath')->value($xpath);
                $elements = $this->element->elements($criteria);
                $answers = array();
                foreach ($elements as $element) {
                    $answers[] = new QuizAnswerRadioButton($this->webdriver, $element);
                }
                return $answers;
        }
        return parent::__get($property);
    }
}
