<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\PhotoAlbum\ConfigurationForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\PhotoAlbum;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * The 'Photo album' Panels content type edit form.
 *
 * @property AutoCompletedText $filterGeneralTags
 * @property AutoCompletedText $filterTags
 */
class ConfigurationForm extends Form
{

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'filterGeneralTags':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byXPath('//input[@name="terms[paddle_general]"]'));
            case 'filterTags':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byXPath('//input[@name="terms[paddle_tags]"]'));
        }

        throw new \Exception("Property with name $name not found");
    }
}
