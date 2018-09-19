<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\ContactPersonPanelsContentTypeForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;

/**
 * Class representing the contact person pane form.
 *
 * @property AutoCompletedText $contactPersonAutocompleteField
 *   The auto complete text field to choose a contact person.
 * @property RadioButton viewModeShort
 *   The radio button to select the short view mode.
 * @property RadioButton viewModeMedium
 *   The radio button to select the medium view mode.
 * @property RadioButton viewModeLong
 *   The radio button to select the long view mode.
 */
class ContactPersonPanelsContentTypeForm extends Form
{

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'contactPersonAutocompleteField':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byName('node'));
            case 'viewModeShort':
                return new RadioButton($this->webdriver, $this->webdriver->byId('edit-view-mode-short'));
            case 'viewModeMedium':
                return new RadioButton($this->webdriver, $this->webdriver->byId('edit-view-mode-medium'));
            case 'viewModeLong':
                return new RadioButton($this->webdriver, $this->webdriver->byId('edit-view-mode-long'));
        }
        throw new FormFieldNotDefinedException($name);
    }
}
