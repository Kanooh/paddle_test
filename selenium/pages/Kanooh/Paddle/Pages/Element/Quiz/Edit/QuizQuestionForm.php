<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\Edit\QuizQuestionForm.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\Edit;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Scald\ImageAtomField;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class QuizQuestionForm
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addAnswerButton
 *   Button to add a new answer.
 * @property Text[] $answers
 *   Text fields for each of the question's answers.
 * @property int $correctAnswerIndex
 *   Index of the correct answer.
 * @property RadioButton[] $correctAnswerRadioButtons
 *   Radio buttons to indicate the correct answer.
 * @property ImageAtomField $image
 *   Image field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $removeButton
 *   Button to remove the question.
 * @property Text $title
 *   The question's title field.
 */
class QuizQuestionForm extends Form
{
    /**
     * Index of the question.
     *
     * @var int
     */
    protected $index;

    /**
     * Field name of the question.
     *
     * @var string
     */
    protected $fieldName;

    /**
     * Constructs a new QuizQuestionForm object.
     *
     * @param WebdriverTestCase $webdriver
     *   The test case element.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The HTML element.
     * @param int $index
     *   Index of the question.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element, $index)
    {
        parent::__construct($webdriver, $element);
        $this->index = $index;
        $this->fieldName = 'field_paddle_quiz_questions[und][' . $index . ']';
    }

    /**
     * Magic getter.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'addAnswerButton':
                $name = 'field_paddle_quiz_questions_und_' . $this->index . '_field_paddle_quiz_answers_add_more';
                return $this->element->byXPath('.//input[@name="' . $name . '"]');
                break;
            case 'answers':
                return $this->getAnswers();
                break;
            case 'correctAnswerIndex':
                return $this->getCorrectAnswerIndex();
                break;
            case 'correctAnswerRadioButtons':
                return $this->getCorrectAnswerRadioButtons();
                break;
            case 'image':
                $element = $this->element->byXPath('.//div[contains(@class, "paddle-scald-atom-field")]');
                return new ImageAtomField($this->webdriver, $element);
                break;
            case 'removeButton':
                $name = 'field_paddle_quiz_questions_und_' . $this->index . '_remove_button';
                return $this->element->byXPath('.//input[@name="' . $name . '"]');
                break;
            case 'title':
                $title_name = $this->fieldName . '[field_paddle_quiz_question][und][0][value]';
                $element = $this->element->byXPath('.//input[@name="' . $title_name .'"]');
                return new Text($this->webdriver, $element);
                break;
        }
    }

    /**
     * Returns text fields for all the answers to the question.
     *
     * @return Text[]
     *   Text fields for each of the answers to the question.
     */
    protected function getAnswers()
    {
        $answers_name = $this->fieldName . '[field_paddle_quiz_answers]';
        $xpath = './/input[contains(@name, "' . $answers_name . '")]';
        $criteria = $this->element->using('xpath')->value($xpath);
        $elements = $this->element->elements($criteria);

        $answers = array();
        foreach ($elements as $element) {
            $answers[] = new Text($this->webdriver, $element);
        }
        return $answers;
    }

    /**
     * Returns the text field for a specific answer based on its index.
     *
     * @param int $index
     *   Answer's index.
     *
     * @return Text
     *   Text field for the answer.
     */
    public function getAnswer($index)
    {
        return $this->answers[$index];
    }

    /**
     * Clicks the "add answer" button and waits until the answer is added.
     */
    public function addAnswer()
    {
        $current_count = count($this->answers);

        $this->addAnswerButton->click();

        $form = $this;
        $this->webdriver->waitUntil(new SerializableClosure(function () use ($form, $current_count) {
            $updated_answers = $form->getAnswers();
            $updated_count = count($updated_answers);
            if ($updated_count > $current_count) {
                return true;
            }
        }), $this->webdriver->getTimeout());
    }

    /**
     * Empties the text field of an answer, so it will be removed it on submit.
     *
     * @param int $index
     *   Index of the answer.
     */
    public function emptyAnswer($index)
    {
        $answer = $this->getAnswer($index);
        $answer->getWebdriverElement()->clear();
    }

    /**
     * Returns all "correct answer" radio buttons.
     *
     * @return RadioButton[]
     *   All radio buttons that can indicate an answer as the correct one.
     */
    protected function getCorrectAnswerRadioButtons()
    {
        $name = 'correct_qa';
        $xpath = './/input[contains(@name, "' . $name . '")]';
        $criteria = $this->element->using('xpath')->value($xpath);
        $elements = $this->element->elements($criteria);

        $radios = array();
        foreach ($elements as $element) {
            $radios[] = new RadioButton($this->webdriver, $element);
        }
        return $radios;
    }

    /**
     * Returns the index of the answer that's indicated as "correct".
     *
     * @return int
     *   Index of the correct answer, or -1 if no correct answer is indicated.
     */
    protected function getCorrectAnswerIndex()
    {
        foreach ($this->getCorrectAnswerRadioButtons() as $index => $radio) {
            if ($radio->isSelected()) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * Indicates an answer with a specific index as the correct answer.
     *
     * @param int $index
     *   Index of the correct answer.
     */
    public function indicateCorrectAnswer($index)
    {
        $radios = $this->getCorrectAnswerRadioButtons();
        $radios[$index]->select();
    }
}
