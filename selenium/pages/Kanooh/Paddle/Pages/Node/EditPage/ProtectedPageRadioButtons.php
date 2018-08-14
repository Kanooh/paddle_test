<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\ProtectedPageRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * Class representing the comment options.
 *
 * @property RadioButton $everyone
 *   The radio button to show published pages to everyone.
 * @property RadioButton $authenticated
 *   The radio button to only show published pages to logged in users.
 * @property RadioButton $specific_roles
 *   The radio button to only show published pages to some logged in users.
 */
class ProtectedPageRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'everyone':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byId('edit-field-paddle-prot-pg-visibility-und-everyone')
                );
            case 'authenticated':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byId('edit-field-paddle-prot-pg-visibility-und-authenticated')
                );
            case 'specific_roles':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byId('edit-field-paddle-prot-pg-visibility-und-specific-roles')
                );
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
