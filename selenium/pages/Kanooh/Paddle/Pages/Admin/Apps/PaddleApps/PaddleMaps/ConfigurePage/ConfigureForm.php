<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMaps\ConfigurePage\ConfigureForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMaps\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\FileField;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the Paddle Maps configuration form.
 *
 * @property Text $gmapAPIKey
 * @property Checkbox $markerDefault
 * @property FileField $markerFile
 */
class ConfigureForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'gmapAPIKey':
                return new Text($this->webdriver, $this->webdriver->byName('gmap_api_key'));
                break;
            case 'markerFile':
                return new FileField(
                    $this->webdriver,
                    '//input[@name="files[field_marker_und_0]"]',
                    '//input[@name="field_marker_und_0_upload_button"]',
                    '//input[@name="field_marker_und_0_remove_button"]'
                );
            case 'markerDefault':
                return new Checkbox($this->webdriver, $this->webdriver->byId('edit-field-marker-und-0-is-default'));
        }
        throw new FormFieldNotDefinedException($name);
    }
}
