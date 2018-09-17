<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\QuizPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

/**
 * Page to edit a quiz page.
 *
 * @property QuizPageForm $quizPageForm
 *   The edit quiz page form.
 */
class QuizPage extends EditPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'quizPageForm':
                return new QuizPageForm($this->webdriver, $this->webdriver->byId('quiz-page-node-form'));
        }
        return parent::__get($property);
    }
}
