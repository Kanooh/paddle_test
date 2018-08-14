<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Poll\PollForm.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Poll;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Utilities\AjaxService;

/**
 * Class representing the poll edit form.
 *
 * @property Text $question
 *   The poll question field.
 * @property Select $chartType
 *   The select to change the chart visualization mode.
 * @property PollChoiceTable $choiceTable
 *   The table containing the poll choices.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $moreChoicesButton
 *   The button to add new choices to the table.
 * @property PollStatusRadioButtons $pollStatusRadioButtons
 *   The radio buttons to activate/deactivate the poll.
 */
class PollForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'question':
                return new Text($this->webdriver, $this->element->byName('field_paddle_poll_question[und][0][value]'));
            case 'chartType':
                return new Select($this->webdriver, $this->element->byName('field_paddle_poll_chart_type[und]'));
            case 'choiceTable':
                return new PollChoiceTable($this->webdriver, '//table[@id = "poll-choice-table"]');
            case 'moreChoicesButton':
                return $this->element->byId('edit-poll-more');
            case 'pollStatusRadioButtons':
                return new PollStatusRadioButtons($this->webdriver, $this->element->byId('edit-active'));
        }
        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Helper method to add a new row to the choices table.
     */
    public function addChoice()
    {
        $ajax_service = new AjaxService($this->webdriver);
        $ajax_service->markAsWaitingForAjaxCallback($this->moreChoicesButton);
        $this->moreChoicesButton->click();
        $ajax_service->waitForAjaxCallback($this->moreChoicesButton);
    }
}
