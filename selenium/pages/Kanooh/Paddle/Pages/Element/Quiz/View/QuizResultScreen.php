<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\View\QuizResultScreen.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\View;

/**
 * Class QuizResultScreen
 * @package Kanooh\Paddle\Pages\Element\Quiz\View
 *
 * @property string $message
 *   Result message.
 * @property string $score
 *   Score percentage. (As a string suffixed by "%".)
 */
class QuizResultScreen extends QuizScreen
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'message':
                $xpath = './/div[contains(@class, "paddle-quiz-result-message")]';
                $element = $this->element->byXPath($xpath);
                return $element->text();
            case 'score':
                $xpath = './/div[contains(@class, "paddle-quiz-result-score")]';
                $element = $this->element->byXPath($xpath);
                return $element->text();
        }
        return parent::__get($property);
    }
}
