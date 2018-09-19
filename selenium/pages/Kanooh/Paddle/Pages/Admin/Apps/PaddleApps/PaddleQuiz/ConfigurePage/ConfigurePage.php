<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleQuiz\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleQuiz\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Quiz\QuizTable;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The configuration page for the Quiz app.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property QuizTable $quizTable
 *   The table of quizzes.
 */
class ConfigurePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_quiz/configure';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
            case 'quizTable':
                return new QuizTable($this->webdriver, '//table[@id="quiz-list"]');
        }
        return parent::__get($property);
    }
}
