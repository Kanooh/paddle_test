<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\NodeContentPanelsContentTypeForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;

/**
 * Class representing the Node Content pane form.
 *
 * @property AutoCompletedText $nodeContentAutocomplete
 *   The auto complete text field to choose a node.
 * @property ViewModeRadioButtons $viewModeRadios
 *   The radio buttons which allow us to pick a view mode.
 */
class NodeContentPanelsContentTypeForm extends Form
{

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'nodeContentAutocomplete':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byName('node'));
            case 'viewModeRadios':
                return new ViewModeRadioButtons($this->webdriver, $this->element->byClassName('form-item-view-mode'));
        }
        throw new FormFieldNotDefinedException($name);
    }
}
