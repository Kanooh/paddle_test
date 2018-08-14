<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleQuiz\AddEditPage\AddEditPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleQuiz\AddEditPage;

use Kanooh\Paddle\Pages\Element\Quiz\Edit\QuizCustomizeForm;
use Kanooh\Paddle\Pages\Element\Quiz\Edit\QuizDesignForm;
use Kanooh\Paddle\Pages\Element\Quiz\Edit\QuizQaForm;
use Kanooh\Paddle\Pages\Element\Quiz\View\QuizForm as QuizViewForm;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The add/edit page for the Quiz app.
 *
 * @property AddEditPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property QuizCustomizeForm $customizeForm
 *   The "customize" form.
 * @property QuizDesignForm $designForm
 *   The "design" form.
 * @property QuizQaForm $qaForm
 *   The "questions and answers" form.
 * @property QuizViewForm $quizForm
 *   The form for the actual quiz when on the preview step.
 */
abstract class AddEditPage extends PaddlePage
{
    /**
     * XPath for the form on the page.
     *
     * @var string
     */
    protected $formXPath = '//form[contains(@id, "paddle-quiz-form")]';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new AddEditPageContextualToolbar($this->webdriver);
            case 'customizeForm':
                $element = $this->webdriver->byXPath($this->formXPath);
                return new QuizCustomizeForm($this->webdriver, $element);
            case 'designForm':
                $element = $this->webdriver->byXPath($this->formXPath);
                return new QuizDesignForm($this->webdriver, $element);
            case 'qaForm':
                $element = $this->webdriver->byXPath($this->formXPath);
                return new QuizQaForm($this->webdriver, $element);
            case 'quizForm':
                $xpath = '//form[contains(@id, "paddle-quiz-participation-form")]';
                $element = $this->webdriver->byXPath($xpath);
                return new QuizViewForm($this->webdriver, $element);
        }
        return parent::__get($property);
    }
}
