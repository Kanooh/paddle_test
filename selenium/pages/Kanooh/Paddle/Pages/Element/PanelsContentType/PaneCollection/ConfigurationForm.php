<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\PaneCollection\ConfigurationForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\PaneCollection;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Select;

/**
 * The 'Pane Collection' Panels content type edit form.
 *
 * @property Select $paneCollectionSelection
 */
class ConfigurationForm extends Form
{

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'paneCollectionSelection':
                return new Select($this->webdriver, $this->webdriver->byName('pane_collection'));
        }

        throw new \Exception("Property with name $name not found");
    }
}
