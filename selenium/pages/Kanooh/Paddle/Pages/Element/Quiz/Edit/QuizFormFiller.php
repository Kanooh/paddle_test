<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\Edit\QuizFormFiller.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\Edit;

use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;

/**
 * Class QuizFormFiller
 * @package Kanooh\Paddle\Pages\Element\Quiz
 */
class QuizFormFiller
{
    /**
     * Fills in the fields of the questions & answers form.
     *
     * @param QuizQaForm $form
     *   The q&a form on the page.
     * @param $fields
     *   Data to fill the fields with. See QuizService::generateRandomData().
     */
    public static function fillQaForm(QuizQaForm $form, $fields)
    {
        // Enter a title.
        $form->title->fill($fields['title']);

        // Enter the questions and answers.
        foreach ($fields['questions'] as $index => $question_data) {
            $question = $form->getQuestion($index);

            // Set question title and image.
            $question->title->fill($question_data['title']);
            $question->image->selectAtom($question_data['image']['id']);

            // Loop over all answers.
            foreach ($question_data['answers'] as $answer_index => $answer) {
                // Enter the answer text.
                $question->getAnswer($answer_index)->fill($answer);

                // If we haven't reached the last answer text, add a new answer.
                if ($answer_index + 1 != count($question_data['answers'])) {
                    $question->addAnswer();
                    $question = $form->getQuestion($index);
                }
            }

            // Indicate the correct answer.
            $question->indicateCorrectAnswer($question_data['correct_answer']);

            // If we haven't reached the last question data, add a new question.
            if ($index + 1 != count($fields['questions'])) {
                $form->addQuestion();
            }
        }
    }

    /**
     * Fills in the fields of the customization form.
     *
     * @param QuizCustomizeForm $form
     *   The customization form on the page.
     * @param $fields
     *   Data to fill the fields with. See QuizService::generateRandomData().
     */
    public static function fillCustomizeForm(QuizCustomizeForm $form, $fields)
    {
        if (!empty($fields['customize']['tiebreakerQuestion']) || !empty($fields['customize']['tiebreakerLabel'])) {
            // Enable tiebreaker so all necessary fields are visible.
            $form->tiebreaker->check();
        }
            // Make all info fields visible.
            $form->infoRequired->check();
        // Loop over all fields and enter the random data.
        foreach ($fields['customize'] as $field => $value) {
            // Wysiwyg fields use setBodyText() instead of fill().
            if ($form->{$field} instanceof Wysiwyg) {
                $form->{$field}->setBodyText($value);
            } else {
                $form->{$field}->fill($value);
            }
        }
    }

    /**
     * Fills in the fields of the design form.
     *
     * @param QuizDesignForm $form
     *   The design form on the page.
     * @param $fields
     *   Data to fill the fields with. See QuizService::generateRandomData().
     */
    public static function fillDesignForm(QuizDesignForm $form, $fields)
    {
        // Add the start page image.
        $form->startImage->selectAtom($fields['design']['startImage']['id']);
    }
}
