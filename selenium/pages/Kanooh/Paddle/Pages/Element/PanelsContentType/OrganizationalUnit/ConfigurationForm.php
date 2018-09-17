<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\OrganizationalUnit\ConfigurationForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\OrganizationalUnit;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\SelectionTypeRadioButtons;

/**
 * Configuration form for the UiTDatabank panels content type.
 *
 * @property AutoCompletedText $organizationalUnitAutocompleteField
 * @property SelectionTypeRadioButtons $viewMode
 */
class ConfigurationForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'organizationalUnitAutocompleteField':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byName('node'));
            case 'viewMode':
                return new SelectionTypeRadioButtons(
                    $this->webdriver,
                    $this->element->byClassName('form-item-view-mode')
                );
        }

        throw new FormFieldNotDefinedException($name);
    }
}
