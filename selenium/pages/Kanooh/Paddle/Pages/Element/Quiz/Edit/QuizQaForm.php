<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\Edit\QuizQaForm.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\Edit;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class QuizQaForm
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addQuestionButton
 *   Button to add a new question.
 * @property Text $title
 *   The quiz title field.
 * @property QuizQuestionForm[] $questions
 *   Forms for each of the question field collection items.
 */
class QuizQaForm extends QuizForm
{
    /**
     * Magic getter.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'addQuestionButton':
                return $this->element->byXPath('.//input[contains(@name, "field_paddle_quiz_questions_add_more")]');
                break;
            case 'title':
                $element = $this->element->byXPath('.//input[@name="title"]');
                return new Text($this->webdriver, $element);
                break;
            case 'questions':
                return $this->getQuestions();
                break;
        }
        return parent::__get($name);
    }

    /**
     * Returns a question field collection item for a specific index.
     *
     * @param int $index
     *   Index of the question field collection item.
     *
     * @return QuizQuestionForm
     *   Form for the question field collection item.
     */
    public function getQuestion($index)
    {
        return $this->questions[$index];
    }

    /**
     * Returns forms for each of the question field collection items.
     *
     * @return QuizQuestionForm[]
     *   All the forms, one for each question field collection item.
     */
    protected function getQuestions()
    {
        $xpath = './/table[contains(@id, "paddle-quiz-questions-values")]/tbody/tr/td';
        $criteria = $this->element->using('xpath')->value($xpath);
        $elements = $this->element->elements($criteria);

        $questions = array();
        foreach ($elements as $index => $element) {
            $questions[] = new QuizQuestionForm($this->webdriver, $element, $index);
        }
        return $questions;
    }

    /**
     * Clicks the "add question" button and waits until the question is added.
     */
    public function addQuestion()
    {
        $current_count = count($this->questions);

        $this->addQuestionButton->click();

        $form = $this;
        $this->webdriver->waitUntil(new SerializableClosure(function () use ($form, $current_count) {
            $updated_count = count($form->questions);
            if ($updated_count > $current_count) {
                return true;
            }
        }), $this->webdriver->getTimeout());
    }

    /**
     * Removes a specific question and waits until the form is updated.
     *
     * @param int $index
     *   Index of the question that should be deleted.
     */
    public function removeQuestion($index)
    {
        $current_count = count($this->questions);

        $question = $this->getQuestion($index);
        $question->removeButton->click();

        $form = $this;
        $this->webdriver->waitUntil(new SerializableClosure(function () use ($form, $current_count) {
            $updated_count = count($form->questions);
            if ($updated_count < $current_count) {
                return true;
            }
        }), $this->webdriver->getTimeout());
    }
}
