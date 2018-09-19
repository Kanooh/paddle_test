<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PaneCollection\PaneCollectionForm.
 */

namespace Kanooh\Paddle\Pages\Element\PaneCollection;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * The main form of the add pane collection entities.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $saveButton
 * @property Text $title
 */
class PaneCollectionForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'saveButton':
                return $this->element->byXPath('.//input[contains(@id, "edit-save")]');
                break;
            case 'title':
                return new Text($this->webdriver, $this->element->byName('title'));
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
