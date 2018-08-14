<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\SiteSettings\MaintenanceModeRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Admin\SiteSettings;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons for the maintenance mode in the site settings.
 *
 * @property RadioButton $enableMaintenanceMode
 *   The radio button to enable Maintenance mode.
 * @property RadioButton $disableMaintenanceMode
 *   The radio button to disable Maintenance mode.
 */
class MaintenanceModeRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'enableMaintenanceMode':
                return new RadioButton($this->webdriver, $this->element->byId('edit-paddle-maintenance-mode-1'));
            case 'disableMaintenanceMode':
                return new RadioButton($this->webdriver, $this->element->byId('edit-paddle-maintenance-mode-0'));
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
