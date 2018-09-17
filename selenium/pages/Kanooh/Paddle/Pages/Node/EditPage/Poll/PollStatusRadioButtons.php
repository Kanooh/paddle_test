<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Poll\PollStatusRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Poll;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons that allow to choose the status of a poll.
 *
 * @property RadioButton $closed
 * @property RadioButton $open
 */
class PollStatusRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'closed':
                return new RadioButton($this->webdriver, $this->element->byId('edit-active-0'));
            case 'open':
                return new RadioButton($this->webdriver, $this->element->byId('edit-active-1'));
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
