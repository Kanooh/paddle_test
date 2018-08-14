<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\ConfigurePage\ConfigureForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomJavascript\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the Paddle Custom Javascript configuration form.
 *
 * @property Text $textArea
 */
class ConfigureForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'textArea':
                return new Text($this->webdriver, $this->webdriver->byId('edit-javascript'));
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
