<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\ViewModeRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * Class representing the selection type switch for the view mode.
 *
 * @property RadioButton $detailed
 * @property RadioButton $list
 */
class ViewModeRadioButtons extends RadioButtons
{
    /**
     * {@inheritDoc}
     */
    public function __get($name)
    {
        return new RadioButton(
            $this->webdriver,
            $this->element->byXPath('.//input[@value="' . $name . '"]')
        );
    }

    /**
     * Selects a radio button and waits for ajax callbacks to be completed.
     *
     * @param string $value
     *   The value parameter of the button to select.
     */
    public function select($value)
    {
        // Cache the current element.
        $radio = $this->{$value};
        // Select the wanted value.
        $radio->select();
        // Wait until the element is stale. This also means that the
        // containing fieldset has been rebuilt.
        $this->webdriver->waitUntilElementIsStale($radio->getWebdriverElement());
        // Wait until the radio is back in place.
        $this->webdriver->waitUntilElementIsPresent('.//input[@value="' . $value . '"]');
    }
}
