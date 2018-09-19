<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\QuizViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Quiz\View\QuizForm;

/**
 * A quiz page node in the frontend view.
 *
 * @property QuizForm $quizForm
 *   The quiz's form.
 */
class QuizViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed(
            '//body[contains(concat(" ", normalize-space(@class), " "), " node-type-quiz-page ")]'
        );
    }

    /**
     * Checks if the required panes are present.
     */
    public function assertLayoutMarkup()
    {
        $this->webdriver->byCssSelector('div.pane-paddle-quiz');
    }

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
                break;
        }
        return parent::__get($property);
    }

    /**
     * Checks if the comments are below the participation form.
     */
    public function checkCommentsBelowParticipationForm()
    {
        // Verify the comments are placed below the quiz itself.
        $xpath = '//div[contains(@class, "pane-paddle-quiz")]/div[@class="pane-content"]/div[@id="paddle-quiz-form-container"]/..//div[@id="comments"]';
        try {
            $this->webdriver->byXPath($xpath);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
