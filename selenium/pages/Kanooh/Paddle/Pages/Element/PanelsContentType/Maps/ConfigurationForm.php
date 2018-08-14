<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\Maps\ConfigurationForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\Maps;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * The 'Maps search field' Panels content type edit form.
 *
 * @property AutoCompletedText $autocompleteField
 *   The autocomplete text field to choose an advanced search page.
 */
class ConfigurationForm extends Form
{

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'autocompleteField':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byName('node'));
        }

        throw new \Exception("Property with name $name not found");
    }
}
