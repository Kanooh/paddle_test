<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\Edit\QuizForm.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\Edit;

use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * Class QuizForm
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $customizeStepButton
 *   Button to proceed to the "customize" step.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $designStepButton
 *   Button to proceed to the "design" step.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $nextStepButton
 *   Button to proceed to the next step.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $previewStepButton
 *   Button to proceed to the "preview" step.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $qaStepButton
 *   Button to proceed to the "questions and answers" step.
 */
abstract class QuizForm extends Form
{
    /**
     * Magic getter.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'customizeStepButton':
                return $this->element->byXPath('.//input[@id="edit-steps-customize"]');
                break;
            case 'designStepButton':
                return $this->element->byXPath('.//input[@id="edit-steps-design"]');
                break;
            case 'nextStepButton':
                return $this->element->byXPath('.//input[contains(@class, "next-step")]');
                break;
            case 'previewStepButton':
                return $this->element->byXPath('.//input[@id="edit-steps-preview"]');
                break;
            case 'qaStepButton':
                return $this->element->byXPath('.//input[@id="edit-steps-qa"]');
                break;
        }
    }
}
