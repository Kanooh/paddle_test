<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\Edit\QuizCustomizeForm.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\Edit;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;

/**
 * Class QuizCustomizeForm
 *
 * @property Text $startTitle
 *   Start screen title text field.
 * @property Text $startSubtitle
 *   Start screen subtitle text field.
 * @property Wysiwyg $startMessage
 *   Start screen message wysiwyg.
 * @property Text $startButtonLabel
 *   Start screen button label text field.
 * @property Checkbox $tiebreaker
 *   Tiebreaker checkbox.
 * @property Wysiwyg $tiebreakerQuestion
 *   Tiebreaker question wysiwyg.
 * @property Text $tiebreakerLabel
 *   Tiebreaker answer label.
 * @property Wysiwyg $disclaimer
 *   Disclaimer wysiwyg.
 * @property Checkbox $infoRequired
 *   Checkbox for the "info required".
 * @property RadioButton $infoLocationStart
 *   Info screen located after start screen radio button.
 * @property RadioButton $infoLocationEnd
 *   Info screen located after last question radio button.
 * @property Text $infoTitle
 *   Info screen title text field.
 * @property Wysiwyg $infoMessage
 *   Info screen message wysiwyg.
 * @property RadioButton $infoEmail
 *   Info screen asks user for email radio button.
 * @property RadioButton $infoNameAndEmail
 *   Info screen asks user for name and email radio button.
 * @property Text $infoButtonLabel
 *   Info screen continue button label.
 * @property Text $resultTitle
 *   Result screen title text field.
 * @property Wysiwyg $resultMessage
 *   Result screen message wysiwyg.
 * @property Text $previousButtonLabel
 *   Question screen previous button label text field.
 * @property Text $nextButtonLabel
 *   Question screen next button label text field.
 */
class QuizCustomizeForm extends QuizForm
{
    /**
     * Magic getter.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'startTitle':
                return $this->getTextField('field_paddle_quiz_start_title[und][0][value]');
                break;
            case 'startSubtitle':
                return $this->getTextField('field_paddle_quiz_subtitle[und][0][value]');
                break;
            case 'startMessage':
                return new Wysiwyg($this->webdriver, 'edit-field-paddle-quiz-start-message-und-0-value');
                break;
            case 'startButtonLabel':
                return $this->getTextField('field_paddle_quiz_start_button[und][0][value]');
                break;
            case 'tiebreaker':
                $xpath = './/input[@name="field_paddle_quiz_tiebreaker[und]"]';
                $element = $this->element->byXPath($xpath);
                return new Checkbox($this->webdriver, $element);
                break;
            case 'tiebreakerQuestion':
                return new Wysiwyg($this->webdriver, 'edit-field-paddle-quiz-tiebreaker-q-und-0-value');
                break;
            case 'tiebreakerLabel':
                return $this->getTextField('field_paddle_quiz_tiebreaker_l[und][0][value]');
                break;
            case 'disclaimer':
                return new Wysiwyg($this->webdriver, 'edit-field-paddle-quiz-disclaimer-und-0-value');
                break;
            case 'infoRequired':
                $element = $this->webdriver->byName('field_paddle_quiz_info_required[und]');
                return new Checkbox($this->webdriver, $element);
            case 'infoLocationStart':
                return $this->getRadioButton('field_paddle_quiz_info_location[und]', 'start');
                break;
            case 'infoLocationEnd':
                return $this->getRadioButton('field_paddle_quiz_info_location[und]', 'end');
                break;
            case 'infoTitle':
                return $this->getTextField('field_paddle_quiz_info_title[und][0][value]');
                break;
            case 'infoMessage':
                return new Wysiwyg($this->webdriver, 'edit-field-paddle-quiz-info-message-und-0-value');
                break;
            case 'infoEmail':
                return $this->getRadioButton('field_paddle_quiz_info_user[und]', 'email');
                break;
            case 'infoNameAndEmail':
                return $this->getRadioButton('field_paddle_quiz_info_user[und]', 'name_email');
                break;
            case 'infoButtonLabel':
                return $this->getTextField('field_paddle_quiz_info_button_l[und][0][value]');
                break;
            case 'resultTitle':
                return $this->getTextField('field_paddle_quiz_result_title[und][0][value]');
                break;
            case 'resultMessage':
                return new Wysiwyg($this->webdriver, 'edit-field-paddle-quiz-result-message-und-0-value');
                break;
            case 'previousButtonLabel':
                return $this->getTextField('field_paddle_quiz_btn_previous_l[und][0][value]');
                break;
            case 'nextButtonLabel':
                return $this->getTextField('field_paddle_quiz_btn_next_l[und][0][value]');
                break;
        }
        return parent::__get($name);
    }

    /**
     * Returns a text field based on its name attribute.
     *
     * @param string $name
     *   Name attribute of the textfield.
     *
     * @return Text
     *   Text field object.
     */
    protected function getTextField($name)
    {
        $xpath = './/input[@name="' . $name . '"]';
        $element = $this->element->byXPath($xpath);
        return new Text($this->webdriver, $element);
    }

    /**
     * Returns a radio button based on its name and value attributes.
     *
     * @param string $name
     *   Name attribute of the radio button.
     * @param string $value
     *   Value attribute of the radio button.
     *
     * @return RadioButton
     *   Radio button object.
     */
    protected function getRadioButton($name, $value)
    {
        $xpath = './/input[@name="' . $name . '"][@value="' . $value . '"]';
        $element = $this->element->byXPath($xpath);
        return new RadioButton($this->webdriver, $element);
    }
}
