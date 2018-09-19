<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\AdvancedSearch\ConfigurationForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\AdvancedSearch;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * The 'Advanced search field' Panels content type edit form.
 *
 * @property AutoCompletedText $autocompleteField
 *   The autocomplete text field to choose an advanced search page.
 * @property Text $defaultFilter
 *   The text field to query string parameters for the advanced search page.
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
            case 'defaultFilter':
                return new Text($this->webdriver, $this->webdriver->byName('parameters'));
        }

        throw new \Exception("Property with name $name not found");
    }
}
