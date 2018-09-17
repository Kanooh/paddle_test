<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\QuizPageViewPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Quiz\View\QuizForm;

/**
 * The administrative node view of a quiz page.
 *
 * @property QuizForm $quizForm
 *   The form for the quiz on the page.
 */
class QuizPageViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'quizForm':
                $xpath = '//form[contains(@id, "paddle-quiz-participation-form")]';
                $element = $this->webdriver->byXPath($xpath);
                return new QuizForm($this->webdriver, $element);
        }
        return parent::__get($property);
    }
}
