<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\WhoIsWho\ConfigurationForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\WhoIsWho;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\SelectionTypeRadioButtons;

/**
 * The 'Who is who' Panels content type edit form.
 *
 * @property AutoCompletedText $autocompleteField
 * @property SelectionTypeRadioButtons $viewMode
 * @property Checkbox $includeChildren
 * @property Checkbox $responsiblePersonShown
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
                return new AutoCompletedText(
                    $this->webdriver,
                    $this->webdriver->byName('node')
                );
                break;

            case 'viewMode':
                return new SelectionTypeRadioButtons(
                    $this->webdriver,
                    $this->element->byClassName('form-item-view-mode')
                );
                break;
            case 'includeChildren':
                return new Checkbox(
                    $this->webdriver,
                    $this->element->byClassName('form-item-children')
                );
                break;
            case 'responsiblePersonShown':
                return new Checkbox(
                    $this->webdriver,
                    $this->element->byClassName('form-item-responsible')
                );
                break;
        }

        throw new \Exception("Property with name $name not found");
    }
}
