<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\View\QuizForm.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\View;

use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * Class QuizForm
 *
 * @property string $currentScreenName
 *   Name of the currently visible screen.
 * @property QuizStartScreen $startScreen
 *   Start screen of the quiz.
 * @property QuizInfoScreen $infoScreen
 *   Info screen of the quiz.
 * @property QuizQuestionScreen $questionScreen
 *   Question screen of the quiz.
 * @property QuizTiebreakerScreen $tiebreakerScreen
 *   Tiebreaker screen of the quiz.
 * @property QuizResultScreen $resultScreen
 *   Result screen of the quiz.
 */
class QuizForm extends Form
{
    /**
     * Magic getter.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'currentScreenName':
                return $this->getCurrentScreenName();
            case 'startScreen':
                return $this->getScreen('start');
            case 'infoScreen':
                return $this->getScreen('info');
            case 'questionScreen':
                return $this->getScreen('question');
            case 'tiebreakerScreen':
                return $this->getScreen('tiebreaker');
            case 'resultScreen':
                return $this->getScreen('result');
        }
        return parent::__get($property);
    }

    /**
     * Returns the name of the currently visible screen.
     *
     * @return string
     *   Name of the currently visible screen.
     */
    protected function getCurrentScreenName()
    {
        $xpath = './/div[contains(@class, "paddle-quiz-screen")]';
        $element = $this->element->byXPath($xpath);
        return $element->attribute('data-screen');
    }

    /**
     * Gets a screen object based on the screen's name.
     *
     * @param string $screen
     *   Name of the screen. If the screen's CSS class is
     *   "paddle-quiz-screen-info", the screen name would be "info".
     *
     * @return mixed
     */
    protected function getScreen($screen)
    {
        $xpath = './/div[contains(@class, "paddle-quiz-screen-' . $screen .'")]';
        $element = $this->element->byXPath($xpath);

        switch ($screen) {
            case 'start':
                return new QuizStartScreen($this->webdriver, $element);
            case 'info':
                return new QuizInfoScreen($this->webdriver, $element);
            case 'question':
                return new QuizQuestionScreen($this->webdriver, $element);
            case 'tiebreaker':
                return new QuizTiebreakerScreen($this->webdriver, $element);
            case 'result':
                return new QuizResultScreen($this->webdriver, $element);
            default:
                return new QuizScreen($this->webdriver, $element);
        }
    }

    /**
     * Waits until a specific screen is present.
     *
     * @param string $screen
     *   Name of the screen. If the screen's CSS class is
     *   "paddle-quiz-screen-info", the screen name would be "info".
     */
    public function waitUntilScreenIsVisible($screen)
    {
        $xpath = '//div[contains(@class, "paddle-quiz-screen-' . $screen . '")]';
        $this->webdriver->waitUntilElementIsDisplayed($xpath);
    }

    /**
     * Waits until a specific screen is no longer present.
     *
     * @param string $screen
     *   Name of the screen. If the screen's CSS class is
     *   "paddle-quiz-screen-info", the screen name would be "info".
     */
    public function waitUntilScreenIsNoLongerPresent($screen)
    {
        $xpath = '//div[contains(@class, "paddle-quiz-screen-' . $screen . '")]';
        $webdriver = $this->webdriver;
        $webdriver->waitUntil(function () use ($webdriver, $xpath) {
            try {
                $element = $webdriver->byXPath($xpath);
                return null;
            } catch (\Exception $e) {
                return true;
            }
        }, $webdriver->getTimeout());
    }
}
