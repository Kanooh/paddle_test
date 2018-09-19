<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\Poll\ConfigurationForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\Poll;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * The 'Poll' Panels content type edit form.
 *
 * @property AutoCompletedText $autocompleteField
 *   The autocomplete text field to choose a poll.
 */
class ConfigurationForm extends Form
{

    /**
     * Magic getter.
     *
     * @param string $name
     *   The name of the property we need.
     *
     * @return mixed
     *   The property found.
     *
     * @throws \Exception
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
