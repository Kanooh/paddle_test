<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\UiTDatabank\ConfigurationForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\UiTDatabank;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\SelectionTypeRadioButtons;

/**
 * Configuration form for the UiTDatabank panels content type.
 *
 * @property AutoCompletedText $spotlightEvent
 *   The autocomplete text field to filter by tags.
 * @property SelectionTypeRadioButtons $selectionType
 *   The radio buttons to choose the atoms selection type.
 * @property SelectionTypeRadioButtons $viewMode
 *   The radio buttons to choose view mode of the list.
 */
class ConfigurationForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'spotlightEvent':
                return new AutoCompletedText(
                    $this->webdriver,
                    $this->webdriver->byXPath('//input[@name="event"]')
                );
            case 'selectionType':
                return new SelectionTypeRadioButtons(
                    $this->webdriver,
                    $this->element->byClassName('form-item-selection-type')
                );
            case 'viewMode':
                return new SelectionTypeRadioButtons(
                    $this->webdriver,
                    $this->element->byClassName('form-item-view-mode')
                );
        }

        throw new FormFieldNotDefinedException($name);
    }
}
