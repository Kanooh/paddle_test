<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\CommentRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * Class representing the comment options.
 *
 * @property RadioButton $open
 *   The radio button to use open comments.
 * @property RadioButton $closed
 *   The radio button to use closed comments.
 * @property RadioButton $hidden
 *   The radio button to use hidden comments.
 */
class CommentRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'open':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('//input[@id="edit-comment-2"]')
                );
            case 'closed':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('//input[@id="edit-comment-1"]')
                );
            case 'hidden':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('//input[@id="edit-comment-0"]')
                );
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
