<?php
/**
 * @file
 * Kanooh\Paddle\Pages\Element\PanelsContentType\SimpleContact\ConfigurationForm
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\SimpleContact;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Select;

/**
 * Class ConfigurationForm
 * @package Kanooh\Paddle\Pages\Element\PanelsContentType\SimpleContact
 *
 * @property Select $node
 *   The dropdown to choose the image style from.
 */
class ConfigurationForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'node':
                return new Select($this->webdriver, $this->webdriver->byId('edit-node'));
        }
    }
}
